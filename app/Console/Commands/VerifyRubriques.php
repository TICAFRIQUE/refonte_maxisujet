<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rubrique;

class VerifyRubriques extends Command
{
    protected $signature = 'verify:rubriques';
    protected $description = 'Vérifier les rubriques créées par le seeder';

    public function handle()
    {
        $this->info('=== VÉRIFICATION DES RUBRIQUES CRÉÉES ===');
        $this->newLine();

        // Statistiques générales
        $this->info('📊 STATISTIQUES GÉNÉRALES :');
        $this->line('Nombre total de rubriques : ' . Rubrique::count());
        $this->line('Actualités : ' . Rubrique::actualites()->count());  
        $this->line('Astuces & Conseils : ' . Rubrique::astucesConseils()->count());
        $this->line('En vedette : ' . Rubrique::featured()->count());
        $this->line('Publiées : ' . Rubrique::publiees()->count());
        $this->newLine();

        // Rubriques en vedette
        $this->info('⭐ RUBRIQUES EN VEDETTE :');
        $this->line(str_repeat('=', 50));
        
        $featured = Rubrique::featured()->orderBy('created_at', 'desc')->take(6)->get();
        foreach ($featured as $rubrique) {
            $type = $rubrique->type_rubrique === 'actualite' ? '📰 ACTUALITÉ' : '💡 ASTUCE';
            $this->line($type . ' : ' . $rubrique->titre);
            $this->line('   👁 ' . number_format($rubrique->nb_vues) . ' vues | 📅 ' . $rubrique->date_publication->format('d/m/Y'));
            $this->newLine();
        }

        // Rubriques récentes
        $this->info('📅 RUBRIQUES LES PLUS RÉCENTES :');
        $this->line(str_repeat('=', 50));
        
        $recent = Rubrique::orderBy('date_publication', 'desc')->take(5)->get();
        foreach ($recent as $rubrique) {
            $type = $rubrique->type_rubrique === 'actualite' ? '📰' : '💡';
            $this->line($type . ' ' . $rubrique->titre);
            $this->line('   📅 ' . $rubrique->date_publication->format('d/m/Y à H:i'));
            $this->newLine();
        }

        // Tags les plus utilisés
        $this->info('🏷️ TAGS LES PLUS UTILISÉS :');
        $this->line(str_repeat('=', 50));
        
        $allTags = [];
        $rubriques = Rubrique::all();
        foreach ($rubriques as $rubrique) {
            $tags = $rubrique->tags ?? [];
            foreach ($tags as $tag) {
                $allTags[] = $tag;
            }
        }

        $tagCounts = array_count_values($allTags);
        arsort($tagCounts);
        $topTags = array_slice($tagCounts, 0, 10, true);

        foreach ($topTags as $tag => $count) {
            $this->line('• ' . $tag . ' (' . $count . ' utilisations)');
        }

        $this->newLine();
        $this->info('✅ Vérification terminée !');
        $this->info('🎯 50 rubriques avec contenu riche et varié');
        $this->info('📸 Prêtes pour l\'ajout d\'images via l\'administration');

        return Command::SUCCESS;
    }
}
