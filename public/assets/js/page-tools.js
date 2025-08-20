"use strict";
var table=null;
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {

		// begin first table
		table = $('#kt_table_1').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
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
                processing: `Processing the content   <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Search",
                lengthMenu: "Show _MENU_  peer page",
                zeroRecords: "Nothing found",
                info: "Page _PAGE_ of _PAGES_  (filtered of _MAX_ records totals)",
                infoEmpty: "There are no records to show.",
                infoFiltered: ""
                  },
            ajax: {
                url:"tool_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val() }
            },
			columns: [
                {data: 'id'},
				{data: 'n',responsivePriority: 2},
                {data: 'title',responsivePriority: 3},
                {data: 'stock',responsivePriority: 4},
                {data: 'type',responsivePriority: 5},
                {data: 'status' ,responsivePriority: -2},
				{data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },

                {
                    'targets': 5,
                  
                    'orderable': true,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){

                        if(data=='false'){
                            return `<div class="status-red p-1">Disable</div>`;
                           }else if(data=='true'){
                            return `<div class="status-green p-1">Enable</div>`;
                           }
                           }
                    }  
                ,
				{
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
                    
						return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick='editElement(${JSON.stringify(data)})'><i class="flaticon-edit"></i> See or edit </a>
                                <a class="dropdown-item" href="#" onclick="deleteElement(`+data.id+`)"><i class="flaticon-delete"></i> Delete</a>
                            
                            </div>
                        </span>
                        `;
					},
				}
            ],
            order: [[0, 'desc']]
            
        });
        
        
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		},

    };
    


}();



function editElement(data){
console.log(data);
$("#n_edit").val(data.n);
$("#title_edit").val(data.title);
$("#stock_edit").val(data.stock);
$("#type_edit").val(data.type);

$("#modal_edit_element").modal("show");
if(data.picture=="" || data.picture==null){
$('#img-change-element-edit').attr('src',"/assets/images/upload_picture.png");
}else{
$('#img-change-element-edit').attr('src',"/storage/images/tools/"+data.picture);
}

$("#id-edit").val(data.id);


$("#modal_edit_element input[name=status][value=" + data.status + "]").prop('checked', true);




}


jQuery(document).ready(function() {
KTDatatablesDataSourceAjaxServer.init();
// Handle click on "Select all" control
$('#select-all').on('click', function(){
// Get all rows with search applied
var rows = table.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

table.on( 'draw', function () {
if($('#select-all').is(":checked")){
var rows = table.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});

// var head_item = table.columns(0).header();
// $(head_item ).addClass('clean-icon-table');



 });


function showInfoCellInModal(title,content){
    $('#modal-info-cell-title').text(((title)? title  : ''));
    $('#modal-info-cell-content').text(((content)? content  : ''));
    $('#modal-info-cell').modal('show');

}
function deleteSelected(){
    var form = '#form_delete';
    $('#container-ids-delete').html('');
    // Iterate over all checkboxes in the table
    table.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-delete').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });

$('#modal_delete').modal('show');
}

function deleteElement(id){
    $('#id_delete').val(id);
    $('#modal_delete_element').modal('show');
}


function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#img-change-profile').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img-change").change(function(){
    readURL2(this);
});


function readURLEdit(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#img-change-element-edit').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#img-change-edit").change(function(){
    readURLEdit(this);
});


checkRol();

$('#id_rol').change(function(){
checkRol();
});

function checkRol(){
   
    var idRol= $($('#id_rol')).find("option:selected").attr('value');
    if(idRol=="4"){
       $("#part-password").hide();
       $("#part-pin").show();
       $("#pin").attr('type', 'text');
       $("#password_confirmation").attr('type', 'hidden');
       $("#password").attr('type', 'hidden');
    }else if(idRol==""){
        $("#part-password").hide();
        $("#part-pin").hide();   
        $("#pin").attr('type', 'hidden');
        $("#password_confirmation").attr('type', 'password');
        $("#password").attr('type', 'password');
    }else{
        $("#part-password").show();
        $("#part-pin").hide();
        $("#pin").attr('type', 'hidden');
        $("#password_confirmation").attr('type', 'password');
        $("#password").attr('type', 'password');
        
    }
}
////////////////////////////////////////////////////////////////protected edit
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