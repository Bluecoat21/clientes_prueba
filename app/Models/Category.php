<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    /**
     * Relación: Una categoría agrupa múltiples productos (1:N)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}