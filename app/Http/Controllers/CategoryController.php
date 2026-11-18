<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categories,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Category::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => 'Activo',
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada con éxito.');
    }

    public function changeState(Category $category)
    {
        $nuevoEstado = $category->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $category->update(['estado' => $nuevoEstado]);

        return back()->with('success', "Categoría actualizada a {$nuevoEstado}.");
    }
        public function update(Request $request, Category $category)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categories,nombre,' . $category->id,
            'descripcion' => 'nullable|string|max:255',
        ]);

        $category->update($request->only(['nombre', 'descripcion']));

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada con éxito.');
    }
    }