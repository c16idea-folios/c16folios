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
                url: "file/dataTable",
                dataType: "json",
                type: "POST",
                data: { _token: $('#token_ajax').val() }
            },
      
      
            columns: [
                { data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'no'},
                { data: 'act'},
                { data: 'client'},
                { data: 'type'},
                { data: 'name_file'},
                { data: 'updated_at_f'},
                { data: 'file_path'},
         
            ],
            columnDefs: [
              
                {
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<div  onclick='editElement(${full.id},${full.no},${full.instrument_act_id},${full.file_type_id},${JSON.stringify(full.acts_list)})' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
                    }
                },

                {
                    'targets': 7,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        var imageUrl = '/storage/' + data;

                        return `
                        <a href="${imageUrl}" download>
                            <img class="img-button" src="/assets/images/formats/${getFileImageType(data)}" width="40px">
                        </a>
                    `;
                    
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
                    filename: 'Informe de archivos.xlsx',
                    columnsNoCustomRender: [7],
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
        $('#id_delete').val( $('#file_id').val());
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

       
});







function  editElement(id,no,instrument_act_id,file_type_id,acts_list){


    $("#no_instrument_e").val(no);
    $("#act_id_e").val(instrument_act_id);
    $("#file_type_e").val(file_type_id);


    $("#file_id").val(id);




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
    
    

