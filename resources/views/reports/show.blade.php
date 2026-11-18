@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4>Reporte: {{ $tipo }}</h4>
    <p class="text-muted">Rango: {{ $fechaInicio }} — {{ $fechaFin }}</p>

    <div class="card">
        <div class="card-body table-responsive">
            @if($tipo === 'Ventas por fechas')
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Fecha</th><th>Cliente (DNI)</th><th>Vendedor</th><th>Método</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @forelse($data as $s)
                            <tr>
                                <td>{{ $s->fecha_venta }}</td>
                                <td>{{ $s->client->dni ?? '—' }}</td>
                                <td>{{ $s->user->name }}</td>
                                <td>{{ $s->metodo_pago }}</td>
                                <td>S/ {{ number_format($s->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Sin ventas en el rango.</td></tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($tipo === 'Productos más vendidos')
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Código</th><th>Producto</th><th>U.M.</th><th>Cantidad</th><th>Ingresos</th></tr>
                    </thead>
                    <tbody>
                        @forelse($data as $p)
                            <tr>
                                <td>{{ $p->codigo }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->unidad_medida }}</td>
                                <td>{{ number_format($p->total_cantidad, 3) }}</td>
                                <td>S/ {{ number_format($p->total_ingresos, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Sin productos vendidos en el rango.</td></tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($tipo === 'Inventario')
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Código</th><th>Producto</th><th>Categoría</th><th>Proveedor</th><th>Stock</th><th>Mín.</th><th>Estado</th></tr>
                    </thead>
                    <tbody>
                        @forelse($data as $p)
                            <tr>
                                <td>{{ $p->codigo }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->category->nombre ?? '—' }}</td>
                                <td>{{ $p->supplier->nombre ?? '—' }}</td>
                                <td>{{ number_format($p->stock, 3) }}</td>
                                <td>{{ number_format($p->stock_minimo, 3) }}</td>
                                <td>
                                    @if($p->stock <= $p->stock_minimo)
                                        <span class="badge bg-danger">Stock bajo</span>
                                    @else
                                        <span class="badge bg-success">OK</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Sin productos en el inventario.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection