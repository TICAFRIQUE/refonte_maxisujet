<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{



    public function index(Request $request)
    {
        $totalSujets = \App\Models\Sujet::count();
        $sujetsApprouves = \App\Models\Sujet::where('approuve', 1)->count();
        $sujetsNonApprouves = \App\Models\Sujet::where('approuve', 0)->count();
        $totalAuteurs = \App\Models\User::role('auteur')->count();
        $totalCategories = \App\Models\Categorie::count();
        $totalTelechargements = \App\Models\DownloadLog::count();
        $totalPoints = \App\Models\User::role('auteur')->sum('points');
        $dernierSujets = \App\Models\Sujet::with('user')->latest()->take(5)->get();
        $dernierUsers = \App\Models\User::role('auteur')->latest()->take(5)->get();

        // Sujets publiés par mois, 6 derniers mois (pour le graphique du tableau de bord)
        $moisLabels = [];
        $sujetsParMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $moisLabels[] = ucfirst($date->translatedFormat('M Y'));
            $sujetsParMois[] = \App\Models\Sujet::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return view('backend.pages.index', compact(
            'totalSujets', 'sujetsApprouves', 'sujetsNonApprouves',
            'totalAuteurs', 'totalCategories', 'totalTelechargements', 'totalPoints',
            'dernierSujets', 'dernierUsers', 'moisLabels', 'sujetsParMois'
        ));
    }
}
