<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('estado', 'Activo')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role_id'  => 'required|exists:roles,id',
        ], [
            'email.unique'       => 'Este correo ya se encuentra registrado.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Bcrypt - RNF-02
            'role_id'  => $request->role_id,
            'estado'   => 'Activo',
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario registrado correctamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::where('estado', 'Activo')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ], [
            'email.unique' => 'Este correo ya se encuentra registrado.',
        ]);

        $user->update($request->only(['name', 'email', 'role_id']));

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function changeState(User $user)
    {
        // Evita que el administrador se desactive a sí mismo
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $nuevoEstado = $user->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $user->update(['estado' => $nuevoEstado]);

        return back()->with('success', "Estado del usuario actualizado a {$nuevoEstado}.");
    }
}