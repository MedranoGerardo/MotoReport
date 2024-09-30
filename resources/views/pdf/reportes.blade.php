<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ str_replace(' ', '-', $nameFile)}}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.5;
      font-size: 12px;
    }

    .container {
      background: #fff;
      padding: 0rem 4rem;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: justify;
    }

    h1 {
      text-align: left;
      font-size: 14px;
    }

    p {
      margin: 10px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    table,
    th,
    td {
      border: 1px solid #ddd;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>
  <table style="border: 0;width: 100%;">
    <tr style="border: 0;">
      <td style="border: 0;">
       <!-- <img
          src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/asset/img/villamotoswhite.jpeg'))) }}"
          style="display: block;margin: 0 auto;width: 219px;height: 62px;"> -->
      </td>
      <td style="text-align: right; border: 0;">
      <!--  <h5 style="font-weight: 700;font-size: 14px; margin-bottom: 0;">MOTOCICLETAS REPUESTOS Y ACCESORIOS</h5>
        <p style="color:rgb(9, 9, 134);font-weight: 700;font-size: 14px;">Av. José Simeón Cañas<br>N°905-E Barrio
          Concepción<br>San
          Miguel
          Tel:2697-0802
        </p> -->
      </td>
    </tr>
  </table>

  <table>
    <tr>
      <td style="text-align: center; background-color: #9c9c9c;"
        colspan="{{ count($columns) == 0 ? 2 : count($columns) }}">
        <h1 style="text-align: center;">
          {{ strtoupper(str_replace('.pdf', '', $nameFile)) }}
        </h1>
      </td>
    </tr>

    <tr>
      @foreach ($columns as $column)
      <td style="text-align: center; background-color: #9c9c9c;">
        {{ $column }}
      </td>
      @endforeach
    </tr>
    @php
    $total = 0;
    $count = 0;
    @endphp
    @foreach ($data as $item)
    <tr>
      @foreach ($columns as $column)
      <td>
        @php
        $column = strtoupper($column);
        $column = str_replace('_', ' ', $column);
        switch ($column) {
        case 'FECHA':
        echo date('d/m/Y h:m A', strtotime($item->$column));
        break;
        case 'TOTAL':
        $count++;
        $total += doubleval($item->$column);
        echo '$ '.number_format($item->$column, 2, '.', ',');
        break;
        case 'ESTADO':{
        $color = "";
        switch ($item->$column) {
        case 'pendiente':
        $color = "style='background-color:#f0ad4e;padding: 5px 10px;'";
        break;
        case 'finalizado':
        $color = "style='background-color:#5cb85c;padding: 5px 10px;'";
        break;
        case 'anulada':
        $color = "style='background-color:#d9534f;padding: 5px 10px;'";
        break;
        }
        echo "<span $color>".strtoupper($item->$column)."</span>";
        }
        break;
        default:
        echo strtoupper($item->$column);
        }
        @endphp
      </td>
      @endforeach
    </tr>
    @endforeach

    <tr>
      <td colspan="{{ ceil(count($columns) / 2) }}">
        <h2 style="text-align: center;">
          TOTAL DE ELEMENTOS: {{ $count }}
        </h2>
      </td>
      <td colspan="{{ ceil(count($columns) / 2) }}">
        <h2 style="text-align: center;">
          TOTAL: $ {{ number_format($total, 2, '.', ',') }}
        </h2>
      </td>
    </tr>
  </table>
</body>

</html>