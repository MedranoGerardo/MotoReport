@section('title', 'Ventas')
<div class="card p-3 mt-2" style="min-height: 90vh;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Ventas</h1>
    </div>

    <div class="mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <input type="search" class="form-control" placeholder="Buscar ventas" wire:model.live="search"
                style="max-width: 50%;">
            <a type="button" class="btn btn-primary" href="{{ route('venta',0) }}">
                <i class="fas fa-plus"></i>
                Nueva Venta
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($ventas->count() > 0)
                        @foreach ($ventas as $venta)
                        <tr>
                            <td>{{ $venta->codigo_venta }}</td>
                            <td>{{ date('d-m-Y h:i A', strtotime($venta->fecha_compra)) }}</td>
                            <td>{{ $venta->cliente_id ? $venta->cliente->nombre : 'Sin cliente' }}</td>
                            <td>${{ $venta->total }}</td>
                            <td>
                                {!! $venta->estado == "pendiente" ? '<span class="badge bg-warning">Pendiente</span>' :
                                ($venta->estado == "finalizado" ? '<span class="badge bg-success">Finalizado</span>' :
                                '<span class="badge bg-secondary">Anulada</span>') !!}
                            </td>
                            <td>
                                @if ($venta->estado != 'anulada')
                                @if ($venta->estado != 'finalizado' || Auth::user()->role == 'admin')
                                <a href="{{ route('venta', $venta->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                @else
                                <a href="{{ route('venta', $venta->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                @endif
                                @if ($venta->estado == 'finalizado')
                                <a href="{{ route('docs', $venta->id) }}" class="btn btn-success btn-sm"
                                    target="_blank">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                @endif
                                <button class="btn btn-danger btn-sm" onclick="confirmarAnulada({{ $venta->id }})">
                                    <i class="fas fa-ban"></i> Anular
                                </button>
                                @else
                                <a href="{{ route('venta', $venta->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center">No hay registros</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $ventas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const confirmarAnulada = async id => {
        if (await window.Confirm('Anular', '¿Estas seguro de anular esta venta?', 'warning', 'Si, anular', 'Cancelar')) {
            Livewire.dispatch('anular', { id });
        }
    }
</script>