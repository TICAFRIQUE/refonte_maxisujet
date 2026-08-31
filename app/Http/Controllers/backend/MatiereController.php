<?php

namespace App\Http\Controllers\backend;

use App\Models\Matiere;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MatiereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $matieres = Matiere::withCount('sujets')->get();
            return view('backend.pages.matiere.index', compact('matieres'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des matieres');
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
        //
        try {
            $request->validate([
                'libelle' => 'required|unique:matieres,libelle',
            ]);

            Matiere::create([
                'libelle' => Str::ucfirst(Str::lower($request->libelle)),
                'statut' => 'active',
            ]);

            return back()->with('success', 'Matière ajoutée avec succès');
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors de l\'ajout de la matière');
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

            $matiere = Matiere::find($id);
            if (!$matiere) {
                return back()->with('error', 'Matière non trouvée');
            }

            $matiere->update([
                'libelle' => Str::ucfirst(Str::lower($request->libelle)),
            ]);
            return back()->with('success', 'Matière modifiée avec succès');
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors de la modification de la matière');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        try {
            $matiere = Matiere::find($id);
            if (!$matiere) {
                return response()->json(['status' => 404]);
            }

            $sujetsCount = \App\Models\Sujet::where('matiere_id', $id)->count();
            if ($sujetsCount > 0) {
                return response()->json([
                    'status' => 409,
                    'message' => "Impossible de supprimer : {$sujetsCount} sujet(s) utilisent encore cette matière. Réaffectez-les d'abord à une autre matière.",
                ]);
            }

            $matiere->delete();
            return response()->json(['status' => 200]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500]);
        }
    }
}
