<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Listado de auditoría de reportes generados (RF-18).
     */
    public function index()
    {
        $reports = Report::with('user')->latest()->paginate(10);
        return view('reports.index', compact('reports'));
    }

    /**
     * Genera un reporte según el tipo solicitado y registra la auditoría (RF-16, RF-17, RF-18).
     */
    public function generate(Request $request)
    {
        $request->validate([
            'tipo'         => 'required|in:Ventas por fechas,Productos más vendidos,Inventario',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ], [
            'fecha_fin.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
        ]);

        // RF-18: registrar auditoría del reporte generado
        Report::create([
            'user_id'      => Auth::id(),
            'tipo'         => $request->tipo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
        ]);

        $tipo        = $request->tipo;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin    = $request->fecha_fin;
        $data        = [];

        switch ($tipo) {
            // RF-16: ventas filtradas por rango de fechas
            case 'Ventas por fechas':
                $data = Sale::with(['client', 'user'])
                    ->where('estado', 'Completada')
                    ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin . ' 23:59:59'])
                    ->orderBy('fecha_venta', 'desc')
                    ->get();
                break;

            // RF-17: productos más vendidos
            case 'Productos más vendidos':
                $data = DB::table('sale_details')
                    ->join('products', 'products.id', '=', 'sale_details.product_id')
                    ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
                    ->where('sales.estado', 'Completada')
                    ->whereBetween('sales.fecha_venta', [$fechaInicio, $fechaFin . ' 23:59:59'])
                    ->select(
                        'products.codigo',
                        'products.nombre',
                        'products.unidad_medida',
                        DB::raw('SUM(sale_details.cantidad) as total_cantidad'),
                        DB::raw('SUM(sale_details.subtotal) as total_ingresos')
                    )
                    ->groupBy('products.id', 'products.codigo', 'products.nombre', 'products.unidad_medida')
                    ->orderByDesc('total_cantidad')
                    ->get();
                break;

            // RF-17: estado actual del inventario (con alerta de stock mínimo — RF-06)
            case 'Inventario':
                $data = Product::with(['category', 'supplier'])->get();
                break;
        }

        return view('reports.show', compact('data', 'tipo', 'fechaInicio', 'fechaFin'))
            ->with('success', 'Reporte generado y registrado en auditoría correctamente.');
    }
}