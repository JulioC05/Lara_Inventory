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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->enum('tipo_persona', ['natural', 'juridica'])->default('natural');
            // Documentos (Ambos quedan nullable porque dependen del tipo de persona)
            $table->string('tipo_documento')->nullable();
            $table->string('numero_documento')->nullable();
            // Campos para Persona Natural
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            // Campos para Persona Jurídica
            $table->string('razon_social')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->string('contacto_nombre')->nullable();
            $table->string('contacto_cargo')->nullable();
            // Datos comunes
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('email')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
            // Índice único compuesto corregido para SoftDeletes en Laravel
            $table->unique(['tipo_documento', 'numero_documento', 'deleted_at'], 'cliente_documento_unico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
