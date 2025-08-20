"use strict";
var tableMain = null;
var KTDatatables = function () {
    var initTable = function () {
        tableMain = $("#kt_table").DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todo"],
            ],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            autoWidth: true,
            pageLength: -1,
            responsive: true,
            colReorder: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            serverMethod: "post",
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "No se encontró nada",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: "",
            },
            ajax: {
                url: "calendar/dataTable",
                dataType: "json",
                type: "POST",
                data:function(data) {
                    data.year= $('#yearSelect').val();
                    data._token = $('#token_ajax').val();
                }
            },
            columns: [
                { data: "date" },
                { data: "holiday" },
            ],
            columnDefs: [
                {
                    targets: 1,
                    createdCell: function (td, cellData, rowData, row, col) {
                        // Aplicar estilo a la celda según el valor de cellData
                        if (cellData === "Sí") {
                            $(td).css("background-color", "rgba(0, 128, 0, 0.2)");
                        }
                    },
                    render: function (data, type, row) {
                      var selectedYes = data === "Sí" ? "selected" : "";
                      var selectedNo = data === "No" ? "selected" : "";
                    
                      
                      return `
                        <div style="padding: 0px;">
                          <select style="border: none; padding: 0px; margin: 0px; height: 20px; background: transparent;" 
                                  class="form-control holiday-select text-center" 
                                  data-date="${row.date}">
                            <option value="Sí" ${selectedYes}>Sí</option>
                            <option value="No" ${selectedNo}>No</option>
                          </select>
                        </div>
                      `;
                    },
                  }
            ],
            drawCallback: function (settings) {
                $("#kt_table").show();
            },
            order: [[0, "asc"]],

            buttons: [
                createExcelExportButton({
                    columnsToOmit: [],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Informe calendario.xlsx',
                    columnsNoCustomRender: [1],
                    columnsAlternateData: {
                   
                      
                    }
                })
            ],
            initComplete: function() {
                tableMain.buttons().container().appendTo('#kt_table_wrapper .col-md-6:eq(0)');
            }
        });
    };

    var handleSelectChange = function () {
        // Detectar cambios en los selects dentro de la tabla
        $("#kt_table").on("change", ".holiday-select", function () {
            var newValue = $(this).val(); // Nuevo valor seleccionado
            var date = $(this).data("date"); // Fecha asociada a la fila (obtenida del atributo data-date)
    // Actualizar el estilo de la celda según el valor seleccionado
    var selectElement = $(this);
    var cell = selectElement.closest('td'); // Obtener la celda que contiene el <select>
    
    if (newValue === "Sí") {
        // Aplicar fondo verde translúcido
        cell.css('background-color', 'rgba(0, 128, 0, 0.2)');
       // selectElement.css('background-color', 'rgba(0, 128, 0, 0.2)');
    } else {
        // Restaurar fondo original
        cell.css('background-color', '');
       // selectElement.css('background-color', '');
    }

            // Enviar el cambio mediante AJAX
            $.ajax({
                url: "calendar",
                type: "POST",
                data: {
                    _token: $("#token_ajax").val(),
                    date: date,
                    holiday: newValue,
                },
                success: function (response) {
                    if (response.success) {
                   
                        showToast(0,"Actualización exitosa");
                    } else {
           
                        showToast(3,"Hubo un error al actualizar el valor.");
                    }
                },
                error: function (e) {
                    console.error(e);
                    alert("Error en la solicitud AJAX.");
                },
            });
        });
    };

    return {
        init: function () {
            initTable();
            handleSelectChange(); // Manejar los eventos de cambio en los selects
        },
    };
}();

function showToast(type,msg,time=1500){
    var types=['success','info','warning','error'];
    toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop:true,
    progressBar: true,
    positionClass: 'toast-top-right',
    preventDuplicates: false,
    onclick: null,
    timeOut: time
    };
    var $toast = toastr[types[type]](msg, ''); // Wire up an event handler to a button in the toast, if it exists
    var $toastlast = $toast;
    if(typeof $toast === 'undefined'){
    return;
    }
}
    

jQuery(document).ready(function () {
    KTDatatables.init();

    // Evento para cambiar el año
    $("#yearSelect").on("change", function () {
        const selectedYear = $(this).val();
        tableMain.search("");
        tableMain.ajax.reload();
    });
});
