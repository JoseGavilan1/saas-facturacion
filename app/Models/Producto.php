<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nombre', 'codigo', 'descripcion', 'precio'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
