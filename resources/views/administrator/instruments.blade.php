@extends("$theme/layout")
@section('title') Instrumentos @endsection
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
'Instrumentos','Activos'
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
            Instrumentos

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
                    <a href="#" class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_extracts">
                        <i class="far fa-file-pdf"></i>
                        Extractos
                    </a>
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
        <div class="container">

            <!--begin: Datatable -->
            <div style="overflow-x: auto;">
            <table class="table-bordered table-hover table-data-custom" id="kt_table">
                <thead>
                    <tr>
                        <th class="clean-icon-table">

                        </th>
                        <th>Expediente</th>
                        <th>Pagos</th>
                        <th>Avisos</th>
                        <th>Entregado</th>
                        <th >Tipo instrumento</th>
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
            </div>


            <!--end: Datatable -->
        </div>
    </div>
</div>


<!-- start: Modal add  -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('instrument.admin.store')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">
                                <label for="responsible_id" class="form-control-label">Responsable</label>
                                <div class="input-group">
                                    <select name="responsible_id" id="responsible_id" class="form-control">

                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('responsible_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} {{ $user->last_name }} {{ $user->second_last_name }}
                                        </option>
                                        @endforeach
                                    </select>

                                    <div class="input-group-append">
                                        <button type="button" class="btn" data-toggle="tooltip" data-theme="dark" title="Responsable del trámite.">
                                            <i class="fas fa-question-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="type" class="form-control-label">Tipo de instrumento *</label>
                                <div class="input-group">
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="">Seleccione</option>

                                        <option value="Acta" {{ old('type') == 'Acta' ? 'selected' : '' }}>Acta</option>
                                        <option value="Póliza" {{ old('type') == 'Póliza' ? 'selected' : '' }}>Póliza</option>

                                    </select>

                                    <div class="input-group-append">
                                        <button type="button" class="btn" data-toggle="tooltip" data-theme="dark" title="Seleccionar el tipo de instrumento.">
                                            <i class="fas fa-question-circle"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="created_at" class="form-control-label">Fecha del instrumento *</label>
                                <div class="input-group">
                                    <input type="text" name="created_at" class="form-control" id="created_at" value="{{ old('created_at') }}" readonly required>

                                    <div class="input-group-append">
                                        <button type="button" class="btn" data-toggle="tooltip" data-theme="dark" title="Fecha en que se realiza el acta o póliza.">
                                            <i class="fas fa-question-circle"></i>
                                        </button>
                                    </div>

                                </div>

                            </div>

                            <div class="form-group">
                                <label for="authorization_date" class="form-control-label">Fecha de autorización</label>

                                <div class="input-group">
                                    <input type="text" name="authorization_date" class="form-control" id="authorization_date" value="{{ old('authorization_date') }}" readonly>

                                    <div class="input-group-append">
                                        <button type="button" class="btn" data-toggle="tooltip" data-theme="dark" title="La 'fecha de autorización' se tomará como fecha de inicio para el cálculo de días restantes para la presentación de avisos.">
                                            <i class="fas fa-question-circle"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar y siguiente</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end: Modal add  -->


<!-- start: Modal expediente  -->
<div class="modal fade" id="modal_record" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle expediente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="p-4" id="modal_record_part1">
                <button type="button" class="btn btn-primary btn-block mb-2" id="button_create_record">Crear archivo <i class="icon-xl fas fa-folder"></i></button>
                <!--begin: Datatable -->
                <table class="table-bordered table-hover table-data-custom " id="kt_table_record">
                    <thead>
                        <tr>
                            <th class="clean-icon-table">

                            </th>
                            <th>No. Instrumento</th>
                            <th>Acto</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Nombre de archivo</th>
                            <th>Última actualización</th>
                            <th>Descarga</th>


                        </tr>
                    </thead>
                </table>

                <!--end: Datatable -->
            </div>

            <div class="p-4" id="modal_record_part2">
                <form action="{{route('file.admin.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="no_instrument" class="form-control-label">No. Instrumento*</label>
                        <input type="text" class="form-control" id="no_instrument" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="act_id" class="form-control-label">Acto *</label>
                        <select name="instrument_act_id" id="act_id" class="form-control" required>

                        </select>
                    </div>

                    <div class="form-group ">
                        <label for="file_type" class="form-control-label">Tipo de archivo *</label>
                        <select name="file_type_id" id="file_type" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($file_types as $file_type)
                            <option value="{{ $file_type->id }}">{{ $file_type->type }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="file" class="form-control-label">Archivo *</label>
                        <input type="file" name="file_upload" class="form-control" id="file" required>
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


            <div class="p-4" id="modal_record_part3">
                <form action="{{route('file.admin.update')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="no_instrument_e" class="form-control-label">No. Instrumento*</label>
                        <input type="text" class="form-control" id="no_instrument_e" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="act_id_e" class="form-control-label">Acto *</label>
                        <select name="instrument_act_id" id="act_id_e" class="form-control" required>

                        </select>
                    </div>

                    <div class="form-group ">
                        <label for="file_type_e" class="form-control-label">Tipo de archivo *</label>
                        <select name="file_type_id" id="file_type_e" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($file_types as $file_type)
                            <option value="{{ $file_type->id }}">{{ $file_type->type }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="file" class="form-control-label">Archivo *</label>
                        <input type="file" name="file_upload" class="form-control" id="file">
                    </div>

                    <input type="hidden" name="id" id="file_id">

                    <div class="modal-footer">
                        <!-- Botón de eliminar alineado a la izquierda -->
                        <button type="button" class="btn btn-danger" id="delete-button-record">Eliminar</button>

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
<!-- end: Modal expediente  -->



<!--start: Modal Delete  expediente-->
<div class="modal fade" id="modal_delete_record" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('file.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="id_delete_record">

                <div class="modal-body">
                    <h4 class="text-uppercase text-center"> <i class="flaticon-danger text-danger display-1"></i> <br> ¿Desea realizar esta acción de supresión?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal Delete expediente-->



<input type="hidden" id="instrument_id_payment">
<!-- start: Modal pagos  -->
<div class="modal fade" id="modal_payments" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registro de pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="p-4" id="modal_payments_part1">
                <button type="button" class="btn btn-primary btn-block mb-2" id="button_create_payment">Crear pago <i class="icon-xl far fa-money-bill-alt"></i></button>
                <!--begin: Datatable -->
                <table class="table-bordered table-hover table-data-custom " id="kt_table_payments">
                    <thead>
                        <tr>
                            <th class="clean-icon-table">

                            </th>
                            <th>Folio de pago</th>
                            <th>Acto</th>
                            <th>Cliente</th>
                            <th>Fecha de pago</th>
                            <th>Recibido de</th>
                            <th>Importe pagado</th>
                            <th>Observaciones</th>
                            <th>Imprimir</th>



                        </tr>
                    </thead>
                    <tfoot>
        <tr>
            <th colspan="6"></th>
            <th></th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
                </table>

                <!--end: Datatable -->
            </div>

            <div class="p-4" id="modal_payments_part2">
                <form action="{{route('payment.admin.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="no_instrument_payment" class="form-control-label">No. Instrumento*</label>
                        <input type="text" class="form-control" id="no_instrument_payment" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="act_id_payment" class="form-control-label">Acto *</label>
                        <select name="instrument_act_id" id="act_id_payment" class="form-control" required>

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


            <div class="p-4" id="modal_payments_part3">
            <form action="{{route('payment.admin.update')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="payment_id_e">
                    <div class="form-group">
                        <label for="no_instrument_payment_e" class="form-control-label">No. Instrumento *</label>
                        <input type="text" class="form-control" id="no_instrument_payment_e" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="act_id_payment_e" class="form-control-label">Acto *</label>
                        <select name="instrument_act_id" id="act_id_payment_e" class="form-control" required>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="payment_date_e" class="form-control-label">Fecha de pago *</label>
                        <input type="text" name="payment_date" class="form-control" id="payment_date_e" value="{{ old('payment_date') }}" required readonly>
                    </div>


                    <div class="form-group">
                        <label for="received_from_e" class="form-control-label">Recibido de</label>
                        <input type="text" name="received_from" class="form-control" id="received_from_e" value="{{ old('received_from') }}">
                    </div>

                    <div class="form-group">
                        <label for="amount_paid_e" class="form-control-label">Importe pagado *</label>
                        <input type="tel" name="amount_paid" class="form-control" id="amount_paid_e" value="{{ old('amount_paid') }}" required>
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
<!-- end: Modal pagos  -->

<!--start: Modal Delete payment -->
<div class="modal fade" id="modal_delete_payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <input type="hidden" name="id" id="id_delete_payment">

                <div class="modal-body">
                    <h4 class="text-uppercase text-center"> <i class="flaticon-danger text-danger display-1"></i> <br> ¿Desea realizar esta acción de supresión?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal Delete payment -->



<!-- start: Modal notificaciones  -->
<input type="hidden" id="instrument_id_notification">
<div class="modal fade" id="modal_notifications" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle de avisos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="p-4" id="modal_notifications_part1">
                <button type="button" class="btn btn-primary btn-block mb-2" id="button_create_notification">Presentar aviso <i class="icon-xl la la-bell"></i></button>
                <!--begin: Datatable -->
                <table class="table-bordered table-hover table-data-custom " id="kt_table_notifications">
                    <thead>
                        <tr>
                            <th class="clean-icon-table">

                            </th>
                            <th>No. Instrumento</th>
                            <th>Acto</th>
                            <th>Cliente</th>
                            <th>Fecha de presentación</th>
                            <th>Tipo de aviso</th>
                            <th>Observaciones</th>




                        </tr>
                    </thead>
                </table>
                <!--end: Datatable -->
            </div>

            <div class="p-4" id="modal_notifications_part2">
                <form action="{{route('notification.admin.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="no_instrument_notification" class="form-control-label">No. Instrumento*</label>
                        <input type="text" class="form-control" id="no_instrument_notification" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="act_id_notification" class="form-control-label">Acto *</label>
                        <select name="instrument_act_id" id="act_id_notification" class="form-control" required>

                        </select>
                    </div>



                    <div class="form-group" id="notice_type_container1">
                        <label for="notice_type" class="form-control-label">Tipo de aviso *</label>
                        <select name="notice_type_id" id="notice_type" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($notices_type as $notice_type)
                            <option value="{{ $notice_type->id }}">{{ $notice_type->type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="notice_type_container2">
                        <label for="notice_type_foreigner" class="form-control-label">Tipo de aviso *</label>
                        <select name="notice_type_id" id="notice_type_foreigner" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($notices_type_foreigner as $notice_type)
                            <option value="{{ $notice_type->id }}">{{ $notice_type->type }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="form-group">
                        <label for="presentation_date" class="form-control-label">Fecha de presentación *</label>
                        <input type="text" name="presentation_date" class="form-control" id="presentation_date" value="{{ old('presentation_date') }}" required readonly>
                    </div>


                    <div class="form-group">
                                <label for="observations_notification" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations_notification" class="form-control" rows="4">{{ old('observations') }}</textarea>
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


            <div class="p-4" id="modal_notifications_part3">
            <form action="{{route('notification.admin.update')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="notification_id_e">

                    <div class="form-group">
                        <label for="no_instrument_notification_e" class="form-control-label">No. Instrumento*</label>
                        <input type="text" class="form-control" id="no_instrument_notification_e" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="act_id_notification_e" class="form-control-label">Acto *</label>
                        <select name="instrument_act_id" id="act_id_notification_e" class="form-control" required>

                        </select>
                    </div>

                    <div class="form-group" id="notice_type_container1_e">
                        <label for="notice_type_e" class="form-control-label">Tipo de aviso *</label>
                        <select name="notice_type_id" id="notice_type_e" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($notices_type as $notice_type)
                            <option value="{{ $notice_type->id }}">{{ $notice_type->type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="notice_type_container2_e">
                        <label for="notice_type_foreigner_e" class="form-control-label">Tipo de aviso *</label>
                        <select name="notice_type_id" id="notice_type_foreigner_e" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($notices_type_foreigner as $notice_type)
                            <option value="{{ $notice_type->id }}">{{ $notice_type->type }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="presentation_date_e" class="form-control-label">Fecha de presentación *</label>
                        <input type="text" name="presentation_date" class="form-control" id="presentation_date_e" value="{{ old('presentation_date') }}" required readonly>
                    </div>


                    <div class="form-group">
                                <label for="observations_notification_e" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations_notification_e" class="form-control" rows="4">{{ old('observations') }}</textarea>
                            </div>

                            <div class="modal-footer">
                        <!-- Botón de eliminar alineado a la izquierda -->
                        <button type="button" class="btn btn-danger" id="delete-button-notification">Eliminar</button>

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
<!-- end: Modal notificaciones  -->


<!--start: Modal Delete notification -->
<div class="modal fade" id="modal_delete_notification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('notification.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="id_delete_notification">

                <div class="modal-body">
                    <h4 class="text-uppercase text-center"> <i class="flaticon-danger text-danger display-1"></i> <br> ¿Desea realizar esta acción de supresión?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal Delete notification -->




<!--start: Modal Delete  -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('user.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="id_delete">

                <div class="modal-body">
                    <h4 class="text-uppercase text-center"> <i class="flaticon-danger text-danger display-1"></i> <br> ¿Desea realizar esta acción de supresión?</h4>
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




<!--start: Modal extracts-->
<div class="modal fade" id="modal_extracts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Imprimir Extractos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form  id="form_extracts" method="GET" autocomplete="off" target="_blank">
                @csrf
                @method('GET')

                <div class="modal-body">
                <div class="form-group">
                        <label for="start_extract" class="form-control-label">Folio Inicio * </label>
                        <input type="text" name="min" class="form-control" id="start_extract" value="{{ $minNo }}" required>
                    </div>

                    <div class="form-group">
                        <label for="end_extract" class="form-control-label">Folio Fin *</label>
                        <input type="tel" name="max" class="form-control" id="end_extract" value="{{ $maxNo }}" required>
                    </div>

                    <div class="form-group">
                                <label for="output" class="form-control-label">Salida *</label>
                                <select name="format" id="output" class="form-control" required>
                                    <option value="PDF" {{ old('foreigners') == 'PDF' ? 'selected' : '' }}>PDF</option>
                                    <option value="WORD" {{ old('foreigners') == 'WORD' ? 'selected' : '' }}>WORD</option>
                                </select>
                            </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn_export_extracts" class="btn btn-danger"> <i class="far fa-file-pdf"></i>
                    Imprimir</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal extracts -->

<!-- start: Modal submission  -->
<div class="modal fade" id="modal_submission" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Instrumento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>


            <form action="{{route('instrument.admin.update.submission')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                       <input type="hidden" name="instrument_id" id="instrument_id_submission">
                    <div class="form-group">
                        <label for="type_instrument_submission" class="form-control-label">Tipo de instrumento</label>
                        <input type="text" class="form-control" id="type_instrument_submission" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="no_instrument_submission" class="form-control-label">No. Instrumento</label>
                        <input type="text" class="form-control" id="no_instrument_submission" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="responsible_submission" class="form-control-label">Operador</label>
                        <input type="text" class="form-control" id="responsible_submission" required disabled>
                    </div>



                    <div class="form-group">
                        <label for="submission_date" class="form-control-label">Fecha de entrega</label>
                        <input type="text" name="submission_date" class="form-control" id="submission_date"   readonly>
                    </div>


                    <div class="form-group">
                        <label for="who_receives" class="form-control-label">Operador</label>
                        <input type="text" name="who_receives" class="form-control" id="who_receives" >
                    </div>




                            <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>


                        <!-- Botones de cancelar y guardar alineados a la derecha -->
                        <div class="ml-auto">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>



        </div>
    </div>
</div>
<!-- end: Modal submission  -->

<input type="hidden" id="instrument_id" value="">

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
    $("#start_extract,#end_extract").ForceNumericOnly();


    $(document).ready(function() {
        const config = {
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: false,
            clearBtn: true,
            language: 'es',
        }

        $('#payment_date,#payment_date_e,#presentation_date,#presentation_date_e,#submission_date,#authorization_date').datepicker(config);

        $('#created_at').datepicker({
            ...config,
            clearBtn: false,
        });

        $('#payment_date,#presentation_date,#created_at').datepicker('setDate', new Date());
    });

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

<script>
    document.getElementById("btn_export_extracts").addEventListener("click", function() {
        let min = document.getElementById("start_extract").value;
        let max = document.getElementById("end_extract").value;
        let format = document.getElementById("output").value;

        if (!min || !max) {
            alert("Por favor, ingresa los valores de Folio Inicio y Folio Fin.");
            return;
        }

        // Construimos la URL con los valores ingresados
        let url = "{{ route('instrument.admin.extracts', ['min' => '__MIN__', 'max' => '__MAX__', 'format' => '__FORMAT__']) }}"
                    .replace('__MIN__', min)
                    .replace('__MAX__', max)
                    .replace('__FORMAT__', format);

        // Abrimos la URL en una nueva pestaña
        window.open(url, '_blank');
        $('#modal_extracts').modal('hide');

    });
</script>

<script>
    var defaultImageUrl = '{{ asset("assets/images/user_default.png") }}';
</script>

@endsection

@section('js_optional_vendors')

@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-instruments.js" type="text/javascript"></script>
@endsection
