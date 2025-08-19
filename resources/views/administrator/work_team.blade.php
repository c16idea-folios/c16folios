@extends("$theme/layout")
@section('title') Informe de equipos de trabajo @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />
<style>
.iti--allow-dropdown{
    display: block !important;
}
</style>
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! Helpers::getMenuEnable([
'Catálogo','Equipos de trabajo'
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
                                Informe de equipos de trabajo
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
                            <table class="table-bordered table-hover table-data-custom"  id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="clean-icon-table">
                                   
                                        </th>
                                        <th>Equipo de trabajo</th>
                                        <th>Identificador</th>
                                        <th>Orden</th>
                     
                                      
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
            <form action="{{route('work_team.admin.store')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">
          
                        <div class="col-12">
         
                            <div class="form-group">
                                <label for="orden" class="form-control-label">Orden</label>
                                <input type="text" name="order" class="form-control" id="order" value="{{ old('order') }}">
                            </div>
                            <div class="form-group">
                                <label for="team" class="form-control-label">Equipo de trabajo *</label>
                                <input type="text" name="team" class="form-control" id="team" value="{{ old('team') }}" required>
                            </div>

                            <div class="form-group">
    <label for="color" class="form-control-label">Color</label>
    <div class="input-group">
        <!-- Div para mostrar el color seleccionado -->
        <div id="colorPreview" style="width: 30px; background-color: #ffffff; border:solid 0.5px #ccc; border-radius: 0px;"></div>
        <input type="text" name="identifier" class="form-control" id="color" placeholder="#ffffff" value="{{ old('identifier') }}" required>
        <div class="input-group-append">
            <button type="button" class="btn btn-secondary" id="colorPickerButton">
                <i class="fas fa-palette"></i>
            </button>
            <input type="color" id="colorPicker" style="display:none;">
        </div>
    </div>
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
            <form action="{{route('work_team.admin.update')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">
          
                        <div class="col-12">
         
                            <div class="form-group">
                                <label for="order_e" class="form-control-label">Orden</label>
                                <input type="text" name="order" class="form-control" id="order_e" value="{{ old('orden') }}">
                            </div>
                            <div class="form-group">
                                <label for="team_e" class="form-control-label">Equipo de trabajo *</label>
                                <input type="text" name="team" class="form-control" id="team_e" value="{{ old('team') }}" required>
                            </div>

                            <div class="form-group">
    <label for="color_e" class="form-control-label">Color</label>
    <div class="input-group">
        <!-- Div para mostrar el color seleccionado -->
        <div id="colorPreview_e" style="width: 30px; background-color: #ffffff; border:solid 0.5px #ccc; border-radius: 0px;"></div>
        <input type="text" name="identifier" class="form-control" id="color_e" placeholder="#ffffff" value="{{ old('identifier') }}" required>
        <div class="input-group-append">
            <button type="button" class="btn btn-secondary" id="colorPickerButton_e">
                <i class="fas fa-palette"></i>
            </button>
            <input type="color" id="colorPicker_e" style="display:none;">
        </div>
    </div>
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
                            <form action="{{route('work_team.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
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

$("#order,#order_e").ForceNumericOnly();


        const $colorPickerButton = $('#colorPickerButton');
        const $colorPicker = $('#colorPicker');
        const $colorInput = $('#color');
        const $colorPreview = $('#colorPreview');

        const $colorPickerButton_e = $('#colorPickerButton_e');
        const $colorPicker_e = $('#colorPicker_e');
        const $colorInput_e = $('#color_e');
        const $colorPreview_e = $('#colorPreview_e');

        // Mostrar el selector de color al hacer clic en el botón
        $colorPickerButton.on('click', function () {
            $colorPicker.trigger('click');
        });

        $colorPickerButton_e.on('click', function () {
            $colorPicker_e.trigger('click');
        });

        // Actualizar el input de texto y el cuadrado de color cuando se selecciona un color
        $colorPicker.on('input', function () {
            const selectedColor = $(this).val();
            $colorInput.val(selectedColor); // Setea el input con el color hexadecimal
            $colorPreview.css('background-color', selectedColor); // Cambia el color del cuadrado
        });

        $colorPicker_e.on('input', function () {
            const selectedColor = $(this).val();
            $colorInput_e.val(selectedColor); // Setea el input con el color hexadecimal
            $colorPreview_e.css('background-color', selectedColor); // Cambia el color del cuadrado
        });

        // Si quieres setear el color dinámicamente usando jQuery
        function setColor(color) {
            $colorInput.val(color); // Actualiza el valor del input
            $colorPreview.css('background-color', color); // Cambia el color del cuadrado
            $colorPicker.val(color); // Actualiza el input colorpicker (opcional)
        }
        function setColorE(color) {
            $colorInput_e.val(color); // Actualiza el valor del input
            $colorPreview_e.css('background-color', color); // Cambia el color del cuadrado
            $colorPicker_e.val(color); // Actualiza el input colorpicker (opcional)
        }

        // Ejemplo de uso de la función setColor
        // setColor('#ff5733'); // Puedes llamar a esta función para establecer el color dinámicamente
 
  </script>

@endsection

@section('js_optional_vendors')
   
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-work-team.js" type="text/javascript"></script>
@endsection

