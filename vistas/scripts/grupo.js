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

	$("#formulario").on("submit",function(e)
	{
		values = [];
		selected = "";
		e.preventDefault();
		$('.checkPermiso').each(function() {
			if ($(this).is(':checked')) {
			  values.push($(this).val()+','+1);
			}
			else{
				values.push($(this).val()+','+0);
			}
		});
		selected = values.join('|');
		permisos = JSON.stringify(selected);
		permisos = permisos.replace(/"/g,'');
		//console.log(permisos);
		guardaryeditar(e);	
	})
	//Mostramos los permisos
	$.post("../ajax/grupo.php?op=permisos&id=",function(r){
	    $("#permisos").empty().html(r);
	});

	$('#mAcceso').addClass("treeview active");
    $('#lGrupos').addClass("active");

}

$(document).on('change', '.allCheckBox', function() {
  var checked = $(this).prop('checked');
  $('.checkPermiso').prop('checked', checked);
});

//Función limpiar
function limpiar()
{
	$(".modal-title").html('Nuevo Grupo');

	$("#nombregrupo").val("");
	$("#idGrupo").val("");
}

//Función mostrar formulario
function mostrarform(flag,accion)
{
	limpiar();
	if (accion==1) {
		if (flag)
		{
			$('#modalNuevoGrupo').modal('show');
			$("#btnGuardar").prop("disabled",false);
		}
		else
		{
			$('#modalNuevoGrupo').modal('hide');
		}
	}
	else{
		if (flag)
		{
			$('#modalCambiarPassword').modal('show');
		}
		else
		{
			$('#modalCambiarPassword').modal('hide');
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
			url: '../ajax/grupo.php?op=listar',
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
	formData.append('permisos',permisos);
	$.ajax({
		url: "../ajax/grupo.php?op=guardaryeditar",
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
	    		mensaje = 'Grupo guardado';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'El Grupo no se pudo registrar';
	    	}
	    	else if (datos == 2){
	    		icono = 'success';
	    		mensaje = 'Grupo actualizado';
	    	}
	    	else{
	    		icono = 'error';
	    		mensaje = 'El Grupo no se pudo actualizar';
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

function mostrar(idgrupo)
{
	$.post("../ajax/grupo.php?op=mostrar",{idgrupo : idgrupo}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true,1);

		$(".modal-title").html('Editar Grupo ' + idgrupo);
		$("#nombregrupo").val(data.nombre);
		$("#idgrupo").val(data.idgrupo);

 	});
 	$.post("../ajax/grupo.php?op=permisos&id="+idgrupo,function(r){
 		$("#permisos").empty().append(r);
	});
}

//Función para desactivar registros
function desactivar(idgrupo)
{
	swal({
	    title: '¿Está seguro de desactivar el grupo?',
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
			//console.log(idGrupoCambio);
			  	$.ajax({
			        type: "POST",
			        url: "../ajax/grupo.php?op=desactivar",
			        data: {idgrupo : idgrupo},
			        cache: false,
			        success: function(response) {
			        	console.log(response);
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'Grupo desactivado';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'El grupo no se pudo desactivar';
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
function activar(idgrupo)
{
	swal({
	    title: '¿Está seguro de activar el grupo?',
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
			        url: "../ajax/grupo.php?op=activar",
			        data: {idgrupo : idgrupo},
			        cache: false,
			        success: function(response) {
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'Grupo activo';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'El grupo no se pudo activar';
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