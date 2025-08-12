<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Elemento;
use App\Models\Colaborador;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ColaboradorDashboardController extends Controller
{
    /**
     * Muestra el dashboard del colaborador.
     */
    public function index()
    {
        $colaborador = Colaborador::where('nombre', Auth::user()->nombre_usuario)->first();

        if (!$colaborador) {
            Log::error('Intento de acceso de colaborador sin registro: ' . Auth::user()->nombre_usuario);
            Auth::logout();
            return redirect()->route('login')->withErrors(['error' => 'No se encontró tu registro de colaborador. Por favor, contacta al administrador.']);
        }

        $valor_aprobado = $colaborador->pedidos()->where('estado', 'aprobado')->sum('valor_total');
        $valor_pendiente = $colaborador->pedidos()->where('estado', 'pendiente')->sum('valor_total');
        $valor_gastado = $valor_aprobado; // El valor gastado es la suma de los pedidos aprobados
        $valor_disponible = $colaborador->valor_maximo_dinero - ($valor_aprobado + $valor_pendiente);

        return view('dashboard.colaborador', compact('colaborador', 'valor_aprobado', 'valor_pendiente', 'valor_disponible', 'valor_gastado'));
    }

    /**
     * Muestra la interfaz para que el colaborador solicite elementos.
     */
    public function solicitarElementos()
    {
        $colaborador = Colaborador::where('nombre', Auth::user()->nombre_usuario)->first();
        if (!$colaborador) {
            Log::error('Intento de acceso de colaborador sin registro: ' . Auth::user()->nombre_usuario);
            Auth::logout();
            return redirect()->route('login')->withErrors(['error' => 'No se encontró tu registro de colaborador. Por favor, contacta al administrador.']);
        }

        if ($colaborador->categoria_id) {
            $elementos = Elemento::where('categoria_id', $colaborador->categoria_id)->orderBy('descripcion', 'asc')->get();
        } else {
            $elementos = collect();
        }
        
        $valor_aprobado = $colaborador->pedidos()->where('estado', 'aprobado')->sum('valor_total');
        $valor_pendiente = $colaborador->pedidos()->where('estado', 'pendiente')->sum('valor_total');
        $valor_gastado = $valor_aprobado; // El valor gastado es la suma de los pedidos aprobados
        $valor_disponible = $colaborador->valor_maximo_dinero - ($valor_aprobado + $valor_pendiente);
        
        return view('colaborador.solicitar-elementos', compact('elementos', 'colaborador', 'valor_disponible', 'valor_pendiente'));
    }

    /**
     * Almacena el pedido del colaborador.
     */
    public function crearPedido(Request $request)
    {
        $request->validate([
            'elementos' => 'required|array|min:1',
            'elementos.*.elemento_id' => 'required|exists:elementos,id',
            'elementos.*.cantidad' => 'required|numeric|min:1',
        ]);
    
        DB::beginTransaction();
    
        try {
            $colaborador = Colaborador::where('nombre', Auth::user()->nombre_usuario)->firstOrFail();
            $valor_total_pedido = 0;
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
    
            $valor_aprobado = $colaborador->pedidos()->where('estado', 'aprobado')->sum('valor_total');
            $valor_pendiente = $colaborador->pedidos()->where('estado', 'pendiente')->sum('valor_total');
            $valor_gastado_actual = $valor_aprobado + $valor_pendiente;
            $nuevo_valor_gastado = $valor_gastado_actual + $valor_total_pedido;
    
            if ($nuevo_valor_gastado > $colaborador->valor_maximo_dinero) {
                DB::rollBack();
                return back()->withErrors(['error' => 'El valor total de este pedido excede tu valor máximo asignado. No se puede crear el pedido.']);
            }
    
            $pedido = Pedido::create([
                'colaborador_id' => $colaborador->id,
                'fecha_pedido' => now()->toDateString(),
                'valor_total' => $valor_total_pedido,
                'estado' => 'pendiente',
            ]);
    
            $pedido->elementos()->attach($elementos_a_adjuntar);
            
            DB::commit();
    
            $mensaje = 'Tu pedido ha sido creado exitosamente. Está pendiente de aprobación.';
            
            return redirect()->route('colaborador.dashboard')->with('success', $mensaje);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se pudo crear el pedido. Por favor, inténtalo de nuevo.']);
        }
    }
}