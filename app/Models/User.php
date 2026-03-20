<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Libro;
use App\Models\Genero;
use App\Models\Localizacion;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function libro()
    {
        return $this->hasMany(Libro::class);
    }

    public function genero()
    {
        return $this->hasMany(Genero::class);
    }

    public function localizacion()
    {
        return $this->hasMany(Localizacion::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {

            Genero::create([
                'generoLit' => 'Desconocido',
                'user_id' => $user->id
            ]);

            Localizacion::create([
                'lugar' => 'Desconocido',
                'user_id' => $user->id
            ]);

        });
    }
}
