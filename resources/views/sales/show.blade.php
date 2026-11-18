@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 font-weight-bold text-gray-800">Detalle de Venta #{{ $sale->id }}</h2>
    <div>
        <button onclick="window.print();" class="btn btn-outline-secondary me-2">Imprimir Comprobante</button>
        <a href="{{ route('sales.index') }}" class="btn btn-primary">Volver al Historial</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">Información de la Transacción</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Cliente (DNI):</strong> {{ $sale->client->dni ?? 'N/D' }}</p>
                <p class="mb-2"><strong>Emisor:</strong> {{ $sale->user->name ?? 'N/D' }}</p>
                <p class="mb-2"><strong>Método de Pago:</strong> {{ ucfirst($sale->metodo_pago) }}</p>
                <p class="mb-2"><strong>Fecha:</strong> {{ $sale->fecha_venta ? \Carbon\Carbon::parse($sale->fecha_venta)->format('d/m/Y H:i') : $sale->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-0">
                    <strong>Estado:</strong> 
                    <span class="badge {{ $sale->estado === 'Completada' ? 'bg-success' : 'bg-danger' }}">
                        {{ $sale->estado }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">Productos Comprados</h5>
            </div>
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Precio Unit.</th>
                            <th>Cantidad</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->details as $detail)
                            <tr>
                                <td>{{ $detail->product->nombre ?? 'Producto Eliminado' }}</td>
                                <td>S/ {{ number_format($detail->precio_unitario, 2) }}</td>
                                <td>{{ $detail->cantidad }}</td>
                                <td class="text-end fw-bold">S/ {{ number_format($detail->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                            <td class="text-end fw-bold">S/ {{ number_format($sale->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">IGV (18%):</td>
                            <td class="text-end fw-bold text-muted">S/ {{ number_format($sale->igv, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fs-5 fw-bold text-success">Total:</td>
                            <td class="text-end fs-5 fw-bold text-success">S/ {{ number_format($sale->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection