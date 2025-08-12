<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class PedidoAdminController extends Controller
{
    /**
     * Muestra la lista de pedidos pendientes para el administrador.
     */
    public function index()
    {
        $pedidos = Pedido::with('colaborador')->where('estado', 'pendiente')->latest()->paginate(10);
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Muestra los detalles de un pedido.
     */
    public function show(Pedido $pedido)
    {
        $pedido->load('colaborador', 'elementos');
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Aprueba un pedido pendiente.
     */
    public function approve(Pedido $pedido)
    {
        $pedido->estado = 'aprobado';
        $pedido->save();

        return Redirect::route('pedidos.index')
                         ->with('success', 'Pedido #' . $pedido->id . ' aprobado exitosamente.');
    }

    /**
     * Rechaza un pedido pendiente.
     */
    public function reject(Pedido $pedido)
    {
        $pedido->estado = 'rechazado';
        $pedido->save();

        return Redirect::route('pedidos.index')
                         ->with('success', 'Pedido #' . $pedido->id . ' rechazado exitosamente.');
    }
}