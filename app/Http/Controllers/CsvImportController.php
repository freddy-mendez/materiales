<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Elemento;
use App\Models\Categoria;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use SplFileObject;
use Illuminate\Support\Facades\DB;

class CsvImportController extends Controller
{
    public function showImportForm()
    {
        $categorias = Categoria::all();
        return view('csv-import.import-form', compact('categorias'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        // Forzar la conexión a UTF-8 antes de la importación
        DB::connection()->getPdo()->exec('SET NAMES utf8mb4;');

        $path = $request->file('csv_file')->store('csv-uploads');
        $file = new SplFileObject(Storage::path($path));
        $file->setFlags(SplFileObject::READ_CSV);
        $file->setCsvControl(';');

        $rowCount = 0;
        foreach ($file as $row) {
            if ($rowCount > 0 && !empty($row[0])) {
                Elemento::create([
                    'nombre' => $row[0],
                    'descripcion' => $row[1],
                    'precio_unitario' => $row[2],
                    'unidad_de_medida' => $row[3],
                    'categoria_id' => $request->categoria_id,
                ]);
            }
            $rowCount++;
        }

        Storage::delete($path);

        return Redirect::route('elementos.index')
            ->with('success', 'Archivo CSV importado exitosamente.');
    }
}