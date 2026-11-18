<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ruc',
        'telefono',
        'direccion',
        'estado',
    ];

    /**
     * Relación: Un proveedor abastece múltiples productos (1:N)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}