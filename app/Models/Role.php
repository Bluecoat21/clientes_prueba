<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    /**
     * Relación: Un rol puede ser asignado a múltiples usuarios (1:N)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}