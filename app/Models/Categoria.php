<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
    ];

    /**
     * Relación: Una categoría tiene muchos elementos.
     */
    public function elementos(): HasMany
    {
        return $this->hasMany(Elemento::class);
    }
}