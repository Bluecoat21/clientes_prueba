@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
            <div class="container-fluid py-3">
                <h1 class="display-5 fw-bold text-dark">Bienvenido al Sistema de Ventas</h1>
                <p class="col-md-8 fs-5 text-muted mt-3">
                    El sistema se encuentra operativo y conectado a la base de datos MySQL. Utiliza el menú lateral para navegar entre los módulos:
                </p>
                <hr class="my-4">
                <div class="d-flex gap-3">
                    <a href="{{ route('sales.create') }}" class="btn btn-success btn-lg fw-bold">+ Registrar Nueva Venta</a>
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-primary btn-lg">Gestionar Clientes (DNI)</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tarjetas de Acceso Rápido / Resumen -->
<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-primary fw-bold">Inventario de Productos</h5>
                <p class="card-text text-muted">Controla el stock actual, precios y visualiza alertas automáticas por stock mínimo.</p>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">Ir a Productos</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-success fw-bold">Gestión de Ventas</h5>
                <p class="card-text text-muted">Realiza transacciones con cálculo de IGV, selección de clientes y anulación con restitución de stock.</p>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-success">Ver Ventas</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-info fw-bold">Reportes y Auditoría</h5>
                <p class="card-text text-muted">Genera reportes diarios, mensuales, anuales y visualiza el registro de auditoría de consultas.</p>
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-info text-white">Ver Reportes</a>
            </div>
        </div>
    </div>
</div>
@endsection