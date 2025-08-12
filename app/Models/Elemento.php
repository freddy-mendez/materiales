<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Elemento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_unitario',
        'unidad_de_medida', // Campo nuevo
        'categoria_id',
    ];

    /**
     * Relación: Un elemento pertenece a una categoría.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relación: Un elemento puede estar en muchos pedidos.
     */
    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedido::class, 'pedido_elementos')
                    ->withPivot('cantidad', 'precio_unitario_en_pedido')
                    ->withTimestamps();
    }
}