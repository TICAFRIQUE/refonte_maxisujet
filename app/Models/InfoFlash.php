<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoFlash extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'lien',
        'lien_texte',
        'type',
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
