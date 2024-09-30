@section('title', $isEdit ?
($currentVenta && $currentVenta->estado == 'anulada' ? 'Ver Venta' :
(($currentVenta && $currentVenta->estado != 'finalizado') ? 'Editar Venta' :
(auth()->user()->role == 'admin' ? 'Editar Venta' : 'Ver Venta')))
: 'Nueva Venta')
<div class="card p-3 mt-2" style="min-height: 90vh;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            {{ $isEdit ?
            ($currentVenta && $currentVenta->estado == 'anulada' ? 'Ver Venta' :
            (($currentVenta && $currentVenta->estado != 'finalizado') ? 'Editar Venta' :
            (auth()->user()->role == 'admin' ? 'Editar Venta' : 'Ver Venta')))
            : 'Nueva Venta'}}
        </h1>
    </div>

    <div class="modal fade" id="addClient" tabindex="-1" aria-labelledby="addClientLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" style="max-width: 70%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addClientLabel">
                        {{
                        $idSelect ? 'Editar Detalle' : 'Nuevo Detalle'
                        }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Marca</label>
                        <select wire:model="marca" id="marca"
                            class="form-select @error('marca') was-validated is-invalid @enderror">
                            <option value="">Seleccione una marca</option>
                            @foreach ($marcas as $marca)
                            <option value="{{ $marca->nombre }}">{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback ">
                            @error('marca') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Modelo</label>
                        <select wire:model="modelo" id="modelo" wire:ignore
                            class="form-select @error('modelo') was-validated is-invalid @enderror">
                            <option value="">Seleccione un modelo</option>
                        </select>
                        <div class="invalid-feedback">
                            @error('modelo') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Color</label>
                        <input wire:model="color" type="text" placeholder="Color" id="color"
                            class="form-control @error('color') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z\s]/g, '')">
                        <div class="invalid-feedback">
                            @error('color') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Tipo</label>
                        <select wire:model="tipo" id="tipo"
                            class="form-select @error('tipo') was-validated is-invalid @enderror">
                            <option value="">Seleccione un tipo</option>
                            <option value="urbana">Urbana</option>
                            <option value="rural">Rural</option>
                        </select>
                        <div class="invalid-feedback ">
                            @error('tipo') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Chasis</label>
                        <input wire:model="chasis" type="text" placeholder="Chasis" id="chasis"
                            class="form-control @error('chasis') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9\s]/g, '')" maxlength="20">
                        <div class="invalid-feedback">
                            @error('chasis') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Motor</label>
                        <input wire:model="motor" type="text" placeholder="Motor" id="motor"
                            class="form-control @error('motor') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9\s]/g, '')" maxlength="20">
                        <div class="invalid-feedback">
                            @error('motor') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Poliza</label>
                        <input wire:model="poliza" type="text" placeholder="Poliza" id="poliza" pattern="4-[0-9]{0,5}"
                            class="form-control @error('poliza') was-validated is-invalid @enderror"
                            oninput="handlePolizaInput(this); this.value = this.value.trim(); this.value = this.value.replace(/[^0-9-]/g, ''); if (this.value.length > 7) this.value = this.value.slice(0, 7);"
                            maxlength="7">
                        <div class="invalid-feedback ">
                            @error('poliza') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Precio</label>
                        <input wire:model="precio" type="number" placeholder="Precio" id="precio" step="0.01" min="0"
                            class="form-control @error('precio') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.trim();">
                        <div class="invalid-feedback ">
                            @error('precio') {{$message }} @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if ($idSelect)
                    <button type="button" class="btn btn-warning" wire:click="update">Actualizar</button>
                    @else
                    <button type="button" class="btn btn-primary" wire:click="save">Guardar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div style="max-width: 50%; width: 100%;">
                <select wire:model="idCliente" id="idCliente" wire:change="changeCliente" class="form-select w-100" {{
                    $isEdit ? ($currentVenta && $currentVenta->estado == 'anulada' ? 'disabled' :
                    (($currentVenta && $currentVenta->estado != 'finalizado') ? '' :
                    (auth()->user()->role == 'admin' ? '' : 'disabled')))
                    : '' }}
                    >
                    <option value="">Seleccione un cliente</option>
                    @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @if ($isEdit ? ($currentVenta && $currentVenta->estado == 'anulada' ? false :
            (($currentVenta && $currentVenta->estado != 'finalizado') ? true :
            (auth()->user()->role == 'admin' ? true : false)))
            : true)
            <button type="button" class="btn btn-primary" wire:click="abrir_modal">
                <i class="fas fa-plus"></i>
                Nuevo Detalle
            </button>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Color</th>
                            <th>Chasis</th>
                            <th>Precio</th>
                            @if ($isEdit ? ($currentVenta && $currentVenta->estado == 'anulada' ? false :
                            (($currentVenta && $currentVenta->estado != 'finalizado' && count($detalleVentas) > 0) ?
                            true :
                            (auth()->user()->role == 'admin' ? true : false)))
                            : true)
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($detalleVentas) > 0)
                        @php
                        $contador = 0;
                        @endphp
                        @foreach ($detalleVentas as $detalle)
                        <tr>
                            <td>{{ ++$contador }}</td>
                            <td>{{ $detalle->marca }}</td>
                            <td>{{ $detalle->modelo }}</td>
                            <td>{{ $detalle->color }}</td>
                            <td>{{ $detalle->chasis }}</td>
                            <td>${{ number_format($detalle->precio, 2, '.', ',') }}</td>
                            @if ($isEdit ? ($currentVenta && $currentVenta->estado == 'anulada' ? false :
                            (($currentVenta && $currentVenta->estado != 'finalizado' && count($detalleVentas) > 0) ?
                            true :
                            (auth()->user()->role == 'admin' ? true : false)))
                            : true)
                            <td>
                                <button type="button" class="btn btn-warning btn-sm"
                                    wire:click="edit({{ $detalle->id }})">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmarEliminar({{ $detalle->id }})">
                                    <i class="fas fa-trash"></i>
                                    Eliminar
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center">No hay registros</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <div class="card" style="width: 100%;max-width: 420px;">
            <div class="card-body">
                <table class="table table-striped" style="max-width: 400px;">
                    <tr>
                        <td class="text-end text-success"
                            style="font-size: 1.25rem;font-weight: bold;letter-spacing: 1px;">
                            <b>Total</b>
                        </td>
                        <td style="font-size: 1.25rem;font-weight: 700;letter-spacing: 1px;">
                            ${{ number_format($total, 2, '.',',') }}
                        </td>
                    </tr>
                </table>
                @if (count($detalleVentas) > 0 && $currentVenta->estado != 'anulada')
                @if ($currentVenta && ($currentVenta->estado == 'finalizado' || $currentVenta->estado == 'anulada'))
                <a href="{{ route('docs', $idVenta) }}" target="_blank" class="btn btn-success btn-lg btn-block w-100">
                    <i class="fas fa-file-pdf"></i> Ver Documento
                </a>
                @else
                <button type="button" class="btn btn-success btn-lg btn-block w-100"
                    wire:click="saveVenta({{ $idVenta }})">
                    <i class="fas fa-check"></i>
                    Cerrar Venta
                </button>
                @endif
                @endif
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('cerrar-modal', function () {
                $('.modal').modal('hide');
            });

            Livewire.on('abrir-modal', function () {
                $('#addClient').modal('show');
            });

            Livewire.on('set-modelo', function (data) {
                let [modelo, list] = data;
                $('#modelo').empty();
                let options = '<option value="">Seleccione un modelo</option>';
                list.forEach(element => {
                    options += `<option value="${element.nombre}">${element.nombre}</option>`;
                });
                $('#modelo').html(options);
                $('#modelo').val(modelo);
            });

            Livewire.on('is-invalid-modelo', function (data) {
                let [isError] = data;
                if (isError) {
                    $('#modelo').addClass('is-invalid');
                } else {
                    $('#modelo').removeClass('is-invalid');
                }
            });

            Livewire.on('abrir-confirm-sale', async function () {
                if (await window.Confirm('Generar Documento', 'Venta Cerrada ¿Desea generar el documento de la venta?', 'info', 'Si, generar', 'No, solo cerrar')) {
                    window.open("{{ route('docs', $idVenta) }}", '_blank');
                    window.location.href = "{{ route('ventas') }}"
                } else {
                    window.location.href = "{{ route('ventas') }}"
                }
            });

            $('#marca').change(function () {
                Livewire.dispatch('change-marca', { name: this.value });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('url-update', function (url) {
                history.pushState(null, null, url);
            });
        });

        const confirmarEliminar = async id => {
            if (await window.Confirm('Eliminar', '¿Estas seguro de eliminar este detalle?', 'warning', 'Si, eliminar', 'Cancelar')) {
                Livewire.dispatch('delete', { id });
            }
        }

        //FUNCION AGREGADA PARA VALIDAR EL INPUT DE POLIZA
        function handlePolizaInput(input) {
            const value = input.value;
            if (value && !value.startsWith('4-')) {
                input.value = '4-' + value;
            }
        }
    </script>
</div>