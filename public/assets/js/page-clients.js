"use strict";
var tableMain = null;
var KTDatatables = function() {
    var initTable = function() {
        // begin first table
        tableMain = $('#kt_table').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            autoWidth: true,
            pageLength: 25,
            responsive: true,
            colReorder: true,
            /* scrollY: false,
			scrollX: true,*/
            searchDelay: 500,
            processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "No se encontró nada",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
            },
            ajax: {
                url: "clients/dataTable",
                dataType: "json",
                type: "POST",
                data: { _token: $('#token_ajax').val() }
            },

            columns: [
                 {data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'person_type'},
                { data: 'rfc'},
                { data: 'client'},
                { data: 'phone_number'},
                { data: 'email'},
                { data: 'country'},
                { data: 'residence'},
                { data: 'observations'},
                { data: 'legal_representative'},

            ],
            columnDefs: [
                {
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<div  onclick='editElement(${JSON.stringify(full)})' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
                    }
                }
            ],
            drawCallback: function(settings) {
                    $('#kt_table').show();
            },
            order: [
                [0, 'desc']
            ],
            buttons: [
                createExcelExportButton({
                    columnsToOmit: [0],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Informe de clientes.xlsx',
                    columnsNoCustomRender: [],
                    columnsAlternateData: {

                    }
                })
            ],
            initComplete: function() {
                tableMain.buttons().container().appendTo('#kt_table_wrapper .col-md-6:eq(0)');
            }

        });


    };

    return {

        //main function to initiate the module
        init: function() {
            initTable();
        },

    };

}();


jQuery(document).ready(function() {
    KTDatatables.init();

    $('#delete-button').click(function() {
        $('#id_delete').val( $('#id_edit').val());
        $('#modal_delete').modal('show');
    });


    var personType = $('#person_type').val();

    changePerson(personType);
    $('#person_type').change(function() {
            personType = $(this).val();
            changePerson(personType);
    });
    $('#person_type_e').change(function() {
        var   personTypeE = $(this).val();
        changePersonEdit(personTypeE);
    });

});


function changePerson(personType){

    $('#legal_representative_container').hide().find('input').prop('disabled', true);
    $('#denomination_container').hide().find('select').prop('disabled', true);
    $('#last_name_container').hide().find('input').prop('disabled', true);
    $('#second_last_name_container').hide().find('input').prop('disabled', true);

    if(personType=="física"){
        $('#last_name_container').show().find('input').prop('disabled', false);
        $('#second_last_name_container').show().find('input').prop('disabled', false);
    }else if(personType=="moral"){
        $('#legal_representative_container').show().find('input').prop('disabled', false);
        $('#denomination_container').show().find('select').prop('disabled', false);
    }
}

function changePersonEdit(personType){

    $('#legal_representative_container_e').hide().find('input').prop('disabled', true);
    $('#denomination_container_e').hide().find('select').prop('disabled', true);
    $('#last_name_container_e').hide().find('input').prop('disabled', true);
    $('#second_last_name_container_e').hide().find('input').prop('disabled', true);

    if(personType=="física"){
        $('#last_name_container_e').show().find('input').prop('disabled', false);
        $('#second_last_name_container_e').show().find('input').prop('disabled', false);
    }else if(personType=="moral"){
        $('#legal_representative_container_e').show().find('input').prop('disabled', false);
        $('#denomination_container_e').show().find('select').prop('disabled', false);
    }

}





function editElement(data){
    console.log(data);


    $("#person_type_e").val(data.person_type);
    $("#rfc_e").val(data.rfc);
    $("#name_e").val(data.name);
    $("#last_name_e").val(data.last_name);
    $("#second_last_name_e").val(data.second_last_name);
    $("#denomination_id_e").val(data.denomination_id);
    $("#legal_representative_e").val(data.legal_representative);
    $("#phone_number_e").val(data.phone_number);
    $("#email_e").val(data.email);
    $("#country_e").val(data.country);
    $("#street_e").val(data.street);
    $("#n_exterior_e").val(data.n_exterior);
    $("#suburb_e").val(data.suburb);

    $("#municipality_e").val(data.municipality);

    $("#entity_e").val(data.entity);
    $("#zip_code_e").val(data.zip_code);
    $("#observations_e").val(data.observations);




    $("#id_edit").val(data.id);

    var personType = $('#person_type_e').val();
    changePersonEdit(personType);

    $('#modal_edit').modal('show');

}
