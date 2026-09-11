<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    use Sluggable;

    protected $fillable = [
        'libelle',
        'slug',
        'statut',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'libelle',
            ],
        ];
    }

    // RELATIONSHIPS
    public function sujets()
    {
        return $this->hasMany(Sujet::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }
}
