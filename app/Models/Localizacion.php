<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\User;

class Localizacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'lugar',
        'user_id',
    ];

    protected $table = 'localizacion';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
