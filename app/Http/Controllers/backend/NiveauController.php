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
            // Validation des données
            $request->validate([
                'libelle' => 'required|string|min:2|max:50|regex:/^[a-zA-ZÀ-ÿ0-9\s]+$/',
                'statut' => 'required|in:active,desactive'
            ], [
                'libelle.required' => 'Le nom du cycle est obligatoire.',
                'libelle.min' => 'Le nom du cycle doit contenir au moins 2 caractères.',
                'libelle.max' => 'Le nom du cycle ne peut pas dépasser 50 caractères.',
                'libelle.regex' => 'Le nom du cycle ne peut contenir que des lettres, chiffres et espaces.',
                'statut.required' => 'Le statut est obligatoire.',
                'statut.in' => 'Le statut doit être "active" ou "desactive".'
            ]);

            // Vérifier si un cycle avec ce nom existe déjà
            $libelle_formatted = Str::ucfirst(Str::lower(trim($request->libelle)));
            $existing = Niveau::whereNull('parent_id')
                             ->where('libelle', $libelle_formatted)
                             ->first();
            
            if ($existing) {
                return back()->with('error', 'Un cycle avec ce nom existe déjà.')->withInput();
            }

            // Compter le nombre de cycles principaux et ajouter +1 pour la position
            $data_count = Niveau::whereNull('parent_id')->count();

            // Créer le nouveau cycle
            Niveau::create([
                'libelle' => $libelle_formatted,
                'parent_id' => null,
                'statut' => $request->statut,
                'position' => $data_count + 1,
                'url' => null,
            ]);

            return back()->with('success', 'Cycle créé avec succès !');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur lors de la création du cycle: ' . $e->getMessage())->withInput();
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
            // Validation des données
            $request->validate([
                'libelle' => 'required|string|min:2|max:50|regex:/^[a-zA-ZÀ-ÿ0-9\s]+$/',
                'statut' => 'required|in:active,desactive',
                'niveau_parent' => 'required|exists:niveaux,id'
            ], [
                'libelle.required' => 'Le nom du niveau est obligatoire.',
                'libelle.min' => 'Le nom du niveau doit contenir au moins 2 caractères.',
                'libelle.max' => 'Le nom du niveau ne peut pas dépasser 50 caractères.',
                'libelle.regex' => 'Le nom du niveau ne peut contenir que des lettres, chiffres et espaces.',
                'statut.required' => 'Le statut est obligatoire.',
                'statut.in' => 'Le statut doit être "active" ou "desactive".',
                'niveau_parent.required' => 'Le niveau parent est obligatoire.',
                'niveau_parent.exists' => 'Le niveau parent spécifié n\'existe pas.'
            ]);

            $niveau_parent = Niveau::findOrFail($request->niveau_parent);
            $libelle_formatted = Str::ucfirst(Str::lower(trim($request->libelle)));

            // Vérifier si un niveau avec ce nom existe déjà pour ce parent
            $existing = Niveau::where('parent_id', $niveau_parent->id)
                             ->where('libelle', $libelle_formatted)
                             ->first();
            
            if ($existing) {
                return back()->with('error', 'Un niveau avec ce nom existe déjà dans ce cycle.')->withInput();
            }

            // Compter les niveaux existants pour ce parent
            $data_count = Niveau::where('parent_id', $niveau_parent->id)->count();

            // Créer le nouveau niveau
            Niveau::create([
                'libelle' => $libelle_formatted,
                'parent_id' => $niveau_parent->id,
                'statut' => $request->statut,
                'position' => $data_count + 1,
                'url' => null,
            ]);

            // Reste sur la même page d'ajout de niveau pour ce cycle : permet d'ajouter
            // plusieurs niveaux à la suite (ex. CP, CE1, CE2...) sans re-naviguer.
            return redirect()->route('niveau.add-subCat', $niveau_parent->id)->with('success', 'Niveau créé avec succès dans le cycle "' . $niveau_parent->libelle . '" !');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur lors de la création du niveau: ' . $e->getMessage())->withInput();
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
        try {
            // Validation des données
            $request->validate([
                'libelle' => 'required|string|min:2|max:50|regex:/^[a-zA-ZÀ-ÿ0-9\s]+$/',
                'statut' => 'required|in:active,desactive',
                'position' => 'required|integer|min:1'
            ], [
                'libelle.required' => 'Le nom est obligatoire.',
                'libelle.min' => 'Le nom doit contenir au moins 2 caractères.',
                'libelle.max' => 'Le nom ne peut pas dépasser 50 caractères.',
                'libelle.regex' => 'Le nom ne peut contenir que des lettres, chiffres et espaces.',
                'statut.required' => 'Le statut est obligatoire.',
                'statut.in' => 'Le statut doit être "active" ou "desactive".',
                'position.required' => 'La position est obligatoire.',
                'position.integer' => 'La position doit être un nombre entier.',
                'position.min' => 'La position doit être supérieure à 0.'
            ]);

            $niveau = Niveau::findOrFail($id);
            $libelle_formatted = Str::ucfirst(Str::lower(trim($request->libelle)));

            // Vérifier si un niveau avec ce nom existe déjà (sauf le niveau actuel)
            $existing = Niveau::where('parent_id', $niveau->parent_id)
                             ->where('libelle', $libelle_formatted)
                             ->where('id', '!=', $id)
                             ->first();
            
            if ($existing) {
                return back()->with('error', 'Un niveau avec ce nom existe déjà.')->withInput();
            }

            // Mettre à jour le niveau
            $niveau->update([
                'libelle' => $libelle_formatted,
                'statut' => $request->statut,
                'position' => $request->position,
                'url' => null,
            ]);

            return redirect()->route('niveau.create')->with('success', 'Niveau modifié avec succès !');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur lors de la modification: ' . $e->getMessage())->withInput();
        }
    }


    public function delete($id)
    {
        try {
            $data_niveau_edit = Niveau::find($id);
            if (!$data_niveau_edit) {
                return response()->json(['status' => 404]);
            }

            $childrenCount = Niveau::where('parent_id', $id)->count();
            if ($childrenCount > 0) {
                return response()->json([
                    'status' => 409,
                    'message' => "Impossible de supprimer : ce cycle contient encore {$childrenCount} niveau(x). Supprimez-les d'abord.",
                ]);
            }

            $sujetsCount = $data_niveau_edit->sujets()->count();
            if ($sujetsCount > 0) {
                return response()->json([
                    'status' => 409,
                    'message' => "Impossible de supprimer : {$sujetsCount} sujet(s) sont encore rattachés à ce niveau. Retirez-le d'abord de ces sujets.",
                ]);
            }

            //reeorganiser l'ordre
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

    /**
     * Mettre à jour les positions via drag and drop
     */
    public function updatePositions(Request $request)
    {
        try {
            $updates = $request->input('updates');
            
            if (!$updates || !is_array($updates)) {
                return response()->json(['success' => false, 'message' => 'Données invalides']);
            }

            foreach ($updates as $update) {
                if (isset($update['id']) && isset($update['position'])) {
                    Niveau::where('id', $update['id'])->update([
                        'position' => $update['position']
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Positions mises à jour avec succès']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }
}
