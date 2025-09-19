<?php

namespace App\Http\Controllers\frontend;

use App\Models\User;
use App\Models\Sujet;
use App\Models\Niveau;
use App\Models\Matiere;
use App\Models\Categorie;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SujetFrontController extends Controller
{
    //pages pour afficher les fichiers des sujets
    public function index(Request $request)
    {
        try {
            // Récupérer les valeurs des filtres
            $categorie = $request->input('categorie');
            $niveau = $request->input('niveau');
            $matiere = $request->input('matiere');
            $annee = $request->input('annee');
            $code = $request->input('code');

            $sujets = Sujet::with(['categorie', 'niveaux', 'matiere', 'user', 'media'])
                ->when($categorie, function ($query, $categorie) {
                    return $query->whereHas('categorie', function ($q) use ($categorie) {
                        $q->where('slug', $categorie);
                    });
                })
                ->when($niveau, function ($query, $niveau) {
                    return $query->whereHas('niveaux', function ($q) use ($niveau) {
                        $q->where('slug', $niveau);
                    });
                })
                ->when($matiere, function ($query, $matiere) {
                    return $query->whereHas('matiere', function ($q) use ($matiere) {
                        $q->where('slug', $matiere);
                    });
                })
                ->when($annee, function ($query, $annee) {
                    return $query->where('annee', $annee);
                })
                ->when($code, function ($query, $code) {
                    return $query->where('code', 'like', "%$code%");
                })
                ->active()->approuve()
                ->orderBy('created_at', 'desc')
                ->paginate(12)
                ->withQueryString();

            // Pour afficher les filtres dans la vue
            $categories = Categorie::all();
            $niveaux = Niveau::all();
            $matieres = Matiere::all();

            return view('frontend.pages.sujets.index', compact('sujets', 'categories', 'niveaux', 'matieres'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function show($libelle)
    {
        try {
            $sujet = Sujet::with(['categorie', 'niveaux', 'matiere', 'user', 'media'])
                ->where('libelle', $libelle)
                ->firstOrFail();

            // Log du téléchargement si utilisateur connecté
            if (Auth::check()) {
                DownloadLog::create([
                    'user_id' => Auth::id(),
                    'sujet_id' => $sujet->id,
                ]);
            }

            return view('frontend.pages.sujets.show', compact('sujet'));
        } catch (\Exception $e) {
            return $e->getMessage(); // redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
    /**
     * Télécharger un fichier sujet ou corrigé
     */
    public function download($id, $type)
    {
        $user = Auth::user();
        $sujet = Sujet::findOrFail($id);

        // Nombre de points à retirer
        $pointsToRemove = 1;

        // Vérifier les points
        if ($user->points < $pointsToRemove) {
            Alert::error('Erreur', 'Vous n\'avez pas assez de points pour télécharger ce fichier.');
            return redirect()->route('sujet.front.index');
        }

        // Retirer les points
        User::where('id', $user->id)->decrement('points', $pointsToRemove);
        

        // Enregistrer le téléchargement
        DownloadLog::create([
            'user_id' => $user->id,
            'sujet_id' => $sujet->id,
            'type' => $type,
            'created_at' => now(),
        ]);

        // Récupérer le fichier
        $mediaUrl = $sujet->getFirstMediaUrl($type);
        if (!$mediaUrl) {
            Alert::error('Erreur', 'Le fichier est introuvable.');
            return redirect()->route('sujet.front.index');
        }

        // Rediriger vers le fichier (ou utiliser Storage::download si local)
        return redirect($mediaUrl);

        // Récupérer le fichier MediaLibrary
        // $media = $sujet->getMedia($type)->first();
        // if (!$media) {
        //     Alert::error('Erreur', 'Le fichier est introuvable.');
        //     return redirect()->route('sujet.front.index');
        // }

        // // Lancer le téléchargement direct
        // return response()->download($media->getPath(), $media->file_name);
    }



    /**
     * Aperçu du fichier (sujet ou corrigé) si connecté et points suffisants
     */
    public function apercu($id, $type)
    {
        $user = Auth::user();
        $sujet = Sujet::findOrFail($id);
        $pointsToRemove = 1;

        if (!$user) {
            Alert::error('Erreur', 'Vous devez être connecté pour voir l\'aperçu.');
            return redirect()->route('user.loginForm');
        }

        if ($user->points < $pointsToRemove) {
           Alert::error('Erreur', 'Vous n\'avez pas assez de points pour voir l\'aperçu.');
            return redirect()->route('sujet.front.index');
        }

        $media = $sujet->getMedia($type)->first();
        if (!$media) {
            Alert::error('Erreur', 'Le fichier est introuvable.');
            return redirect()->route('sujet.front.index');
        }

        // Retirer les points et enregistrer l'aperçu
        User::where('id', $user->id)->decrement('points', $pointsToRemove);

        DownloadLog::create([
            'user_id' => $user->id,
            'sujet_id' => $sujet->id,
            'type' => $type,
            'created_at' => now(),
        ]);

        // Affiche le fichier dans le navigateur (PDF, DOC, etc.)
        return response()->file($media->getPath());
    }
}
