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
            // récupérer les derniers sujets ajoutés (12 = 4 lignes de 3 sur la page d'accueil)
            $sujetsRecents = \App\Models\Sujet::with(['categorie', 'niveaux', 'matiere', 'media'])
                ->orderByDesc('created_at')
                ->take(12)
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
