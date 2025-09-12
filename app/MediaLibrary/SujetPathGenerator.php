<?php

namespace App\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class SujetPathGenerator extends DefaultPathGenerator
{
    /**
     * Path pour le fichier original
     */
    public function getPath(Media $media): string
    {
        $collection = $media->collection_name; // 'corrige' ou 'non_corrige'
        $modelId = $media->model_id ?? ($media->model?->id ?? 'unknown');

        // retourne par ex: "sujets/1/corrige/"
        return "sujets/{$modelId}/{$collection}/";
    }

    /**
     * Path pour les conversions (images) 
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    /**
     * Path pour responsive images
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}
