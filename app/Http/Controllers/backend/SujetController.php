<?php

namespace App\Http\Controllers\backend;

use App\Models\Sujet;
use App\Models\Niveau;
use App\Models\Matiere;
use App\Models\Concours;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SujetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sujet::query();

        if ($request->filled('approuve')) {
            $query->where('approuve', $request->approuve);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }
        if ($request->filled('concours_id')) {
            $query->where('concours_id', $request->concours_id);
        }
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $sujets = $query->with(['categorie', 'matiere', 'concours', 'user'])
            ->withCount('downloads')
            ->latest()
            ->get();
        $sujetsNonApprouves = Sujet::where('approuve', 0)->count();

        $categories = Categorie::orderBy('libelle')->get();
        $matieres = Matiere::orderBy('libelle')->get();
        $concoursList = Concours::orderBy('libelle')->get();

        return view('backend.pages.sujet.index', compact('sujets', 'sujetsNonApprouves', 'categories', 'matieres', 'concoursList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        try {
            $categories = \App\Models\Categorie::all();
            $matieres = \App\Models\Matiere::all();
            $users = \App\Models\User::all();
            $concours = \App\Models\Concours::all();


            $niveaux = Niveau::whereNull('parent_id')
                ->with('children', fn($q) => $q->orderBy('position', 'ASC'))
                ->withCount('children')
                ->orderBy('position', 'ASC')
                ->get();


            return view('backend.pages.sujet.create', compact('categories', 'matieres', 'users', 'concours', 'niveaux'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'categorie_id' => 'required|exists:categories,id',
                'matiere_id' => 'nullable|exists:matieres,id',
                'concours_id' => 'nullable|exists:concours,id',
                'description' => '',
                'statut' => 'required|in:active,desactive',
                'approuve' => 'required|boolean',
                'annee' => '',
                'niveaux' => 'required|array',
                'niveaux.*' => 'exists:niveaux,id',
                'non_corrige' => 'required|file|mimes:pdf,doc,docx',
                'corrige' => 'nullable|file|mimes:pdf,doc,docx',
            ]);

            //generer le libelle a partir de la categorie et matiere

            $sujet = new Sujet();
            $sujet->categorie_id = $request->categorie_id;
            $sujet->matiere_id = $request->matiere_id;
            $sujet->concours_id = $request->concours_id;
            $sujet->description = $request->description;
            $sujet->statut = $request->statut;
            $sujet->approuve = $request->approuve;
            $sujet->annee = $request->annee;
            $sujet->user_id = Auth::user()->id;


            // Générer le libelle à partir de la catégorie
            $categorie = Categorie::find($request->categorie_id);
            $sujet->libelle = $categorie->libelle . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ' . '0123456789'), 0, 5);
            $sujet->code = 'MS' . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ' . '0123456789'), 0, 5);

            // Créé directement approuvé par un admin : créditer les points tout de suite.
            // En transaction pour ne pas créditer les points si la sauvegarde échoue.
            DB::transaction(function () use ($sujet) {
                if ($sujet->approuve && $sujet->user) {
                    (new \App\Services\PointsService())->givePublicationPoints($sujet->user);
                    $sujet->points_attribues = true;
                    $sujet->felicitations_vues = false;
                }

                $sujet->save();
            });

            // Attacher les niveaux (relation many-to-many)
            $sujet->niveaux()->sync($request->niveaux);

            // Gestion des fichiers avec MediaLibrary
            if ($request->hasFile('non_corrige')) {
                $sujet->addMediaFromRequest('non_corrige')->toMediaCollection('non_corrige');
            }
            if ($request->hasFile('corrige')) {
                $sujet->addMediaFromRequest('corrige')->toMediaCollection('corrige');
            }

            return redirect()->route('sujet.index')->with('success', 'Sujet créé avec succès.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $th->getMessage())->withInput();
        }
    }

    /**
     * Affiche le fichier (sujet ou corrigé) dans le navigateur pour la modération admin.
     * Les fichiers vivent sur un disque privé : ce contrôleur, protégé par la permission
     * "voir-sujet", est le seul moyen d'y accéder côté back-office.
     */
    public function preview($id, $type)
    {
        $sujet = Sujet::findOrFail($id);
        $media = $sujet->getMedia($type)->first();

        if (!$media) {
            abort(404, 'Fichier introuvable.');
        }

        return response()->file($media->getPath());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $sujet = Sujet::with(['categorie', 'matiere', 'concours', 'user', 'niveaux', 'media'])
                ->withCount('downloads')
                ->withCount(['downloads as downloads_non_corrige_count' => function ($q) {
                    $q->where('type', 'non_corrige');
                }])
                ->withCount(['downloads as downloads_corrige_count' => function ($q) {
                    $q->where('type', 'corrige');
                }])
                ->findOrFail($id);

            $derniersTelechargements = $sujet->downloads()->with('user')->latest()->take(10)->get();

            return view('backend.pages.sujet.show', compact('sujet', 'derniersTelechargements'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $sujet = Sujet::with(['categorie', 'matiere', 'user', 'niveaux', 'media'])->findOrFail($id);

            // dd($sujet->toArray());
            $categories = \App\Models\Categorie::all();
            $matieres = \App\Models\Matiere::all();
            $users = \App\Models\User::all();
            $concours = \App\Models\Concours::all();
            $niveaux = \App\Models\Niveau::whereNull('parent_id')
                ->with('children', fn($q) => $q->orderBy('position', 'ASC'))
                ->withCount('children')
                ->orderBy('position', 'ASC')
                ->get();

            return view('backend.pages.sujet.edit', compact('sujet', 'categories', 'matieres', 'users', 'concours', 'niveaux'))
                ->with('selectedNiveaux', $sujet->niveaux->pluck('id')->toArray());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Approuve the specified resource.
     */
    public function approuve($id, $etat)
    {
        try {
            $sujet = Sujet::with('user')->findOrFail($id);
            $etat = (bool) $etat;

            // Transaction : le crédit/reprise de points et la sauvegarde du sujet doivent
            // réussir ensemble, sinon un échec de sauvegarde laisserait les points
            // crédités sans que l'approbation soit réellement enregistrée.
            DB::transaction(function () use ($sujet, $etat) {
                if ($sujet->user) {
                    $pointsService = new \App\Services\PointsService();
                    if ($etat && !$sujet->points_attribues) {
                        $pointsService->givePublicationPoints($sujet->user);
                        $sujet->points_attribues = true;
                        $sujet->felicitations_vues = false;
                    } elseif (!$etat && $sujet->points_attribues) {
                        $pointsService->revokePublicationPoints($sujet->user);
                        $sujet->points_attribues = false;
                        $sujet->felicitations_vues = true;
                    }
                }

                $sujet->approuve = $etat;
                $sujet->save();
            });

            return back()->with('success', $etat ? 'Sujet approuvé, points crédités à l\'auteur.' : 'Approbation retirée, points repris à l\'auteur.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'categorie_id' => 'required|exists:categories,id',
                'matiere_id' => 'nullable|exists:matieres,id',
                'concours_id' => 'nullable|exists:concours,id',
                'description' => '',
                'statut' => 'required|in:active,desactive',
                'approuve' => 'required|boolean',
                'annee' => '',
                'niveaux' => 'required|array',
                'niveaux.*' => 'exists:niveaux,id',
                'non_corrige' => 'nullable|file|mimes:pdf,doc,docx',
                'corrige' => 'nullable|file|mimes:pdf,doc,docx',
            ]);

            $sujet = Sujet::with('user')->findOrFail($id);
            $sujet->categorie_id = $request->categorie_id;
            $sujet->matiere_id = $request->matiere_id;
            $sujet->concours_id = $request->concours_id;
            $sujet->description = $request->description;
            $sujet->statut = $request->statut;
            $sujet->annee = $request->annee;

            // Idem que sur approuve() : ne créditer/reprendre les points que si le
            // statut d'approbation change réellement, jamais deux fois. En transaction
            // pour ne pas créditer/reprendre les points si la sauvegarde échoue.
            $nouvelEtat = (bool) $request->approuve;
            $categorie = Categorie::find($request->categorie_id);

            DB::transaction(function () use ($sujet, $nouvelEtat, $categorie) {
                if ($sujet->user) {
                    $pointsService = new \App\Services\PointsService();
                    if ($nouvelEtat && !$sujet->points_attribues) {
                        $pointsService->givePublicationPoints($sujet->user);
                        $sujet->points_attribues = true;
                        $sujet->felicitations_vues = false;
                    } elseif (!$nouvelEtat && $sujet->points_attribues) {
                        $pointsService->revokePublicationPoints($sujet->user);
                        $sujet->points_attribues = false;
                        $sujet->felicitations_vues = true;
                    }
                }
                $sujet->approuve = $nouvelEtat;

                $sujet->libelle = $categorie->libelle . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ' . '0123456789'), 0, 5);
                $sujet->save();
            });

            // Met à jour les niveaux liés
            $sujet->niveaux()->sync($request->niveaux);

            // Met à jour les fichiers
            if ($request->hasFile('non_corrige')) {
                $sujet->clearMediaCollection('non_corrige');
                $sujet->addMediaFromRequest('non_corrige')->toMediaCollection('non_corrige');
            }
            if ($request->hasFile('corrige')) {
                $sujet->clearMediaCollection('corrige');
                $sujet->addMediaFromRequest('corrige')->toMediaCollection('corrige');
            }

            return redirect()->route('sujet.index')->with('success', 'Sujet modifié avec succès.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $th->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        //
        try {
            Sujet::find($id)->delete();
            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
            ]);
        }
    }
}
