@extends("$theme/layout")
@section('title') Instrumentos cancelados @endsection
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
'Instrumentos','Cancelados'
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
            Instrumentos cancelados
            </h3>
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
    <div class="kt-portlet__body">
        <div class="container">

            <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom" id="kt_table">
                <thead>
                    <tr>
                       
                        <th>Tipo instrumento</th>
                        <th>No. Instrumento</th>
                        <th>Fecha instrumento</th>
                        <th>Acto</th>
                        <th>Cliente(s)</th>
                        <th>Total</th>
                        <th>Pagado</th>
                        <th>Pendiente</th>
                        <th>Responsable</th>



                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
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

<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-timepicker/init.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>

<script src="{{asset("assets")}}/js/excel-export.js" type="text/javascript"></script>

<script>
    // Numeric only control handler
    jQuery.fn.ForceNumericOnly = function() {
        return this.each(function() {
            $(this).keydown(function(e) {
                var key = e.charCode || e.keyCode || 0;
                // Allow: backspace, tab, delete, enter, arrows, and numbers ONLY
                return (
                    key == 8 || // Backspace
                    key == 9 || // Tab
                    key == 13 || // Enter
                    key == 46 || // Delete
                    (key >= 35 && key <= 40) || // Arrow keys/Home/End
                    (key >= 48 && key <= 57) || // Numbers 0-9
                    (key >= 96 && key <= 105) // Numpad numbers 0-9
                );
            });
        });
    };


        // Numeric with single decimal point only
        jQuery.fn.ForceNumericDotOnly = function() {
        return this.each(function() {
            $(this).on('keydown input', function(e) {
                var key = e.charCode || e.keyCode || 0;
                var currentValue = $(this).val();

                // Allow: backspace, tab, enter, delete, arrow keys, numbers, and a single dot
                if (e.type === 'keydown') {
                    if (
                        key == 8 || // Backspace
                        key == 9 || // Tab
                        key == 13 || // Enter
                        key == 46 || // Delete
                        (key >= 35 && key <= 40) || // Arrow keys/Home/End
                        (key >= 48 && key <= 57) || // Numbers 0-9
                        (key >= 96 && key <= 105) // Numpad numbers 0-9
                    ) {
                        return true; // Allow these keys
                    }

                    // Allow a single period (dot), but only if not already present
                    if ((key == 110 || key == 190) && currentValue.indexOf('.') === -1) {
                        return true;
                    }

                    // Block everything else
                    return false;
                }

                // Extra validation on 'input' event to clean up invalid content
                if (e.type === 'input') {
                    // Remove anything that's not a digit or a single dot
                    $(this).val(currentValue.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'));
                }
            });
        });
    };

    $("#amount_paid_e,#amount_paid").ForceNumericDotOnly();

    $(document).ready(function() {
        $('#payment_date,#payment_date_e,#presentation_date,#presentation_date_e').datepicker({
            format: 'yyyy-mm-dd', // Formato de la fecha (puedes personalizarlo)
            autoclose: true, // Cierra el selector al seleccionar una fecha
            todayHighlight: false, // Resalta la fecha actual
            clearBtn: true, // Muestra un botón para limpiar la fecha
            language: 'es', // Cambia el idioma (opcional, si tienes los archivos de idioma cargados)
        });

        $('#payment_date,#presentation_date').datepicker('setDate', new Date());



    });

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

<script>
    var defaultImageUrl = '{{ asset("assets/images/user_default.png") }}';
</script>

@endsection

@section('js_optional_vendors')

@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-canceled.js" type="text/javascript"></script>
@endsection