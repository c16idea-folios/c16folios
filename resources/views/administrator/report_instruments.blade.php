@extends("$theme/layout")
@section('title') Indicadores Instrumento @endsection
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
'Reportes','Instrumentos'
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
            Indicadores Instrumento

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

            <div class="row">
                <div class="col-12 col-lg-6">
                    <h2 class="text-dark">Acto</h2>
                    <div class="container-chart">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <h2 class="text-dark">Responsable</h2>
                    <div class="container-chart" >
                        <canvas id="myChartResponsible"></canvas>
                    </div>
                </div>


                <div class="col-12 mt-4">
                    <h2 class="text-dark">Equipo</h2>
                    <div style="width: 100%; text-align:center" class="d-flex justify-content-center">
                    <div class="container-chart" style="width: 50%; ">
                        <canvas id="myChartTeam"></canvas>
                    </div>
                    </div>
                   
                </div>
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
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.js" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>




@endsection

@section('js_optional_vendors')

<script>
    $(document).ready(function() {
        const ctx = $('#myChart');

        // Obtener los valores y etiquetas de los datos
        const rawValues = @json($data['values']);
        const values = rawValues.map(value => value.percent); // Extraer solo los porcentajes para graficar
        const counts = rawValues.map(value => value.count); // Extraer las cantidades
        const labels = @json($data['labels']);

        // Verificar si todos los valores son cero
        if (values.every(value => value === 0)) {
            // Mostrar un mensaje en lugar de la gráfica
            $('#myChart').parent().html('<div class="empty-donut-chart"> <p style="text-align: center; font-size: 16px; color: #666;">No hay datos disponibles</p></div>');
        } else {
            // Configuración de datos y gráfica
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Actos',
                    data: values,
                    backgroundColor: [
    '#FF5733', // Rojo anaranjado
    '#2ECC71', // Verde esmeralda
    '#3357FF', // Azul fuerte
    '#FF33A8', // Rosa vibrante
    '#A833FF', // Púrpura oscuro
    '#33FFF5', // Cian brillante
    '#FFC733', // Amarillo cálido
    '#FF6F33', // Naranja intenso
    '#27AE60', // Verde bosque
    '#8A33FF', // Violeta fuerte
    '#FF3388', // Rosa neón
    '#FF3333', // Rojo puro
    '#33D4FF', // Azul celeste claro
    '#FFD433'  // Amarillo dorado
],
                    hoverOffset: 15 // Incrementar el tamaño del segmento al hacer hover
                }]
            };

            const config = {
                type: 'doughnut',
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
                                    const count = counts[index]; // Valor de la cantidad
                                    return `${label}: ${count} (${percent.toFixed(1)}%)`;

                                    return `${tooltipItem.label}: ${value} (${percentage}%)`;
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
            const myChart = new Chart(ctx, config);
        }
    });
</script>

<script>
    $(document).ready(function() {
        const ctx = $('#myChartResponsible');

        // Obtener los valores y etiquetas de los datos
        const rawValues = @json($data['values2']);
        const values = rawValues.map(value => value.percent); // Extraer solo los porcentajes para graficar
        const counts = rawValues.map(value => value.count); // Extraer las cantidades
        const labels = @json($data['labels2']);

        // Verificar si todos los valores son cero
        if (values.every(value => value === 0)) {
            // Mostrar un mensaje en lugar de la gráfica
            $('#myChartResponsible').parent().html('<div class="empty-donut-chart"> <p style="text-align: center; font-size: 16px; color: #666;">No hay datos disponibles</p></div>');
        } else {
            // Configuración de datos y gráfica
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Actos',
                    data: values,
          
                    hoverOffset: 15 // Incrementar el tamaño del segmento al hacer hover
                }]
            };

            const config = {
                type: 'doughnut',
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
                                    const count = counts[index]; // Valor de la cantidad
                                    return `${label}: ${count} (${percent.toFixed(1)}%)`;

                                    return `${tooltipItem.label}: ${value} (${percentage}%)`;
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
            const myChart = new Chart(ctx, config);
        }
    });
</script>

<script>
    $(document).ready(function() {
        const ctx = $('#myChartTeam');

        // Obtener los valores y etiquetas de los datos
        const rawValues = @json($data['values3']);
        const values = rawValues.map(value => value.percent); // Extraer solo los porcentajes para graficar
        const counts = rawValues.map(value => value.count); // Extraer las cantidades
        const labels = @json($data['labels3']);

        // Verificar si todos los valores son cero
        if (values.every(value => value === 0)) {
            // Mostrar un mensaje en lugar de la gráfica
            $('#myChartTeam').parent().html('<div class="empty-donut-chart"> <p style="text-align: center; font-size: 16px; color: #666;">No hay datos disponibles</p></div>');
        } else {
            // Configuración de datos y gráfica
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Actos',
                    data: values,
          
                    hoverOffset: 15 // Incrementar el tamaño del segmento al hacer hover
                }]
            };

            const config = {
                type: 'doughnut',
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
                                    const count = counts[index]; // Valor de la cantidad
                                    return `${label}: ${count} (${percent.toFixed(1)}%)`;

                                    return `${tooltipItem.label}: ${value} (${percentage}%)`;
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
            const myChart = new Chart(ctx, config);
        }
    });
</script>
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-index.js" type="text/javascript"></script>
@endsection