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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago');
            $table->string('numero_comprobante', 100)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('isc', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'recibida', 'anulada'])->default('pendiente');
            $table->dateTime('fecha_pedido');
            $table->dateTime('fecha_entrega')->nullable(); // Nullable porque al inicio está "pendiente"
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
