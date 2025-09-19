<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'titre',
        'description',
        'bouton_text',
        'bouton_url',
        'position',
        'statut',
    ];



   
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }



   
}