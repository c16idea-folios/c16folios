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
                url: "user/dataTable",
                dataType: "json",
                type: "POST",
                data: { _token: $('#token_ajax').val() }
            },

            columns: [
                 {data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'profile_photo_path'},
                { data: 'username'},
                { data: 'name'},
                { data: 'last_name'},
                { data: 'second_last_name'},
                { data: 'tel'},
                { data: 'email'},
                { data: 'rol'},
                { data: 'team'},
                { data: 'status'},
                { data: 'created_at_f'},
                { data: 'expires'},
                { data: 'observations'},



         
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
                },
                {
                    'targets': 1,
                   
                    'render': function(data, type, full, meta) {

                        var imageUrl = (data && data !== "") ? '/storage/' + data : defaultImageUrl;
                        return `<img src="${imageUrl}" alt="User Image" style="width: 50px; height: 50px; object-fit: cover;">`;
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
                    filename: 'Informe de usuarios.xlsx',
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
        $('#reset-button').click(function() { 
            $('#id_reset').val( $('#id_edit').val());
           $('#modal_reset_password').modal('show');
            });
       
});








function editElement(data){
    console.log(data);


    if(data.profile_photo_path=="" || data.profile_photo_path==null){
        $('#img-change-profile_e').attr('src',"/assets/images/user_default.png");
        }else{
        $('#img-change-profile_e').attr('src',"/storage/"+data.profile_photo_path);
        }
        

    $("#username_e").val(data.username);
    $("#name_e").val(data.name);
    $("#last_name_e").val(data.last_name);
    $("#second_last_name_e").val(data.second_last_name);
    $("#tel_e").val(data.tel);
    $("#email_e").val(data.email);
    $("#rol_e").val(data.rol_o);
    $("#work_team_id_e").val(data.work_team_id);
    $("#is_active_e").val(data.is_active);
    $("#created_e").val(data.created_at_f);
    $("#expires_e").val(data.expires);
    $("#observations_e").val(data.observations);


$("#id_edit").val(data.id);

var personType = $('#person_type_e').val();

$('#modal_edit').modal('show');

    }
    


