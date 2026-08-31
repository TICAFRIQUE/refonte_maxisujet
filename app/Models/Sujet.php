<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Sujet extends Model implements HasMedia
{
    //
    use InteractsWithMedia;


    protected $fillable = [
        'code',
        'libelle', // unique  = libelle categorie+random(5)
        'description',
        'statut', // enum['active', 'desactive']
        'approuve', // boolean [true, false]
        'annee',
        'categorie_id',
        'matiere_id',
        'concours_id',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'sujets', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }




    // RELATIONSHIPS
    // un sujet appartient a une categorie, une matiere et un user (auteur)
    // un sujet peut appartenir a plusieurs niveaux (cycle)
    // un niveau peut avoir plusieurs sujets (cycle)
    // relation many to many entre niveau et sujet via la table pivot niveau_sujet
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
    public function concours()
    {
        return $this->belongsTo(Concours::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function niveaux()
    {
        return $this->belongsToMany(Niveau::class, 'niveau_sujet', 'sujet_id', 'niveau_id');
    }

    public function downloads()
        {
            return $this->hasMany(DownloadLog::class);
        }




    /**
     * Sujets et corrigés sont des fichiers payants (1 point/téléchargement) : ils vivent sur le
     * disque "local" (storage/app/private, non exposé publiquement), jamais sur "public". Tout
     * accès passe donc obligatoirement par un contrôleur authentifié (apercu()/download() côté
     * front, preview() côté admin) qui lit le fichier avec Media::getPath(), pas par une URL.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('corrige')->useDisk('local');
        $this->addMediaCollection('non_corrige')->useDisk('local');
    }


    // scopes
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }
    public function scopeApprouve($query)
    {
        return $query->where('approuve', true);
    }
}
