<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Colaborador;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class ReportesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pedidos = collect(); // Inicializamos una colección vacía
        $total_aprobado = 0; // Nueva variable para el total del colaborador
        $total_reporte = 0;

        if ($user->rol === 'administrador') {
            $query = Pedido::with('colaborador', 'elementos');

            if ($request->filled('colaborador_id')) {
                $query->where('colaborador_id', $request->colaborador_id);
            }
            if ($request->filled('categoria_id')) {
                $query->whereHas('elementos', function ($q) use ($request) {
                    $q->where('categoria_id', $request->categoria_id);
                });
            }
            if ($request->filled('pedido_id')) {
                $query->where('id', $request->pedido_id);
            }

            $pedidos = $query->latest()->paginate(25);
            $total_reporte = $query->sum('valor_total');
            $colaboradores = Colaborador::all();
            $categorias = Categoria::all();

            return view('reports.reports', compact('pedidos', 'colaboradores', 'categorias', 'user', 'total_reporte'));

        } elseif ($user->rol === 'colaborador') {
            $colaborador = Colaborador::where('nombre', $user->nombre_usuario)->first();
            if ($colaborador) {
                // El colaborador solo ve sus pedidos aprobados
                $pedidos = Pedido::with('elementos')->where('colaborador_id', $colaborador->id)->latest()->paginate(25);
                $total_reporte = $pedidos->where('estado', 'aprobado')->sum('valor_total');

            }

            return view('reports.reports', compact('pedidos', 'user', 'total_reporte'));
        }

        return redirect()->route('dashboard');
    }
}