<?php

namespace App\Console\Commands;

use App\Models\Niveau;
use App\Models\Sujet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Vide les tables sujets et niveaux (fichiers/médias associés compris) avant un
 * import propre depuis les données récupérées de l'ancien site — utile quand
 * l'environnement (dev ou prod) ne contient encore que des données de démo/test,
 * pas de vrais sujets soumis par des utilisateurs. Ne touche jamais à
 * categories/matieres/concours/etablissements.
 *
 * Prend une sauvegarde SQL automatique avant de supprimer quoi que ce soit
 * (recovery/backups/, non versionné) si `mysqldump` est disponible sur le serveur ;
 * sinon prévient et laisse confirmer explicitement la poursuite sans sauvegarde.
 */
class ResetSujetsNiveaux extends Command
{
    protected $signature = 'legacy:reset-sujets-niveaux {--force : Ne pas demander de confirmation interactive}';

    protected $description = 'Vide sujets + niveaux (avec sauvegarde SQL automatique) avant un import propre';

    public function handle(): int
    {
        $sujetsCount = Sujet::count();
        $niveauxCount = Niveau::count();

        $this->info("État actuel : {$sujetsCount} sujet(s), {$niveauxCount} niveau(x).");

        if ($sujetsCount === 0 && $niveauxCount === 0) {
            $this->info('Rien à supprimer.');
            return self::SUCCESS;
        }

        $backedUp = $this->backup();

        if (!$backedUp && !$this->option('force')) {
            if (!$this->confirm('La sauvegarde automatique a échoué (mysqldump indisponible ?). Continuer quand même SANS sauvegarde ?', false)) {
                $this->warn('Annulé.');
                return self::FAILURE;
            }
        }

        if (!$this->option('force')) {
            if (!$this->confirm("Supprimer définitivement {$sujetsCount} sujet(s) et {$niveauxCount} niveau(x) ?", false)) {
                $this->warn('Annulé.');
                return self::FAILURE;
            }
        }

        Sujet::all()->each(function (Sujet $sujet) {
            $sujet->clearMediaCollection('non_corrige');
            $sujet->clearMediaCollection('corrige');
            $sujet->delete();
        });

        Niveau::query()->delete();

        // Résidus éventuels d'anciens sujets déjà supprimés par le passé sans nettoyage média.
        $orphans = Media::where('model_type', Sujet::class)->get();
        $orphans->each(fn (Media $m) => $m->delete());

        $this->info("Supprimé : {$sujetsCount} sujet(s), {$niveauxCount} niveau(x)" . ($orphans->count() ? ", {$orphans->count()} média(s) orphelin(s)" : '') . '.');
        $this->info('sujets=' . Sujet::count() . ' | niveaux=' . Niveau::count());

        return self::SUCCESS;
    }

    private function backup(): bool
    {
        $dir = base_path('recovery/backups');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $file = $dir . DIRECTORY_SEPARATOR . 'avant_reset_sujets_niveaux_' . now()->format('Ymd_His') . '.sql';
        $db = config('database.connections.mysql');

        $command = [
            'mysqldump',
            '-u', $db['username'],
            '-h', $db['host'],
        ];
        if (!empty($db['password'])) {
            $command[] = '-p' . $db['password'];
        }
        $command[] = $db['database'];
        $command = array_merge($command, ['sujets', 'niveaux', 'niveau_sujet', 'media', 'download_logs']);

        $result = Process::run($command);

        if (!$result->successful() || trim($result->output()) === '') {
            $this->warn('Sauvegarde automatique impossible (mysqldump indisponible ou erreur) : ' . $result->errorOutput());
            return false;
        }

        file_put_contents($file, $result->output());
        $this->info("Sauvegarde écrite : {$file}");

        return true;
    }
}
