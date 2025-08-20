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
                url: "payment_report/dataTable",
                dataType: "json",
                type: "POST",
                data:function(data) {
                    data.year= $('#yearSelect').val();
                    data._token = $('#token_ajax').val();
                }
            },

            columns: [

                { data: 'rfc'},
                { data: 'client'},
                { data: 'procedures'},
                { data: 'payments'},

         
            ],
            columnDefs: [
       

                {
                    'targets': 3,
                   
                    'render': function(data, type, full, meta) {

                        return formatCurrency(data) ;
                    }
                },
            ],
            drawCallback: function(settings) {
                    $('#kt_table').show();
            },
            order: [
                [0, 'desc']
            ],
            buttons: [
                createExcelExportButton({
                    columnsToOmit: [],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Reporte.xlsx',
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

       
});


function formatCurrency(amount) {
    // Verifica si el número es válido
    if (isNaN(amount)) {
        return "$0.00";
    }

    // Convierte el número a un formato con comas y dos decimales
    const formatted = Number(amount).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    return formatted;
}






