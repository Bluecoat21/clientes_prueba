@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4>Registrar Venta</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST" class="card p-3">
        @csrf
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Cliente (DNI)</label>
                <select name="client_id" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>{{ $client->dni }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Método de pago</label>
                <select name="metodo_pago" class="form-select" required>
                    <option value="Efectivo" @selected(old('metodo_pago') === 'Efectivo')>Efectivo</option>
                    <option value="Virtual" @selected(old('metodo_pago') === 'Virtual')>Virtual</option>
                </select>
            </div>
        </div>

        <h5>Productos</h5>
        <div id="items-container">
            <div class="row g-3 mb-2 item-row">
                <div class="col-md-6">
                    <select name="products[0][id]" class="form-select" required>
                        <option value="">-- Producto --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->codigo }} - {{ $p->nombre }} (S/ {{ $p->precio }} / {{ $p->unidad_medida }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.001" name="products[0][cantidad]" class="form-control" placeholder="Cantidad" required>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger btn-remove">Quitar</button>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <button type="button" id="add-item" class="btn btn-outline-primary btn-sm">+ Agregar otro producto</button>
        </div>

        <div>
            <button type="submit" class="btn btn-success">Registrar Venta</button>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    let index = 1;
    const container = document.getElementById('items-container');

    document.getElementById('add-item').addEventListener('click', function () {
        const row = document.createElement('div');
        row.classList.add('row', 'g-3', 'mb-2', 'item-row');
        row.innerHTML = `
            <div class="col-md-6">
                <select name="products[${index}][id]" class="form-select" required>
                    <option value="">-- Producto --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->codigo }} - {{ $p->nombre }} (S/ {{ $p->precio }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.001" name="products[${index}][cantidad]" class="form-control" placeholder="Cantidad" required>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-remove">Quitar</button>
            </div>
        `;
        container.appendChild(row);
        index++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>
@endsection