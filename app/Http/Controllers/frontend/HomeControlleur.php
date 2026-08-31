<?php

namespace App\Http\Controllers\frontend;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeControlleur extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            // récupérer les derniers sujets ajoutés (8 = 2 lignes de 4 sur la page d'accueil)
            $sujetsRecents = \App\Models\Sujet::with(['categorie', 'niveaux', 'matiere'])
                ->orderByDesc('created_at')
                ->take(8)
                ->active()->approuve()
                ->get();

            // récupérer les sliders actifs
            $sliders = Slider::active()->ordered()->get();

            return view('frontend.index', compact('sujetsRecents', 'sliders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
}
