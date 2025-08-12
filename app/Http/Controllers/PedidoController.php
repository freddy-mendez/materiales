<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Colaborador;
use App\Models\Elemento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Muestra la lista de todos los pedidos.
     */
    public function index()
    {
        $pedidos = Pedido::with('colaborador')->latest()->paginate(10);
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Muestra el formulario para crear un nuevo pedido.
     */
    public function create()
    {
        $colaboradores = Colaborador::all();
        $elementos = Elemento::all();
        return view('pedidos.create', compact('colaboradores', 'elementos'));
    }

    /**
     * Almacena un nuevo pedido en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validar la solicitud
        $request->validate([
            'colaborador_id' => 'required|exists:colaboradores,id',
            'fecha_pedido' => 'required|date',
            'elementos' => 'required|array|min:1',
            'elementos.*.elemento_id' => 'required|exists:elementos,id',
            'elementos.*.cantidad' => 'required|numeric|min:1',
        ]);

        // 2. Iniciar una transacción de base de datos
        DB::beginTransaction();

        try {
            $colaborador = Colaborador::find($request->colaborador_id);
            $valor_total_pedido = 0;

            // 3. Calcular el valor total del pedido
            $elementos_a_adjuntar = [];
            foreach ($request->elementos as $item) {
                $elemento = Elemento::find($item['elemento_id']);
                $subtotal = $item['cantidad'] * $elemento->precio_unitario;
                $valor_total_pedido += $subtotal;

                $elementos_a_adjuntar[$elemento->id] = [
                    'cantidad' => $item['cantidad'],
                    'precio_unitario_en_pedido' => $elemento->precio_unitario
                ];
            }

            // 4. Calcular el valor ya gastado por el colaborador
            $valor_gastado_actual = $colaborador->pedidos()->sum('valor_total');
            $nuevo_valor_gastado = $valor_gastado_actual + $valor_total_pedido;

            // 5. Verificar si se excede el valor máximo y guardar la advertencia
            $advertencia = null;
            if ($nuevo_valor_gastado > $colaborador->valor_maximo_dinero) {
                $advertencia = 'ADVERTENCIA: Este pedido excede el valor máximo de ' . $colaborador->nombre . ' por $' . number_format($nuevo_valor_gastado - $colaborador->valor_maximo_dinero, 2) . '.';
            }

            // 6. Crear el nuevo pedido
            $pedido = Pedido::create([
                'colaborador_id' => $colaborador->id,
                'fecha_pedido' => $request->fecha_pedido,
                'valor_total' => $valor_total_pedido,
                'estado' => 'pendiente', // Estado inicial
            ]);

            // 7. Adjuntar los elementos al pedido
            $pedido->elementos()->attach($elementos_a_adjuntar);
            
            DB::commit();

            // 8. Redirigir con mensaje de éxito y la advertencia (si existe)
            $mensaje = 'Pedido registrado exitosamente.';
            if ($advertencia) {
                $mensaje .= ' ' . $advertencia;
            }

            return redirect()->route('pedidos.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            // Retornar al formulario con un error genérico o el error de la excepción
            return back()->withErrors(['error' => 'No se pudo registrar el pedido. Por favor, inténtalo de nuevo.']);
        }
    }

    /**
     * Muestra los detalles de un pedido.
     */
    public function show(Pedido $pedido)
    {
        $pedido->load('colaborador', 'elementos');
        return view('pedidos.show', compact('pedido'));
    }
    
    // Los demás métodos (edit, update, destroy) no son necesarios por ahora
    public function edit() { abort(404); }
    public function update() { abort(404); }
    public function destroy() { abort(404); }
}