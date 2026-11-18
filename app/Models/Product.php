<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'codigo',
        'nombre',
        'descripcion',
        'unidad_medida',
        'precio',
        'stock',
        'stock_minimo',
    ];

    protected function casts(): array
    {
        return [
            'precio'       => 'decimal:2',
            'stock'        => 'decimal:3',   // ← antes decimal:2
            'stock_minimo' => 'decimal:3',   // ← antes decimal:2
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}