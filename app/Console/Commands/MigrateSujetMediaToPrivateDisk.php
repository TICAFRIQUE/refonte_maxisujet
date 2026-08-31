<?php

namespace App\Console\Commands;

use App\Models\Sujet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;

class MigrateSujetMediaToPrivateDisk extends Command
{
    /**
     * Sujet::registerMediaCollections() envoie désormais les fichiers "corrige" et
     * "non_corrige" sur le disque privé "local" (storage/app/private) au lieu du disque
     * "public" (accessible par URL directe sans authentification ni points). Cette commande
     * migre les fichiers déjà uploadés avant ce changement : elle ne concerne que les lignes
     * dont le disque enregistré en base est encore "public".
     */
    protected $signature = 'sujets:migrate-media-to-private {--dry-run : Lister les fichiers concernés sans rien déplacer}';

    protected $description = 'Migre les fichiers de sujets/corrigés du disque public vers le disque privé "local"';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $mediaItems = Media::where('model_type', Sujet::class)
            ->whereIn('collection_name', ['corrige', 'non_corrige'])
            ->where('disk', 'public')
            ->get();

        if ($mediaItems->isEmpty()) {
            $this->info('Aucun fichier à migrer : tout est déjà sur le disque privé.');
            return self::SUCCESS;
        }

        $this->info("{$mediaItems->count()} fichier(s) trouvé(s) sur le disque public.");

        if ($dryRun) {
            $this->table(
                ['Media ID', 'Sujet ID', 'Collection', 'Fichier'],
                $mediaItems->map(fn (Media $m) => [$m->id, $m->model_id, $m->collection_name, $m->file_name])
            );
            $this->comment('Mode --dry-run : rien n\'a été déplacé.');
            return self::SUCCESS;
        }

        $migrated = 0;
        $failed = 0;

        foreach ($mediaItems as $media) {
            // Sujet utilise un PathGenerator personnalisé ("sujets/{id}/{collection}/"),
            // pas le format par défaut de Spatie ("{media_id}/") : on le respecte ici
            // pour ne pas chercher les fichiers au mauvais endroit.
            $relativePath = PathGeneratorFactory::create($media)->getPath($media) . $media->file_name;

            try {
                if (!Storage::disk('public')->exists($relativePath)) {
                    $this->warn("Fichier source introuvable pour media #{$media->id} ({$relativePath}), ignoré.");
                    $failed++;
                    continue;
                }

                $contents = Storage::disk('public')->get($relativePath);
                Storage::disk('local')->put($relativePath, $contents);

                $copiedSize = Storage::disk('local')->size($relativePath);
                if ($copiedSize !== $media->size) {
                    $this->error("Taille incohérente après copie pour media #{$media->id}, fichier public conservé.");
                    Storage::disk('local')->delete($relativePath);
                    $failed++;
                    continue;
                }

                $media->disk = 'local';
                $media->save();

                Storage::disk('public')->delete($relativePath);

                $migrated++;
                $this->line("Migré : media #{$media->id} ({$media->file_name})");
            } catch (\Throwable $e) {
                $this->error("Échec pour media #{$media->id} : {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("Terminé : {$migrated} fichier(s) migré(s), {$failed} échec(s).");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
