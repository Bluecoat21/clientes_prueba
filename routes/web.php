<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Ruta Raíz / Dashboard principal
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard.index');

    // Ruta provisional para evitar el error de perfil de Breeze
    Route::get('/profile', function () {
        return redirect()->route('dashboard');
    })->name('profile.edit');

    // Módulo de Clientes (Solo DNI y Estado)
    Route::resource('clients', ClientController::class);
    Route::patch('clients/{client}/state', [ClientController::class, 'changeState'])->name('clients.changeState');

    // Módulo de Categorías
    Route::resource('categories', CategoryController::class);
    Route::patch('categories/{category}/state', [CategoryController::class, 'changeState'])->name('categories.changeState');

    // Módulo de Proveedores
    Route::resource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/state', [SupplierController::class, 'changeState'])->name('suppliers.changeState');

    // Módulo de Productos e Inventario
    Route::resource('products', ProductController::class);

    // Módulo de Ventas
    Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');

    // Módulo de Reportes
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    // Módulo de Roles (RF-14)
    Route::resource('roles', RoleController::class)->only(['index', 'store', 'update']);
    Route::patch('roles/{role}/state', [RoleController::class, 'changeState'])->name('roles.changeState');

    // Módulo de Usuarios (RF-13)
    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::patch('users/{user}/state', [UserController::class, 'changeState'])->name('users.changeState');
});

// Cargar las rutas de autenticación de Breeze
require __DIR__.'/auth.php';