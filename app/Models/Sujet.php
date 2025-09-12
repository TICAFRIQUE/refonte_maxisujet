<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Sujet extends Model implements HasMedia
{
    //
    use InteractsWithMedia;


    protected $fillable = [
        'code',
        'libelle', // unique  = libelle categorie+random(5)
        'description',
        'statut', // enum['active', 'desactive']
        'approuve', // boolean
        'annee',
        'categorie_id',
        'matiere_id',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function niveaux()
    {
        return $this->belongsToMany(Niveau::class, 'niveau_sujet', 'sujet_id', 'niveau_id');
    }



    /**
     * Registers the media collections used by the model.
     * 
     * In this case, the model uses two collections:
     * - 'corrige' for the corrected file
     * - 'non_corrige' for the uncorrected file
     * 
     * The custom path for each collection is set to "sujets/{id}/corrige" and "sujets/{id}/non_corrige" respectively.
     * The disk used is the "public" disk.
     */
    // public function registerMediaCollections(): void
    // {
    //     // // Collection pour corrigé
    //     // $this->addMediaCollection('corrige')
    //     //     ->useDisk('public')
    //     //     ->useCustomPath(fn(Media $media) => "sujets/{$this->id}/corrige");

    //     // // Collection pour non corrigé
    //     // $this->addMediaCollection('non_corrige')
    //     //     ->useDisk('public')
    //     //     ->useCustomPath(fn(Media $media) => "sujets/{$this->id}/non_corrige");

    //     $this->addMediaCollection('corrige')->useDisk('public');
    //     $this->addMediaCollection('non_corrige')->useDisk('public');
    // }
}
