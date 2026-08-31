<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\Sujet;
use App\Models\DownloadLog;
use App\Http\Controllers\Controller;

class AuteurController extends Controller
{
    /**
     * Liste des auteurs (contributeurs inscrits publiquement), séparée de la gestion
     * de l'équipe admin. Affiche leurs points et leurs contributions plutôt que des
     * rôles/permissions.
     */
    public function index()
    {
        $auteurs = User::role('auteur')
            ->withCount('sujets')
            ->withCount(['sujets as sujets_approuves_count' => function ($q) {
                $q->where('approuve', 1);
            }])
            ->orderByDesc('created_at')
            ->get();

        return view('backend.pages.auteur.index', compact('auteurs'));
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
}
