<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Rubrique extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'titre',
        'slug',
        'contenu',
        'resume',
        'type_rubrique',
        'image_principale',
        'est_publie',
        'est_featured',
        'ordre_affichage',
        'tags',
        'auteur_id',
        'date_publication',
        'nb_vues'
    ];

    protected $casts = [
        'tags' => 'array',
        'est_publie' => 'boolean',
        'est_featured' => 'boolean',
        'date_publication' => 'datetime',
        'nb_vues' => 'integer'
    ];

    const TYPE_ACTUALITE = 'actualite';
    const TYPE_ASTUCE_CONSEIL = 'astuce_conseil';

    public static function getTypesRubriques(): array
    {
        return [
            self::TYPE_ACTUALITE => 'Actualité',
            self::TYPE_ASTUCE_CONSEIL => 'Astuces & Conseils'
        ];
    }

    // Relations
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    // Scopes
    public function scopePubliees($query)
    {
        return $query->where('est_publie', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('est_featured', true);
    }

    public function scopeActualites($query)
    {
        return $query->where('type_rubrique', self::TYPE_ACTUALITE);
    }

    public function scopeAstucesConseils($query)
    {
        return $query->where('type_rubrique', self::TYPE_ASTUCE_CONSEIL);
    }

    public function scopeOrdonneesParDate($query)
    {
        return $query->orderBy('date_publication', 'desc');
    }

    // Accesseurs
    public function getTypeRubriqueLibelleAttribute(): string
    {
        return self::getTypesRubriques()[$this->type_rubrique] ?? '';
    }

    public function getEstPublieTexteAttribute(): string
    {
        return $this->est_publie ? 'Publié' : 'Brouillon';
    }

    // Méthodes
    public function incrementerVues(): void
    {
        $this->increment('nb_vues');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rubrique) {
            if (empty($rubrique->slug)) {
                $rubrique->slug = Str::slug($rubrique->titre);
            }
        });

        static::updating(function ($rubrique) {
            if ($rubrique->isDirty('titre')) {
                $rubrique->slug = Str::slug($rubrique->titre);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image_principale')
              ->singleFile()
              ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg']);

        $this->addMediaCollection('images_contenu')
              ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(300)
              ->height(200)
              ->sharpen(10);

        $this->addMediaConversion('medium')
              ->width(800)
              ->height(600)
              ->sharpen(10);
    }
}
