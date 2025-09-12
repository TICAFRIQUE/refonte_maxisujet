<?php

namespace App\Http\Controllers\backend;

use App\Models\Niveau;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;


class NiveauController extends Controller
{

    public function create()
    {
        //create Niveau principal
        $data_niveaux = Niveau::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->get();

        return view('backend.pages.cycle_niveau.create', compact('data_niveaux'));
    }



    public function store(Request $request)
    {

        try {
            //  dd($request->all());
            //request validation ......
            $request->validate([
                'libelle' => 'required:Niveaus',
            ]);

            //compter le nombre de Niveau principale et ajouter +1 pour la position
            $data_count = Niveau::where('parent_id', null)->count();

            // recuperer la Niveau et la mettre en lowercase 

            $Niveau_lowercase = Str::lower($request['libelle']);

            Niveau::firstOrCreate([
                'libelle' => Str::ucfirst($Niveau_lowercase),

            ], [
                'parent_id' => null,
                'status' => $request['status'],
                'position' => $data_count + 1,
                'url' => $request['url'],
            ]);



            return back()->with('success', 'Opération réussi');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**page view for add item */
    public function addSubCat(Request $request, $id)
    {
        try {
            //List Niveau
            $data_niveaux = Niveau::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->get();

            $data_niveau_parent = Niveau::findOrFail($id);
            // dd( $data_niveau_parent->toArray());

            return view('backend.pages.cycle_niveau.niveau-item',  compact('data_niveaux', 'data_niveau_parent'));
        } catch (\Throwable $e) {
            return redirect()->route('niveau.create')->with('error', $e->getMessage());
        }
    }


    public function addSubCatStore(Request $request)
    {
        try {
            //request validation ......
            $request->validate([
                'libelle' => 'required:niveaux',
            ]);

            $niveau_parent = Niveau::whereId($request['niveau_parent'])->first();

            // dd($niveau_parent->toArray());
            //function for add position
            $data_count = Niveau::where('parent_id', $niveau_parent['id'])->count();

            Niveau::firstOrCreate(
                [
                    'libelle' => Str::ucfirst(Str::lower($request['libelle'])),
                    'parent_id' => $niveau_parent['id'],
                ],

                [
                    'parent_id' => $niveau_parent['id'],
                    'statut' => $request['statut'],
                    'url' => $request['url'],
                    'position' => $data_count + 1,
                ]
            );


            return redirect()->route('niveau.create')->with('success', 'Opération réussi');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function edit(Request $request, $id)
    {
        try {
            //List Niveau  -- PARTAGER AVEC TOUTES LES VUES DEPUIS APPSERVICEPROVIDER
            // $data_niveaux = Niveau::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->get();

            $data_niveau_edit = Niveau::find($id);

            $data_count = Niveau::where('parent_id', $data_niveau_edit['parent_id'])->count();
            // dd($data_count);

            return view('backend.pages.cycle_niveau.niveau-edit',  compact('data_niveau_edit', 'data_count'));
        } catch (\Throwable $e) {
           return back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {

        //request validation ......
        $request->validate([
            'libelle' => 'required',
        ]);

        try {

            $data_Niveau = Niveau::find($id)->update([
                'libelle' => Str::ucfirst(Str::lower($request['libelle'])),
                'statut' => $request['statut'],
                'url' => $request['url'],
                'position' => $request['position'],
            ]);


            return redirect()->route('niveau.create')->with('success', 'Opération réussi');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            //reeorganiser l'ordre
            $data_niveau_edit = Niveau::find($id);
            $data_niveau = Niveau::where('parent_id', $data_niveau_edit['parent_id'])->get();
            foreach ($data_niveau as $key => $value) {
                Niveau::whereId($value['id'])->update([
                    'position' => $key + 1
                ]);
            }
            //supprimer
            Niveau::find($id)->forceDelete();

            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
