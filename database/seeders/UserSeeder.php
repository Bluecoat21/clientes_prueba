<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nombre', 'Administrador')->first();

        User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password123'), 
            'role_id' => $adminRole ? $adminRole->id : null,
            'estado' => 'Activo',
        ]);
    }
}