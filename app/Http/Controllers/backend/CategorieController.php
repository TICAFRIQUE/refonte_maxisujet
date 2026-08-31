<?php

namespace App\Http\Controllers\backend;

use App\Models\Categorie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $categories = Categorie::withCount('sujets')->get();
            return view('backend.pages.categorie.index', compact('categories'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des catégories');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'libelle' => 'required|unique:categories,libelle',
            ]);

            Categorie::create([
                'libelle' => Str::ucfirst(Str::lower($request->libelle)),
                'statut' => 'active',
            ]);

            return back()->with('success', 'Catégorie ajoutée avec succès');
        } catch (\Throwable $th) {

            // return back()->with('error', 'Une erreur s\'est produite lors de l\'ajout de la catégorie');
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur s\'est produite lors de l\'ajout de la catégorie' . $th->getMessage(),
            ], 500);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $validate = $request->validate([
                'libelle' => 'required',
            ]);

            $categorie = Categorie::find($id);
            if (!$categorie) {
                return back()->with('error', 'Catégorie non trouvée');
            }

            $categorie->update([
                'libelle' => Str::ucfirst(Str::lower($request->libelle)),
            ]);

            return back()->with('success', 'Catégorie modifiée avec succès');
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors de la modification de la catégorie');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        try {
            $categorie = Categorie::find($id);
            if (!$categorie) {
                return response()->json(['status' => 404]);
            }

            // La suppression d'une catégorie supprime en cascade tous les sujets qui en
            // dépendent (contrainte de clé étrangère) : on bloque plutôt que de laisser
            // disparaître des sujets silencieusement.
            $sujetsCount = \App\Models\Sujet::where('categorie_id', $id)->count();
            if ($sujetsCount > 0) {
                return response()->json([
                    'status' => 409,
                    'message' => "Impossible de supprimer : {$sujetsCount} sujet(s) utilisent encore cette catégorie. Réaffectez-les d'abord à une autre catégorie.",
                ]);
            }

            $categorie->delete();
            return response()->json(['status' => 200]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500]);
        }
    }
}
