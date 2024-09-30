<?php
function numeroATexto($numero) {
        $unidad = array('', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve');
        $diez = array(10 => 'diez', 11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince', 16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho', 19 => 'diecinueve');
        $decena = array('', '', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa');
        $centena = array('', 'cien', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos');
        $miles = array('mil', 'millón', 'mil millones', 'billón', 'mil billones', 'trillón', 'mil trillones', 'cuatrillón', 'mil cuatrillones', 'quintillón', 'mil quintillones', 'sextillón', 'mil sextillones', 'septillón', 'mil septillones', 'octillón', 'mil octillones', 'nonillón', 'mil nonillones', 'decillón', 'mil decillones');

        if ($numero == 0) {
            return 'cero';
        } elseif ($numero < 10) {
            return $unidad[$numero];
        } elseif ($numero < 20) {
            return $diez[$numero];
        } elseif ($numero < 100) {
            return $decena[intval($numero / 10)] . ($numero % 10 > 0 ? ' y ' . $unidad[$numero % 10] : '');
        } elseif ($numero < 1000) {
            return $centena[intval($numero / 100)] . ($numero % 100 > 0 ? ' ' . numeroATexto($numero % 100) : '');
        } elseif ($numero < 1000000) {
            return numeroATexto(intval($numero / 1000)) . ' ' . $miles[0] . ($numero % 1000 > 0 ? ' ' . numeroATexto($numero % 1000) : '');
        } 
        return $numero; // Para manejar números mayores a  999999
    }

function formatearFecha($fecha) {
    // Array de meses en español
    $meses = array(
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    );


    if (empty($fecha)) {
        return "Fecha no proporcionada";
    }

    // Convertir la fecha a una marca de tiempo Unix
    $timestamp = strtotime($fecha);

    if (!$timestamp) {
        return "Fecha inválida";
    }

    // Obtener el día, mes y año
    $dia = date("d", $timestamp);
    $mes = date("n", $timestamp);
    $anio = date("Y", $timestamp);

    if (!isset($meses[$mes])) {
        return "Mes inválido";
    }

    // Convertir el día y el año a texto
    $diaTexto = numeroATexto(intval($dia));
    $anioTexto = numeroATexto(intval($anio));

    // Formatear la fecha en el formato "DD de Mes del año YYYY"
    return ucfirst($diaTexto) . " días del mes de " . $meses[$mes] . " del año " . $anioTexto;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Londrina+Outline&family=Rampart+One&display=swap"
    rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Emoji:wght@300..700&display=swap" rel="stylesheet">
  <title>{{ "doc-venta-$venta->codigo_venta-" . date('Y-m-d') . ".pdf" }}</title>
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

    .highlight {
      font-weight: bold;
    }

    .signature {
      margin-top: 40px;
    }

    footer {
      text-align: center;
      margin-top: 20px;
    }

    .image-container {
      text-align: center;
      margin-top: 20px;
    }

    .image-container img {
      width: 200px;
      height: auto;
    }

    .input-box {
      border: 1px solid #000;
      display: inline-block;
      text-align: left;
      padding: 5px;
      width: 545px;
      height: 20px;
      margin-bottom: -12px;
      margin-left: 5px;
    }

    .doc3 {
      font-family: Arial, sans-serif, 'Roboto';
      display: block;
      margin: 0 auto;
      background-color: #fff;
    }

    .doc3 * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      display: block;
    }

    .doc3 .container {
      text-align: center;
      border: 2px dashed #000;
      padding: 20px 0px;
      background-color: #fff;
      width: 600px;
      display: block;
      margin: 0 auto;
    }

    .doc3 h3 {
      font-family: "Anton", sans-serif;
      font-weight: 400;
      font-style: normal;
      font-size: 100px;
      text-align: center;
      margin: 0;
      margin-top: -80px;
    }

    .doc3 h3.pricipal {
      font-size: 120px;
      margin-bottom: -50px;
    }

    .doc3 h2 {
      margin-bottom: -100px;
      height: 100px;
    }

    .doc3 .logo {
      width: 300px;
    }

    .doc3 .scissors {
      font-family: 'Noto Emoji', sans-serif;
      position: absolute;
      font-size: 1.5rem;
      margin-top: 0.45rem;
      margin-left: -2rem;
      transform: rotate(-120deg);
    }
  </style>
</head>

<body>
  @foreach ($detalle as $d)
  <div class="container doc1">
    <table style="border: 0;">
      <tr style="border: 0;">
        <td style="border: 0;">
         <!-- <img
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/asset/img/villamotoswhite.jpeg'))) }}"
            style="display: block;margin: 0 auto;width: 219px;height: 62px;"> -->
        </td>
        <td style="text-align: right; border: 0;">
         <!-- <h5 style="font-weight: 700;font-size: 14px; margin-bottom: 0;">MOTOCICLETAS REPUESTOS Y ACCESORIOS</h5>
          <p style="color:rgb(9, 9, 134);font-weight: 700;font-size: 14px;">Av. José Simeón Cañas<br>N°905-E Barrio
            Concepción<br>San
            Miguel
            Tel:2697-0802
          </p>-->
        </td>
      </tr>
    </table>
    <!--<h1>A QUIEN INTERESE:</h1> -->
   <!-- <p>
      Por medio de la presente hacemos constar a todas las autoridades que en esta empresa distribuidora de motocicletas
      el Sr. <span class="highlight">{{ $cliente->nombre }}</span> (A). TEL: <span
        class="highlight">{{$cliente->telefono}}</span>.
      Con documento de identidad personal Nº <span class="highlight">{{$cliente->dui}}</span>. Y NIT N° <span
        class="highlight">{{$cliente->nit}}</span>.
      Ha adquirido una motocicleta con las siguientes características.
    </p> -->

    <table>
      <tr>
        <th>MARCA:</th>
        <td>{{ $d->marca }}</td>
      </tr>
      <tr>
        <th>MODELO:</th>
        <td>{{ $d->modelo }}</td>
      </tr>
      <tr>
        <th>ESTILO:</th>
        <td>{{ $d->tipo }}</td>
      </tr>
      <tr>
        <th>COLOR:</th>
        <td>{{ $d->color }}</td>
      </tr>
      <tr>
        <th>POLIZA:</th>
        <td>{{ $d->poliza }}</td>
      </tr>
      <tr>
        <th>MOTOR:</th>
        <td>{{ $d->motor }}</td>
      </tr>
      <tr>
        <th>CHASIS:</th>
        <td>{{ $d->chasis }}</td>
      </tr>
    </table>

    <!--<p>
      Por parte de la empresa distribuidora Villamotos pedimos a la Policía Nacional Civil que tome consideración al
      caso. Ya que toda la documentación legal de dicha motocicleta se encuentra en proceso.
    </p> -->

    <p>
      A la vez recordándoles que la empresa se exonera ante cualquier falta multa o decomiso por parte de la Policía
      Nacional Civil. Según el artículo 18 del reglamento de tránsito y seguridad vial se prohíbe circular sin placas
      cualquier automotor.
    </p>

    <p>
      Se le extiende la presente en la Ciudad en San Miguel. a los {{ formatearFecha(date('Y-m-d H:i:s')) }}.
    </p>

    <br>
    <br>
    <br>
    <p>
      Atentamente
    </p>

    <br>
    <br>
    <br><br>
    <!--<p class="signature"
      style="text-align: center;border-top: 1px solid #000; width: 40%;display: block;margin: 0 auto;padding-top: 0.75rem;">
      SAMUEL VILLATORO<br>
      JEFE DE TIENDA
    </p> -->
  </div>
  <div class="doc2">
    <table style="border: 1px solid #000;background-color: #fff;">
      <tr style="border: 0;">
        <th style="border: 0;text-align: center;background-color: #fff;">
         <!-- <img
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/asset/img/villamotoswhite.jpeg'))) }}"
            style="display: block;margin: 0 auto;width: 100%;height: 50px;"> -->
        </th>
        <th style="border: 0;text-align: center;background-color: #fff;">
          <h2>INFORME DE NEGOCIOS</h2>
        </th>
        <th style="border: 0;background-color: #fff;line-height: 0.5;">
          <!--<p style="margin-bottom: 0;padding-bottom: 0;">Av. José Simeón Cañas N°905-E</p>
          <p style="margin-bottom: 0;padding-bottom: 0;">Barrio Concepción San Miguel</p>
          <p style="margin-bottom: 1.5rem;padding-bottom: 0;">Tel. 2697-0802</p>
          <p>Fecha: {{ date("d/m/Y", strtotime($venta->fecha_compra)) }}</p> -->
        </th>
      </tr>
      <tr>
        <td colspan="3"
          style="text-align: center;background-color: #0d3766;color: #fff; padding: 2px;border: 1px solid #000;">
          <h4 style="padding: 0;margin: 0;">DATOS DEL CLIENTE</h4>
        </td>
      </tr>
      <tr>
        <td colspan="3" style="padding: 1.25rem 10px;border: 1px solid #000;">
          <p style="font-size: 13px; margin-bottom: 1.5rem;">Nombre del Cliente: <span class="input-box">{{
              $cliente->nombre }}</span></p>
          <p style="font-size: 13px; margin-bottom: 1.5rem">
            DUI: <span class="input-box" style="margin-left:94px;width: 205px;margin-right:55px;">{{$cliente->dui
              }}</span>
            Teléfono: <span class="input-box" style="width: 206px;">{{ $cliente->telefono }}</span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem">
            NIT: <span class="input-box" style="margin-left:95px;width: 409;">{{$cliente->nit }}</span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem">
            Dirección: <span class="input-box" style="margin-left:62px;width: 408;">{{$cliente->direccion }}</span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem">
            Municipio: <span class="input-box"
              style="margin-left:61.5px;width: 205px;margin-right:21.5px;">{{$cliente->municipio
              }}</span>
            Departamento: <span class="input-box" style="width: 206px;">{{$cliente->departamento }}</span>
          </p>
        </td>
      </tr>
      <tr>
        <td colspan="3"
          style="text-align: center;background-color: #0d3766;color: #fff; padding: 2px;border: 1px solid #000;">
          <h4 style="padding: 0;margin: 0;">CARACTERISTICAS DE LA MOTOCICLETA</h4>
        </td>
      </tr>
      <tr>
        <td colspan="3" style="padding: 1.25rem 10px;border: 1px solid #000;">
          <p style="font-size: 13px; margin-bottom: 1.5rem;">
            Marca: <span class="input-box" style="margin-left: 82px;width: 210px;margin-right: 62px;">{{ $d->marca
              }}</span>
            Color: <span class="input-box" style="width: 210px;">{{ $d->color }}</span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem;">
            Modelo: <span class="input-box" style="margin-left: 76px;width: 210px;margin-right: 62px;">{{ $d->modelo
              }}</span>
            Estilo: <span class="input-box" style="width: 210px;">{{ $d->tipo }}</span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem;">
            Numero de Motor: <span class="input-box" style="margin-left: 17px;width: 541px;">{{ $d->motor }}</span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem;">
            Numero de Chasis: <span class="input-box" style="margin-left: 11px; width: 540px;">{{ $d->chasis }}</span>
          </p>
        </td>
      </tr>
      <tr>
        <td colspan="3"
          style="text-align: center;background-color: #0d3766;color: #fff; padding: 2px;border: 1px solid #000;">
          <h4 style="padding: 0;margin: 0;">DATOS DE VENTA</h4>
        </td>
      </tr>
      <tr>
        <td colspan="3" style="padding: 1.25rem 10px;border: 1px solid #000;">
          <p style="font-size: 13px; margin-bottom: 1.5rem;">
            Factura N°: <span class="input-box" style="margin-left: 57px;width: 210px;margin-right: 61px;"></span>
            Valor: <span class="input-box" style="width: 210px;">{{ number_format($d->precio, 2) }}</span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem;">
            Condición: <span class="input-box" style="margin-left: 61.5px;width: 210px;margin-right: 16px;"></span>
            Prima/Abono: <span class="input-box" style="width: 210px;"></span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem;">
            Vendedor: <span class="input-box" style="margin-left: 62.8px;width: 210px;margin-right: 58px;">{{
              $vendedor->name
              }}</span>
            Saldo: <span class="input-box" style="width: 210px;"></span>
          </p>
          <p style="font-size: 13px; margin-bottom: 1.5rem;">
            Cuota: <span class="input-box" style="margin-left: 84.5px;width: 210px;margin-right: 59px;"></span>
            Plazo: <span class="input-box" style="width: 210px;"></span>
          </p>
        </td>
      </tr>
    </table>
  </div>
  <br>
  <br>
  <br>
  <div class="doc3">
    <div class="container">
      <!--<h2>Av. José Simeón Cañas #905-E San Miguel Tel. 2697-0802</h2> -->
      <h3 class="pricipal">POLIZA</h3>
      <!--<img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/asset/img/villamotoswhite.jpeg'))) }}"
        class="logo"> -->
      <h3>{{ $d->poliza }}</h3>
      <div class="scissors">✂</div>
    </div>
  </div>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  @endforeach
</body>

</html>