<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria; // Importamos el modelo Categoria

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'LOTE 1: ARTESANÍAS',
            'LOTE 2: 3D',
            'LOTE 3: BISUTERÍA',
            'LOTE 6: SERIGRAFÍA',
            'LOTE 8: SISTEMAS',
            'LOTE 9: JOYERÍA',
            'LOTE 10: CARPINTERÍA',
            'LOTE 4: CONFECCIONES',
            'LOTE 5: GESTIÓN ADMINISTRATIVA',
            'LOTE 7: AUDIOVISUALES',
        ];

        foreach ($categorias as $categoria) {
            Categoria::create([
                'nombre' => $categoria,
            ]);
        }
    }
}