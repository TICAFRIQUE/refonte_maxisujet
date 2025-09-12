<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    //
    use HasFactory, sluggable;

    public $incrementing = false;

    protected $fillable = [
        'libelle', // unique
        'slug',
        'statut',
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'libelle'
            ]
        ];
    }


    //

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'matieres', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }


    // RELATIONSHIPS
    // une matiere peut avoir plusieurs sujets
    public function sujets()
    {
        return $this->hasMany(Sujet::class);
    }
}
