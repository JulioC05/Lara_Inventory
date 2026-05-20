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
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('marca_id')->constrained('marcas');
            $table->foreignId('user_id')->constrained();
            $table->string('nombre', 100);
            $table->string('descripcion')->nullable();
            $table->integer('contenido_ml')->nullable();
            $table->decimal('graduacion_alcoholica', 5, 2)->nullable();
            $table->string('codigo_barras')->nullable()->unique();
            $table->string('imagen')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('margen_ganancia', 5, 2)->default(20.00);
            $table->decimal('precio_venta', 10, 2);
            $table->enum('tipo_afectacion_igv', ['gravado', 'exonerado', 'inafecto'])->default('gravado');
            $table->decimal('porcentaje_igv', 5, 2)->default(18.00);
            $table->decimal('isc', 10, 2)->default(0.00);
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['nombre', 'deleted_at']);
            $table->unique(['codigo_barras', 'deleted_at']);
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
