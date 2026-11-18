<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni',
        'estado',
    ];

    /**
     * Relación: Un cliente puede realizar múltiples ventas (1:N)
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}