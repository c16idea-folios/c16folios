@extends("$theme/layout")
@section('title') Registro Instrumento @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Incluyendo FontAwesome (asegúrate de estar usando la versión correcta) -->

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
'Instrumentos','Activos','Instrumento'
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
                Registro Instrumento
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
            <form action="{{route('instrument.admin.update')}}" method="POST" id="form_update" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">

                    <div class="col-12">

                        <input type="hidden" name="instrument_id" id="instrument_id" value="{{$instrument->id}}">

                        <div class="form-group">
                            <label for="responsible_id" class="form-control-label">Responsable</label>
                            <div class="input-group">
                                <select name="responsible_id" id="responsible_id" class="form-control">

                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('responsible_id') == $user->id || $user->id == $instrument->responsible_id) ? 'selected' : '' }}>
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

                                    <option value="Acta" {{ (old('type') == 'Acta' || $instrument->type == 'Acta') ? 'selected' : '' }}>Acta</option>
                                    <option value="Póliza" {{ (old('type') == 'Póliza' || $instrument->type == 'Póliza' ) ? 'selected' : '' }}>Póliza</option>

                                </select>

                                <div class="input-group-append">
                                    <button type="button" class="btn" data-toggle="tooltip" data-theme="dark" title="Seleccionar el tipo de instrumento.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="no" class="form-control-label">No. Instrumento *</label>
                            <div class="input-group">
                                <input type="tel" name="no" class="form-control" id="no" value="{{ $instrument->no }}" required>

                                <div class="input-group-append">
                                    <button type="button" class="btn" data-toggle="tooltip" data-theme="dark" title="No. Consecutivo asignado automáticamente.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>

                            </div>

                        </div>

                        <div class="form-group">
                            <label for="created_at" class="form-control-label">Fecha del instrumento *</label>
                            <div class="input-group">
                                <input type="text" name="created_at" class="form-control" id="created_at" value="{{ $instrument->created_at_f }}" readonly required>

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
                                <input type="text" name="authorization_date" class="form-control" id="authorization_date" value="{{ $instrument->authorization_date }}" readonly>

                                <div class="input-group-append">
                                    <button type="button" class="btn" data-toggle="tooltip" data-theme="dark" title="La 'fecha de autorización' se tomará como fecha de inicio para el cálculo de días restantes para la presentación de avisos.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>

                            </div>
                        </div>



                    </div>


                </div>
            </form>


            <div class="row">

                <div class="part1-instrument-edit d-flex justify-content-end align-items-center">
                    <div class="d-flex flex-row align-items-center">

                        <h1>Actos</h1>
                        <button data-toggle="modal" data-target="#modal_add_act"><i class="fas fa-plus"></i> </button>
                    </div>

                </div>
                <div class="part2-instrument-edit">
                @if($instrument->instrumentActs->count() == 0)
                        <div class="part3-instrument-edit">
                        <p>No se ha agregado "Actos" al instrumento.</p>
                        </div>
                @else
                    <!--begin: Datatable -->
                    <table class="table-bordered table-hover table-data-custom" id="kt_table_acts">
                        <thead>
                            <tr>
                                <th class="clean-icon-table"></th>
                                <th>Fecha del acto</th>
                                <th>Acto</th>
                                <th>Cliente</th>
                                <th>Representante legal</th>
                                <th>Costo del trámite</th>
                                <th>Factura</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instrument->instrumentActs as $insAct)
                                <tr data-act-id="{{ $insAct->act->id }}"
                                    data-client-id="{{ $insAct->client_id }}"
                                    data-person-type="{{ $insAct->client->person_type ?? '' }}"
                                    data-legal-representative="{{ $insAct->legal_representative }}"
                                    data-act="{{ $insAct->act->act ?? '' }}"
                                    data-cost="{{ $insAct->cost }}"
                                    data-invoice="{{ $insAct->invoice }}"
                                    data-created-at="{{ $insAct->created_at }}"
                                >
                                    <td class="dt-body-center" style="width: 20px">
                                        <div class="pencil-edit">
                                            <i class="icon-2x text-dark-50 flaticon-edit"></i>
                                        </div>
                                    </td>
                                    <td>{{ $insAct->created_at ? $insAct->created_at->format('Y-m-d') : '' }}</td>
                                    <td>{{ $insAct->act->act ?? '' }}</td>
                                    <td>{{ $insAct->client->name ?? '' }}</td>
                                    <td>{{ $insAct->legal_representative }}</td>
                                    <td data-order="{{ $insAct->cost }}">{{ '$' . $insAct->cost }}</td>
                                    <td>{{ $insAct->invoice_print }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!--end: Datatable -->
                @endif




            </div>
        </div>


        @if($instrument->instrumentActs->count() > 0)
        <div class="row">

            <div class="part1-instrument-edit d-flex justify-content-end align-items-center">
                <div class="d-flex flex-row align-items-center">

                    <h1>Compareciente(s)</h1>
                    <button data-toggle="modal" data-target="#modal_add_appearer"><i class="fas fa-plus"></i> </button>
                </div>

            </div>
            <div class="part2-instrument-edit">
            @if(count($appearers)<=0)
                <div class="part3-instrument-edit">
                    <p>No se han asociado "Comparecientes" a los "Actos" del instrumento.</p>
                </div>

            @else
            <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom" id="kt_table_appearer">
                <thead>
                    <tr>
                        <th class="clean-icon-table">

                        </th>
                        <th>Acto asociado</th>
                        <th>Cliente</th>
                        <th>Compareciente</th>
                        <th>Representante legal</th>
                        <th>Observaciones</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($appearers as $appearer)
                        <tr data-appearer-id="{{ $appearer->id }}"
                            data-instrument-act-id="{{ $appearer->instrument_act_id }}"
                            data-appearer-id-type="{{ $appearer->appearer.'|'.$appearer->appearerClient->person_type }}"
                            data-legal-representative="{{ $appearer->legal_representative }}"
                            data-legend="{{ $appearer->legend }}"
                            data-observations="{{ $appearer->observations }}">
                            <td class="dt-body-center" style="width: 20px">
                                <div class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>
                            </td>
                            <td>{{ $appearer->instrumentAct->act->act }}</td>
                            <td>{{ $appearer->instrumentAct->client->name }}</td>
                            <td>{{ $appearer->appearerClient->name }}</td>
                            <td>{{ $appearer->legal_representative }}</td>
                            <td>{{ $appearer->observations }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->

            @endif

        </div>
    </div>
    @endif



    <div class="modal-footer mt-3">
        <a href="{{route('instrument.admin')}}" class="btn btn-default">
            <i class="icon-xl fas fa-arrow-left"></i>
            Cancelar
        </a>
        <!-- Botones de cancelar y guardar alineados a la derecha -->
        <div class="ml-auto">

            <button type="button" class="btn btn-danger" id="delete-button">Eliminar</button>
            <button type="button" class="btn btn-primary" id="save-button">Guardar</button>
        </div>
    </div>


</div>
</div>
</div>


<!--start: Modal Delete instrument -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('instrument.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
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
<!--end: Modal Delete instrument -->


<!-- start: Modal add act  -->
<div class="modal fade" id="modal_add_act" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle de instrumento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('instrument_act.admin.store')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">

                            <input type="hidden" name="instrument_id" value="{{$instrument->id}}">

                            <div class="form-group">
                                <label for="created_at_act" class="form-control-label">Fecha del acto *</label>

                                <input type="text" name="created_at" class="form-control" id="created_at_act" readonly required>
                            </div>

                            <div class="form-group ">
                                <label for="client" class="form-control-label">Cliente *</label>
                                <select name="client_id" id="client" class="form-control select2" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id.'|'.$client->person_type }}" data-legal-representative="{{ $client['legal_representative'] }}">{{ $client["formatted_name"] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="legal_representative_container_add_act">
                                <label for="legal_representative_add_act" class="form-control-label">Representante legal</label>
                                <input type="text" name="legal_representative" class="form-control" id="legal_representative_add_act" value="">
                            </div>


                            <div class="form-group ">
                                <label for="act_id" class="form-control-label">Acto *</label>
                                <select name="act_id" id="act_id" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($acts as $act)
                                    <option value="{{ $act->id }}">{{ $act->act }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- inputs dinamic: start -->
                            <div id="dinamic_inputs">

                            </div>

                            <!-- inputs dinamic: end -->

                            <div class="form-group">
                                <label for="cost" class="form-control-label">Costo del tramite *</label>
                                <input type="text" name="cost" class="form-control" id="cost" value="{{ old('cost') }}" required>
                            </div>


                            <div class="form-group">
                                <label for="invoice" class="form-control-label">Factura *</label>
                                <select name="invoice" id="invoice" class="form-control" required>
                                    <option value="not_applicable" {{ old('invoice') == 'not_applicable' ? 'selected' : '' }}>No aplica</option>
                                    <option value="request" {{ old('invoice') == 'request' ? 'selected' : '' }}>Solicitar</option>
                                    <option value="sent" {{ old('invoice') == 'sent' ? 'selected' : '' }}>Enviada</option>

                                </select>
                            </div>





                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline btn-outline-dark" data-toggle="modal" data-target="#modal_add_client" data-target-select="#client">Agregar Cliente</button>

                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end: Modal add act -->




<!-- start: Modal edit act  -->
<div class="modal fade" id="modal_edit_act" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle de instrumento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('instrument_act.admin.update')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">

                            <input type="hidden" name="instrument_id" value="{{$instrument->id}}">

                            <div class="form-group">
                                <label for="created_at_act_e" class="form-control-label">Fecha del acto *</label>

                                <input type="text" name="created_at" class="form-control" id="created_at_act_e" readonly required>
                            </div>

                            <div class="form-group ">
                                <label for="client_e" class="form-control-label">Cliente *</label>
                                <select name="client_id" id="client_e" class="form-control select2" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id.'|'.$client->person_type }}" data-legal-representative="{{ $client['legal_representative'] }}">{{ $client["formatted_name"] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="legal_representative_container_e">
                                <label for="legal_representative_e" class="form-control-label">Representante legal</label>
                                <input type="text" name="legal_representative" class="form-control" id="legal_representative_e" value="">
                            </div>


                            <div class="form-group ">
                                <label for="act_id_e" class="form-control-label">Acto *</label>
                                <select name="act_id" id="act_id_e" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($acts as $act)
                                    <option value="{{ $act->id }}">{{ $act->act }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- inputs dinamic: start -->
                            <div id="dinamic_inputs_e">

                            </div>

                            <!-- inputs dinamic: end -->

                            <div class="form-group">
                                <label for="cost_e" class="form-control-label">Costo del tramite *</label>
                                <input type="text" name="cost" class="form-control" id="cost_e" value="{{ old('cost') }}" required>
                            </div>


                            <div class="form-group">
                                <label for="invoice_e" class="form-control-label">Factura *</label>
                                <select name="invoice" id="invoice_e" class="form-control" required>
                                    <option value="not_applicable" {{ old('invoice') == 'not_applicable' ? 'selected' : '' }}>No aplica</option>
                                    <option value="request" {{ old('invoice') == 'request' ? 'selected' : '' }}>Solicitar</option>
                                    <option value="sent" {{ old('invoice') == 'sent' ? 'selected' : '' }}>Enviada</option>

                                </select>
                            </div>


                            <input type="hidden" name="id" id="id_edit_act">


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botón de eliminar alineado a la izquierda -->
                    <button type="button" class="btn btn-danger" id="delete-button-act">Eliminar</button>

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
<!-- end: Modal edit act -->



<!--start: Modal Delete act  -->
<div class="modal fade" id="modal_delete_act" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('instrument_act.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="id_delete_act">

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
<!--end: Modal Delete act -->





<!-- comparecientes -->


<!-- start: Modal add appearer  -->
<div class="modal fade" id="modal_add_appearer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registro de compareciente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('appearer.admin.store')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">



                            <div class="form-group ">
                                <label for="instrument_act" class="form-control-label">Acto asociado *</label>
                                <select name="instrument_act_id" id="instrument_act" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($instrument->instrumentActs as $instrumentAct)
                                    <option value="{{ $instrumentAct->id }}">{{ $instrumentAct->act_and_client }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group ">
                                <label for="appearer" class="form-control-label">Compareciente *</label>
                                <select name="appearer" id="appearer" class="form-control select2" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id.'|'.$client->person_type }}"   data-legal-representative="{{ $client['legal_representative'] }}">{{ $client["formatted_name"] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="legal_representative_container_appearer">
                                <label for="legal_representative_appearer" class="form-control-label">Representante legal</label>
                                <input type="text" name="legal_representative" class="form-control" id="legal_representative_appearer" value="">
                            </div>

                            <div class="form-group">
                                <label for="legend" class="form-control-label">Leyenda propio derecho *</label>
                                <select name="legend" id="legend" class="form-control" required>
                                    <option value="yes" {{ old('legend') == 'yes' ? 'selected' : '' }}>Sí</option>
                                    <option value="no" {{ old('legend') == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>




                            <div class="form-group">
                                <label for="observations" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations" class="form-control" rows="4">{{ old('observations') }}</textarea>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline btn-outline-dark" data-toggle="modal" data-target="#modal_add_client" data-target-select="#appearer">Agregar Cliente</button>
                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end: Modal add appearer -->



<!-- start: Modal edit appearer  -->
<div class="modal fade" id="modal_edit_appearer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registro de compareciente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('appearer.admin.update')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">



                            <div class="form-group ">
                                <label for="instrument_act_e" class="form-control-label">Acto asociado *</label>
                                <select name="instrument_act_id" id="instrument_act_e" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($instrument->instrumentActs as $instrumentAct)
                                        <option value="{{ $instrumentAct->id }}">{{ $instrumentAct->act_and_client }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group ">
                                <label for="appearer_e" class="form-control-label">Compareciente *</label>
                                <select name="appearer" id="appearer_e" class="form-control select2" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id.'|'.$client->person_type }}" data-legal-representative="{{ $client['legal_representative'] }}">{{ $client["formatted_name"] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="legal_representative_container_appearer_e">
                                <label for="legal_representative_appearer_e" class="form-control-label">Representante legal</label>
                                <input type="text" name="legal_representative" class="form-control" id="legal_representative_appearer_e" value="">
                            </div>

                            <div class="form-group">
                                <label for="legend_e" class="form-control-label">Leyenda propio derecho *</label>
                                <select name="legend" id="legend_e" class="form-control" required>
                                    <option value="yes" {{ old('legend') == 'yes' ? 'selected' : '' }}>Sí</option>
                                    <option value="no" {{ old('legend') == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>




                            <div class="form-group">
                                <label for="observations_e" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations_e" class="form-control" rows="4">{{ old('observations') }}</textarea>
                            </div>
                            <input type="hidden" name="id" id="id_edit_appearer">


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botón de eliminar alineado a la izquierda -->
                    <button type="button" class="btn btn-danger" id="delete-button-appearer">Eliminar</button>

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
<!-- end: Modal edit appearer -->


<!--start: Modal Delete act  -->
<div class="modal fade" id="modal_delete_appearer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('appearer.admin.delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="id_delete_appearer">

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
<!--end: Modal Delete act -->

<!-- start: Modal add client -->
@include('partials.modal_create_client', ['formClass' => 'ajax-form'])
<!-- end: Modal add client -->

<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">

{{-- Acto principal, cuando sólo hay 1 --}}
<input type="hidden" id="default_instrument_act_id" value="{{ $instrument->instrumentActs->count() === 1 ? $instrument->instrumentActs->first()->id : '' }}">
<!-- end:: Content -->

@endsection


@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.js" type="text/javascript"></script>




<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-timepicker/init.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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



    $(document).ready(function() {
        $('#authorization_date,#created_at,#created_at_act,#created_at_act_e').datepicker({
            format: 'yyyy-mm-dd', // Formato de la fecha (puedes personalizarlo)
            autoclose: true, // Cierra el selector al seleccionar una fecha
            todayHighlight: false, // Resalta la fecha actual
            clearBtn: true, // Muestra un botón para limpiar la fecha
            language: 'es', // Cambia el idioma (opcional, si tienes los archivos de idioma cargados)
        });
    });

    $("#no").ForceNumericOnly();
    $("#cost").ForceNumericDotOnly();
    $("#cost_e").ForceNumericDotOnly();



    $('#modal_add_act').on('shown.bs.modal', function() {

        $('#client').select2({
            placeholder: 'Seleccione',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modal_add_act')
        });

        $('#created_at_act').datepicker('setDate', new Date());
    });


    $('#modal_edit_act').on('shown.bs.modal', function() {

        $('#client_e').select2({
            placeholder: 'Seleccione',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modal_edit_act')
        });
    });


    $('#modal_add_appearer').on('shown.bs.modal', function() {

        $('#appearer').select2({
            placeholder: 'Seleccione',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modal_add_appearer')
        });

        // Si hay un acto, seleccionarlo
        const default_instrument_act_id = $('#default_instrument_act_id').val();
        if (default_instrument_act_id) {

            console.log('selecciona', default_instrument_act_id)
            $('#instrument_act').val(default_instrument_act_id).trigger('change');
        }
    });


    $('#modal_edit_appearer').on('shown.bs.modal', function() {

        $('#appearer_e').select2({
            placeholder: 'Seleccione',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modal_edit_appearer')
        });

    });









    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

<script>

    /**
     * Modal add client
     */
    function changePerson(personType){
        $('#modal_add_client #legal_representative_container').hide().find('input').prop('disabled', true);
        $('#modal_add_client #denomination_container').hide().find('select').prop('disabled', true);
        $('#modal_add_client #last_name_container').hide().find('input').prop('disabled', true);
        $('#modal_add_client #second_last_name_container').hide().find('input').prop('disabled', true);

        if(personType=="física"){
            $('#modal_add_client #last_name_container').show().find('input').prop('disabled', false);
            $('#modal_add_client #second_last_name_container').show().find('input').prop('disabled', false);
        }else if(personType=="moral"){
            $('#modal_add_client #legal_representative_container').show().find('input').prop('disabled', false);
            $('#modal_add_client #denomination_container').show().find('select').prop('disabled', false);
        }
    }

    $(document).ready(function() {
        // Evento change
        $('#person_type').change(function() {
            var personType = $(this).val();
            changePerson(personType);
        });

        // Establecer el estado inicial
        $('#person_type').trigger('change');

        $('#modal_add_client').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            targetSelect = button.data('target-select'); // Extraer info del data-target-select
        });

        // Submit de nuevo cliente
        $('#form_add_client').on('submit', function(e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            var formData = new FormData(this);
            var url = "{{ route('clients.admin') }}";

            showOverlay()

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        var client = response.client;
                        var optionValue = client.id + '|' + client.person_type;

                        // Crear la nueva opción para el select
                        var newOption = new Option(client.formatted_name, optionValue, false, false);
                        $(newOption).attr('data-legal-representative', client.legal_representative || '');

                        // Agregar la nueva opción a todos los selects donde se listan clientes
                        $('#client, #client_e, #appearer, #appearer_e').append($(newOption).clone());


                        // En el select de destino, establecer el valor para seleccionarlo
                        if (targetSelect) {
                            $(targetSelect).val(optionValue).trigger('change');
                        }

                        // Cerrar el modal y mostrar notificación
                        $('#modal_add_client').modal('hide');
                        toastr.success('Cliente agregado exitosamente.');

                    } else {
                        toastr.error('Hubo un error al agregar el cliente.');
                    }
                },
                error: function(xhr) {
                    // Manejo de errores de validación u otros
                    var errors = xhr.responseJSON.errors;
                    var errorMsg = 'Error al agregar el cliente. Por favor, verifique los datos.<br>';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '<br>';
                    });
                    toastr.error(errorMsg);
                },
                complete: function() {
                    hideOverlay();
                }
            });
        });

        // Limpiar el formulario al cerrar el modal
        $('#modal_add_client').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
            $('#person_type').trigger('change');
            targetSelect = null; // Limpiar el selector de destino
        });
    });

    /*
    * END - Modal add client
    */
</script>
@endsection

@section('js_optional_vendors')

@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-instrument-edit.js" type="text/javascript"></script>
@endsection
