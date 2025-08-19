@extends("$theme/layout")
@section('title') Indicadores de pagos @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />


<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />

<style>
    .iti--allow-dropdown {
        display: block !important;
    }
</style>
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs')
{!! Helpers::getMenuEnable([
'Reportes','Pagos'
]) !!}
@endsection

@section('content_page')
<!-- begin:: Content -->



<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
            </span>
            <h3 class="kt-portlet__head-title">
                Indicadores de pagos


            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">


                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="container">
        <div class="form-group">
    <label for="yearSelect" class="form-control-label">Ejercicio</label>
    <select id="yearSelect" class="form-control">
        @for ($year = date('Y'); $year >= 2015; $year--)
            <option value="{{ $year }}">{{ $year }}</option>
        @endfor
    </select>
</div>


            <h2 class="text-dark">Importe pagado
            </h2>
            <canvas id="dynamicChart" width="400" height="200"></canvas>

<br><br><br>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <h2 class="text-dark">Equipo</h2>
                    <div class="container-chart d-flex justify-content-center" id="container-chart-work-team">
                        <canvas id="chartWorkTeam"></canvas>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <h2 class="text-dark">Modalidad</h2>
                    <div class="container-chart d-flex justify-content-center" id="container-chart-payment-method">
                        <canvas id="chartPaymentMethod"></canvas>
                    </div>
                </div>


            </div>

<br><br>
            <div class="container">
            <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
           
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i> Acciones
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" id="downloadExcelBtn"  >
                                        <i class="kt-nav__link-icon fas fa-file-excel"></i>
                                        <span class="kt-nav__link-text">Descargar</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
            <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom" id="kt_table">
                <thead>
                    <tr>
                   
                        <th>RFC</th>
                        <th>Cliente</th>
                        <th>Tramites</th>
                        <th>Pagos</th>
                    
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>

        </div>
    </div>
</div>


<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
<!-- end:: Content -->

@endsection


@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx-style/dist/xlsx.full.min.js"></script>

<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.js" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script src="{{asset("assets")}}/js/excel-export.js" type="text/javascript"></script>



@endsection

@section('js_optional_vendors')
<script src="{{asset("assets")}}/js/page-report-payment.js" type="text/javascript"></script>


<script>
    let chartInstance;

    function fetchChartData(year) {
        $.ajax({
            url: 'payment_report', // Endpoint ficticio (puedes cambiarlo luego)
            type: 'POST',
            data: {
                year: year,
                _token: $('#token_ajax').val()
            },
            success: function(response) {
                // Datos ficticios para simular una respuesta como la imagen proporcionada
                const labels = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                updateChart(labels, response.datasets);


                ///////////////////////

                // Extraemos los datos para la gráfica
                const labelsWorks = response.labelsWorkTeam; // Aquí asumo que labels2 es el array con las etiquetas
                const rawValues = response.valuesWorkTeam; // Aquí asumo que values2 contiene los valores
                const values = rawValues.map(value => value.percent); // Extraemos los porcentajes para la gráfica
                const cant = rawValues.map(value => value.cant); // Extraemos las cantidades

                // Verificamos si todos los valores son cero
                if (values.every(value => value === 0)) {
                    // Mostrar mensaje si no hay datos disponibles
                    $('#chartWorkTeam').parent().html('<div class="empty-donut-chart"> <p style="text-align: center; font-size: 16px; color: #666;">No hay datos disponibles</p></div>');
                } else {
                    $('#container-chart-work-team').html('<canvas id="chartWorkTeam"></canvas>');
                    // Llamamos a la función para actualizar la gráfica
                    updateChartWorkTeam(labelsWorks, values, cant);
                }


                 ///////////////////////

                // Extraemos los datos para la gráfica
                const labelsPaymentMethod = response.labelsPaymentMethod; // Aquí asumo que labels2 es el array con las etiquetas
                const rawValuesPaymentMethod = response.valuesPaymentMethod; // Aquí asumo que values2 contiene los valores
                const valuesPaymentMethod = rawValuesPaymentMethod.map(value => value.percent); // Extraemos los porcentajes para la gráfica
                const cantPaymentMethod = rawValuesPaymentMethod.map(value => value.cant); // Extraemos las cantidades

                // Verificamos si todos los valores son cero
                if (values.every(value => value === 0)) {
                    // Mostrar mensaje si no hay datos disponibles
                    $('#chartPaymentMethod').parent().html('<div class="empty-donut-chart"> <p style="text-align: center; font-size: 16px; color: #666;">No hay datos disponibles</p></div>');
                } else {
                    $('#container-chart-payment-method').html('<canvas id="chartPaymentMethod"></canvas>');
                    // Llamamos a la función para actualizar la gráfica
                    updateChartPaymentMethod(labelsPaymentMethod, valuesPaymentMethod, cantPaymentMethod);
                }

            },
            error: function() {
                console.error("Error fetching data");
            }
        });
    }

    function updateChart(labels, datasets) {
        const data = {
            labels: labels,
            datasets: datasets.map(dataset => ({
                label: dataset.label,
                data: dataset.data,
                backgroundColor: dataset.backgroundColor
            }))
        };

        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = document.getElementById('dynamicChart').getContext('2d');
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true, // Círculos en lugar de cuadros
                            pointStyle: 'circle',
                            generateLabels: (chart) => {
                                if (chart && chart.data && chart.data.datasets) {
                                    return chart.data.datasets.map((dataset, index) => ({
                                        text: dataset.label,
                                        fillStyle: dataset.backgroundColor,
                                        hidden: !chart.isDatasetVisible(index),
                                        pointStyle: 'circle',
                                        datasetIndex: index
                                    }));
                                }
                                return [];
                            }
                        },
                        onHover: (event, legendItem, legend) => {
                            const chart = legend.chart;
                            if (chart) {
                                const index = legendItem.datasetIndex;
                                // Obtener todas las barras de la gráfica
                                const dataset = chart.data.datasets[index];
                                const elements = chart.getDatasetMeta(index).data;

                                // Resaltar las barras correspondientes
                                elements.forEach((element, idx) => {
                                    if (element._model) {
                                        // Cambiar el color de la barra activa
                                        element._model.backgroundColor = "rgba(255, 99, 132, 0.6)"; // Color de resaltado
                                    }
                                });

                                chart.update();
                            }
                        },
                        onLeave: (event, legendItem, legend) => {
                            const chart = legend.chart;
                            if (chart) {
                                const index = legendItem.datasetIndex;
                                const elements = chart.getDatasetMeta(index).data;

                                // Restaurar el color original de las barras
                                elements.forEach((element, idx) => {
                                    if (element._model) {
                                        element._model.backgroundColor = chart.data.datasets[index].backgroundColor; // Restaurar el color original
                                    }
                                });

                                chart.update();
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                 // Obtener el índice del dataset
            const datasetIndex = tooltipItem.datasetIndex;
            const datasetLabel = tooltipItem.chart.data.datasets[datasetIndex].label; // Obtener la leyenda (label) del dataset
            const value = tooltipItem.raw; // Valor del dato
            return `${datasetLabel}: $${value.toLocaleString()}`; // Mostrar la leyenda en lugar del mes
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true, // Apilar las barras en el eje X
                        categoryPercentage: 0.8, // Ajusta el espacio entre las barras
                        barPercentage: 0.9, // Ajusta el tamaño de las barras dentro del espacio
                    },
                    y: {
                        stacked: true, // Apilar las barras en el eje Y
                        ticks: {
                            callback: function(value) {
                                return "$" + value.toLocaleString(); // Formatear como precio
                            }
                        }
                    }
                }
            }
        });
    }



    ////////////////////////////////////////
    let chartInstanceWorkTeam;
    function updateChartWorkTeam(labels, values, cants) {
        $(document).ready(function() {
            const ctx = document.getElementById('chartWorkTeam').getContext('2d');;

            if (chartInstanceWorkTeam) {
                chartInstanceWorkTeam.destroy();
            }

            // Datos para la gráfica
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Actos',
                    data: values,
                    hoverOffset: 15 // Incrementar el tamaño del segmento al hacer hover
                }]
            };

            const config = {
                type: 'doughnut', // Tipo de gráfica
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                generateLabels: (chart) => {
                                    const originalLabels = Chart.overrides.doughnut.plugins.legend.labels.generateLabels(chart);
                                    return originalLabels.map((label, index) => ({
                                        ...label,
                                        text: `${label.text}`,
                                        customIndex: index
                                    }));
                                }
                            },
                            onHover: (event, legendItem, legend) => {
                                const chart = legend.chart;
                                const index = legendItem.customIndex;

                                // Resaltar el segmento correspondiente
                                chart.setActiveElements([{
                                    datasetIndex: 0,
                                    index
                                }]);
                                chart.tooltip.setActiveElements([{
                                    datasetIndex: 0,
                                    index
                                }]);

                                // Obtener valor numérico correspondiente
                                const value = chart.data.datasets[0].data[index];
                                const labelElement = $(event.native.target); // Elemento del label

                                // Actualizar el texto del label para mostrar el número
                                if (!labelElement.data('original-text')) {
                                    labelElement.data('original-text', labelElement.text()); // Guardar texto original
                                }
                                const originalText = labelElement.data('original-text');
                                labelElement.text(`${originalText} ${value}`); // Agregar el número al lado del texto del label

                                chart.update();
                            },
                            onLeave: (event, legendItem, legend) => {
                                const chart = legend.chart;

                                // Quitar el resaltado del segmento
                                chart.setActiveElements([]);
                                chart.tooltip.setActiveElements([]);

                                // Restaurar el texto original del label
                                const labelElement = $(event.native.target);
                                const originalText = labelElement.data('original-text');
                                if (originalText) {
                                    labelElement.text(originalText); // Restaurar el texto original
                                }

                                chart.update();
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (tooltipItem) => {
                                    const index = tooltipItem.dataIndex;
                                    const label = tooltipItem.label;
                                    const percent = tooltipItem.raw; // Valor del porcentaje
                                    const count = cants[index]; // Valor de la cantidad
                                    return `${label}: ${count} (${percent.toFixed(1)}%)`;
                                }
                            }
                        }
                    }
                },
                plugins: [{
                    id: 'doughnutLabels', // Plugin para mostrar porcentajes en los segmentos
                    afterDatasetDraw: (chart) => {
                        const ctx = chart.ctx;
                        const dataset = chart.data.datasets[0];
                        const total = dataset.data.reduce((sum, val) => sum + val, 0);

                        dataset.data.forEach((value, index) => {
                            const meta = chart.getDatasetMeta(0).data[index];
                            const center = meta.tooltipPosition();
                            const percentage = ((value / total) * 100).toFixed(1);

                            // Dibujar porcentaje en el centro del segmento
                            ctx.save();
                            ctx.fillStyle = '#fff';
                            ctx.font = '12px Arial';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(`${percentage}%`, center.x, center.y);
                            ctx.restore();
                        });
                    }
                }]
            };

            // Crear la gráfica
            chartInstanceWorkTeam = new Chart(ctx, config);
        });
    }





    /////////////////////////////////////


    ////////////////////////////////////////
    let chartInstancePaymentMethod; // Variable global para almacenar la instancia de la gráfica
    function updateChartPaymentMethod(labels, values, cants) {
        $(document).ready(function() {
            const ctx = document.getElementById('chartPaymentMethod').getContext('2d');;

            if (chartInstancePaymentMethod) {
                chartInstancePaymentMethod.destroy();
            }

            // Datos para la gráfica
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Actos',
                    data: values,
                    hoverOffset: 15 // Incrementar el tamaño del segmento al hacer hover
                }]
            };

            const config = {
                type: 'doughnut', // Tipo de gráfica
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                generateLabels: (chart) => {
                                    const originalLabels = Chart.overrides.doughnut.plugins.legend.labels.generateLabels(chart);
                                    return originalLabels.map((label, index) => ({
                                        ...label,
                                        text: `${label.text}`,
                                        customIndex: index
                                    }));
                                }
                            },
                            onHover: (event, legendItem, legend) => {
                                const chart = legend.chart;
                                const index = legendItem.customIndex;

                                // Resaltar el segmento correspondiente
                                chart.setActiveElements([{
                                    datasetIndex: 0,
                                    index
                                }]);
                                chart.tooltip.setActiveElements([{
                                    datasetIndex: 0,
                                    index
                                }]);

                                // Obtener valor numérico correspondiente
                                const value = chart.data.datasets[0].data[index];
                                const labelElement = $(event.native.target); // Elemento del label

                                // Actualizar el texto del label para mostrar el número
                                if (!labelElement.data('original-text')) {
                                    labelElement.data('original-text', labelElement.text()); // Guardar texto original
                                }
                                const originalText = labelElement.data('original-text');
                                labelElement.text(`${originalText} ${value}`); // Agregar el número al lado del texto del label

                                chart.update();
                            },
                            onLeave: (event, legendItem, legend) => {
                                const chart = legend.chart;

                                // Quitar el resaltado del segmento
                                chart.setActiveElements([]);
                                chart.tooltip.setActiveElements([]);

                                // Restaurar el texto original del label
                                const labelElement = $(event.native.target);
                                const originalText = labelElement.data('original-text');
                                if (originalText) {
                                    labelElement.text(originalText); // Restaurar el texto original
                                }

                                chart.update();
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (tooltipItem) => {
                                    const index = tooltipItem.dataIndex;
                                    const label = tooltipItem.label;
                                    const percent = tooltipItem.raw; // Valor del porcentaje
                                    const count = cants[index]; // Valor de la cantidad
                                    return `${label}: ${count} (${percent.toFixed(1)}%)`;
                                }
                            }
                        }
                    }
                },
                plugins: [{
                    id: 'doughnutLabels', // Plugin para mostrar porcentajes en los segmentos
                    afterDatasetDraw: (chart) => {
                        const ctx = chart.ctx;
                        const dataset = chart.data.datasets[0];
                        const total = dataset.data.reduce((sum, val) => sum + val, 0);

                        dataset.data.forEach((value, index) => {
                            const meta = chart.getDatasetMeta(0).data[index];
                            const center = meta.tooltipPosition();
                            const percentage = ((value / total) * 100).toFixed(1);

                            // Dibujar porcentaje en el centro del segmento
                            ctx.save();
                            ctx.fillStyle = '#fff';
                            ctx.font = '12px Arial';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(`${percentage}%`, center.x, center.y);
                            ctx.restore();
                        });
                    }
                }]
            };

            // Crear la gráfica
            chartInstanceWorkTeam = new Chart(ctx, config);
        });
    }





    /////////////////////////////////////
    $(document).ready(function() {
        // Cargar datos al iniciar la página
        const initialYear = $('#yearSelect').val();
        fetchChartData(initialYear);

        // Cambiar datos al seleccionar un año diferente
        $('#yearSelect').on('change', function() {
            const selectedYear = $(this).val();
            fetchChartData(selectedYear);
            tableMain.search("");
            tableMain.ajax.reload(); 
        });
    });
</script>





@endsection
@section('js_page_scripts')
@endsection