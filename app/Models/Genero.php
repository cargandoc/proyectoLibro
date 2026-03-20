<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Users;

class Genero extends Model
{
    use HasFactory;

    protected $fillable = [
        'generoLit',
        'user_id',
    ];

    protected $table = 'genero';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
