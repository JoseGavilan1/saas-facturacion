<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'cliente_id', 'numero', 'fecha_emision',
        'estado', 'subtotal', 'iva', 'total'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function detalles() { return $this->hasMany(DetalleFactura::class); }
}
