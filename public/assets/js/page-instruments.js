"use strict";
var tableMain = null;
var tableMainRecord = null;
var tablePayments = null;
var tableNotifications = null;
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
                data: { _token: $('#token_ajax').val()  }
            },
         
            columns: [
                { data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'record'},
                { data: 'payments'},
                { data: 'notices'},
                { data: 'delivered'},
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
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<img onclick='openRecordModal(${full.id},${JSON.stringify(full.acts_list)},${full.no})' class="img-button" src="/assets/images/expediente-flat.png" width="40px">`;
                    }
                },
                {
                    'targets': 2,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<img onclick='openPaymentsModal(${full.id},${full.no},${JSON.stringify(full.acts_list)})' class="img-button" src="/assets/images/pago-flat.png" width="40px">`;
                    }
                },
                {
                    'targets': 3,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {
                        if(full.show_notification=="true" || full.show_notification==true){
                            return `<img onclick='openNotificationsModal(${full.id},${full.no},${JSON.stringify(full.acts_list)})' class="img-button" src="/assets/images/alerta-flat.png" width="40px">`;

                        }else{
                            return "";
                        }

                    }
                },
                {
                    'targets': 4,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        if(full.submission_date=="" || full.submission_date==null){
                            return `<img onclick='openSubmissionModal(${full.id},${full.no},"${full.type}","${full.responsible}","${full.submission_date}","${full.who_receives}")' class="img-button" src="/assets/images/wait.png" width="40px">`;

                        }else{
                            return `<img onclick='openSubmissionModal(${full.id},${full.no},"${full.type}","${full.responsible}","${full.submission_date}","${full.who_receives}")' class="img-button" src="/assets/images/check.png" width="40px">`;

                        }


                    }
                },

                {
                    'targets': 5,
                    'width': '10%',
                    'render': function(data, type, full, meta) {

                        return data;
                    }
                },
                {
                    'targets': 6,
                    'width': '10%',
                    'render': function(data, type, full, meta) {

                        return data;
                    }
                },

                {
                    'targets': 8,
                    'className': 'fix-ul-large',
                    'render': function(data, type, full, meta) {

                        return data;
                    }
                },

                {
                    'targets': 9,
                    'className': 'fix-ul-large',
                    'width': '30%',
                    'render': function(data, type, full, meta) {

                        return data;
                    }
                },
                {
                    'targets': 10,
                   
                    'render': function(data, type, full, meta) {

                        return `<p style="font-weight:bold;">${ formatCurrency(data)}</p>`;
                    }
                },
                {
                    'targets': 11,
                   
                    'render': function(data, type, full, meta) {

                        return formatCurrency(data);
                    }
                },
                {
                    'targets': 12,
                   
                    'render': function(data, type, full, meta) {

                        return formatCurrency(data);
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
                    columnsToOmit: [0,1,2,3],   // Omitir columna 0
                    text: 'Descargar Excel',
                    filename: 'Instrumentos.xlsx',
                    columnsNoCustomRender: [4,8,9,10],
                    columnsAlternateData: {
                        4: 'delivered_status',
                        8: 'acts_formated',
                        9: 'clients_formated',
                        10: 'total_formated'
                    }
                })
            ],
            initComplete: function() {
                tableMain.buttons().container().appendTo('#kt_table_wrapper .col-md-6:eq(0)');
            }

        });



        tableMainRecord = $('#kt_table_record').DataTable({
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
                data:function(data) {
                    data.instrument_id= $('#instrument_id').val();
                    data._token = $('#token_ajax').val();
                }
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

                        return `<div  onclick='editRecordModal(${full.id},${full.no},${full.instrument_act_id},${full.file_type_id})' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
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
                    $('#kt_table_record').show();
            },
            order: [
                [0, 'desc']
            ]

        });




        tablePayments = $('#kt_table_payments').DataTable({
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
                    data.instrument_id= $('#instrument_id_payment').val();
                    data._token = $('#token_ajax').val();
                }
            },



            columns: [
                { data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'id'},
                { data: 'act'},
                { data: 'client'},
                { data: 'payment_date_f'},
                { data: 'received_from'},
                { data: 'amount_paid'},
                { data: 'observations'},
                { data: 'id'},

   
            
         
            ],

            footerCallback: function(row, data, start, end, display) {
                // Calcula el total de "Importe pagado"
                var api = this.api();
                var total = 0;
        
                // Itera sobre los datos visibles de la columna (índice 5)
                api.column(6, { page: 'current' }).data().each(function(value, index) {
                    total +=  parseFloat(value) || 0;
                });
        
                // Inserta el total en el pie de la tabla
                $(api.column(6).footer()).html(formatCurrency(total) );
            },
            columnDefs: [
                {
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<div  onclick='editPaymentsModal(${full.id},${full.no},${full.instrument_act_id},"${full.payment_date_f}","${full.received_from}",${full.amount_paid},${full.payment_method_id},"${full.observations}")' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
                    }
                },
                {
                    'targets': 8,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<a href='payment/print/${full.id}' target='_blank' ><img onclick="" class="img-button" src="/assets/images/print.png" width="40px"></a>`;
                    }
                },

                {
                    'targets': 6,
                   
                    'render': function(data, type, full, meta) {

                        return formatCurrency(data);
                    }
                },
       
       
                
                
            ],
            drawCallback: function(settings) {
                    $('#kt_table_record').show();
            },
            order: [
                [0, 'desc']
            ]

        });





        tableNotifications = $('#kt_table_notifications').DataTable({
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
                    data.instrument_id= $('#instrument_id_notification').val();
                    data._token = $('#token_ajax').val();
                }
            },

            columns: [
                { data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'no'},
                { data: 'act'},
                { data: 'client'},
                { data: 'presentation_date_f'},
                { data: 'notice_type_text'},
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

                    return `<div  onclick='editNotificationModal(${full.id},${full.no},${full.instrument_act_id},"${full.presentation_date_f}","${full.notice_type_id}","${full.observations}")' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
                    }
                }
                
                
            ],
            drawCallback: function(settings) {
                    $('#kt_table_notifications').show();
            },
            order: [
                [0, 'desc']
            ]

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





function editElement(data){
    console.log(data);
    window.location.href = `instrument/${data.id}`;


    }
    

    $('#button_create_record').click(function() { 
    $("#modal_record_part1").hide();
    $("#modal_record_part3").hide();
    $("#modal_record_part2").show();
        });
    


        
function openRecordModal(id,list_acts,no){

    

   $("#instrument_id").val(id);

    $("#modal_record_part1").show();
    $("#modal_record_part2").hide();
    $("#modal_record_part3").hide();


    
    

    const selectElement1 = document.getElementById('act_id'); // Primer select
    const selectElement2 = document.getElementById('act_id_e'); // Segundo select
    
    // Limpiar los select antes de agregar opciones
    selectElement1.innerHTML = '';
    selectElement2.innerHTML = '';
    
    // Crear la opción "Seleccione"
    const option = document.createElement('option');
    option.value = ""; // Asigna el valor vacío para la opción predeterminada
    option.textContent = "Seleccione"; // Asigna el texto mostrado
    selectElement1.appendChild(option);
    selectElement2.appendChild(option.cloneNode(true)); // Agrega la misma opción al segundo select
    
    // Iterar sobre los elementos del array y agregar las opciones a ambos select
    list_acts.forEach(act => {
        const option = document.createElement('option');
        option.value = act.id; // Asigna el valor del id
        option.textContent = act.text; // Asigna el texto mostrado
    
        selectElement1.appendChild(option);
        selectElement2.appendChild(option.cloneNode(true)); // Agrega la opción al segundo select
    });
    
    $('#no_instrument').val(no);

    $('#modal_record').modal('show');

    tableMainRecord.search("");
    tableMainRecord.ajax.reload(); 
}

function  editRecordModal(id,no,instrument_act_id,file_type_id){


$("#no_instrument_e").val(no);
$("#act_id_e").val(instrument_act_id);
$("#file_type_e").val(file_type_id);

$("#file_id").val(id);

$("#modal_record_part1").hide();
$("#modal_record_part2").hide();
$("#modal_record_part3").show();

 }
 

 $('#button_create_payment').click(function() { 
    $("#modal_payments_part1").hide();
    $("#modal_payments_part2").show();
    $("#modal_payments_part3").hide();

        });
    

 function openPaymentsModal(id,no,acts_list){


    $("#modal_payments_part1").show();
    $("#modal_payments_part2").hide();
    $("#modal_payments_part3").hide();


    $("#no_instrument_payment").val(no);
    $("#instrument_id_payment").val(id);
    

    const selectElement1 = document.getElementById('act_id_payment'); // Primer select
    const selectElement2 = document.getElementById('act_id_payment_e'); // Segundo select
    
    // Limpiar los select antes de agregar opciones
    selectElement1.innerHTML = '';
    selectElement2.innerHTML = '';
    
    // Crear la opción "Seleccione"
    const option = document.createElement('option');
    option.value = ""; // Asigna el valor vacío para la opción predeterminada
    option.textContent = "Seleccione"; // Asigna el texto mostrado
    selectElement1.appendChild(option);
    selectElement2.appendChild(option.cloneNode(true)); // Agrega la misma opción al segundo select
    
    // Iterar sobre los elementos del array y agregar las opciones a ambos select
    acts_list.forEach(act => {
        const option = document.createElement('option');
        option.value = act.id; // Asigna el valor del id
        option.textContent = act.text; // Asigna el texto mostrado
    
        selectElement1.appendChild(option);
        selectElement2.appendChild(option.cloneNode(true)); // Agrega la opción al segundo select
    });


    $('#modal_payments').modal('show');
    tablePayments.search("");
    tablePayments.ajax.reload(); 

 }


 function editPaymentsModal(id,no,instrument_act_id,payment_date,received_from,amount_paid,payment_method_id,observations){


    $("#modal_payments_part1").hide();
    $("#modal_payments_part2").hide();
    $("#modal_payments_part3").show();


    $("#no_instrument_payment_e").val(no);
    $("#act_id_payment_e").val(instrument_act_id);
    console.log("payment_date",payment_date);
    $("#payment_date_e").val(payment_date);
    $("#received_from_e").val(received_from);
    $("#amount_paid_e").val(amount_paid);
    $("#payment_method_e").val(payment_method_id);
    $("#observations_payment_e").val(observations);


    $("#payment_id_e").val(id);
 



 }


 $('#button_create_notification').click(function() { 
    $("#modal_notifications_part1").hide();
    $("#modal_notifications_part2").show();
    $("#modal_notifications_part3").hide();

        });

 function openNotificationsModal(id,no,acts_list){
    $("#modal_notifications_part1").show();
    $("#modal_notifications_part2").hide();
    $("#modal_notifications_part3").hide();


    $("#instrument_id_notification").val(id);


    $("#no_instrument_notification").val(no);

    

    const selectElement1 = document.getElementById('act_id_notification'); // Primer select
    const selectElement2 = document.getElementById('act_id_notification_e'); // Segundo select
    
    // Limpiar los select antes de agregar opciones
    selectElement1.innerHTML = '';
    selectElement2.innerHTML = '';
    
    // Crear la opción "Seleccione"
    const option = document.createElement('option');
    option.value = ""; // Asigna el valor vacío para la opción predeterminada
    option.textContent = "Seleccione"; // Asigna el texto mostrado
    selectElement1.appendChild(option);
    selectElement2.appendChild(option.cloneNode(true)); // Agrega la misma opción al segundo select
    
    // Iterar sobre los elementos del array y agregar las opciones a ambos select
    acts_list.forEach(act => {
        if(act.show_in_notification=="true" || act.show_in_notification==true ){
            const option = document.createElement('option');
            option.value = act.id; // Asigna el valor del id
            option.textContent = act.text; // Asigna el texto mostrado
            option.setAttribute('is_foreigner', act.is_foreigner); // Asigna un tercer parámetro como atributo personalizado
    
            selectElement1.appendChild(option);
            selectElement2.appendChild(option.cloneNode(true)); // Agrega la opción al segundo select
        }
        
    });



    const noticeType = $('#notice_type');
    noticeType.prop('disabled', true);
    $('#notice_type_container1').show();

    const noticeType2 = $('#notice_type_foreigner');
    noticeType2.prop('disabled', true);
    $('#notice_type_container2').hide();


    $('#modal_notifications').modal('show');
    tableNotifications.search("");
    tableNotifications.ajax.reload(); 
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

function editNotificationModal(id,no,instrument_act_id,presentation_date_f,notice_type_id,observations){



    const noticeType = $('#notice_type_e');
    noticeType.prop('disabled', true);
    $('#notice_type_container1_e').show();

    const noticeType2 = $('#notice_type_foreigner_e');
    noticeType2.prop('disabled', true);
    $('#notice_type_container2_e').hide();


    $("#modal_notifications_part1").hide();
    $("#modal_notifications_part2").hide();
    $("#modal_notifications_part3").show();


    $("#no_instrument_notification_e").val(no);
    $("#act_id_notification_e").val(instrument_act_id);
    $("#presentation_date_e").val(presentation_date_f);
    $("#observations_notification_e").val(observations);

    $("#notice_type_foreigner_e").val(notice_type_id);
    $("#notice_type_e").val(notice_type_id);
    

    $("#notification_id_e").val(id);
    checkSelectNotification();
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


function openSubmissionModal(id,no,type,responsible,submission_date,who_receives){
    $('#modal_submission').modal('show');
    $('#no_instrument_submission').val(no);
    $('#type_instrument_submission').val(type);
    $('#responsible_submission').val(responsible);


    $('#submission_date').val((submission_date!="null")?submission_date:"");
    $('#who_receives').val((who_receives!="null")?who_receives:"");
    $('#instrument_id_submission').val(id);
    
}
