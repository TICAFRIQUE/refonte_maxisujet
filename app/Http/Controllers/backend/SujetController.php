<?php

namespace App\Http\Controllers\backend;

use App\Models\Sujet;
use App\Models\Niveau;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SujetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sujets = Sujet::with(['categorie', 'matiere', 'user', 'niveaux', 'media'])->get();
            return view('backend.pages.sujet.index', compact('sujets'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
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

            $sujet = new Sujet();
            $sujet->categorie_id = $request->categorie_id;
            $sujet->matiere_id = $request->matiere_id;
            $sujet->description = $request->description;
            $sujet->statut = $request->statut;
            $sujet->approuve = $request->approuve;
            $sujet->annee = $request->annee;
            $sujet->user_id = Auth::user()->id;

            // Génération du libelle et du code si besoin
            $sujet->libelle = $request->libelle ?? 'Sujet-' . uniqid();
            $sujet->code = $request->code ?? strtoupper(substr($sujet->libelle, 0, 3)) . rand(10000, 99999);

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
    public function show(string $id)
    {
        //
        
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
            $sujet->libelle = $request->libelle ?? $sujet->libelle;
            $sujet->code = $request->code ?? $sujet->code;
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
    public function destroy(string $id)
    {
        //
    }
}
