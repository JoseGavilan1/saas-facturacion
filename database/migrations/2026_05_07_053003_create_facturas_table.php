<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // restrictOnDelete evita que borres un cliente si ya tiene facturas emitidas
            $table->foreignId('cliente_id')->constrained()->restrictOnDelete();

            // Datos de la factura
            $table->string('numero')->nullable(); // Folio (puede ser nulo mientras es borrador)
            $table->date('fecha_emision')->useCurrent();
            $table->string('estado')->default('Borrador'); // Borrador, Emitida, Anulada

            // Totales (Guardados como enteros para CLP)
            $table->integer('subtotal')->default(0); // Neto
            $table->integer('iva')->default(0);      // 19%
            $table->integer('total')->default(0);    // Neto + IVA

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
