<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id',
        'fecha_venta',
        'metodo_pago',
        'subtotal',
        'impuesto',
        'total',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_venta' => 'datetime',
            'subtotal' => 'decimal:2',
            'impuesto' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /**
     * Relación: Una venta pertenece a un usuario que la procesó (1:N inverso)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una venta pertenece a un cliente mediante su DNI (1:N inverso)
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relación: Una venta contiene múltiples ítems de detalle (1:N)
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    /**
     * Alias de saleDetails() para compatibilidad con SaleController
     */
    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}