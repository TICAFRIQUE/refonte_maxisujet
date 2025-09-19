<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Générer les métas SEO pour un sujet
     */
    public static function generateSujetMetas($sujet)
    {
        return [
            'title' => "Sujet de {$sujet->matiere->libelle} - {$sujet->titre} | MaxiSujets",
            'description' => "Téléchargez le sujet de {$sujet->matiere->libelle} : {$sujet->titre}. Document éducatif gratuit pour {$sujet->niveau->libelle} avec corrigé disponible.",
            'keywords' => "{$sujet->matiere->libelle}, {$sujet->niveau->libelle}, sujet gratuit, exercice corrigé, téléchargement, {$sujet->titre}",
            'og_title' => "{$sujet->titre} - Sujet de {$sujet->matiere->libelle}",
            'og_description' => "Téléchargez gratuitement ce sujet de {$sujet->matiere->libelle} pour {$sujet->niveau->libelle} avec corrigé détaillé.",
            'og_image' => $sujet->getFirstMediaUrl('image_sujet') ?: asset('frontend/images/sujet-default.jpg')
        ];
    }

    /**
     * Générer les métas SEO pour une catégorie
     */
    public static function generateCategorieMetas($categorie)
    {
        return [
            'title' => "Documents de {$categorie->libelle} - Cours et Exercices | MaxiSujets",
            'description' => "Découvrez tous les documents de {$categorie->libelle} : cours, exercices corrigés, examens. Ressources éducatives gratuites pour tous les niveaux.",
            'keywords' => "{$categorie->libelle}, cours, exercices, examens, documents gratuits, téléchargement",
            'og_title' => "Documents de {$categorie->libelle} - MaxiSujets",
            'og_description' => "Tous les documents de {$categorie->libelle} en téléchargement gratuit."
        ];
    }

    /**
     * Générer les métas SEO pour un niveau
     */
    public static function generateNiveauMetas($niveau)
    {
        return [
            'title' => "Documents pour {$niveau->libelle} - Cours et Exercices | MaxiSujets",
            'description' => "Téléchargez gratuitement tous les documents pour {$niveau->libelle} : cours, exercices corrigés, examens blancs, sujets de concours.",
            'keywords' => "{$niveau->libelle}, cours, exercices, examens, documents scolaires, téléchargement gratuit",
            'og_title' => "Documents pour {$niveau->libelle} - MaxiSujets",
            'og_description' => "Toutes les ressources éducatives pour {$niveau->libelle} en téléchargement gratuit."
        ];
    }

    /**
     * Générer les métas SEO pour une matière
     */
    public static function generateMatiereMetas($matiere)
    {
        return [
            'title' => "Cours de {$matiere->libelle} - Exercices et Sujets | MaxiSujets",
            'description' => "Téléchargez gratuitement les cours de {$matiere->libelle}, exercices corrigés et sujets d'examens pour tous les niveaux.",
            'keywords' => "{$matiere->libelle}, cours, exercices corrigés, sujets examens, documents gratuits",
            'og_title' => "Cours de {$matiere->libelle} - MaxiSujets",
            'og_description' => "Tous les documents de {$matiere->libelle} en téléchargement gratuit."
        ];
    }

    /**
     * Générer un sitemap simple
     */
    public static function generateSitemap()
    {
        $urls = [
            [
                'url' => url('/'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'url' => url('/sujets'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ]
        ];

        // Ajouter les sujets
        $sujets = \App\Models\Sujet::where('statut', 'active')->get();
        foreach ($sujets as $sujet) {
            $urls[] = [
                'url' => route('sujet.front.show', $sujet->libelle),
                'lastmod' => $sujet->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ];
        }

        return $urls;
    }
}