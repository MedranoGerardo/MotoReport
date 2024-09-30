@section('title', 'Reportes')
<div class="card p-3 mt-2" style="min-height: 90vh;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Reportes</h1>
    </div>

    <div class="mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <select class="form-select" id="reportType">
                    <option value="">Selecione el tipo de reporte</option>
                    <option value="1">Reporte de ventas</option>
                    <option value="2">Reporte de ventas por vendedores</option>
                    <option value="3">Reporte de ventas por cliente</option>
                </select>
            </div>
            <button type="button" class="btn btn-primary" id="btnGenerateReport">
                <i class="fas fa-print"></i>
                Generar Reporte
            </button>
        </div>
    </div>

    <div class="mb-3 row" id="reportBySeller">
        <div class="col-12 col-md-12 mt-2">
            <label for="exampleFormControlInput1" class="form-label">Vendedor</label>
            <select class="form-select" id="seller_id">
                <option value="">Todos</option>
                @foreach ($users as $seller)
                <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 mt-2">
            <label for="exampleFormControlInput1" class="form-label">Fecha inicial</label>
            <input type="date" class="form-control" id="desde-seller" placeholder="Fecha inicial">
        </div>
        <div class="col-12 col-md-6 mt-2">
            <label for="exampleFormControlInput1" class="form-label">Fecha final</label>
            <input type="date" class="form-control" id="hasta-seller" placeholder="Fecha final">
        </div>
    </div>

    <div class="mb-3 row" id="reportByClient">
        <div class="col-12 col-md-12 mt-2">
            <label for="exampleFormControlInput1" class="form-label">Cliente</label>
            <select class="form-select" id="client_id">
                <option value="">Todos</option>
                @foreach ($clientes as $client)
                <option value="{{ $client->id }}">{{ $client->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 mt-2">
            <label for="exampleFormControlInput1" class="form-label">Fecha inicial</label>
            <input type="date" class="form-control" id="desde-client" placeholder="Fecha inicial">
        </div>
        <div class="col-12 col-md-6 mt-2">
            <label for="exampleFormControlInput1" class="form-label">Fecha final</label>
            <input type="date" class="form-control" id="hasta-client" placeholder="Fecha final">
        </div>
    </div>

    <div class="mb-3 row" id="reportByDate">
        <div class="col-12 col-md-6 mt-2">
            <label for="exampleFormControlInput1" class="form-label">Fecha inicial</label>
            <input type="date" class="form-control" id="desde" placeholder="Fecha inicial">
        </div>
        <div class="col-12 col-md-6 mt-2">
            <label for="exampleFormControlInput1" class="form-label">Fecha final</label>
            <input type="date" class="form-control" id="hasta" placeholder="Fecha final">
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        const url = "{{ route('reportes-doc') }}";
        $('#reportType').on('change', function () {
            if ($(this).val() == 1) {
                $('#reportByDate').show();
                $('#reportByClient').hide();
                $('#reportBySeller').hide();
            } else if ($(this).val() == 2) {
                $('#reportByDate').hide();
                $('#reportByClient').hide();
                $('#reportBySeller').show();
            } else if ($(this).val() == 3) {
                $('#reportByDate').hide();
                $('#reportByClient').show();
                $('#reportBySeller').hide();
            } else {
                $('#reportByDate').hide();
                $('#reportByClient').hide();
                $('#reportBySeller').hide();
            }
        })
        $('#reportType').trigger('change');
        $('#btnGenerateReport').on('click', function () {
            let reportType = $('#reportType').val();
            let desde = $('#desde').val();
            let hasta = $('#hasta').val();
            let desdeClient = $('#desde-client').val();
            let hastaClient = $('#hasta-client').val();
            let desdeSeller = $('#desde-seller').val();
            let hastaSeller = $('#hasta-seller').val();
            let seller_id = $('#seller_id').val();
            let nameSeller = $('#seller_id option:selected').text();
            let client_id = $('#client_id').val();
            let nombreCliente = $('#client_id option:selected').text();

            let params = `type=${reportType}`;
            let nameFile = "";

            if (reportType == "") {
                Alert('Error', 'Favor de seleccionar un tipo de reporte', 'error');
                return;
            }

            if (reportType == 1) {
                if (desde == '') {
                    Alert('Error', 'Favor de seleccionar una fecha inicial', 'error');
                    return;
                }

                if (hasta == '') {
                    Alert('Error', 'Favor de seleccionar una fecha final', 'error');
                    return;
                }

                if (desde > hasta) {
                    Alert('Error', 'La fecha inicial no puede ser mayor a la fecha final', 'error');
                    return;
                }

                params += `&desde=${desde}&hasta=${hasta}`;
                nameFile = `reporte de ventas ${desde} a ${hasta}`;
            }

            if (reportType == 2) {
                if (desdeSeller == '') {
                    Alert('Error', 'Favor de seleccionar una fecha inicial', 'error');
                    return;
                }

                if (hastaSeller == '') {
                    Alert('Error', 'Favor de seleccionar una fecha final', 'error');
                    return;
                }

                if (desdeSeller > hastaSeller) {
                    Alert('Error', 'La fecha inicial no puede ser mayor a la fecha final', 'error');
                    return;
                }

                params += `&seller_id=${seller_id}&desde=${desdeSeller}&hasta=${hastaSeller}`;
                nameFile = `reporte de ventas ${nameSeller} ${desdeSeller} a ${hastaSeller}`;
            }

            if (reportType == 3) {
                if (desdeClient == '') {
                    Alert('Error', 'Favor de seleccionar una fecha inicial', 'error');
                    return;
                }

                if (hastaClient == '') {
                    Alert('Error', 'Favor de seleccionar una fecha final', 'error');
                    return;
                }

                if (desdeClient > hastaClient) {
                    Alert('Error', 'La fecha inicial no puede ser mayor a la fecha final', 'error');
                    return;
                }

                params += `&client_id=${client_id}&desde=${desdeClient}&hasta=${hastaClient}`;
                nameFile = `reporte de ventas cliente ${nombreCliente} ${desdeClient} a ${hastaClient}`;
            }

            /* Generar reporte */
            params += `&nameFile=${nameFile}.pdf`;
            showLoader();
            fetch(url + '?' + params).then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = nameFile.replaceAll(" ", '-') + '.pdf';
                    document.body.appendChild(a);
                    hiddenLoader();
                    a.click();
                    setTimeout(() => {
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    }, 0);
                });
        });
    });
</script>