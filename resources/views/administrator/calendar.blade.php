@extends("$theme/layout")
@section('title') Informe calendario @endsection
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
'Administración','Calendario'
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
            Informe calendario
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
            <div class="form-group">
                <label for="yearSelect" class="form-control-label">Ejercicio</label>
                <select id="yearSelect" class="form-control">
                    @for ($year = date('Y'); $year >= 2015; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div style=" width: 100%; overflow-y: auto; max-height: 100%;display: block;">
                        <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom" id="kt_table">
                <thead>
                    <tr>

                        <th>Fecha</th>
                        <th>Día Festivo</th>

                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
            </div>
    
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
            <form action="{{route('notice_type.admin.store')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">




                            <div class="form-group">
                                <label for="type" class="form-control-label">Tipo de aviso *</label>
                                <input type="text" name="type" class="form-control" id="type" value="{{ old('type') }}" required>
                            </div>


                            <div class="form-group">
                                <label for="days" class="form-control-label">Días para presentar *</label>
                                <input type="text" name="days" class="form-control" id="days" value="{{ old('days') }}" required>
                            </div>



                            <div class="form-group">
                                <label for="observations" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations" class="form-control" rows="4">{{ old('observations') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end: Modal add  -->



<!-- start: Modal edit  -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('notice_type.admin.update')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">




                            <div class="form-group">
                                <label for="type_e" class="form-control-label">Tipo de aviso *</label>
                                <input type="text" name="type" class="form-control" id="type_e" value="{{ old('type') }}" required>
                            </div>


                            <div class="form-group">
                                <label for="days_e" class="form-control-label">Días para presentar *</label>
                                <input type="text" name="days" class="form-control" id="days_e" value="{{ old('days') }}" required>
                            </div>



                            <div class="form-group">
                                <label for="observations_e" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations_e" class="form-control" rows="4">{{ old('observations') }}</textarea>
                            </div>
                            <input type="hidden" name="id" id="id_edit">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botón de eliminar alineado a la izquierda -->
                    <button type="button" class="btn btn-danger" id="delete-button">Eliminar</button>

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
<!-- end: Modal edit  -->






<!--start: Modal Delete  -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('notice_type.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
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

<!-- Dropdown de edición -->
<div id="holidayDropdown" class="dropdown" style="display:none;">
    <select id="holidaySelect" class="form-control">
        <option value="Yes">Sí</option>
        <option value="No">No</option>
    </select>
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

    $("#phone_number").ForceNumericOnly();
</script>

@endsection

@section('js_optional_vendors')

@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-calendar.js" type="text/javascript"></script>
@endsection