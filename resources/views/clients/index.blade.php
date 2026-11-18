
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Gestión de Clientes</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClientModal">
            + Registrar Cliente
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>DNI del Cliente</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>
                                @if($client->dni)
                                    <span class="fw-semibold">{{ $client->dni }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Sin DNI (Público General)</span>
                                @endif
                            </td>
                            <td>
                                @if($client->estado === 'Activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $client->created_at->format('Y-m-d') }}</td>
                            <td>
                                <form action="{{ route('clients.changeState', $client) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-{{ $client->estado === 'Activo' ? 'danger' : 'success' }}">
                                        {{ $client->estado === 'Activo' ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No hay clientes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $clients->links() }}
        </div>
    </div>
</div>

<!-- MODAL CREAR CLIENTE -->
<div class="modal fade" id="createClientModal" tabindex="-1" x-data="clientFormHandler()">
    <div class="modal-dialog">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Checkbox Sin DNI -->
                    <div class="form-check form-switch mb-3 p-2 bg-light rounded border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="sin_dni" id="sinDniCheck" value="1" x-model="sinDni" @change="toggleSinDni()">
                        <label class="form-check-label fw-bold text-primary" for="sinDniCheck" style="cursor: pointer;">
                            Registrar cliente SIN DNI (Público General)
                        </label>
                    </div>

                    <!-- Input DNI y Botón API -->
                    <div class="mb-3" :class="{ 'opacity-50': sinDni }">
                        <label class="form-label">DNI (8 dígitos)</label>
                        <div class="input-group">
                            <input type="text" 
                                   name="dni" 
                                   x-model="dni" 
                                   class="form-control" 
                                   maxlength="8" 
                                   pattern="\d{8}" 
                                   title="Ingrese 8 números" 
                                   placeholder="Ej. 72075152"
                                   :disabled="sinDni" 
                                   :required="!sinDni">
                            <button class="btn btn-outline-primary" 
                                    type="button" 
                                    @click="consultarApi()" 
                                    :disabled="sinDni || loading || dni.length !== 8">
                                <span x-show="!loading">Consultar API</span>
                                <span x-show="loading">Buscando...</span>
                            </button>
                        </div>
                        <small class="text-muted" x-show="!sinDni">Escriba el DNI y pulse Consultar API para autocompletar.</small>
                    </div>

                    <!-- Mensaje de respuesta API -->
                    <div x-show="apiMessage" x-transition class="alert py-2 px-3 small" :class="apiSuccess ? 'alert-info' : 'alert-warning'">
                        <strong x-text="apiTitle"></strong>
                        <p class="mb-0" x-text="apiMessage"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function clientFormHandler() {
        return {
            sinDni: false,
            dni: '',
            loading: false,
            apiMessage: '',
            apiTitle: '',
            apiSuccess: false,

            toggleSinDni() {
                if (this.sinDni) {
                    this.dni = '';
                    this.apiMessage = '';
                }
            },

            async consultarApi() {
                if (this.dni.length !== 8) return;
                this.loading = true;
                this.apiMessage = '';

                try {
                    const response = await fetch(`/api/consultar-dni/${this.dni}`);
                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.apiSuccess = true;
                        this.apiTitle = 'Ciudadano Encontrado:';
                        this.apiMessage = `${data.nombres} ${data.apellidoPaterno} ${data.apellidoMaterno}`;
                    } else {
                        this.apiSuccess = false;
                        this.apiTitle = 'Aviso:';
                        this.apiMessage = data.message || 'No se hallaron registros en la API.';
                    }
                } catch (error) {
                    this.apiSuccess = false;
                    this.apiTitle = 'Error de Red:';
                    this.apiMessage = 'No se pudo conectar con el servidor de consulta.';
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection