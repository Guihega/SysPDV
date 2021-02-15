var tabla;
var icono;
var mensaje;
var values = [];
var selected;
var permisos;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();
	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})

	$('#mConfiguracion').addClass("treeview active");
    $('#lMonedas').addClass("active");

}

//Función limpiar
function limpiar()
{
	$(".modal-title").html('Nueva Moneda');

	$("#nombremoneda").val("");
	$("#simbolo").val("");
	$("#presicion").val("");
	$("#separadormiles").val("");
	$("#separadordecimal").val("");
	$("#codigo").val("");
	$("#idmoneda").val("");
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$('#modalNuevaMoneda').modal('show');
		$("#btnGuardar").prop("disabled",false);
	}
	else
	{
		$('#modalNuevaMoneda').modal('hide');
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
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
			url: '../ajax/moneda.php?op=listar',
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
		url: "../ajax/moneda.php?op=guardaryeditar",
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
	    		mensaje = 'Moneda guardada';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'La moneda no se pudo registrar';
	    	}
	    	else if (datos == 2){
	    		icono = 'success';
	    		mensaje = 'Moneda actualizada';
	    	}
	    	else{
	    		icono = 'error';
	    		mensaje = 'La moneda no se pudo actualizar';
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

function mostrar(idmoneda)
{
	$.post("../ajax/moneda.php?op=mostrar",{idmoneda : idmoneda}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true,1);

		$(".modal-title").html('Editar moneda ' + data.idmoneda);
		$("#nombremoneda").val(data.nombre);
		$("#alias").val(data.alias);
		$("#decimales").val(data.decimales);
		$("#simbolo").val(data.simbolo);
		$("#presicion").val(data.presicion);
		$("#separadormiles").val(data.separadormiles);
		$("#separadordecimal").val(data.separadordecimal);
		$("#codigo").val(data.codigo);
		$("#idmoneda").val(data.idmoneda);

		$("#nombremonedamoneda").val(data.nombremoneda);
		$("#alias").val(data.alias);
		$("#url").val(data.url);
		$("#idpermiso").val(data.idpermiso);
		$("#idpermiso").selectpicker('refresh');
		$("#idmoneda").val(data.idmoneda);

 	});
 // 	$.post("../ajax/moneda.php?op=permisos&id="+idmoneda,function(r){
 // 		$("#permisos").empty().append(r);
	// });
}

//Función para desactivar registros
function desactivar(idmoneda, idusuariocambio)
{
	swal({
	    title: '¿Está seguro de desactivar la moneda?',
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
			//console.log(idmonedaCambio);
			  	$.ajax({
			        type: "POST",
			        url: "../ajax/moneda.php?op=desactivar",
			        data: {idmoneda : idmoneda, idusuariocambio : idusuariocambio},
			        cache: false,
			        success: function(response) {
			        	console.log(response);
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'moneda desactivada';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'La moneda no se pudo desactivar';
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
function activar(idmoneda, idusuariocambio)
{
	swal({
	    title: '¿Está seguro de activar la moneda?',
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
			        url: "../ajax/moneda.php?op=activar",
			        data: {idmoneda : idmoneda, idusuariocambio : idusuariocambio},
			        cache: false,
			        success: function(response) {
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'moneda activa';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'La moneda no se pudo activar';
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