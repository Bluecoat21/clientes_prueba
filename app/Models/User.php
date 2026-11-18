<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Asegura el cifrado automático Bcrypt/Argon de Laravel
        ];
    }

    /**
     * Relación Eloquent: Un usuario pertenece a un rol (1:N inverso)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación Eloquent: Un usuario puede registrar múltiples ventas (1:N)
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Relación Eloquent: Un usuario puede generar múltiples reportes (1:N)
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}