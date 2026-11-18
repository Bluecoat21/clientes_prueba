@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 font-weight-bold text-gray-800">Historial de Ventas</h2>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">+ Nueva Venta</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID Venta</th>
                        <th>Cliente (DNI)</th>
                        <th>Usuario Emisor</th>
                        <th>Método Pago</th>
                        <th>Fecha y Hora</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="fw-bold">#{{ $sale->id }}</td>
                            <td>{{ $sale->client->dni ?? 'N/D' }}</td>
                            <td>{{ $sale->user->name ?? 'N/D' }}</td>
                            <td>{{ ucfirst($sale->metodo_pago) }}</td>
                            <td>{{ $sale->fecha_venta ? \Carbon\Carbon::parse($sale->fecha_venta)->format('d/m/Y H:i') : $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold text-success">S/ {{ number_format($sale->total, 2) }}</td>
                            <td>
                                <span class="badge {{ $sale->estado === 'Completada' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $sale->estado }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if($sale->estado === 'Completada')
                                    <form action="{{ route('sales.cancel', $sale) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de anular la venta y restituir el stock al inventario?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Anular Venta</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No hay transacciones de ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sales->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $sales->links() }}
        </div>
    @endif
</div>
@endsection