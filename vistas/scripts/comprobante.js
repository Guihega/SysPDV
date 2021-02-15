var tabla;
var icono;
var mensaje;
//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});

    $('#mConfiguracion').addClass("treeview active");
    $('#lComprobante').addClass("active");

    cargaImpuestos();
}

function cargaImpuestos(){
	//Cargamos los items al select cliente
	$.post("../ajax/impuesto.php?op=selectImpuesto", function(r){
        $("#impuesto").html(r);
        $('#impuesto').selectpicker('refresh');
	});
}

//Función limpiar
function limpiar()
{
	$(".modal-title").html('Nuevo comprobante');
	$("#idcomprobante").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$('#modalNuevocomprobante').modal('show')
		$("#btnGuardar").prop("disabled",false);
	}
	else
	{
		$("#listadoregistros").show();
		$('#modalNuevocomprobante').modal('hide');
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
			url: '../ajax/comprobante.php?op=listar',
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
		url: "../ajax/comprobante.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
			//bootbox.alert(datos);
			if (datos == 0) {
	    		icono = 'success';
	    		mensaje = 'Comprobante registrado';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'El comprobante no se pudo registrar';
	    	}
	    	else if (datos == 2){
	    		icono = 'success';
	    		mensaje = 'Comprobante actualizado';
	    	}
	    	else{
	    		icono = 'error';
	    		mensaje = 'El comprobante no se pudo actualizar';
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

			mostrarform(false);
			tabla.ajax.reload();
	    }

	});
	limpiar();
}

function mostrar(idcomprobante)
{
	$.post("../ajax/comprobante.php?op=mostrar",{idcomprobante : idcomprobante}, function(data, status)
	{
		data = JSON.parse(data);
		//console.log(data);
		mostrarform(true);

		$(".modal-title").html('Editar comprobante ' + idcomprobante);

		$("#nombreComprobante").val(data.nombre);
		$("#descripcionComprobante").val(data.descripcion);
 		$("#idcomprobante").val(data.idcomprobante);
 		$("#impuesto").val(data.idimpuesto);
 		$("#impuesto").selectpicker('refresh');
 	})
}

//Función para desactivar registros
function desactivar(idcomprobante,idusuariocambio)
{
	swal({
	    title: '¿Está seguro de desactivar el comprobante?',
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
			        url: "../ajax/comprobante.php?op=desactivar",
			        data: {idcomprobante : idcomprobante, idusuariocambio : idusuariocambio},
			        cache: false,
			        success: function(response) {
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'Comprobante desactivado';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'El comprobante no se puede desactivar';
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
function activar(idcomprobante,idusuariocambio)
{
	swal({
	    title: '¿Está seguro de activar el comprobante?',
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
			        url: "../ajax/comprobante.php?op=activar",
			        data: {idcomprobante : idcomprobante, idusuariocambio : idusuariocambio},
			        cache: false,
			        success: function(response) {
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'Comprobante activado';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'El comprobante no se puede activar';
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