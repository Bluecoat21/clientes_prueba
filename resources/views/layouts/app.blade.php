<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema de Ventas</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts y Estilos de Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap CSS (CDN por si acaso no lo tienes en tu 7SCSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Estilos personalizados para el Sidebar -->
    <style>
        .sidebar { min-height: 100vh; background-color: #1e293b; color: white; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #334155; color: white; }
        .main-content { background-color: #f8fafc; min-height: 100vh; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-2 d-none d-md-block p-0">
            <h4 class="p-3 text-white border-bottom border-secondary">Sistema Ventas</h4>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('clients.index') }}">Clientes</a>
            <a href="{{ route('categories.index') }}">Categorías</a>
            <a href="{{ route('suppliers.index') }}">Proveedores</a>
            <a href="{{ route('products.index') }}">Productos</a>
            <a href="{{ route('sales.index') }}">Ventas</a>
            <a href="{{ route('reports.index') }}">Reportes</a>
            <a href="{{ route('roles.index') }}">Roles</a>
            <a href="{{ route('users.index') }}">Usuarios</a>
        </div>

        <!-- Contenido Principal -->
        <div class="main-content col-md-10">
            <!-- Barra Superior -->
            <nav class="navbar navbar-light bg-white border-bottom px-4 py-3 d-flex justify-content-end">
                <span class="me-3">{{ Auth::user()->name ?? 'Usuario' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Cerrar Sesión</button>
                </form>
            </nav>

            <!-- Aquí se inyectan las vistas -->
            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (OBLIGATORIO para que funcionen los modales) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>