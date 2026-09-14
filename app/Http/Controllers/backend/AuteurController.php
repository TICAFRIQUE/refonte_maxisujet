<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\Sujet;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuteurController extends Controller
{
    /**
     * Liste des auteurs (contributeurs inscrits publiquement), séparée de la gestion
     * de l'équipe admin. Affiche leurs points et leurs contributions plutôt que des
     * rôles/permissions.
     *
     * Paginée côté serveur (des dizaines de milliers de comptes après l'import de
     * l'ancien site) : on ne charge jamais tous les auteurs en mémoire juste pour
     * les compter ou les afficher — chaque KPI est une requête d'agrégation SQL
     * séparée, et la liste elle-même n'envoie qu'une page à la fois.
     */
    public function index(Request $request)
    {
        $query = User::role('auteur');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $auteurs = $query->withCount('sujets')
            ->withCount(['sujets as sujets_approuves_count' => function ($q) {
                $q->where('approuve', 1);
            }])
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        $kpiAuteurs = [
            'total' => User::role('auteur')->count(),
            'inscrits_aujourdhui' => User::role('auteur')->whereDate('created_at', today())->count(),
            'actifs' => User::role('auteur')->where('statut', 'active')->count(),
            'desactives' => User::role('auteur')->where('statut', '!=', 'active')->count(),
            'sujets_publies' => Sujet::whereHas('user', fn ($q) => $q->role('auteur'))->count(),
        ];

        return view('backend.pages.auteur.index', compact('auteurs', 'kpiAuteurs'));
    }

    /**
     * Fiche détaillée d'un auteur : informations, sujets publiés (avec statut et
     * téléchargements), historique de ses propres téléchargements.
     */
    public function show($id)
    {
        $auteur = User::role('auteur')->findOrFail($id);

        $sujets = Sujet::where('user_id', $id)
            ->with(['categorie', 'matiere'])
            ->withCount('downloads')
            ->latest()
            ->get();

        $downloads = DownloadLog::where('user_id', $id)
            ->with('sujet')
            ->latest()
            ->paginate(10);

        return view('backend.pages.auteur.show', compact('auteur', 'sujets', 'downloads'));
    }

    /**
     * Activer / désactiver un compte auteur (alternative réversible à la suppression).
     */
    public function toggleStatut($id)
    {
        try {
            $user = User::role('auteur')->findOrFail($id);
            $user->statut = $user->statut === 'active' ? 'desactive' : 'active';
            $user->save();

            return response()->json(['status' => 200, 'statut' => $user->statut]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 500]);
        }
    }

    /**
     * Modifier les informations d'un auteur (profil, coordonnées, points, statut).
     * Ne touche jamais au mot de passe ni au rôle depuis cet écran.
     */
    public function update(Request $request, $id)
    {
        $auteur = User::role('auteur')->findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $auteur->id,
            'email' => 'required|email|max:255|unique:users,email,' . $auteur->id,
            'phone' => 'nullable|string|max:30',
            'profil' => 'nullable|in:eleve,enseignant,etudiant,parent,autre',
            'points' => 'required|integer|min:0',
            'statut' => 'required|in:active,desactive',
        ]);

        try {
            $auteur->update($request->only(['username', 'email', 'phone', 'profil', 'points', 'statut']));

            return back()->with('success', 'Le compte auteur a été mis à jour.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du compte.');
        }
    }

    /**
     * Supprimer un auteur ET tous les sujets qu'il a publiés (fichiers médias compris).
     * Le compte est retiré en douceur (SoftDeletes sur User) ; les sujets, eux, sont
     * définitivement supprimés — ils n'ont pas de sens sans leur auteur.
     */
    public function delete($id)
    {
        try {
            $auteur = User::role('auteur')->find($id);
            if (!$auteur) {
                return response()->json(['status' => 404]);
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($auteur) {
                Sujet::where('user_id', $auteur->id)->get()->each(function (Sujet $sujet) {
                    $sujet->clearMediaCollection('non_corrige');
                    $sujet->clearMediaCollection('corrige');
                    $sujet->delete();
                });

                $auteur->delete();
            });

            return response()->json(['status' => 200]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 500]);
        }
    }
}
