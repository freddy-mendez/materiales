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
        // Si la tabla 'entregas' existe, la eliminamos para reemplazarla
        Schema::dropIfExists('entregas');

        // Tabla para almacenar los pedidos de los colaboradores
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onUpdate('cascade')->onDelete('restrict');
            $table->date('fecha_pedido');
            $table->decimal('valor_total', 10, 2);
            $table->string('estado', 50)->default('pendiente'); // Ejemplo de estados: pendiente, aprobado, rechazado
            $table->timestamps();
        });

        // Tabla pivote para los elementos que están en cada pedido
        Schema::create('pedido_elementos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('elemento_id')->constrained('elementos')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario_en_pedido', 10, 2); // Guardamos el precio al momento del pedido
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_elementos');
        Schema::dropIfExists('pedidos');
    }
};