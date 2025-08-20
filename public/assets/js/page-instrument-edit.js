"use strict";
var tableMain = null;
var KTDatatables = function() {
    var initTable = function() {
        // begin first table
        tableMain = $('#kt_table_acts').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            dom: 't',
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
                url: "/admin/instrument_act/dataTable",
                dataType: "json",
                type: "POST",
                data:function(data) {
                  data.instrument_id= $('#instrument_id').val();
                  data._token = $('#token_ajax').val();
              }
            },


            columns: [
                 {data: 'id',responsivePriority: 1,  width: "20px", },
                { data: 'created_at_f'},
                { data: 'act'},
                { data: 'client'},
                { data: 'legal_representative'},
                { data: 'cost'},
                { data: 'invoice_text'},
         
            ],
            columnDefs: [
              
                {
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return `<div  onclick='editElementActs(${JSON.stringify(full)})' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
                    }
                },
                {
                  'targets': 5,
               
                  'render': function(data, type, full, meta) {

                      return "$"+data;
                  }
              }
            ],
            drawCallback: function(settings) {
                    $('#kt_table_acts').show();
            },
            order: [
                [0, 'desc']
            ]

        });


                // begin first table
              var  tableAppearer = $('#kt_table_appearer').DataTable({
                  lengthMenu: [
                      [10, 25, 50, 100, -1],
                      [10, 25, 50, 100, "All"]
                  ],
                  dom: 't',
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
                      url: "/admin/appearer/dataTable",
                      dataType: "json",
                      type: "POST",
                      data: { _token: $('#token_ajax').val() }
                  },
                  columns: [
                       {data: 'id',responsivePriority: 1,  width: "20px", },
                      { data: 'act'},
                      { data: 'client'},
                      { data: 'appearer'},
                      { data: 'legal_representative'},
                      { data: 'observations'}
               
                  ],
                  columnDefs: [
                    
                      {
                          'targets': 0,
                          'type': "alt-string",
                          'searchable': false,
                          'orderable': false,
                          'className': 'dt-body-center',
                          'render': function(data, type, full, meta) {
      
                              return `<div  onclick='editElementAppearer(${JSON.stringify(full)})' class="pencil-edit"><i class="icon-2x text-dark-50 flaticon-edit"></i></div>`;
                          }
                      }
                  ],
                  drawCallback: function(settings) {
                          $('#kt_table_appearer').show();
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

    $('#legal_representative_container').hide().find('select').prop('disabled', true);

    $('#delete-button-act').click(function() { 

        $('#id_delete_act').val( $('#id_edit_act').val());
       $('#modal_delete_act').modal('show');
        });

        $('#delete-button-appearer').click(function() { 

          $('#id_delete_appearer').val( $('#id_edit_appearer').val());
         $('#modal_delete_appearer').modal('show');
          });



          $('#delete-button').click(function() { 

            $('#id_delete').val( $('#instrument_id').val());
            $('#modal_delete').modal('show');
            
          });

          $('#save-button').click(function() { 
            $('#form_update').submit();

          });

          
          
  
        


        $('#client').change(function() {
        
            var selectedValue = $(this).val(); // Obtén el valor del select

            // Dividir el valor en id y person_type
            var parts = selectedValue.split('|');
            var id = parts[0]; // El ID
            var person_type = parts[1]; // El tipo de persona
            if(person_type=="moral"){
                $('#legal_representative_container').show().find('select').prop('disabled', false);
            }else{
                $('#legal_representative_container').hide().find('select').prop('disabled', true);
            }
      

            var selectedOption = $(this).find(':selected');
        
            // Obtener el valor del representante legal desde el atributo "data-legal-representative"
            var legalRepresentative = selectedOption.data('legal-representative') || '';
            
            // Actualizar el campo de texto con el valor del representante legal
            $('#legal_representative').val(legalRepresentative);

          });

        $('#client_e').change(function() {
        
          var selectedValue = $(this).val(); // Obtén el valor del select

          // Dividir el valor en id y person_type
          var parts = selectedValue.split('|');
          var id = parts[0]; // El ID
          var person_type = parts[1]; // El tipo de persona
          if(person_type=="moral"){
              $('#legal_representative_container_e').show().find('select').prop('disabled', false);
          }else{
              $('#legal_representative_container_e').hide().find('select').prop('disabled', true);
          }


          var selectedOption = $(this).find(':selected');
        
          // Obtener el valor del representante legal desde el atributo "data-legal-representative"
          var legalRepresentative = selectedOption.data('legal-representative') || '';
          
          // Actualizar el campo de texto con el valor del representante legal
          $('#legal_representative_e').val(legalRepresentative);
    
        });


        $('#appearer').change(function() {
        
          var selectedValue = $(this).val(); // Obtén el valor del select

          // Dividir el valor en id y person_type
          var parts = selectedValue.split('|');
          var id = parts[0]; // El ID
          var person_type = parts[1]; // El tipo de persona
          if(person_type=="moral"){
              $('#legal_representative_container_appearer').show().find('select').prop('disabled', false);
          }else{
              $('#legal_representative_container_appearer').hide().find('select').prop('disabled', true);
          }
    
          var selectedOption = $(this).find(':selected');
        
          // Obtener el valor del representante legal desde el atributo "data-legal-representative"
          var legalRepresentative = selectedOption.data('legal-representative') || '';
          
          // Actualizar el campo de texto con el valor del representante legal
          $('#legal_representative_appearer').val(legalRepresentative);
        });

        $('#appearer_e').change(function() {
        
          var selectedValue = $(this).val(); // Obtén el valor del select

          // Dividir el valor en id y person_type
          var parts = selectedValue.split('|');
          var id = parts[0]; // El ID
          var person_type = parts[1]; // El tipo de persona
          if(person_type=="moral"){
              $('#legal_representative_container_appearer_e').show().find('select').prop('disabled', false);
          }else{
              $('#legal_representative_container_appearer_e').hide().find('select').prop('disabled', true);
          }

          var selectedOption = $(this).find(':selected');
        
          // Obtener el valor del representante legal desde el atributo "data-legal-representative"
          var legalRepresentative = selectedOption.data('legal-representative') || '';
          
          // Actualizar el campo de texto con el valor del representante legal
          $('#legal_representative_appearer_e').val(legalRepresentative);
    
        });

       
});



  const inputConfigurations = {
    "Designación": [
      {
        title: "Caracter compareciente",
        container_id: "appearing_character_container",
        input_id: "appearing_character",
        input_name: "appearing_character",
        type_input: "text",
        required: true
      }
    ],
    "Fe de hechos": [
      {
        title: "Hecho que se hizo constar",
        container_id: "fact_recorded_container",
        input_id: "fact_recorded",
        input_name: "fact_recorded",
        type_input: "textarea",
        required: true
      }
    ],
    "Formalización": [
      {
        title: "Tipo de formalización",
        container_id: "formalization_type_container",
        input_id: "formalization_type",
        input_name: "formalization_type",
        type_input: "select",
        options: ["NA", "ORDINARIA", "EXTRAORDINARIA"],
        required: true
      }
    ],
    "Notificación": [
      {
        title: "Persona a la que se realizó la notificación",
        container_id: "notified_person_container",
        input_id: "notified_person",
        input_name: "notified_person",
        type_input: "text",
        required: true
      },
      {
        title: "Materia de notificación",
        container_id: "notification_subject_container",
        input_id: "notification_subject",
        input_name: "notification_subject",
        type_input: "text",
        required: true
      }
    ],
    "Revocación": [
      {
        title: "Caracter compareciente",
        container_id: "appearing_character_container",
        input_id: "appearing_character",
        input_name: "appearing_character",
        type_input: "text",
        required: true
      }
    ],
    "Ratificación": [
      {
        title: "Documento que se ratifica",
        container_id: "document_ratified_container",
        input_id: "document_ratified",
        input_name: "document_ratified",
        type_input: "textarea",
        required: true
      }
    ],
    "Formalización de Contrato / Convenio": [
      {
        title: "Tipo de formalización",
        container_id: "formalization_contract_container",
        input_id: "formalization_contract",
        input_name: "formalization_contract",
        type_input: "select",
        options: ["NA", "CONTRATO", "CONVENIO"],
        required: true
      },
      {
        title: "De",
        container_id: "of_container",
        input_id: "of",
        input_name: "of",
        type_input: "text",
        required: false
      }
    ],
    "Declaraciones mercantiles": [
      {
        title: "Declaraciones mercantiles respecto:",
        container_id: "mercantile_declarations_container",
        input_id: "mercantile_declarations",
        input_name: "mercantile_declarations",
        type_input: "textarea",
        required: true
      }
    ],
    
    "Comisión mercantil": [
      {
        title: "A favor de:",
        container_id: "in_favor_of_container",
        input_id: "in_favor_of",
        input_name: "in_favor_of",
        type_input: "text",
        required: true
      }
    ]
  };

  // Función para generar los inputs dinámicamente
  function generateInputs(option) {
    // Limpiar el contenido previo
    $('#dinamic_inputs').empty();

    // Obtener la configuración de inputs para la opción seleccionada
    const inputs = inputConfigurations[option] || [];

    // Generar y agregar cada input al div
    inputs.forEach(input => {
      const isRequired = input.required ? 'required' : '';
      let inputElement = '';

      // Crear el input según su tipo
      switch (input.type_input) {
        case 'text':
          inputElement = `<input type="text" name="${input.input_name}" class="form-control" id="${input.input_id}" ${isRequired}>`;
          break;
        case 'textarea':
          inputElement = `<textarea name="${input.input_name}" class="form-control" id="${input.input_id}" ${isRequired}></textarea>`;
          break;
        case 'select':
          const options = input.options.map(option => `<option value="${option}">${option}</option>`).join('');
          inputElement = `<select name="${input.input_name}" class="form-control" id="${input.input_id}" ${isRequired}>${options}</select>`;
          break;
        default:
          console.warn(`Tipo de input no reconocido: ${input.type_input}`);
      }

      // Crear el contenedor del input
      const inputContainer = `
        <div class="form-group" id="${input.container_id}">
          <label for="${input.input_id}" class="form-control-label">${input.title} ${(input.required)?"*":""}</label>
          ${inputElement}
        </div>
      `;

      // Agregar el input al div
      $('#dinamic_inputs').append(inputContainer);
    });
  }

    // Función para generar los inputs dinámicamente
    function generateInputsEdit(option) {
      // Limpiar el contenido previo
      $('#dinamic_inputs_e').empty();
  
      // Obtener la configuración de inputs para la opción seleccionada
      const inputs = inputConfigurations[option] || [];
  
      // Generar y agregar cada input al div
      inputs.forEach(input => {
        const isRequired = input.required ? 'required' : '';
        let inputElement = '';
  
        // Crear el input según su tipo
        switch (input.type_input) {
          case 'text':
            inputElement = `<input type="text" name="${input.input_name}" value="${(fullDataEdit[input.input_name]!=null)?fullDataEdit[input.input_name]:""}" class="form-control" id="${input.input_id}_e" ${isRequired}>`;
            break;
          case 'textarea':
            inputElement = `<textarea name="${input.input_name}" class="form-control" id="${input.input_id}_e" ${isRequired}>${(fullDataEdit[input.input_name]!=null)?fullDataEdit[input.input_name]:""}</textarea>`;
            break;
          case 'select':
            const options = input.options.map(option => `<option value="${option}" ${(fullDataEdit[input.input_name]==option)?'selected':''}>${option}</option>`).join('');
            inputElement = `<select name="${input.input_name}" class="form-control" id="${input.input_id}" ${isRequired}>${options}</select>`;
            break;
          default:
            console.warn(`Tipo de input no reconocido: ${input.type_input}`);
        }
  
        // Crear el contenedor del input
        const inputContainer = `
          <div class="form-group" id="${input.container_id}_e">
            <label for="${input.input_id}_e" class="form-control-label">${input.title} ${(input.required)?"*":""}</label>
            ${inputElement}
          </div>
        `;
  
        // Agregar el input al div
        $('#dinamic_inputs_e').append(inputContainer);
      });
    }

  // Evento que detecta el cambio en el select
  $('#act_id').on('change', function() {
    const selectedOption = $(this).find('option:selected').text();
    generateInputs(selectedOption);
  });



    // Evento que detecta el cambio en el select
    $('#act_id_e').on('change', function() {
      const selectedOption = $(this).find('option:selected').text();
      generateInputsEdit(selectedOption);
    });
  
  





var fullDataEdit=null;


function editElementActs(data){
  fullDataEdit=data;

    $("#created_at_act_e").val(data.created_at_f);
    $("#client_e").val(`${data.client_id}|${data.person_type}`);

    console.log("client:",`${data.client_id}|${data.person_type}`);
    $("#legal_representative_e").val(data.legal_representative);
    $("#act_id_e").val(data.act_id);
    $("#cost_e").val(data.cost);
    $("#invoice_e").val(data.invoice);

    var selectedValue =`${data.client_id}|${data.person_type}`; // Obtén el valor del select

    // Dividir el valor en id y person_type
    var parts = selectedValue.split('|');
    var id = parts[0]; // El ID
    var person_type = parts[1]; // El tipo de persona
    if(person_type=="moral"){
        $('#legal_representative_container_e').show().find('select').prop('disabled', false);
    }else{
        $('#legal_representative_container_e').hide().find('select').prop('disabled', true);
    }


    generateInputsEdit( data.act);

$("#id_edit_act").val(data.id);
$('#modal_edit_act').modal('show');

    }
    


    function editElementAppearer(data){
      
    $("#instrument_act_e").val(data.instrument_act_id);
    $("#appearer_e").val(`${data.appearer_id}|${data.appearer_person_type}`);
    $("#legal_representative_appearer_e").val(data.legal_representative);
    $("#legend_e").val(data.legend);
    $("#cost_e").val(data.cost);
    $("#observations_e").val(data.observations);

    $("#id_edit_appearer").val(data.id);


    var selectedValue =`${data.appearer}|${data.appearer_person_type}`; // Obtén el valor del select
      // Dividir el valor en id y person_type
      var parts = selectedValue.split('|');
      var id = parts[0]; // El ID
      var person_type = parts[1]; // El tipo de persona
      if(person_type=="moral"){
          $('#legal_representative_container_appearer_e').show().find('select').prop('disabled', false);
      }else{
          $('#legal_representative_container_appearer_e').hide().find('select').prop('disabled', true);
      }

      

    $('#modal_edit_appearer').modal('show');

    }