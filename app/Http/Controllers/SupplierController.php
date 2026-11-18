<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruc' => 'required|numeric|digits:11|unique:suppliers,ruc',
            'nombre' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        Supplier::create([
            'ruc' => $request->ruc,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'estado' => 'Activo',
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Proveedor registrado exitosamente.');
    }

    public function changeState(Supplier $supplier)
    {
        $nuevoEstado = $supplier->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $supplier->update(['estado' => $nuevoEstado]);

        return back()->with('success', "Estado del proveedor actualizado a {$nuevoEstado}.");
    }
        public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'ruc' => 'required|numeric|digits:11|unique:suppliers,ruc,' . $supplier->id,
            'nombre' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        $supplier->update($request->only(['ruc', 'nombre', 'telefono', 'direccion']));

        return redirect()->route('suppliers.index')->with('success', 'Proveedor actualizado correctamente.');
    }
}