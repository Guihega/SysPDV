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
	cargarMonedas();
	cargarImpuestos();
	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})

	$("#formularioMoneda").on("submit",function(e)
	{
		guardarMoneda(e);	
	});

	$("#formularioImpuesto").on("submit",function(e)
	{
		guardarImpuesto(e);	
	});

	$('#mConfiguracion').addClass("treeview active");
    $('#lGenerales').addClass("active");

    $("#logomuestra").hide();


	$('.inputfile').change(function(){
		var label = $('.inputfile').siblings('label');
		var fileName = '';
		if($(this).files && $(this).files.length > 1)
			fileName = ($(this).attr('data-multiple-caption') || '' ).replace('{count}', this.files.length);
		else
			fileName = $(this).val().split('\\').pop();

		if(fileName)
			$(label).find('span').html(fileName);
		else
			$(label).html($(label).val());
    });
}


function cargarMonedas(){
	//Cargamos los items al select cliente
	$.post("../ajax/moneda.php?op=selectMoneda", function(r){
        $("#idmonedaselect").html(r);
        $('#idmonedaselect').selectpicker('refresh');
	});
}

function cargarImpuestos(){
	//Cargamos los items al select cliente
	$.post("../ajax/impuesto.php?op=selectImpuesto", function(r){
        $("#idimpuestoselect").html(r);
        $('#idimpuestoselect').selectpicker('refresh');
	});
}

//Función limpiar
function limpiar(modal)
{
	if (modal==1) {
		$("#nombreconfiguracion").val("");
		$("#alias").val("");
		$("#abreviatura").val("");
		$("#direccion").val("");
		$("#cp").val("");
		$("#correo").val("");
		$("#telefono").val("");
		$("#rfc").val("");
	    $("#idmonedaselect").val("");
		$("#idmonedaselect").selectpicker('refresh');
		$("#idimpuestoselect").val("");
		$("#idimpuestoselect").selectpicker('refresh');
		$("#idconfiguracion").val("");
	}
	else if (modal==2) {
		$("#nombremoneda").val("");
		$("#simbolo").val("");
		$("#presicion").val("");
		$("#separadormiles").val("");
		$("#separadordecimal").val("");
		$("#codigo").val("");
		$("#idmoneda").val("");
	}
	else if (modal==3) {
		$("#nombreimpuesto").val("");
		$("#valor").val("");
		$("#idimpuesto").val("");
	}
}

//Función mostrar formulario
function mostrarform(flag,accion)
{
	
	if (accion==1) {
		if (flag)
		{
			$('#modalNuevaconfiguracion').modal('show');
			$(".modal-title").html('Nueva Configuración');
			$("#btnGuardar").prop("disabled",false);
			limpiar(1);
		}
		else
		{
			$('#modalNuevaconfiguracion').modal('hide');
		}
	}
	else if(accion==2){
		if (flag)
		{
			$('#modalNuevaMoneda').modal('show');
			$(".modal-title").html('Nueva Moneda');
			$("#btnGuardarMoneda").prop("disabled",false);
			limpiar(2);
		}
		else
		{
			$('#modalNuevaMoneda').modal('hide');
		}
	}
	else{
		if (flag)
		{
			$('#modalNuevoImpuesto').modal('show');
			$(".modal-title").html('Nuevo Impuesto');
			$("#btnGuardarImpuesto").prop("disabled",false);
			limpiar(3);
		}
		else
		{
			$('#modalNuevoImpuesto').modal('hide');
		}
	}
}

//Función cancelarform
function cancelarform(accion)
{
	//limpiar();
	if (accion==1) {
		mostrarform(false,1);
	}
	else if(accion==2){
		mostrarform(false,2);
	}
	else {
		mostrarform(false,3);
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
			url: '../ajax/configuracion.php?op=listar',
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
	for (var value of formData.values()) {
	   console.log(value);
	}
	for (var pair of formData.entries()) {
	    console.log(pair[0]+ ', ' + pair[1]); 
	}
	$.ajax({
		url: "../ajax/configuracion.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos)
	    {
			console.log(datos);
			if (datos == 0) {
	    		icono = 'success';
	    		mensaje = 'Configuracion guardada';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'La configuracion no se pudo registrar';
	    	}
	    	else if (datos == 2){
	    		icono = 'success';
	    		mensaje = 'Configuracion actualizada';
	    	}
	    	else{
	    		icono = 'error';
	    		mensaje = 'La configuracion no se pudo actualizar';
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
	limpiar(1);
}

function guardarMoneda(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardarMoneda").prop("disabled",true);
	$.ajax({
		url: "../ajax/moneda.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos)
	    {
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
		    cargarMonedas();
			mostrarform(false,2);
			tabla.ajax.reload();
	    }
	});
	limpiar(2);
}

function guardarImpuesto(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardarImpuesto").prop("disabled",true);
	var formData = new FormData($("#formularioImpuesto")[0]);
	$.ajax({
		url: "../ajax/impuesto.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos)
	    {
			console.log(datos);
			if (datos == 0) {
	    		icono = 'success';
	    		mensaje = 'Impuesto guardado';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'El impuesto no se pudo registrar';
	    	}
	    	else if (datos == 2){
	    		icono = 'success';
	    		mensaje = 'Impuesto actualizado';
	    	}
	    	else{
	    		icono = 'error';
	    		mensaje = 'El impuesto no se pudo actualizar';
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
		    cargarImpuestos();
			mostrarform(false,3);
			tabla.ajax.reload();
	    }
	});
	limpiar(3);
}
function mostrar(idconfiguracion)
{
	$.post("../ajax/configuracion.php?op=mostrar",{idconfiguracion : idconfiguracion}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true,1);

		$(".modal-title").html('Editar configuracion ' + idconfiguracion);
		$("#nombreconfiguracion").val(data.empresa);
		$("#alias").val(data.alias);
		$("#abreviatura").val(data.abreviatura);
		$("#direccion").val(data.direccion);
		$("#cp").val(data.cp);
		$("#correo").val(data.correo);
		$("#telefono").val(data.telefono);
		$("#rfc").val(data.rfc);
	    $("#idmonedaselect").val(data.moneda);
		$("#idmonedaselect").selectpicker('refresh');
		$("#idimpuestoselect").val(data.impuesto);
		$("#idimpuestoselect").selectpicker('refresh');
		$("#logomuestra").show();
		$("#logomuestra").attr("src","../files/logotipos/"+data.logo);
		$("#logoactual").val(data.logo);
		$("#idconfiguracion").val(data.idconfiguracion);

 	});
}

//Función para desactivar registros
function desactivar(idconfiguracion)
{
	swal({
	    title: '¿Está seguro de desactivar la configuracion?',
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
			//console.log(idconfiguracionCambio);
			  	$.ajax({
			        type: "POST",
			        url: "../ajax/configuracion.php?op=desactivar",
			        data: {idconfiguracion : idconfiguracion},
			        cache: false,
			        success: function(response) {
			        	console.log(response);
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'configuracion desactivada';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'La configuracion no se pudo desactivar';
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
function activar(idconfiguracion)
{
	swal({
	    title: '¿Está seguro de activar la configuracion?',
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
			        url: "../ajax/configuracion.php?op=activar",
			        data: {idconfiguracion : idconfiguracion},
			        cache: false,
			        success: function(response) {
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'configuracion activa';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'La configuracion no se pudo activar';
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