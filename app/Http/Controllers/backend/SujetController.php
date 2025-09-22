<?php

namespace App\Http\Controllers\backend;

use App\Models\Sujet;
use App\Models\Niveau;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $sujets = $query->with(['categorie', 'matiere', 'user'])->get();
        $sujetsNonApprouves = Sujet::where('approuve', 0)->count();

        return view('backend.pages.sujet.index', compact('sujets', 'sujetsNonApprouves'));
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


            $niveaux = Niveau::whereNull('parent_id')
                ->with('children', fn($q) => $q->orderBy('position', 'ASC'))
                ->withCount('children')
                ->orderBy('position', 'ASC')
                ->get();


            return view('backend.pages.sujet.create', compact('categories', 'matieres', 'users', 'niveaux'));
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
                'matiere_id' => 'required|exists:matieres,id',
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
            $sujet->description = $request->description;
            $sujet->statut = $request->statut;
            $sujet->approuve = $request->approuve;
            $sujet->annee = $request->annee;
            $sujet->user_id = Auth::user()->id;


            // Générer le libelle à partir de la catégorie
            $categorie = Categorie::find($request->categorie_id);
            $sujet->libelle = $categorie->libelle . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ' . '0123456789'), 0, 5);
            $sujet->code = 'MS' . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ' . '0123456789'), 0, 5);

            $sujet->save();

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
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $sujet = Sujet::with(['categorie', 'matiere', 'user', 'niveaux', 'media'])->findOrFail($id);
            return view('backend.pages.sujet.show', compact('sujet'));
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
            $niveaux = \App\Models\Niveau::whereNull('parent_id')
                ->with('children', fn($q) => $q->orderBy('position', 'ASC'))
                ->withCount('children')
                ->orderBy('position', 'ASC')
                ->get();

            return view('backend.pages.sujet.edit', compact('sujet', 'categories', 'matieres', 'users', 'niveaux'))
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
            $sujet = Sujet::findOrFail($id);
            $sujet->approuve = $etat;
            $sujet->save();
            return back()->with('success', 'Statut mis à jour.');
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
                'matiere_id' => 'required|exists:matieres,id',
                'description' => '',
                'statut' => 'required|in:active,desactive',
                'approuve' => 'required|boolean',
                'annee' => '',
                'niveaux' => 'required|array',
                'niveaux.*' => 'exists:niveaux,id',
                'non_corrige' => 'nullable|file|mimes:pdf,doc,docx',
                'corrige' => 'nullable|file|mimes:pdf,doc,docx',
            ]);

            $sujet = Sujet::findOrFail($id);
            $sujet->categorie_id = $request->categorie_id;
            $sujet->matiere_id = $request->matiere_id;
            $sujet->description = $request->description;
            $sujet->statut = $request->statut;
            $sujet->approuve = $request->approuve;
            $sujet->annee = $request->annee;


            $categorie = Categorie::find($request->categorie_id);
            $sujet->libelle = $categorie->libelle . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ' . '0123456789'), 0, 5);
            $sujet->save();

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
