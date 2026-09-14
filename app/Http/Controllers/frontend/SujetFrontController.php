<?php

namespace App\Http\Controllers\frontend;

use App\Models\User;
use App\Models\Sujet;
use App\Models\Niveau;
use App\Models\Matiere;
use App\Models\Categorie;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                ->paginate(12) // Nombre de sujets par page
                ->withQueryString(); // Ajout de la pagination

            // Chargement progressif (scroll infini) : ne renvoyer que les cartes de la
            // page demandée, sans recharger tout le layout/les filtres.
            if ($request->ajax()) {
                $html = view('frontend.pages.sujets.partials._cards', compact('sujets'))->render();
                return response($html)->header('X-Next-Page', $sujets->hasMorePages() ? $sujets->nextPageUrl() : '');
            }

            // Pour afficher les filtres dans la vue
            $categories = Categorie::all();
            $niveaux = Niveau::all();
            $matieres = Matiere::all();

            // Statistiques affichées en bas de catalogue.
            // Le compteur de téléchargements part d'une base de 1354 (historique de
            // l'ancien site) à laquelle s'ajoutent les téléchargements réels de la
            // plateforme actuelle.
            $totalSujetsDisponibles = Sujet::active()->approuve()->count();
            $totalTelechargements = 1354 + DownloadLog::count();

            return view('frontend.pages.sujets.index', compact(
                'sujets', 'categories', 'niveaux', 'matieres',
                'totalSujetsDisponibles', 'totalTelechargements'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function show($libelle)
    {
        try {
            $sujet = Sujet::with(['categorie', 'niveaux', 'matiere', 'user', 'media'])
                ->where('libelle', $libelle)
                ->active()
                ->approuve()
                ->first();

            if (!$sujet) {
                Alert::error('Erreur', 'Le sujet demandé est introuvable ou indisponible.');
                return redirect()->route('sujet.front.index');
            }

            // Log de la consultation si utilisateur connecté
            // if (Auth::check()) {
            //     DownloadLog::create([
            //         'user_id' => Auth::id(),
            //         'sujet_id' => $sujet->id,
            //         // 'type' => 'view',
            //     ]);
            // }

            // Sujets similaires : même matière, sinon même catégorie
            $similaires = Sujet::with(['categorie', 'matiere', 'niveaux', 'media'])
                ->where('id', '!=', $sujet->id)
                ->active()->approuve()
                ->where(function ($query) use ($sujet) {
                    $query->where('matiere_id', $sujet->matiere_id)
                        ->orWhere('categorie_id', $sujet->categorie_id);
                })
                ->orderByDesc('created_at')
                ->take(4)
                ->get();

            return view('frontend.pages.sujets.show', compact('sujet', 'similaires'));
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors de la récupération du sujet.');
            return redirect()->route('sujet.front.index');
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
        $pointsToRemove = \App\Services\PointsService::COUT_TELECHARGEMENT;

        // Vérifier les points
        if ($user->points < $pointsToRemove) {
            Alert::error('Erreur', 'Vous n\'avez pas assez de points pour télécharger ce fichier.');
            return redirect()->route('sujet.front.index');
        }

        // Récupérer le fichier MediaLibrary d'abord
        $media = $sujet->getMedia($type)->first();
        if (!$media) {
            Alert::error('Erreur', 'Le fichier est introuvable.');
            return redirect()->route('sujet.front.index');
        }

        // Retirer les points avec transaction pour s'assurer de la cohérence
        DB::transaction(function () use ($user, $sujet, $type, $pointsToRemove) {
            User::where('id', $user->id)->decrement('points', $pointsToRemove);
            
            // Enregistrer le téléchargement
            DownloadLog::create([
                'user_id' => $user->id,
                'sujet_id' => $sujet->id,
                'type' => $type,
                'created_at' => now(),
            ]);
        });

        // S'assurer que toutes les opérations DB sont terminées
        DB::commit();

        // Pour les PDF, on tamponne le logo MaxiSujets sur une copie temporaire
        // avant envoi (le fichier stocké n'est jamais modifié).
        if (strtolower($media->extension) === 'pdf') {
            $stampedPath = app(\App\Services\PdfWatermarkService::class)->stamp($media->getPath());

            if ($stampedPath !== $media->getPath()) {
                return response()->download($stampedPath, $media->file_name)->deleteFileAfterSend(true);
            }
        }

        // Lancer le téléchargement direct
        return response()->download($media->getPath(), $media->file_name);

        // Ancienne méthode qui redirige vers l'URL (commentée)
        // $mediaUrl = $sujet->getFirstMediaUrl($type);
        // if (!$mediaUrl) {
        //     Alert::error('Erreur', 'Le fichier est introuvable.');
        //     return redirect()->route('sujet.front.index');
        // }
        // return redirect($mediaUrl);
    }



    /**
     * Aperçu du fichier (sujet ou corrigé) : gratuit, réservé aux utilisateurs connectés.
     * Seul le téléchargement (download()) consomme un point. Les fichiers vivent sur un
     * disque privé (voir Sujet::registerMediaCollections) : cette route est le seul moyen
     * d'y accéder, il n'existe pas d'URL publique à partager.
     */
    public function apercu($id, $type)
    {
        $sujet = Sujet::findOrFail($id);

        $media = $sujet->getMedia($type)->first();
        if (!$media) {
            Alert::error('Erreur', 'Le fichier est introuvable.');
            return redirect()->route('sujet.front.index');
        }

        // Affiche le fichier dans le navigateur (PDF, DOC, etc.)
        return response()->file($media->getPath());
    }
}
