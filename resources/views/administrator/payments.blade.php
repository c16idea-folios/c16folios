@extends("$theme/layout")
@section('title') Informe de pagos @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />
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
'Seguimiento','Pagos'
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
            Informe de pagos
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
                    &nbsp;
                    <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add">
                        <i class="la la-plus"></i>
                        Agregar
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">



    <div class="form-group">
                                <label for="status" class="form-control-label">Estatus *</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="Todos" {{ old('status') == 'Todos' ? 'selected' : '' }}>Todos</option>
                                    <option value="Pagado" {{ old('status') == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                                    <option value="Pendiente" {{ old('status') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>

                                </select>
                            </div>


        <div class="container">
            <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom" id="kt_table">
                <thead>
                    <tr>
                        <th class="clean-icon-table"> </th>
                        <th>Instrumento</th>
                        <th>Acto</th>
                        <th>Compareciente</th>
                        <th>Costo trámite</th>
                        <th>Pagado</th>
                        <th>Pendiente</th>
                        <th>Factura</th>
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>




<!-- start: Modal add   -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registro de pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
            <div class="row">

            <div class="col-12">
            <form action="{{route('payment.admin.store')}}" method="POST"  autocomplete="off">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="from_payments_view" value="">

                <div class="form-group">
                    <label for="no_instrument" class="form-control-label">No. Instrumento*</label>
                    <select  id="no_instrument" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($instruments as $instrument)
                        <option data-acts="{{ json_encode($instrument->acts_list, JSON_UNESCAPED_UNICODE)}}" value="{{ $instrument->id }}">{{ $instrument->no }}</option>
                        @endforeach

                        </select>
                </div>


                    <div class="form-group">
                        <label for="act_id" class="form-control-label">Acto *</label>
                        <select name="instrument_act_id" id="act_id" class="form-control" required>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="payment_date" class="form-control-label">Fecha de pago *</label>
                        <input type="text" name="payment_date" class="form-control" id="payment_date" value="{{ old('payment_date') }}" required readonly>
                    </div>


                    <div class="form-group">
                        <label for="received_from" class="form-control-label">Recibido de</label>
                        <input type="text" name="received_from" class="form-control" id="received_from" value="{{ old('received_from') }}">
                    </div>

                    <div class="form-group">
                        <label for="amount_paid" class="form-control-label">Importe pagado *</label>
                        <input type="tel" name="amount_paid" class="form-control" id="amount_paid" value="{{ old('amount_paid') }}" required>
                    </div>


                    <div class="form-group ">
                        <label for="payment_method" class="form-control-label">Modalidad *</label>
                        <select name="payment_method_id" id="payment_method" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($payment_methods as $payment_method)
                            <option value="{{ $payment_method->id }}">{{ $payment_method->method }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                                <label for="observations_payment" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations_payment" class="form-control" rows="4">{{ old('observations') }}</textarea>
                            </div>

                    <div class="modal-footer">
                        <!-- Botón de eliminar alineado a la izquierda -->
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                        <!-- Botones de cancelar y guardar alineados a la derecha -->
                        <div class="ml-auto">

                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </div>
                </form>
            </div>
            
            </div>
            </div>


         

        </div>
    </div>
</div>
<!-- end: Modal add  -->



<!-- start: Modal edit   -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle de pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
            <div class="row">

            <div class="col-12">
            <form action="{{route('payment.admin.update')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="payment_id_e">
                    <input type="hidden" name="from_payments_view" value="">

                    <div class="form-group">
                        <label for="no_instrument_payment_e" class="form-control-label">No. Instrumento *</label>
                        <input type="text" class="form-control" id="no_instrument_payment_e" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="act_id_e" class="form-control-label">Acto *</label>
                        <select name="instrument_act_id" id="act_id_e" class="form-control" required>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="payment_date_e" class="form-control-label">Fecha de pago *</label>
                        <input type="text" name="payment_date" class="form-control" id="payment_date_e"  required readonly>
                    </div>


                    <div class="form-group">
                        <label for="received_from_e" class="form-control-label">Recibido de</label>
                        <input type="text" name="received_from" class="form-control" id="received_from_e" >
                    </div>

                    <div class="form-group">
                        <label for="amount_paid_e" class="form-control-label">Importe pagado *</label>
                        <input type="tel" name="amount_paid" class="form-control" id="amount_paid_e"  required>
                    </div>


                    <div class="form-group ">
                        <label for="payment_method_e" class="form-control-label">Modalidad *</label>
                        <select name="payment_method_id" id="payment_method_e" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($payment_methods as $payment_method)
                            <option value="{{ $payment_method->id }}">{{ $payment_method->method }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                                <label for="observations_payment_e" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations_payment_e" class="form-control" rows="4">{{ old('observations') }}</textarea>
                            </div>

                            <div class="modal-footer">
                        <!-- Botón de eliminar alineado a la izquierda -->
                        <button type="button" class="btn btn-danger" id="delete-button-payment">Eliminar</button>

                        <!-- Botones de cancelar y guardar alineados a la derecha -->
                        <div class="ml-auto">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
            
            </div>
            </div>


         

        </div>
    </div>
</div>
<!-- end: Modal edit  -->




                               <!--start: Modal Delete  -->
                               <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <form action="{{route('payment.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" id="id_delete">
                                <input type="hidden" name="from_payments_view" value="">

    
                            <div class="modal-body">
                                <h4 class="text-uppercase text-center">  <i class="flaticon-danger text-danger display-1"></i> <br> ¿Desea realizar esta acción de supresión?</h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default " data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                  <!--end: Modal Delete -->



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

    $("#amount_paid,#amount_paid_e").ForceNumericDotOnly();

    $(document).ready(function() {
        $('#payment_date,#payment_date_e').datepicker({
            format: 'yyyy-mm-dd', // Formato de la fecha (puedes personalizarlo)
            autoclose: true, // Cierra el selector al seleccionar una fecha
            todayHighlight: false, // Resalta la fecha actual
            clearBtn: true, // Muestra un botón para limpiar la fecha
            language: 'es', // Cambia el idioma (opcional, si tienes los archivos de idioma cargados)
        });

        $('#payment_date').datepicker('setDate', new Date());



    });

</script>

@endsection

@section('js_optional_vendors')

@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-payments.js" type="text/javascript"></script>
@endsection