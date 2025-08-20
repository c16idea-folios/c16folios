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
                url: "notification/dataTable",
                dataType: "json",
                type: "POST",
                data:function(data) {
                    data.status = $('#status').val();
                    data._token = $('#token_ajax').val();
                    data.from_notifications_view="" ;
                }
            },


            columns: [
                { data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'no'},
                { data: 'client'},
                { data: 'act'},
                { data: 'created_at_act'},
                { data: 'notice_type_text'},
                { data: 'days'},
                { data: 'expiration_date'},
                { data: 'days_remaining'},
                { data: 'presentation_date'},
                { data: 'authorization_date'},

         
            ],
            columnDefs: [
                {
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<div  onclick='editNotificationModal(${full.id},${full.no},${full.instrument_act_id},"${full.presentation_date}","${full.notice_type_id}","${full.observations}",${JSON.stringify(full.acts_list)})' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
                    }
                },
                {
                    'targets': 8,
                   
                    'render': function(data, type, full, meta) {
                        if(full.status=="Presentado"){
                            return "";
                        }else{
                            return `<p style="color:red; font-weight:bold;">${data}</p>` ;


                        }

                    }
                },
                {
                    'targets': 9,
                   
                    'render': function(data, type, full, meta) {
                        if(full.status=="Pendiente"){
                            return "";
                        }else{
return data;

                        }

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
                    columnsToOmit: [0],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Informe de avisos.xlsx',
                    columnsNoCustomRender: [8],
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
        $('#id_delete').val( $('#notification_id_e').val());
       $('#modal_delete').modal('show');
        });

            // Escuchar el evento "change" del select principal
    $('#no_instrument').on('change', function () {
        // Obtener el atributo data-acts del option seleccionado
        const dataActs = $(this).find(':selected').data('acts');
         // Convertir el JSON en un array de objetos (si no es null o undefined)
         const acts = dataActs ? dataActs : [];
        loadActList(acts);
       
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


function loadActList(acts_list){


    

    const selectElement1 = document.getElementById('act_id_notification'); // Primer select
   // const selectElement2 = document.getElementById('act_id_notification_e'); // Segundo select
    
    // Limpiar los select antes de agregar opciones
    selectElement1.innerHTML = '';
    //selectElement2.innerHTML = '';
    
    // Crear la opción "Seleccione"
    const option = document.createElement('option');
    option.value = ""; // Asigna el valor vacío para la opción predeterminada
    option.textContent = "Seleccione"; // Asigna el texto mostrado
    selectElement1.appendChild(option);
    //selectElement2.appendChild(option.cloneNode(true)); // Agrega la misma opción al segundo select
    
    // Iterar sobre los elementos del array y agregar las opciones a ambos select
    acts_list.forEach(act => {
        if(act.show_in_notification=="true" || act.show_in_notification==true ){
            const option = document.createElement('option');
            option.value = act.id; // Asigna el valor del id
            option.textContent = act.text; // Asigna el texto mostrado
            option.setAttribute('is_foreigner', act.is_foreigner); // Asigna un tercer parámetro como atributo personalizado
    
            selectElement1.appendChild(option);
           // selectElement2.appendChild(option.cloneNode(true)); // Agrega la opción al segundo select
        }
        
    });



    const noticeType = $('#notice_type');
    noticeType.prop('disabled', true);
    $('#notice_type_container1').show();

    const noticeType2 = $('#notice_type_foreigner');
    noticeType2.prop('disabled', true);
    $('#notice_type_container2').hide();


 }

 $('#act_id_notification').on('change', function () {
    const selectedOption = $(this).find(':selected');
    const isForeigner = selectedOption.attr('is_foreigner');
   
    if(isForeigner=="true"){

  const selectElement = $('#notice_type');
  selectElement.prop('disabled', true);
  $('#notice_type_container1').hide();


  const selectElement2 = $('#notice_type_foreigner');
  selectElement2.prop('disabled', false);
  $('#notice_type_container2').show();

    }else{

        const selectElement = $('#notice_type');
        selectElement.prop('disabled', false);
        $('#notice_type_container1').show();
      
      
        const selectElement2 = $('#notice_type_foreigner');
        selectElement2.prop('disabled', true);
        $('#notice_type_container2').hide();
    }
});


function editNotificationModal(id,no,instrument_act_id,presentation_date,notice_type_id,observations,acts_list){



    const selectElement1 = document.getElementById('act_id_notification_e'); // Primer select
   // const selectElement2 = document.getElementById('act_id_notification_e'); // Segundo select
    
    // Limpiar los select antes de agregar opciones
    selectElement1.innerHTML = '';
    //selectElement2.innerHTML = '';
    
    // Crear la opción "Seleccione"
    const option = document.createElement('option');
    option.value = ""; // Asigna el valor vacío para la opción predeterminada
    option.textContent = "Seleccione"; // Asigna el texto mostrado
    selectElement1.appendChild(option);
    //selectElement2.appendChild(option.cloneNode(true)); // Agrega la misma opción al segundo select
    
    // Iterar sobre los elementos del array y agregar las opciones a ambos select
    acts_list.forEach(act => {
        if(act.show_in_notification=="true" || act.show_in_notification==true ){
            const option = document.createElement('option');
            option.value = act.id; // Asigna el valor del id
            option.textContent = act.text; // Asigna el texto mostrado
            option.setAttribute('is_foreigner', act.is_foreigner); // Asigna un tercer parámetro como atributo personalizado
    
            selectElement1.appendChild(option);
           // selectElement2.appendChild(option.cloneNode(true)); // Agrega la opción al segundo select
        }
        
    });


    const noticeType = $('#notice_type_e');
    noticeType.prop('disabled', true);
    $('#notice_type_container1_e').show();

    const noticeType2 = $('#notice_type_foreigner_e');
    noticeType2.prop('disabled', true);
    $('#notice_type_container2_e').hide();


   


    $("#no_instrument_notification_e").val(no);
    $("#act_id_notification_e").val(instrument_act_id);
    $("#presentation_date_e").val(presentation_date);
    $("#observations_notification_e").val(observations);

    $("#notice_type_foreigner_e").val(notice_type_id);
    $("#notice_type_e").val(notice_type_id);
    

    $("#notification_id_e").val(id);
    checkSelectNotification();
    $('#modal_edit').modal('show');

}

$('#act_id_notification_e').on('change', function () {
    checkSelectNotification();
});
function checkSelectNotification(){
    const selectedOption = $("#act_id_notification_e").find(':selected');
    const isForeigner = selectedOption.attr('is_foreigner');
   
    if(isForeigner=="true"){

  const selectElement = $('#notice_type');
  selectElement.prop('disabled', true);
  $('#notice_type_container1_e').hide();


  const selectElement2 = $('#notice_type_foreigner_e');
  selectElement2.prop('disabled', false);
  $('#notice_type_container2_e').show();

    }else{

        const selectElement = $('#notice_type_e');
        selectElement.prop('disabled', false);
        $('#notice_type_container1_e').show();
      
      
        const selectElement2 = $('#notice_type_foreigner_e');
        selectElement2.prop('disabled', true);
        $('#notice_type_container2_e').hide();
    }
}

function openModalAdd(){
    
    const noticeType = $('#notice_type');
    noticeType.prop('disabled', true);
    $('#notice_type_container1').show();

    const noticeType2 = $('#notice_type_foreigner');
    noticeType2.prop('disabled', true);
    $('#notice_type_container2').hide();

    $('#modal_add').modal('show');

}





    
    

