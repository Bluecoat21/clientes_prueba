<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        // Verificamos si el usuario marcó la opción de "Sin DNI"
        $hasSinDni = $request->has('sin_dni') && $request->sin_dni == '1';

        $request->validate([
            'dni' => [
                $hasSinDni ? 'nullable' : 'required',
                'nullable',
                'numeric',
                'digits:8',
                Rule::unique('clients', 'dni')->where(function ($query) {
                    return $query->whereNotNull('dni');
                }),
            ],
        ], [
            'dni.required' => 'El DNI es obligatorio si no selecciona la opción Sin DNI.',
            'dni.numeric'  => 'El DNI debe contener solo números.',
            'dni.digits'   => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'   => 'Este DNI ya se encuentra registrado en el sistema.',
        ]);

        // Si marcó Sin DNI, guardamos null, de lo contrario guardamos el DNI ingresado
        $dniToSave = $hasSinDni ? null : $request->dni;

        Client::create([
            'dni' => $dniToSave,
            'estado' => 'Activo',
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function changeState(Client $client)
    {
        $nuevoEstado = $client->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $client->update(['estado' => $nuevoEstado]);

        return back()->with('success', "Estado del cliente cambiado a {$nuevoEstado}.");
    }
}
