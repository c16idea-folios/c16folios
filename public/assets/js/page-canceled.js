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
                url: "instrument/dataTable",
                dataType: "json",
                type: "POST",
                data: { _token: $('#token_ajax').val(), status: 'canceled' }
            },
         
            columns: [

                { data: 'type'},
                { data: 'no'},
                { data: 'created_at_f'},
                { data: 'acts'},
                { data: 'clients'},
                { data: 'total'},
                { data: 'paid'},
                { data: 'pending'},
                { data: 'responsible'}
         
            ],
            columnDefs: [
                {
                    'targets': 3,
                    'className': 'fix-ul-large',
                    'render': function(data, type, full, meta) {
                        return data;
                    }
                },
                {
                    'targets': 4,
                    'className': 'fix-ul-large',
                    'width': '30%',
                    'render': function(data, type, full, meta) {
                        return data;
                    }
                },
                {
                    'targets': 5,
                   
                    'render': function(data, type, full, meta) {

                        return `<p style="font-weight:bold;">${ formatCurrency(data)}</p>`;
                    }
                },
                {
                    'targets': 6,
                   
                    'render': function(data, type, full, meta) {

                        return formatCurrency(data);
                    }
                },
                {
                    'targets': 7,
                   
                    'render': function(data, type, full, meta) {

                        return formatCurrency(data);
                    }
                },
                
            ],
            drawCallback: function(settings) {
                    $('#kt_table').show();
            },
            order: [
                [1, 'desc']
            ],
            buttons: [
                createExcelExportButton({
                    columnsToOmit: [],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Instrumentos cancelados.xlsx',
                    columnsNoCustomRender: [3,4,5],
                    columnsAlternateData: {
                        3: 'acts_formated',
                        4: 'clients_formated',
                        5: 'total_formated'
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
        $('#id-delete').val( $('#id-edit').val());
       $('#modal_delete').modal('show');
        });
       
        $('#delete-button-record').click(function() { 
            $('#id_delete_record').val( $('#file_id').val());
           $('#modal_delete_record').modal('show');
            });
           
            $('#delete-button-payment').click(function() { 
                $('#id_delete_payment').val( $('#payment_id_e').val());
               $('#modal_delete_payment').modal('show');
                });
               
                       
                $('#delete-button-notification').click(function() { 
                    $('#id_delete_notification').val( $('#notification_id_e').val());
                   $('#modal_delete_notification').modal('show');
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




