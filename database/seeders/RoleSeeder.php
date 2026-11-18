<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso total al sistema',
        ]);

        Role::create([
            'nombre' => 'Vendedor',
            'descripcion' => 'Acceso a ventas y consulta de productos',
        ]);
    }
}