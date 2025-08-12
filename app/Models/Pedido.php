<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'colaborador_id',
        'fecha_pedido',
        'valor_total',
        'estado',
    ];

    /**
     * Relación: Un pedido pertenece a un colaborador.
     */
    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }

    /**
     * Relación: Un pedido tiene muchos elementos.
     */
    public function elementos(): BelongsToMany
    {
        return $this->belongsToMany(Elemento::class, 'pedido_elementos')
                    ->withPivot('cantidad', 'precio_unitario_en_pedido')
                    ->withTimestamps();
    }
}