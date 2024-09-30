@section('title', 'Inicio')
<div class="card p-3 mt-2 ">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Escritorio</h1>
  </div>


  <!-- Cards -->
  <div class="row mb-3">
    <div class="col-md-3">
      <div class="card bg-primary text-white mb-4">
        <div class="card-body d-flex justify-content-center align-items-center flex-column">
          <h3 style="font-weight: 600;text-align: center;">Clientes</h3>
          <span class="badge bg-light text-dark rounded-pill mb-3" style="font-size: 1rem;">
            {{ $clienteCount }}
          </span>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-white mb-4">
        <div class="card-body d-flex justify-content-center align-items-center flex-column">
          <h3 style="font-weight: 600;text-align: center;">Ventas</h3>
          <span class="badge bg-light text-dark rounded-pill mb-3" style="font-size: 1rem;">
            {{ $ventaCount }}
          </span>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white mb-4">
        <div class="card-body d-flex justify-content-center align-items-center flex-column">
          <h3 style="font-weight: 600;text-align: center;">Ventas Hoy</h3>
          <span class="badge bg-light text-dark rounded-pill mb-3" style="font-size: 1rem;">
            $ {{ number_format($totalVentaToday, 2, ',', '.') }}
          </span>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-danger text-white mb-4">
        <div class="card-body d-flex justify-content-center align-items-center flex-column">
          <h3 style="font-weight: 600;text-align: center;">Ventas Mes</h3>
          <span class="badge bg-light text-dark rounded-pill mb-3" style="font-size: 1rem;">
            $ {{ number_format($totalVentaMonth, 2, ',', '.') }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- parte dode estan las graficas -->
  <div class="row">
    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-body" wire:ignore.self>
          <h5 class="card-title">Totales Ultimos 30 dias</h5>
          <canvas id="chartDias" width="400" height="200"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">Totales por mes</h5>
          <canvas id="chartMes" width="400" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- tabla -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Ultimas Ventas</h5>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Codigo</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Total</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            @if ($lastVentas->count() > 0)
            @foreach ($lastVentas as $venta)
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
            </tr>
            @endforeach
            @else
            <tr>
              <td colspan="5" class="text-center">No hay registros</td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const ventasDiarias = @json($totalVentaDiarias);
    const ventasAnuales = @json($totalVentaAnuales);

    let label = ventasDiarias.map(item => moment(item.date).format('DD/MM'));
    let data = ventasDiarias.map(item => parseFloat(item.total));
    chartJs('#chartDias', label, data);

    label = ventasAnuales.map(item => months[item.month]);
    data = ventasAnuales.map(item => parseFloat(item.total));
    chartJs('#chartMes', label, data);
  });
</script>