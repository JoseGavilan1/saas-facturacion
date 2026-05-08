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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            // Relación con el usuario dueño del producto
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('nombre');
            $table->string('codigo')->nullable(); // Para un SKU o código interno
            $table->text('descripcion')->nullable();
            $table->integer('precio'); // Guardamos el valor neto o bruto (como entero)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
