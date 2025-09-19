<?php

namespace App\Http\Controllers;

use App\Models\Sujet;
use App\Models\Categorie;
use App\Models\Niveau;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            [
                'loc' => url('/'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => url('/sujets'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ]
        ];

        // Ajouter les sujets actifs
        $sujets = Sujet::where('statut', 'active')
                       ->where('approuve', 'oui')
                       ->get();
        
        foreach ($sujets as $sujet) {
            $urls[] = [
                'loc' => route('sujet.front.show', $sujet->libelle),
                'lastmod' => $sujet->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ];
        }

        // Ajouter les catégories
        $categories = Categorie::where('statut', 'active')->get();
        foreach ($categories as $categorie) {
            $urls[] = [
                'loc' => url('/categories/' . $categorie->libelle),
                'lastmod' => $categorie->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ];
        }

        // Ajouter les niveaux
        $niveaux = Niveau::where('statut', 'active')->get();
        foreach ($niveaux as $niveau) {
            $urls[] = [
                'loc' => url('/niveaux/' . $niveau->libelle),
                'lastmod' => $niveau->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ];
        }

        // Ajouter les matières
        $matieres = Matiere::where('statut', 'active')->get();
        foreach ($matieres as $matiere) {
            $urls[] = [
                'loc' => url('/matieres/' . $matiere->libelle),
                'lastmod' => $matiere->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.6'
            ];
        }

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}