@extends("$theme/layout")
@section('title') Informe de clientes @endsection
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
'Catálogo','Clientes/Comp'
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
            Informe de clientes/comparecientes

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
        <div class="container">

            <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom" id="kt_table">
                <thead>
                    <tr>
                        <th class="clean-icon-table">
               
                        </th>
                        <th>Tipo de persona</th>
                        <th>RFC</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Correo electrónico</th>
                        <th>País</th>
                        <th>Domicilio</th>
                        <th>Observaciones</th>
                        <th>Representante legal</th>

                    </tr>
                </thead>
            </table>

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
            <form action="#" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">
                                <label for="person_type" class="form-control-label">Tipo de Persona *</label>
                                <select name="person_type" id="person_type" class="form-control" required>
                                    <option value="física" {{ old('person_type') == 'física' ? 'selected' : '' }}>Física</option>
                                    <option value="moral" {{ old('person_type') == 'moral' ? 'selected' : '' }}>Moral</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="rfc" class="form-control-label">RFC</label>
                                <input type="text" name="rfc" class="form-control" maxlength="13" id="rfc" value="{{ old('rfc') }}">
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-control-label">Nombre(s) o Razón Social *</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group" id="last_name_container">
                                <label for="last_name" class="form-control-label">Primer apellido</label>
                                <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}">
                            </div>
                            <div class="form-group" id="second_last_name_container">
                                <label for="second_last_name" class="form-control-label">Segundo apellido</label>
                                <input type="text" name="second_last_name" class="form-control" id="second_last_name" value="{{ old('second_last_name') }}">
                            </div>

                            <div class="form-group" id="denomination_container">
                                <label for="denomination_id" class="form-control-label">Denominación *</label>
                                <select name="denomination_id" id="denomination_id" class="form-control" required>
                                    <option value="">Selecciona una denominación</option>
                                    @foreach($denominations as $denomination)
                                    <option value="{{ $denomination->id }}" {{ old('denomination_id') == $denomination->id ? 'selected' : '' }}>
                                        {{ $denomination->acronym }} ({{ $denomination->denomination }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="form-group" id="legal_representative_container">
                                <label for="legal_representative" class="form-control-label">Representante legal</label>
                                <input type="text" name="legal_representative" class="form-control" id="legal_representative" value="{{ old('legal_representative') }}">
                            </div>

                            <div class="form-group">
                                <label for="phone_number" class="form-control-label">Número Teléfonico </label>
                                <input type="tel" name="phone_number" class="form-control" id="phone_number" value="{{ old('phone_number') }}" maxlength="10">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-control-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="country" class="form-control-label">País</label>
                                <select name="country" id="country" class="form-control">
                                    @foreach (Helpers::getCountries() as $key => $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="street" class="form-control-label">Calle</label>
                                <input type="text" name="street" class="form-control" id="street" value="{{ old('street') }}">
                            </div>
                            <div class="form-group">
                                <label for="n_exterior" class="form-control-label">No. Exterior </label>
                                <input type="text" name="n_exterior" class="form-control" id="n_exterior"
                                    value="{{ old('n_exterior') }}">
                            </div>
                            <div class="form-group">
                                <label for="suburb" class="form-control-label">Colonia</label>
                                <input type="text" name="suburb" class="form-control" id="suburb"
                                    value="{{ old('suburb') }}">
                            </div>
                            <div class="form-group">
                                <label for="municipality" class="form-control-label">Municipio</label>
                                <input type="text" name="municipality" class="form-control" id="municipality"
                                    value="{{ old('municipality') }}">
                            </div>
                            <div class="form-group">
                                <label for="entity" class="form-control-label">Entidad</label>
                                <input type="text" name="entity" class="form-control" id="entity" value="{{ old('entity') }}">
                            </div>
                            <div class="form-group">
                                <label for="zip_code" class="form-control-label">C.P. </label>
                                <input type="text" name="zip_code" class="form-control" id="zip_code" maxlength="10" value="{{ old('zip_code') }}">
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
            <form action="{{route('clients.admin.update')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">
                                <label for="person_type_e" class="form-control-label">Tipo de Persona *</label>
                                <select name="person_type" id="person_type_e" class="form-control" required>
                                    <option value="física" {{ old('person_type') == 'física' ? 'selected' : '' }}>Física</option>
                                    <option value="moral" {{ old('person_type') == 'moral' ? 'selected' : '' }}>Moral</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="rfc_e" class="form-control-label">RFC</label>
                                <input type="text" name="rfc" class="form-control" maxlength="13" id="rfc_e" value="{{ old('rfc') }}">
                            </div>

                            <div class="form-group">
                                <label for="name_e" class="form-control-label">Nombre(s) o Razón Social *</label>
                                <input type="text" name="name" class="form-control" id="name_e" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group" id="last_name_container_e">
                                <label for="last_name_e" class="form-control-label">Primer apellido</label>
                                <input type="text" name="last_name" class="form-control" id="last_name_e" value="{{ old('last_name') }}">
                            </div>
                            <div class="form-group" id="second_last_name_container_e">
                                <label for="second_last_name_e" class="form-control-label">Segundo apellido</label>
                                <input type="text" name="second_last_name" class="form-control" id="second_last_name_e" value="{{ old('second_last_name') }}">
                            </div>

                            <div class="form-group" id="denomination_container_e">
                                <label for="denomination_id_e" class="form-control-label">Denominación *</label>
                                <select name="denomination_id" id="denomination_id_e" class="form-control" required>
                                    <option value="">Selecciona una denominación</option>
                                    @foreach($denominations as $denomination)
                                    <option value="{{ $denomination->id }}" {{ old('denomination_id') == $denomination->id ? 'selected' : '' }}>
                                        {{ $denomination->acronym }} ({{ $denomination->denomination }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="form-group" id="legal_representative_container_e">
                                <label for="legal_representative_e" class="form-control-label">Representante legal</label>
                                <input type="text" name="legal_representative" class="form-control" id="legal_representative_e" value="{{ old('legal_representative') }}">
                            </div>

                            <div class="form-group">
                                <label for="phone_number_e" class="form-control-label">Número Teléfonico </label>
                                <input type="tel" name="phone_number" class="form-control" id="phone_number_e" value="{{ old('phone_number') }}" maxlength="10">
                            </div>
                            <div class="form-group">
                                <label for="email_e" class="form-control-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" id="email_e"
                                    value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="country_e" class="form-control-label">País</label>
                                <select name="country" id="country_e" class="form-control">
                                    @foreach (Helpers::getCountries() as $key => $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="street_e" class="form-control-label">Calle</label>
                                <input type="text" name="street" class="form-control" id="street_e" value="{{ old('street') }}">
                            </div>
                            <div class="form-group">
                                <label for="n_exterior_e" class="form-control-label">No. Exterior </label>
                                <input type="text" name="n_exterior" class="form-control" id="n_exterior_e"
                                    value="{{ old('n_exterior') }}">
                            </div>
                            <div class="form-group">
                                <label for="suburb_e" class="form-control-label">Colonia</label>
                                <input type="text" name="suburb" class="form-control" id="suburb_e"
                                    value="{{ old('suburb') }}">
                            </div>
                            <div class="form-group">
                                <label for="municipality_e" class="form-control-label">Municipio</label>
                                <input type="text" name="municipality" class="form-control" id="municipality_e"
                                    value="{{ old('municipality') }}">
                            </div>
                            <div class="form-group">
                                <label for="entity_e" class="form-control-label">Entidad</label>
                                <input type="text" name="entity" class="form-control" id="entity_e" value="{{ old('entity') }}">
                            </div>
                            <div class="form-group">
                                <label for="zip_code_e" class="form-control-label">C.P. </label>
                                <input type="text" name="zip_code" class="form-control" id="zip_code_e" maxlength="10" value="{{ old('zip_code') }}">
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
                               <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <form action="{{route('clients.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" id="id_delete">
    
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

<script src="{{asset("assets")}}/js/excel-export.js" type="text/javascript"></script>


<script>
    


    // Numeric only control handler
    jQuery.fn.ForceNumericOnly = function() {
    return this.each(function() {
        $(this).keydown(function(e) {
            var key = e.charCode || e.keyCode || 0;
            // Allow: backspace, tab, delete, enter, arrows, and numbers ONLY
            return (
                key == 8 ||  // Backspace
                key == 9 ||  // Tab
                key == 13 || // Enter
                key == 46 || // Delete
                (key >= 35 && key <= 40) || // Arrow keys/Home/End
                (key >= 48 && key <= 57) || // Numbers 0-9
                (key >= 96 && key <= 105)   // Numpad numbers 0-9
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
<script src="{{asset("assets")}}/js/page-clients.js" type="text/javascript"></script>
@endsection