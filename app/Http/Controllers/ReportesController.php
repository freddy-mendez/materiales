<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Colaborador;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ReportesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pedidos = new Collection();
        $total_reporte = 0;
        $is_filtered_by_pedido = $request->filled('pedido_id');

        if ($user->rol === 'administrador') {
            $query = Pedido::with('colaborador')->where('estado', 'aprobado');

            if ($is_filtered_by_pedido) {
                $query->with('elementos.categoria');
            }

            if ($request->filled('colaborador_id')) {
                $query->where('colaborador_id', $request->colaborador_id);
            }
            if ($request->filled('categoria_id')) {
                $query->whereHas('elementos', function ($q) use ($request) {
                    $q->where('categoria_id', $request->categoria_id);
                });
            }
            if ($is_filtered_by_pedido) {
                $query->where('id', $request->pedido_id);
            }

            $pedidos = $query->orderBy('id', 'desc')->paginate(25);
            
            if ($is_filtered_by_pedido) {
                $total_reporte = $pedidos->sum(function($pedido) {
                    return $pedido->elementos->sum(function($elemento) {
                        return $elemento->pivot->cantidad * $elemento->pivot->precio_unitario_en_pedido;
                    });
                });
            } else {
                $total_reporte = $pedidos->sum('valor_total');
            }

            $colaboradores = Colaborador::all();
            $categorias = Categoria::all();

            // Nuevo: Calcular el reporte por colaborador para el resumen
            $reporte_colaboradores = $colaboradores->map(function ($colaborador) {
                $valor_aprobado = $colaborador->pedidos()->where('estado', 'aprobado')->sum('valor_total');
                $valor_pendiente = $colaborador->pedidos()->where('estado', 'pendiente')->sum('valor_total');
                $valor_gastado = $valor_aprobado + $valor_pendiente;
                $valor_restante = $colaborador->valor_maximo_dinero - $valor_gastado;

                return (object)[
                    'nombre' => $colaborador->nombre,
                    'valor_maximo' => $colaborador->valor_maximo_dinero,
                    'valor_aprobado' => $valor_aprobado,
                    'valor_pendiente' => $valor_pendiente,
                    'valor_gastado' => $valor_gastado,
                    'valor_restante' => $valor_restante,
                ];
            });

            return view('reports.reports', compact('pedidos', 'colaboradores', 'categorias', 'user', 'total_reporte', 'is_filtered_by_pedido', 'reporte_colaboradores'));

        } elseif ($user->rol === 'colaborador') {
            $colaborador = Colaborador::where('nombre', $user->nombre_usuario)->first();
            if ($colaborador) {
                $pedidos = Pedido::with('elementos')->where('colaborador_id', $colaborador->id)->where('estado', 'aprobado')->orderBy('id', 'desc')->paginate(25);
                $total_reporte = $pedidos->sum('valor_total');
            }
            
            $is_filtered_by_pedido = false;
            return view('reports.reports', compact('pedidos', 'user', 'total_reporte', 'is_filtered_by_pedido'));
        }

        return redirect()->route('dashboard');
    }
}