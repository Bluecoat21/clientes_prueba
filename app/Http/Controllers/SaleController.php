<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['client', 'user'])->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        $clients  = Client::where('estado', 'Activo')->get();
        return view('sales.create', compact('products', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'         => 'required|exists:clients,id',
            'metodo_pago'       => 'required|string|in:Efectivo,Virtual',
            'products'          => 'required|array|min:1',
            'products.*.id'     => 'required|exists:products,id',
            'products.*.cantidad' => 'required|numeric|gt:0',   // ← permite decimales (kilos/litros)
        ], [
            'products.*.cantidad.gt' => 'La cantidad debe ser mayor a cero.',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;

            // Validar stock y calcular subtotal antes de crear la venta
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'products' => "Stock insuficiente para {$product->nombre} (disponible: {$product->stock}).",
                    ]);
                }

                $subtotal += $product->precio * $item['cantidad'];
            }

            $impuesto = round($subtotal * 0.18, 2);
            $total    = round($subtotal + $impuesto, 2);

            $sale = Sale::create([
                'client_id'   => $request->client_id,
                'user_id'     => Auth::id(),
                'metodo_pago' => $request->metodo_pago,
                'subtotal'    => $subtotal,
                'impuesto'    => $impuesto,   // ← antes 'igv'
                'total'       => $total,
                'estado'      => 'Completada',
                'fecha_venta' => now(),
            ]);

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);

                $sale->details()->create([
                    'product_id'      => $product->id,
                    'cantidad'        => $item['cantidad'],
                    'unidad_medida'   => $product->unidad_medida,  // ← nuevo (RF-10)
                    'precio_unitario' => $product->precio,
                    'subtotal'        => round($product->precio * $item['cantidad'], 2),
                ]);

                $product->decrement('stock', $item['cantidad']);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Venta registrada exitosamente.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['client', 'user', 'details.product']);
        return view('sales.show', compact('sale'));
    }

    public function cancel(Sale $sale)
    {
        if ($sale->estado === 'Anulada') {
            return back()->with('error', 'La venta ya ha sido anulada anteriormente.');
        }

        DB::transaction(function () use ($sale) {
            $sale->update(['estado' => 'Anulada']);

            foreach ($sale->details as $detail) {
                $detail->product->increment('stock', $detail->cantidad);
            }
        });

        return back()->with('success', 'Venta anulada y stock restituido correctamente.');
    }
}