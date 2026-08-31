<?php

namespace App\Http\Controllers\backend;

use App\Models\Concours;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConcourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $concours = Concours::withCount('sujets')->get();
            return view('backend.pages.concours.index', compact('concours'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des concours');
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
                'libelle' => 'required|unique:concours,libelle',
            ]);

            Concours::create([
                'libelle' => Str::ucfirst(Str::lower($request->libelle)),
                'statut' => 'active',
            ]);

            return back()->with('success', 'Concours ajouté avec succès');
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors de l\'ajout du concours');
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

            $concours = Concours::find($id);
            if (!$concours) {
                return back()->with('error', 'Concours non trouvé');
            }

            $concours->update([
                'libelle' => Str::ucfirst(Str::lower($request->libelle)),
            ]);
            return back()->with('success', 'Concours modifié avec succès');
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors de la modification du concours');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        try {
            $concours = Concours::find($id);

            if (!$concours) {
                return back()->with('error', 'Concours non trouvé');
            }

            $sujetsCount = \App\Models\Sujet::where('concours_id', $id)->count();
            if ($sujetsCount > 0) {
                return back()->with('error', "Impossible de supprimer : {$sujetsCount} sujet(s) utilisent encore ce concours. Réaffectez-les d'abord à un autre concours.");
            }

            $concours->delete();

            return back()->with('success', 'Concours supprimé avec succès');
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors de la suppression du concours');
        }
    }
}
