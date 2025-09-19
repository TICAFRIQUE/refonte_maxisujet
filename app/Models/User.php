<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasPermissions, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [

        'username',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'role',
        //autheur fields
        'profil', //['eleve', 'enseignant', 'etudiant', 'parent', 'autre']
        'last_login_at', //[derniere connexion]
        'last_login_ip', //['adresse ip']
        'points', // ['points de l'utilisateur']
        'statut', // ['actif', 'inactif']
        'created_at',
        'updated_at',
        'deleted_at',

    ];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'users', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }


    // RELATIONSHIPS
    // un user a plusieurs sujets
    public function sujets()
    {
        return $this->hasMany(Sujet::class);
    }

        // Historique des téléchargements
        public function downloads()
        {
            return $this->hasMany(DownloadLog::class);
        }






    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
