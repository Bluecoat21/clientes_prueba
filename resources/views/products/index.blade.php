@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Productos</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
            + Nuevo Producto
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Alerta de stock bajo --}}
    @if(isset($productsBajoStock) && $productsBajoStock > 0)
        <div class="alert alert-warning">
            <strong>Alerta:</strong> {{ $productsBajoStock }} producto(s) tienen stock igual o por debajo del mínimo.
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>U.M.</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->codigo }}</td>
                            <td>{{ $product->nombre }}</td>
                            <td>{{ $product->category->nombre ?? '—' }}</td>
                            <td>{{ $product->supplier->nombre ?? '—' }}</td>
                            <td>{{ $product->unidad_medida }}</td>
                            <td>S/ {{ number_format($product->precio, 2) }}</td>
                            <td>
                                {{ number_format($product->stock, 3) }}
                                @if($product->stock <= $product->stock_minimo)
                                    <span class="badge bg-danger">Bajo</span>
                                @endif
                            </td>
                            <td>
                                @if($product->stock <= $product->stock_minimo)
                                    <span class="badge bg-danger">Stock bajo</span>
                                @else
                                    <span class="badge bg-success">OK</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">Editar</button>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Editar Producto --}}
                        <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form action="{{ route('products.update', $product) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Producto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Código</label>
                                                    <input type="text" name="codigo" class="form-control" value="{{ $product->codigo }}" required>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" name="nombre" class="form-control" value="{{ $product->nombre }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Categoría</label>
                                                    <select name="category_id" class="form-select" required>
                                                        @foreach(\App\Models\Category::all() as $cat)
                                                            <option value="{{ $cat->id }}" @selected($product->category_id == $cat->id)>{{ $cat->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Proveedor</label>
                                                    <select name="supplier_id" class="form-select" required>
                                                        @foreach(\App\Models\Supplier::all() as $sup)
                                                            <option value="{{ $sup->id }}" @selected($product->supplier_id == $sup->id)>{{ $sup->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Unidad de medida</label>
                                                    <select name="unidad_medida" class="form-select" required>
                                                        @foreach(['Kilo','Litro','Unidad'] as $um)
                                                            <option value="{{ $um }}" @selected($product->unidad_medida === $um)>{{ $um }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Precio</label>
                                                    <input type="number" step="0.01" name="precio" class="form-control" value="{{ $product->precio }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Stock</label>
                                                    <input type="number" step="0.001" name="stock" class="form-control" value="{{ $product->stock }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Stock mínimo</label>
                                                    <input type="number" step="0.001" name="stock_minimo" class="form-control" value="{{ $product->stock_minimo }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">No hay productos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
</div>

{{-- Modal Crear Producto --}}
<div class="modal fade" id="createProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach(\App\Models\Category::where('estado','Activo')->get() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Proveedor</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach(\App\Models\Supplier::where('estado','Activo')->get() as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Unidad de medida</label>
                            <select name="unidad_medida" class="form-select" required>
                                <option value="Kilo">Kilo</option>
                                <option value="Litro">Litro</option>
                                <option value="Unidad">Unidad</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Precio</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock</label>
                            <input type="number" step="0.001" name="stock" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock mínimo</label>
                            <input type="number" step="0.001" name="stock_minimo" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection