<?php

namespace App\Http\Controllers\frontend;

use App\Models\User;
use App\Models\Sujet;
use App\Models\Categorie;
use App\Models\DownloadLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserDashboardControlleur extends Controller
{
    //

    /** Dashboard utilisateur */
    public function dashboard()
    {
        try {
            $user = Auth::user();

            // Historique téléchargements
            $downloads = DownloadLog::where('user_id', $user->id)->with('sujet')->paginate(10);

            // Compter les éléments
            $downloadsCount = $downloads->count();
            $publishedSubjectsCount = Sujet::where('user_id', $user->id)->count();
            $points = $user->points ?? 0; // Assurez-vous que le modèle User a un attribut points

            return view('frontend.pages.user.dashboard', compact('user', 'downloads', 'downloadsCount', 'publishedSubjectsCount', 'points'));
        } catch (\Throwable $e) {
            return $e->getMessage(); // redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /** Modifier le profil utilisateur */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'username' => 'required|string|max:50|unique:users,username,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
                'profil' => 'required|in:eleve,etudiant,enseignant,parent',
                'password' => 'nullable|string|min:8',
            ]);

            $data = [
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'profil' => $request->profil,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            User::where('id', $user->id)->update($data);

            Alert::success('Profil mis à jour', 'Votre profil a été mis à jour avec succès.');
            return redirect()->back();
        } catch (\Throwable $e) {
            return $e->getMessage(); // redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /** Afficher la liste des sujets de l'utilisateur */
    public function indexSujet()
    {
        try {
            $user = Auth::user();
            $sujets = Sujet::where('user_id', $user->id)->with(['categorie', 'matiere', 'niveaux', 'media'])->orderBy('created_at', 'desc')
                ->withCount('downloads')
                ->paginate(10); //->get(); //->paginate(10); //

            return view('frontend.pages.user.sujet.index', compact('sujets'));
        } catch (\Throwable $e) {
            return $e->getMessage(); // redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /** Afficher le formulaire de création de sujet */
    public function createSujet()
    {
        $categories = \App\Models\Categorie::all();
        $matieres = \App\Models\Matiere::all();
        $niveaux = \App\Models\Niveau::whereNull('parent_id')
            ->with('children', fn($q) => $q->orderBy('position', 'ASC'))
            ->withCount('children')
            ->orderBy('position', 'ASC')
            ->get();
        return view('frontend.pages.user.sujet.create', compact('categories', 'matieres', 'niveaux'));
    }

    /** Enregistrer un nouveau sujet */
    public function storeSujet(Request $request)
    {


        try {
            // dd($request->all());
            $request->validate([
                'categorie_id' => 'required|exists:categories,id',
                'matiere_id' => 'required|exists:matieres,id',
                'description' => '',
                // 'statut' => 'required|in:active,desactive',
                // 'approuve' => 'required|boolean',
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
            // $sujet->statut = $request->statut;
            // $sujet->approuve = $request->approuve;
            $sujet->annee = $request->annee;
            $sujet->user_id = Auth::user()->id;

            // Génération du libelle et du code si besoin
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

            // Donner des points pour la publication
            $pointsService = new \App\Services\PointsService();
            $pointsService->givePublicationPoints(Auth::user());


            Alert::success('Sujet créé', 'Le sujet a été créé avec succès.');
            return redirect()->route('user.sujet.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $th->getMessage())->withInput();
        }
    }

    /** Afficher le formulaire d’édition de sujet */
    public function editSujet($id)
    {
        $sujet = \App\Models\Sujet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $categories = \App\Models\Categorie::all();
        $matieres = \App\Models\Matiere::all();
        $niveaux = \App\Models\Niveau::all();
        return view('frontend.pages.user.sujet.edit', compact('sujet', 'categories', 'matieres', 'niveaux'));
    }

    /** Mettre à jour un sujet */
    public function updateSujet(Request $request, $id)
    {
        try {
            $sujet = Sujet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $request->validate([
                'description' => 'nullable|string',
                'categorie_id' => 'required|exists:categories,id',
                'matiere_id' => 'required|exists:matieres,id',
                'niveaux' => 'required|array',
                'niveaux.*' => 'exists:niveaux,id',
                'non_corrige' => 'nullable|file|mimes:pdf,doc,docx',
                'corrige' => 'nullable|file|mimes:pdf,doc,docx',
            ]);

            // Mise à jour des attributs du sujet
            // Générer le libelle à partir de la catégorie
            $categorie = Categorie::find($request->categorie_id);
            $sujet->libelle = $categorie->libelle . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ' . '0123456789'), 0, 5);

            $sujet->description = $request->description;
            $sujet->categorie_id = $request->categorie_id;
            $sujet->matiere_id = $request->matiere_id;
            $sujet->save();

            // Synchroniser les niveaux
            $sujet->niveaux()->sync($request->niveaux);

            // Gestion des fichiers
            if ($request->hasFile('non_corrige')) {
                $sujet->clearMediaCollection('non_corrige');
                $sujet->addMediaFromRequest('non_corrige')->toMediaCollection('non_corrige');
            }
            if ($request->hasFile('corrige')) {
                $sujet->clearMediaCollection('corrige');
                $sujet->addMediaFromRequest('corrige')->toMediaCollection('corrige');
            }

            Alert::success('Sujet modifié', 'Les modifications ont été enregistrées.');
            return redirect()->route('user.sujet.index'); // Redirige vers la liste des sujets
        } catch (\Throwable $th) {
            return $th->getMessage(); // redirect()->back()->with('error', 'Une erreur est survenue: ' . $th->getMessage())->withInput();
        }
    }

    /** Afficher la page de suppression */
    public function deleteSujet($id)
    {
        $sujet = \App\Models\Sujet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('frontend.pages.user.sujet.delete', compact('sujet'));
    }

    /** Supprimer le sujet */
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
