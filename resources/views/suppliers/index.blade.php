@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Proveedores</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
            + Nuevo Proveedor
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>RUC</th>
                        <th>Nombre / Razón Social</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->ruc }}</td>
                            <td>{{ $supplier->nombre }}</td>
                            <td>{{ $supplier->telefono ?? '—' }}</td>
                            <td>{{ $supplier->direccion ?? '—' }}</td>
                            <td>
                                @if($supplier->estado === 'Activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}">Editar</button>
                                <form action="{{ route('suppliers.changeState', $supplier) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-{{ $supplier->estado === 'Activo' ? 'danger' : 'success' }}">
                                        {{ $supplier->estado === 'Activo' ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Editar Proveedor -->
                        <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Proveedor</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">RUC</label>
                                                <input type="text" name="ruc" class="form-control" value="{{ $supplier->ruc }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" name="nombre" class="form-control" value="{{ $supplier->nombre }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Teléfono</label>
                                                <input type="text" name="telefono" class="form-control" value="{{ $supplier->telefono }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Dirección</label>
                                                <input type="text" name="direccion" class="form-control" value="{{ $supplier->direccion }}">
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
                        <tr><td colspan="6" class="text-center text-muted">No hay proveedores registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $suppliers->links() }}
        </div>
    </div>
</div>

<!-- MODAL CREAR PROVEEDOR -->
<div class="modal fade" id="createSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">RUC (11 dígitos)</label>
                        <input type="text" name="ruc" class="form-control" maxlength="11" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre / Razón Social</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control">
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