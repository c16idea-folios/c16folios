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
                url: "payment/dataTable",
                dataType: "json",
                type: "POST",
                data:function(data) {
                    data.status = $('#status').val();
                    data._token = $('#token_ajax').val();
                    data.from_payments_view="" ;
                }
            },
      
  
            columns: [
                { data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'no'},
                { data: 'act'},
                { data: 'client'},
                { data: 'cost_vat'},
                { data: 'amount_paid'},
                { data: 'pending'},
                { data: 'invoice'},
         
            ],
            columnDefs: [
                {
                    'targets': 4,
                   
                    'render': function(data, type, full, meta) {

                        return `<p style="color:#000; font-weight:bold;">${formatCurrency(data)}</p>` ;
                    }
                },
                {
                    'targets': 5,
                   
                    'render': function(data, type, full, meta) {

                        return `<p style="color:green; font-weight:bold;">${formatCurrency(data)}</p>` ;
                    }
                },
                {
                    'targets': 6,
                   
                    'render': function(data, type, full, meta) {

                        return `<p style="color:red; font-weight:bold;">${formatCurrency(data)}</p>` ;
                    }
                },
                {
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<div  onclick='editElement(${full.id},${full.no},${full.instrument_act_id},"${full.payment_date_f}","${full.received_from}",${full.amount_paid},${full.payment_method_id},"${full.observations}",${JSON.stringify(full.acts_list)})' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
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
                    columnsToOmit: [0],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Informe de pagos.xlsx',
                    columnsNoCustomRender: [4,5,6],
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



    $('#delete-button-payment').click(function() { 
        $('#id_delete').val( $('#payment_id_e').val());
       $('#modal_delete').modal('show');
        });

            // Escuchar el evento "change" del select principal
    $('#no_instrument').on('change', function () {
        // Obtener el atributo data-acts del option seleccionado
        const dataActs = $(this).find(':selected').data('acts');

        console.log("dataActs", dataActs);

        // Convertir el JSON en un array de objetos (si no es null o undefined)
        const acts = dataActs ? dataActs : [];

        // Obtener el select donde se cargarán los datos
        const actSelect = $('#act_id');

        // Limpiar las opciones actuales del select
        actSelect.empty();

        // Verificar si hay datos en el array
        if (acts.length > 0) {
            // Recorrer el array y agregar cada opción al select
            acts.forEach(act => {
                actSelect.append(new Option(act.text, act.id));
            });
        } else {
            // Si no hay datos, agregar una opción vacía
            actSelect.append(new Option('No hay actos disponibles', ''));
        }
    });



    $('#status').on('change', function () {
        // Obtener el atributo data-acts del option seleccionado
        const selected = $(this).find(':selected').val();
        $('#status').val(selected);
console.log("status",selected);
        tableMain.search("");
        tableMain.ajax.reload(); 
    
    });

       
});







function  editElement(id,no,instrument_act_id,payment_date,received_from,amount_paid,payment_method_id,observations,acts_list){



    const selectElement1 = document.getElementById('act_id_e'); // Primer select
    
    // Limpiar los select antes de agregar opciones
    selectElement1.innerHTML = '';
    
    // Crear la opción "Seleccione"
    const option = document.createElement('option');
    option.value = ""; // Asigna el valor vacío para la opción predeterminada
    option.textContent = "Seleccione"; // Asigna el texto mostrado
    selectElement1.appendChild(option);
    
    // Iterar sobre los elementos del array y agregar las opciones a ambos select
    acts_list.forEach(act => {
        const option = document.createElement('option');
        option.value = act.id; // Asigna el valor del id
        option.textContent = act.text; // Asigna el texto mostrado
    
        selectElement1.appendChild(option);
    });
    


    $("#no_instrument_payment_e").val(no);
    $("#act_id_payment_e").val(instrument_act_id);
    $("#payment_date_e").val(payment_date);
    $("#received_from_e").val(received_from);
    $("#amount_paid_e").val(amount_paid);
    $("#payment_method_e").val(payment_method_id);
    $("#observations_payment_e").val(observations);
    $("#payment_id_e").val(id);
    

    $("#act_id_e").val(instrument_act_id);
    $('#modal_edit').modal('show');
    
     }
     

    function getFileImageType(filePath) {
        // Mapeo de extensiones a los tipos de imagen representativos
        const fileTypes = {
            'png': 'file_type_image.png',
            'jpg': 'file_type_image.png',
            'jpeg': 'file_type_image.png',
            'gif': 'file_type_image.png',
            'bmp': 'file_type_image.png',
            'pdf': 'file_type_pdf.png',
            'docx': 'file_type_word.png',
            'doc': 'file_type_word.png',
            'xlsx': 'file_type_excel.png',
            'xls': 'file_type_excel.png',
            'pptx': 'file_type_powerpoint.png',
            'ppt': 'file_type_powerpoint.png',
            'txt': 'file_type_text.png',
            'zip': 'file_type_zip.png',
            'rar': 'file_type_zip.png',
            'csv': 'file_type_csv.png',
            'html': 'file_type_html.png',
            'css': 'file_type_css.png',
            'js': 'file_type_js.png'
        };
    
        // Agrupar tipos de archivos multimedia (audio y video)
        const multimediaTypes = ['mp3', 'wav', 'mp4', 'avi', 'mov', 'mkv'];
    
        // Extraer la extensión del archivo
        const extension = filePath.split('.').pop().toLowerCase(); // Obtener la extensión y pasarla a minúsculas
    
        // Verificar si la extensión está en el mapeo de tipos de archivo estándar
        if (fileTypes[extension]) {
            return fileTypes[extension];
        } else if (multimediaTypes.includes(extension)) {
            // Si es un archivo multimedia, devolver una imagen de audio o video
            if (['mp3', 'wav'].includes(extension)) {
                return 'file_type_audio.png'; // Imagen para audio
            } else {
                return 'file_type_video.png'; // Imagen para video
            }
        } else {
            return 'file_type_unknown.png'; // Si no está en el mapeo, retornar una imagen por defecto
        }
    }


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
    
    
    

