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
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_entrega');
            $table->unsignedInteger('cantidad');
            $table->decimal('valor_total_entregado', 10, 2);
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('elemento_id')->constrained('elementos')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};