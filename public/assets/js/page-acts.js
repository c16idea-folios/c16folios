"use strict";
var tableMain = null;

var KTDatatables = function() {
    var initTable = function() {
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
                url: "acts/dataTable",
                dataType: "json",
                type: "POST",
                data: function(d) {
                    // Agregar el _token y otros parámetros que no deben cambiar
                    d._token = $('#token_ajax').val();
                    // Los parámetros que DataTables envía originalmente
                    return d;
                }
            },
            columns: [
                {data: 'id', responsivePriority: 1, width: "20px"},
                {data: 'order'},
                {data: 'act'},
                {data: 'extract'}
            ],
            columnDefs: [
                {
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {
                        return `<div onclick='editElement(${JSON.stringify(full)})' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
                    }
                },
                {
                    'targets': 3,
                    'render': function(data, type, full, meta) {
                        return (data == "yes") ? "Sí" : "No";
                    }
                }
            ],
            drawCallback: function(settings) {
                $('#kt_table').show();
            },
            order: [
                [1, 'asc']
            ],
            buttons: [
                createExcelExportButton({
                    columnsToOmit: [0],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Informe de actos.xlsx',
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
        init: function() {
            initTable();
        }
    };
}();

jQuery(document).ready(function() {
    KTDatatables.init();

    $('#delete-button').click(function() { 
        $('#id_delete').val($('#id_edit').val());
        $('#modal_delete').modal('show');
    });
});

function editElement(data) {
    $("#order_e").val(data.order);
    $("#act_e").val(data.act);
    $("#extract_e").val(data.extract);
    $("#id_edit").val(data.id);
    $('#modal_edit').modal('show');
}

