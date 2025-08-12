<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ColaboradorController extends Controller
{
    // Mostrar la lista de colaboradores
    public function index()
    {
        $colaboradores = Colaborador::latest()->paginate(10);
        return view('colaboradores.index', compact('colaboradores'));
    }

    // Mostrar el formulario para crear un nuevo colaborador
    public function create()
    {
        $categorias = Categoria::all();
        return view('colaboradores.create', compact('categorias'));
    }

    // Guardar un nuevo colaborador en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'numero_identificacion' => 'required|string|max:50|unique:colaboradores,numero_identificacion',
            'valor_maximo_dinero' => 'required|numeric|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        Colaborador::create($request->all());

        return Redirect::route('colaboradores.index')
                         ->with('success', 'Colaborador creado exitosamente.');
    }

    // Mostrar la información de un colaborador específico
    public function show($id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return Redirect::route('colaboradores.index')
                             ->with('error', 'El colaborador solicitado no existe.');
        }

        return view('colaboradores.show', compact('colaborador'));
    }

    // Mostrar el formulario para editar un colaborador
    public function edit($id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return Redirect::route('colaboradores.index')
                             ->with('error', 'El colaborador solicitado no existe.');
        }
        
        $categorias = Categoria::all();
        return view('colaboradores.edit', compact('colaborador', 'categorias'));
    }

    // Actualizar un colaborador en la base de datos
    public function update(Request $request, $id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return Redirect::route('colaboradores.index')
                             ->with('error', 'El colaborador solicitado no existe.');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'numero_identificacion' => 'required|string|max:50|unique:colaboradores,numero_identificacion,'.$colaborador->id,
            'valor_maximo_dinero' => 'required|numeric|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $colaborador->update($request->all());

        return Redirect::route('colaboradores.index')
                         ->with('success', 'Colaborador actualizado exitosamente.');
    }

    // Eliminar un colaborador de la base de datos
    public function destroy($id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
             return Redirect::route('colaboradores.index')
                             ->with('error', 'El colaborador solicitado no existe.');
        }

        if ($colaborador->pedidos()->exists()) {
            return Redirect::route('colaboradores.index')
                             ->with('error', 'No se puede eliminar el colaborador porque tiene pedidos asociados.');
        }

        $colaborador->delete();

        return Redirect::route('colaboradores.index')
                         ->with('success', 'Colaborador eliminado exitosamente.');
    }
}