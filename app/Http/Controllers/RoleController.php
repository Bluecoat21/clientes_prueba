<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->latest()->paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:50|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique'   => 'Este rol ya se encuentra registrado.',
        ]);

        Role::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado'      => 'Activo',
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol registrado correctamente.');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'nombre'      => 'required|string|max:50|unique:roles,nombre,' . $role->id,
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique'   => 'Este rol ya se encuentra registrado.',
        ]);

        $role->update($request->only(['nombre', 'descripcion']));

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function changeState(Role $role)
    {
        $nuevoEstado = $role->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $role->update(['estado' => $nuevoEstado]);

        return back()->with('success', "Estado del rol actualizado a {$nuevoEstado}.");
    }
}