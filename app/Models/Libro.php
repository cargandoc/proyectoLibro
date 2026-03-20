<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\User;

class Libro extends Model
{
    protected $table = 'libro';

    use HasFactory;
    
    protected $fillable = [
        'titulo',
        'autor',
        'anho_publicacion',
        'genero',
        'editorial',
        'paginas',
        'calificacion',
        'localizacion',
        'inicio_lectura', 
        'fin_lectura',
        'resenha',
        'imagen',
        'estado',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
