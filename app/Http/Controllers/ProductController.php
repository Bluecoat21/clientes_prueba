<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->latest()->paginate(10);
        $productsBajoStock = Product::whereColumn('stock', '<=', 'stock_minimo')->count();
        return view('products.index', compact('products', 'productsBajoStock'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo'        => 'required|string|max:50|unique:products,codigo',
            'nombre'        => 'required|string|max:150',
            'category_id'   => 'required|exists:categories,id',
            'supplier_id'   => 'required|exists:suppliers,id',
            'unidad_medida' => 'required|string|in:Kilo,Litro,Unidad',
            'precio'        => 'required|numeric|min:0',
            'stock'         => 'required|numeric|min:0',
            'stock_minimo'  => 'required|numeric|min:0',
        ], [
            'unidad_medida.in' => 'La unidad de medida debe ser Kilo, Litro o Unidad.',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Producto registrado en el inventario con éxito.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'codigo'        => 'required|string|max:50|unique:products,codigo,' . $product->id,
            'nombre'        => 'required|string|max:150',
            'category_id'   => 'required|exists:categories,id',
            'supplier_id'   => 'required|exists:suppliers,id',
            'unidad_medida' => 'required|string|in:Kilo,Litro,Unidad',
            'precio'        => 'required|numeric|min:0',
            'stock'         => 'required|numeric|min:0',
            'stock_minimo'  => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * RF-05: baja lógica mediante estado (no eliminación física)
     * Nota: si decides mantener destroy, asegúrate de que no tenga ventas asociadas.
     */
    public function destroy(Product $product)
    {
        if ($product->saleDetails()->exists()) {
            return back()->with('error', 'No se puede eliminar un producto con ventas registradas.');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado del sistema.');
    }
}