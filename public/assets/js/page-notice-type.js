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
                url: "notice_type/dataTable",
                dataType: "json",
                type: "POST",
                data: { _token: $('#token_ajax').val() }
            },

            columns: [
                 {data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'act'},
                { data: 'type'},
                { data: 'days'},
                { data: 'observations'},
                { data: 'foreigners'},

         
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
                    'targets': 5,
                    
                    'render': function(data, type, full, meta) {

                        return (data=="yes")?"Sí":"No";
                    }
                }
            ],
            drawCallback: function(settings) {
                    $('#kt_table').show();
            },
            order: [
                [0, 'asc']
            ],

            buttons: [
                createExcelExportButton({
                    columnsToOmit: [0],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Informe tipo de avisos.xlsx',
                    columnsNoCustomRender: [0],
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

       
});








function editElement(data){


    $("#act_id_e").val(data.act_id);
    $("#type_e").val(data.type);
    $("#days_e").val(data.days);
   
    $("#foreigners_e").val(data.foreigners);
    $("#observations_e").val(data.observations);


$("#id_edit").val(data.id);
$('#modal_edit').modal('show');

    }
    

