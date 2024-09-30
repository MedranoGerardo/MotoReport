@section('title', 'Marcas y Modelos')
<div class="card p-3 mt-2" style="min-height: 90vh;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Marcas y Modelos</h1>
    </div>

    <div class="modal fade" id="marca" tabindex="-1" aria-labelledby="marcaLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="marcaLabel">
                        {{
                        $idSelect ? 'Editar Marca' : 'Nueva Marca'
                        }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-12 mt-2">
                        <div class="form-group" style="display: flex;align-items: center;">
                            <input type="checkbox" wire:model="activo" id="activo">
                            <label class="form-check" for="activo" style="margin: 0;padding: 0.5rem;">Activo</label>
                        </div>
                        <div class="invalid-feedback @error('activo') was-validated is-invalid @enderror">
                            @error('activo') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Nombre</label>
                        <input wire:model="nombre" type="text" placeholder="Nombre" id="nombre"
                            class="form-control @error('nombre') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase();">
                        <div class="invalid-feedback @error('nombre') was-validated is-invalid @enderror">
                            @error('nombre') {{$message }} @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if ($idSelect)
                    <button type="button" class="btn btn-warning" wire:click="update_marca">Actualizar</button>
                    @else
                    <button type="button" class="btn btn-primary" wire:click="store_marca">Guardar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modelo" tabindex="-1" aria-labelledby="modeloLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modeloLabel">
                        {{ $marcaSelection }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-12 my-2 px-5">
                        <div class="row align-items-center flex-wrap justify-content-center">
                            <div class="col-10">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" wire:model="activoModelo" id="activoModelo">
                                    <input wire:model="nombreModelo" type="text" placeholder="Nombre" id="nombreModelo"
                                        class="form-control @error('nombreModelo') was-validated is-invalid @enderror ms-3"
                                        oninput="this.value = this.value.toUpperCase();">
                                </div>
                                <div class="invalid-feedback @error('nombreModelo') was-validated is-invalid @enderror">
                                    @error('nombreModelo') {{$message }} @enderror
                                </div>
                            </div>
                            <div class="col-2 d-flex justify-content-center align-items-center"
                                style="column-gap: 0.5rem;">
                                @if($idmodelo)
                                <button type="button" class="btn btn-warning btn-sm" wire:click="update_modelo">
                                    Actualizar
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" wire:click="cancel_edit_modelo">
                                    <i class="fas fa-times"></i>
                                </button>
                                @else
                                <button type="button" class="btn btn-primary btn-sm" wire:click="store_modelo">
                                    Guardar
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Activo</th>
                                        <th>Nombre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($listModelos) > 0)
                                    @foreach ($listModelos as $modelo)
                                    <tr>
                                        <td>
                                            <span class=" badge bg-{{ $modelo->activo ? 'success' : 'danger' }}">
                                                {{ $modelo->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td>{{ $modelo->nombre }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"
                                                wire:click="edit_modelo({{ $modelo->id }})">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="confirmarEliminar({{ $modelo->id }}, 'modelo')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="3" class="text-center">No hay modelos registrados</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <input type="search" class="form-control" placeholder="Buscar marca" wire:model.live="search"
                style="max-width: 50%;">
            <button type="button" class="btn btn-primary" wire:click="abrir_modal_marca">
                <i class="fas fa-plus"></i>
                Nueva Marca
            </button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Activo</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($marcas->count() > 0)
                        @foreach ($marcas as $marca)
                        <tr>
                            <td>
                                <span class=" badge bg-{{ $marca->activo ? 'success' : 'danger' }}">
                                    {{ $marca->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>{{ $marca->nombre }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" wire:click="edit_marca({{ $marca->id }})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-info btn-sm" wire:click="abrir_modal_modelo({{ $marca->id }})">
                                    <i class="fas fa-plus"></i> Modelos
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="confirmarEliminar({{ $marca->id }}, 'marca')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="text-center">No hay marcas registradas</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $marcas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:initialized', function () {
        Livewire.on('cerrar-modal', function (type) {
            $(`.modal#${type}`).modal('hide');
        });

        Livewire.on('abrir-modal', function (type) {
            $(`.modal#${type}`).modal('show');
        });

        Livewire.on('asignar-valores', function (data) {
            const [id, value, type] = data;
            switch (type) {
                case 'checkbox':
                    $(`#${id}`).prop('checked', value);
                    break;
                case 'html':
                    $(`#${id}`).html(value);
                    break;
                default:
                    $(`#${id}`).val(value);
                    break;
            }
        });
    });

    const confirmarEliminar = async (id, type) => {
        if (await window.Confirm('Eliminar', '¿Estas seguro de eliminar este elemento?', 'warning', 'Si, eliminar', 'Cancelar')) {
            Livewire.dispatch(`delete-${type}`, { id });
        }
    }
</script>