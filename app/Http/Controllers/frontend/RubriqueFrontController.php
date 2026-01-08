<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Rubrique;
use Illuminate\Http\Request;

class RubriqueFrontController extends Controller
{
    public function actualites(Request $request)
    {
        $query = Rubrique::publiees()
                         ->actualites()
                         ->with('auteur')
                         ->ordonneesParDate();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('contenu', 'like', '%' . $request->search . '%')
                  ->orWhere('resume', 'like', '%' . $request->search . '%');
            });
        }

        $actualites = $query->paginate(12);
        $actualitesFeatured = Rubrique::publiees()
                                    ->actualites()
                                    ->featured()
                                    ->ordonneesParDate()
                                    ->take(3)
                                    ->get();

        return view('frontend.pages.actualites.index', compact('actualites', 'actualitesFeatured'));
    }

    public function astucesConseils(Request $request)
    {
        $query = Rubrique::publiees()
                         ->astucesConseils()
                         ->with('auteur')
                         ->ordonneesParDate();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('contenu', 'like', '%' . $request->search . '%')
                  ->orWhere('resume', 'like', '%' . $request->search . '%');
            });
        }

        $astucesConseils = $query->paginate(12);
        $astucesConseilsFeatured = Rubrique::publiees()
                                         ->astucesConseils()
                                         ->featured()
                                         ->ordonneesParDate()
                                         ->take(3)
                                         ->get();

        return view('frontend.pages.astuces-conseils.index', compact('astucesConseils', 'astucesConseilsFeatured'));
    }

    public function show($slug)
    {
        $rubrique = Rubrique::where('slug', $slug)
                           ->publiees()
                           ->with('auteur')
                           ->firstOrFail();

        // Incrémenter le nombre de vues
        $rubrique->incrementerVues();

        // Rubriques similaires
        $rubriquesSimilaires = Rubrique::publiees()
                                     ->where('type_rubrique', $rubrique->type_rubrique)
                                     ->where('id', '!=', $rubrique->id)
                                     ->ordonneesParDate()
                                     ->take(4)
                                     ->get();

        return view('frontend.pages.rubriques.show', compact('rubrique', 'rubriquesSimilaires'));
    }

    public function rechercheGlobale(Request $request)
    {
        $search = $request->get('q', '');
        
        if (empty($search)) {
            return redirect()->back()->with('error', 'Veuillez saisir un terme de recherche');
        }

        $rubriques = Rubrique::publiees()
                           ->where(function ($query) use ($search) {
                               $query->where('titre', 'like', '%' . $search . '%')
                                     ->orWhere('contenu', 'like', '%' . $search . '%')
                                     ->orWhere('resume', 'like', '%' . $search . '%');
                           })
                           ->with('auteur')
                           ->ordonneesParDate()
                           ->paginate(15);

        return view('frontend.pages.rubriques.recherche', compact('rubriques', 'search'));
    }

    // API pour récupérer des rubriques pour les widgets/sections
    public function getActualitesRecentes($nombre = 5)
    {
        return Rubrique::publiees()
                      ->actualites()
                      ->ordonneesParDate()
                      ->take($nombre)
                      ->get();
    }

    public function getAstucesConseils($nombre = 5)
    {
        return Rubrique::publiees()
                      ->astucesConseils()
                      ->ordonneesParDate()
                      ->take($nombre)
                      ->get();
    }
}
