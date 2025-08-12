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
        Schema::table('elementos', function (Blueprint $table) {
            $table->string('unidad_de_medida', 50)->nullable()->after('precio_unitario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elementos', function (Blueprint $table) {
            $table->dropColumn('unidad_de_medida');
        });
    }
};