@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4>Reportes</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de generación --}}
    <div class="card mb-4">
        <div class="card-header">Generar nuevo reporte</div>
        <div class="card-body">
            <form action="{{ route('reports.generate') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Tipo de reporte</label>
                    <select name="tipo" class="form-select" required>
                        <option value="">-- Seleccione --</option>
                        <option value="Ventas por fechas" @selected(old('tipo') === 'Ventas por fechas')>Ventas por fechas</option>
                        <option value="Productos más vendidos" @selected(old('tipo') === 'Productos más vendidos')>Productos más vendidos</option>
                        <option value="Inventario" @selected(old('tipo') === 'Inventario')>Inventario</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Generar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Auditoría de reportes generados (RF-18) --}}
    <div class="card">
        <div class="card-header">Historial de reportes generados</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Usuario</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th>Generado el</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->tipo }}</td>
                            <td>{{ $report->user->name ?? '—' }}</td>
                            <td>{{ $report->fecha_inicio?->format('Y-m-d') }}</td>
                            <td>{{ $report->fecha_fin?->format('Y-m-d') }}</td>
                            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No se han generado reportes aún.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection