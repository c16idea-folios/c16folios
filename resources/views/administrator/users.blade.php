@extends("$theme/layout")
@section('title') Informe de usuarios @endsection
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
'Administración','Usuarios'
]) !!}
@endsection

@section('content_page')
<!-- begin:: Content -->

<div class="alert alert-primary fade show p-1 mb-4" role="alert">
    <div class="alert-icon"><i class="flaticon2-accept"></i></div>
    <div class="alert-text">
    Para usuarios nuevos la contraseña asignada por defecto correspondera al mismo USUARIO especificado.


    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="la la-close"></i></span>
        </button>
    </div>
</div>

<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
            </span>
            <h3 class="kt-portlet__head-title">
            Informe de usuarios

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
                        <th>Imagen</th>
                        <th>Usuario</th>
                        <th>Nombre(s)</th>
                        <th>Primer Apellido</th>
                        <th>Segundo Apellido</th>
                        <th>Teléfono</th>
                        <th>Corre electrónico</th>
                        <th>Tipo Usuario</th>
                        <th>Equipo</th>
                        <th>Estatus</th>
                        <th>Creado en</th>
                        <th>Expira en</th>
                        <th>Observaciones</th>



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
            <form action="{{route('user.admin.store')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">


                            <div class="col-12 d-flex justify-content-center align-items-center">

                                <label for="img-change" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click para subir foto de perfil">
                                    <img id="img-change-profile" class="picture-profile" src="{{ asset("assets/images/user_default.png") }}" />
                                </label>

                                <input type='file' id="img-change" style="display:none" name="picture_upload" accept="image/*" />
                                <br>
                                {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                            </div>

                            <div class="form-group">
                                <label for="username" class="form-control-label">Usuario *</label>
                                <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-control-label">Nombre(s) *</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="form-control-label">Primer apellido *</label>
                                <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="second_last_name" class="form-control-label">Segundo apellido</label>
                                <input type="text" name="second_last_name" class="form-control" id="second_last_name" value="{{ old('second_last_name') }}">
                            </div>

                            <div class="form-group">
                                <label for="tel" class="form-control-label">Número Teléfonico </label>
                                <input type="tel" name="tel" class="form-control" id="tel" value="{{ old('tel') }}" placeholder="10 dígitos" maxlength="10">
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-control-label">Correo Electrónico *</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ old('email') }}" required>
                            </div>


                            <div class="form-group">
                                <label for="rol" class="form-control-label">Tipo usuario *</label>
                                <select name="rol" id="rol" class="form-control" required>
                                    <option value="">Seleccione</option>

                                    <option value="technical_support" {{ old('rol') == 'technical_support' ? 'selected' : '' }}>Soporte Técnico</option>
                                    <option value="administrator" {{ old('rol') == 'administrator' ? 'selected' : '' }}>Administrador</option>
                                    <option value="operator" {{ old('rol') == 'operator' ? 'selected' : '' }}>Operador</option>
                                </select>
                            </div>




                            <div class="form-group">
                                <label for="work_team_id" class="form-control-label">Equipo</label>
                                <select name="work_team_id" id="work_team_id" class="form-control">
                                    <option value="">No aplica</option>
                                    @foreach($work_teams as $work_team)
                                    <option value="{{ $work_team->id }}" {{ old('work_team_id') == $work_team->id ? 'selected' : '' }}>
                                        {{ $work_team->team }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active" class="form-control-label">Estatus usuario *</label>
                                <select name="is_active" id="is_active" class="form-control" required disabled>
                                    <option value="1" {{ old('is_active') == 'física' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('is_active') == 'moral' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="created" class="form-control-label">Creado en *</label>
                                <input type="text" name="created" class="form-control" id="created" value="{{ old('created') }}" readonly disabled>
                            </div>


                            <div class="form-group">
                                <label for="expires" class="form-control-label">Expira en</label>
                                <input type="text" name="expires" class="form-control" id="expires" value="{{ old('expires') }}" readonly>
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
            <form action="{{route('user.admin.store')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">


                            <div class="col-12 d-flex justify-content-center align-items-center">

                                <label for="img-change_e" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click para subir foto de perfil">
                                    <img id="img-change-profile_e" class="picture-profile" src="{{ asset("assets/images/user_default.png") }}" />
                                </label>

                                <input type='file' id="img-change_e" style="display:none" name="picture_upload" accept="image/*" />
                                <br>
                                {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                            </div>

                            <div class="form-group">
                                <label for="username_e" class="form-control-label">Usuario *</label>
                                <input type="text" name="username" class="form-control" id="username_e" value="{{ old('username') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="name_e" class="form-control-label">Nombre(s) *</label>
                                <input type="text" name="name" class="form-control" id="name_e" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name_e" class="form-control-label">Primer apellido *</label>
                                <input type="text" name="last_name" class="form-control" id="last_name_e" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="second_last_name_e" class="form-control-label">Segundo apellido</label>
                                <input type="text" name="second_last_name" class="form-control" id="second_last_name_e" value="{{ old('second_last_name') }}">
                            </div>

                            <div class="form-group">
                                <label for="tel_e" class="form-control-label">Número Teléfonico </label>
                                <input type="tel" name="tel" class="form-control" id="tel_e" value="{{ old('tel') }}" placeholder="10 dígitos" maxlength="10">
                            </div>

                            <div class="form-group">
                                <label for="email_e" class="form-control-label">Correo Electrónico *</label>
                                <input type="email" name="email" class="form-control" id="email_e"
                                    value="{{ old('email') }}" required>
                            </div>


                            <div class="form-group">
                                <label for="rol_e" class="form-control-label">Tipo usuario *</label>
                                <select name="rol" id="rol_e" class="form-control" required>
                                    <option value="">Seleccione</option>

                                    <option value="technical_support" {{ old('rol') == 'technical_support' ? 'selected' : '' }}>Soporte Técnico</option>
                                    <option value="administrator" {{ old('rol') == 'administrator' ? 'selected' : '' }}>Administrador</option>
                                    <option value="operator" {{ old('rol') == 'operator' ? 'selected' : '' }}>Operador</option>
                                </select>
                            </div>




                            <div class="form-group">
                                <label for="work_team_id_e" class="form-control-label">Equipo</label>
                                <select name="work_team_id" id="work_team_id_e" class="form-control">
                                    <option value="">No aplica</option>
                                    @foreach($work_teams as $work_team)
                                    <option value="{{ $work_team->id }}" {{ old('work_team_id') == $work_team->id ? 'selected' : '' }}>
                                        {{ $work_team->team }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active_e" class="form-control-label">Estatus usuario *</label>
                                <select name="is_active" id="is_active_e" class="form-control" required disabled>
                                    <option value="1" {{ old('is_active') == 'física' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('is_active') == 'moral' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="created_e" class="form-control-label">Creado en *</label>
                                <input type="text" name="created" class="form-control" id="created_e" value="{{ old('created') }}" readonly disabled>
                            </div>


                            <div class="form-group">
                                <label for="expires_e" class="form-control-label">Expira en</label>
                                <input type="text" name="expires" class="form-control" id="expires_e" value="{{ old('expires') }}" readonly>
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
                        <button type="button" class="btn btn-warning"  id="reset-button">Restablecer</button>
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



<!--start: Modal reset password  -->
<div class="modal fade" id="modal_reset_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('user.admin.reset_password')}}" id="form_reset" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="id_reset">

                <div class="modal-body">
                    <h4 class="text-uppercase text-center"> <i class="flaticon-danger text-danger display-1"></i> <br>¿Se encuentra seguro que desea reestablecer la contraseña para este usuario?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal reset password -->



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

    $("#tel").ForceNumericOnly();

    $(document).ready(function() {
        $('#expires,#created,#expires_e,#created_e').datepicker({
            format: 'yyyy-mm-dd', // Formato de la fecha (puedes personalizarlo)
            autoclose: true, // Cierra el selector al seleccionar una fecha
            todayHighlight: false, // Resalta la fecha actual
            clearBtn: true, // Muestra un botón para limpiar la fecha
            language: 'es', // Cambia el idioma (opcional, si tienes los archivos de idioma cargados)
        });

        $('#created').datepicker('setDate', new Date());



    });


    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#img-change-profile').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#img-change").change(function() {
        readURL2(this);
    });


    function readURLe(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#img-change-profile_e').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#img-change_e").change(function() {
        readURLe(this);
    });
</script>

<script>
    var defaultImageUrl = '{{ asset("assets/images/user_default.png") }}';
</script>

@endsection

@section('js_optional_vendors')

@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-users.js" type="text/javascript"></script>
@endsection