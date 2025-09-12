<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorie extends Model
{
    //
    use HasFactory, sluggable;

    public $incrementing = false;

    protected $fillable = [
        'libelle', // unique
        'slug',
        'statut', 
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'categories', 'length' => 10, 'prefix' =>
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
    // une categorie peut avoir plusieurs sujets
    public function sujets()
    {
        return $this->hasMany(Sujet::class);
    }
}
