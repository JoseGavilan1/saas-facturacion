<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    use HasFactory;

    // Campos que permitimos guardar desde el formulario
    protected $fillable = [
        'user_id', 'nombre', 'rut', 'email', 'telefono', 'direccion', 'giro'
    ];

    // Relación: Un cliente pertenece a un Usuario (Tenant)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
