@section('title', 'Clientes')
<div class="card p-3 mt-2" style="min-height: 90vh;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Clientes</h1>
    </div>

    <div class="modal fade" id="addClient" tabindex="-1" aria-labelledby="addClientLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" style="max-width: 70%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addClientLabel">
                        {{
                        $idSelect ? 'Editar Cliente' : 'Nuevo Cliente'
                        }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Nombre Completo</label>
                        <!-- Agregado oninput para mayúsculas -->
                        <input wire:model="nombre" type="text" placeholder="Nombre" id="nombre"
                            class="form-control @error('nombre') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase();">

                        <div class="invalid-feedback">
                            @error('nombre') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">DUI</label>
                        <input wire:model="dui" type="text" placeholder="00000000-0" id="dui"
                            class="form-control @error('dui') was-validated is-invalid @enderror">
                        <div class="invalid-feedback ">
                            @error('dui') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">NIT</label> <input wire:model="nit" type="text"
                            placeholder="0000-000000-000-0" id="nit"
                            class="form-control @error('nit') was-validated is-invalid @enderror">
                        <div class="invalid-feedback ">
                            @error('nit') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Correo</label>
                        <input wire:model="correo" type="email" placeholder="Correo" id="correo"
                            class="form-control @error('correo') was-validated is-invalid @enderror">
                        <div class="invalid-feedback ">
                            @error('correo') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Telefono</label>
                        <input wire:model="telefono" type="text" placeholder="0000-0000" id="telefono"
                            class="form-control @error('telefono') was-validated is-invalid @enderror">
                        <div class="invalid-feedback ">
                            @error('telefono') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Departamento</label>
                        <select wire:model="departamento" placeholder="Departamento" id="departamento" wire:ignore
                            class="form-control @error('departamento') was-validated is-invalid @enderror">
                        </select>
                        <div class="invalid-feedback ">
                            @error('departamento') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Municipio</label>
                        <select wire:model="municipio" placeholder="Municipio" id="municipio" wire:ignore
                            class="form-control @error('municipio') was-validated is-invalid @enderror">
                        </select>
                        <div class="invalid-feedback ">
                            @error('municipio') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Dirección</label>
                        <input wire:model="direccion" type="text" placeholder="Dirección" id="direccion"
                            class="form-control @error('direccion') was-validated is-invalid @enderror">
                        <div class="invalid-feedback ">
                            @error('direccion') {{$message }} @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if ($idSelect)
                    <button type="button" class="btn btn-warning" wire:click="update">Actualizar</button>
                    @else
                    <button type="button" class="btn btn-primary" wire:click="store">Guardar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <input type="search" class="form-control" placeholder="Buscar cliente" wire:model.live="search"
                style="max-width: 50%;">
            <button type="button" class="btn btn-primary" wire:click="abrir_modal">
                <i class="fas fa-plus"></i>
                Nuevo Cliente
            </button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Telefono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($clientes->count() > 0)
                        @foreach ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->correo }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $cliente->id }})">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </button>
                                @if(auth()->user()->role == 'admin')
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminar({{ $cliente->id }})">
                                    <i class="fas fa-trash"></i>
                                    Eliminar
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center">No hay registros</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var dataMunDep = [];
    const getDepartamentos = (dataCliente) => {
        let url = "https://api.npoint.io/253f0ee259ef1620a547/departamentos/";
        fetch(url)
            .then(response => response.json())
            .then(data => {
                dataMunDep = data;
                let select = document.getElementById('departamento');
                select.innerHTML = '';
                data.forEach(element => {
                    let option = document.createElement('option');
                    option.value = element.nombre;
                    option.setAttribute('data-id', element.id);
                    option.text = element.nombre;
                    select.appendChild(option);
                });
                $('#departamento').trigger('change');
                if (dataCliente.length > 0) {
                    $('#departamento').val(dataCliente[0].departamento).trigger('change');
                    $('#municipio').val(dataCliente[0].municipio);
                }
            });
    }

    $('#departamento').change(function () {
        let id = $(this).find(':selected').data('id');
        let municipios = dataMunDep.find(element => element.id == id).municipios;
        let select = document.getElementById('municipio');
        select.innerHTML = '';
        municipios.forEach(element => {
            let option = document.createElement('option');
            option.value = element.nombre;
            option.text = element.nombre;
            select.appendChild(option);
        });
    });

    document.addEventListener('livewire:initialized', function () {
        Livewire.on('cerrar-modal', function () {
            $('.modal').modal('hide');
        });

        Livewire.on('abrir-modal', function (data) {
            getDepartamentos(data);
            $('#addClient').modal('show');
        });
    });

    const confirmarEliminar = async id => {
        if (await window.Confirm('Eliminar', '¿Estas seguro de eliminar este cliente?', 'warning', 'Si, eliminar', 'Cancelar')) {
            Livewire.dispatch('delete', { id });
        }
    }

    //Validaciones de datos del cliente
    //Nombre
    document.getElementById('nombre').addEventListener('input', function (e) {
        let value = e.target.value;
        e.target.value = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'’`´\-.\s]/g, '');
    });

    //Dui
    function formatDUI(input) {
        let cursorPosition = input.selectionStart;
        let oldValue = input.value;
        let value = oldValue.replace(/\D/g, '');
        let addedChar = value.length > oldValue.replace(/\D/g, '').length;


        if (value.length > 8) {
            value = value.slice(0, 8) + '-' + value.slice(8, 9);
        }

        input.value = value;


        if (addedChar) {
            if (cursorPosition <= 8) {
                cursorPosition++;
            } else if (cursorPosition === 9) {
                cursorPosition += 2;
            }
        } else {
            if (cursorPosition > 0 && oldValue[cursorPosition - 1] === '-') {
                cursorPosition--;
            }
        }


        input.setSelectionRange(cursorPosition, cursorPosition);
    }

    const duiInput = document.getElementById('dui');
    duiInput.addEventListener('keydown', function (event) {
        const allowedKeys = [8, 37, 38, 39, 40];
        const value = event.target.value.replace(/\D/g, '');

        if (value.length >= 9 && !allowedKeys.includes(event.keyCode)) {
            event.preventDefault();
        }
    });

    duiInput.addEventListener('input', function (event) {
        formatDUI(event.target);
    });


    //Nit
    document.getElementById('nit').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 4) {
            value = value.slice(0, 4) + '-' + value.slice(4);
        }
        if (value.length > 11) {
            value = value.slice(0, 11) + '-' + value.slice(11);
        }
        if (value.length > 15) {
            value = value.slice(0, 15) + '-' + value.slice(15, 16);
        }
        e.target.value = value;
    });

    //Telefono
    document.getElementById('telefono').addEventListener('keydown', function (event) {
        let value = event.target.value.replace(/\D/g, '');


        if (value.length >= 8) {

            if (![8, 37, 38, 39, 40].includes(event.keyCode)) {
                event.preventDefault();
            }
        }
    });

    document.getElementById('telefono').addEventListener('input', function (event) {
        let value = event.target.value.replace(/\D/g, '');
        let cursorPosition = event.target.selectionStart;

        if (value.length > 4) {
            value = value.slice(0, 4) + '-' + value.slice(4, 8);
        }


        if (value.length > 9) {
            value = value.slice(0, 9);
        }


        event.target.value = value;


        if (cursorPosition <= 4 && event.data === null) {
            event.target.setSelectionRange(cursorPosition, cursorPosition);
        }
    });


</script>