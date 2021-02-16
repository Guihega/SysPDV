var tabla;
var icono;
var mensaje;
var values = [];
var selected;
var permisos;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false,1);
	listar();
	cargarPermisos();
	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})

	$('#mConfiguracion').addClass("treeview active");
    $('#lVistas').addClass("active");

}

function cargarPermisos(){
	//Cargamos los items al select cliente
	$.post("../ajax/vista.php?op=selectPermiso", function(r){
        $("#idpermiso").html(r);
        $('#idpermiso').selectpicker('refresh');
	});
}

//Función limpiar
function limpiar()
{
	$(".modal-title").html('Nueva Vista');

	$("#nombrevista").val("");
	$("#alias").val("");
	$("#url").val("");
	$("#idvista").val("");
}

//Función mostrar formulario
function mostrarform(flag,accion)
{
	limpiar();
	if (accion==1) {
		if (flag)
		{
			$('#modalNuevaVista').modal('show');
			$("#btnGuardar").prop("disabled",false);
		}
		else
		{
			$('#modalNuevaVista').modal('hide');
		}
	}
	else{
		if (flag)
		{
			$('#modalNuevoPermiso').modal('show');
		}
		else
		{
			$('#modalNuevoPermiso').modal('hide');
		}
	}
}

//Función cancelarform
function cancelarform(accion)
{
	limpiar();
	if (accion==1) {
		mostrarform(false,1);
	}
	else{
		mostrarform(false,2);
	}
}


//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
		"ajax":
		{
			url: '../ajax/vista.php?op=listar',
			type : "get",
			dataType : "json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		"language": {
            "url": "../public/plugins/datatables/language/Spanish.json"
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

	var buttons = new $.fn.dataTable.Buttons(tabla, {
	    buttons: [
            {
            	text: 'Copiar',
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Events export'
            },
            {
                extend: 'pdfHtml5',
                title: 'Events export'
            },
            {
                extend: 'csvHtml5',
                title: 'Events export'
            }
        ]
	}).container().appendTo($('.exportButtons'));

	$('.btn-secondary').addClass('btn-sm');
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: "../ajax/vista.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
			//bootbox.alert(datos);
			console.log(datos);
			if (datos == 0) {
	    		icono = 'success';
	    		mensaje = 'Vista guardada';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'La vista no se pudo registrar';
	    	}
	    	else if (datos == 2){
	    		icono = 'success';
	    		mensaje = 'Vista actualizada';
	    	}
	    	else{
	    		icono = 'error';
	    		mensaje = 'La vista no se pudo actualizar';
	    	}
	    	//mensaje(icono,mensaje);
	    	Swal.fire({
		      position: 'top-end',
		      icon: icono,
		      title: mensaje,
		      showConfirmButton: false,
		      timer: 3000,
		      showClass: {
		        popup: 'animate__animated animate__fadeInDown'
		      },
		      hideClass: {
		        popup: 'animate__animated animate__fadeOutUp'
		      },
		      showCloseButton: true,
		      focusConfirm: false,
		      timerProgressBar: true,
		    });   

			mostrarform(false,1);
			tabla.ajax.reload();
	    }

	});
	limpiar();
}

function mostrar(idvista)
{
	$.post("../ajax/vista.php?op=mostrar",{idvista : idvista}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true,1);

		$(".modal-title").html('Editar vista ' + idvista);
		$("#nombrevista").val(data.nombre);
		$("#alias").val(data.alias);
		$("#url").val(data.url);
		$("#idpermiso").val(data.idpermiso);
		$("#idpermiso").selectpicker('refresh');
		$("#idvista").val(data.idvista);

 	});
 // 	$.post("../ajax/Vista.php?op=permisos&id="+idvista,function(r){
 // 		$("#permisos").empty().append(r);
	// });
}

//Función para desactivar registros
function desactivar(idvista)
{
	swal({
	    title: '¿Está seguro de desactivar la vista?',
	    icon: "warning",
	    buttons: true,
	    showCancelButton: true,
	    buttons:{
		  cancel: {
		    text: "Cancelar",
		    value: true,
		    visible: true,
		    className: "",
		    closeModal: true,
		  },
		  confirm: {
		    text: "Aceptar",
		    //value: true,
		    value: "Aceptar",
		    visible: true,
		    className: "",
		    closeModal: true
		  }
		},
	    dangerMode: true,
	    buttonsStyling: true,
	    closeOnEsc: false,
	    closeOnClickOutside: false
		}).then((value) => {
		switch (value) {
			case "Aceptar":
			//console.log(idvistaCambio);
			  	$.ajax({
			        type: "POST",
			        url: "../ajax/vista.php?op=desactivar",
			        data: {idvista : idvista},
			        cache: false,
			        success: function(response) {
			        	console.log(response);
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'Vista desactivada';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'La vista no se pudo desactivar';
				    	}

				    	Swal.fire({
					      position: 'top-end',
					      icon: icono,
					      title: mensaje,
					      showConfirmButton: false,
					      timer: 3000,
					      showClass: {
					        popup: 'animate__animated animate__fadeInDown'
					      },
					      hideClass: {
					        popup: 'animate__animated animate__fadeOutUp'
					      },
					      showCloseButton: true,
					      focusConfirm: false,
					      timerProgressBar: true,
					    });
					    
					    tabla.ajax.reload();
			        }
			    });
			  	break;
			default:
		  		break;
		}
	});
}

//Función para activar registros
function activar(idvista)
{
	swal({
	    title: '¿Está seguro de activar la vista?',
	    icon: "warning",
	    buttons: true,
	    showCancelButton: true,
	    buttons:{
		  cancel: {
		    text: "Cancelar",
		    value: true,
		    visible: true,
		    className: "",
		    closeModal: true,
		  },
		  confirm: {
		    text: "Aceptar",
		    //value: true,
		    value: "Aceptar",
		    visible: true,
		    className: "",
		    closeModal: true
		  }
		},
	    dangerMode: true,
	    buttonsStyling: true,
	    closeOnEsc: false,
	    closeOnClickOutside: false
		}).then((value) => {
		switch (value) {
			case "Aceptar":
			  	$.ajax({
			        type: "POST",
			        url: "../ajax/vista.php?op=activar",
			        data: {idvista : idvista},
			        cache: false,
			        success: function(response) {
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'Vista activa';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'La vista no se pudo activar';
				    	}

				    	Swal.fire({
					      position: 'top-end',
					      icon: icono,
					      title: mensaje,
					      showConfirmButton: false,
					      timer: 3000,
					      showClass: {
					        popup: 'animate__animated animate__fadeInDown'
					      },
					      hideClass: {
					        popup: 'animate__animated animate__fadeOutUp'
					      },
					      showCloseButton: true,
					      focusConfirm: false,
					      timerProgressBar: true,
					    });
					    
					    tabla.ajax.reload();
			        }
			    });
			  	break;
			default:
		  		break;
		}
	});
}

init();