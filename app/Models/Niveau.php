<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Niveau extends Model
{
    //

    use HasFactory, sluggable;

    public $incrementing = false;

    protected $fillable = [
        'libelle', // unique
        'slug',
        'statut',
        'url',
        'position',
        'parent_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'niveaux', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'libelle'
            ]
        ];
    }


    // RELATIONSHIPS

    public function children()
    {
        return $this->hasMany(Niveau::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Niveau::class, 'parent_id');
    }

    public function getPrincipalNiveaux() // recuperer la Niveau principale 
    {
        if ($this->parent) {
            return $this->parent->getPrincipalNiveaux();
        }

        return $this;
    }

    /**
     * Relation vers la catégorie principale (celle sans parent)
     */
    public function principalNiveaux()
    {
        return $this->parent()->with('principalNiveaux');
    }


    // un niveau peut avoir plusieurs sujets (cycle)
    // un sujet peut appartenir a plusieurs niveaux (cycle)
    // relation many to many entre niveau et sujet via la table pivot niveau_sujet
    public function sujets()
    {
        return $this->belongsToMany(Sujet::class, 'niveau_sujet', 'niveau_id', 'sujet_id');
    }

    // scopes
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }
}
