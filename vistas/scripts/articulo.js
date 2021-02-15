var tabla;
var rows = 0;
var icono;
var mensaje;
//Función que se ejecuta al inicio
function init(){
	//configuracion();
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})

	cargarCategorias();

	$("#imagenmuestra").hide();
	$('#mAlmacen').addClass("treeview active");
    $('#lArticulos').addClass("active");

    $('#caduca').change(function() {
        if(this.checked) {
        	$(this).val(1);
            $("#caducidad").prop("disabled",false);
        }
        else{
        	$(this).val(0);
        	$("#caducidad").prop("disabled",true);
        }
    });

    //countRows();
}

function cargarCategorias(){
	//Cargamos los items al select categoria
	$.post("../ajax/articulo.php?op=selectCategoria", function(r){
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');
	});
}

// function configuracion(){
//   // $.post("../ajax/configuracion.php?op=listarActiva", function(data, status)
//   // {
//   //   data = JSON.parse(data);
//   //   $(".empresa").val(data.nombre);
//   //   // $(".login-page").val(data.alias);
//   // });
//   $.post("../ajax/configuracion.php?op=listarActiva", function(data){
//     //data = JSON.parse(data);
//     //$(".empresa").val(data.nombre);
//     // $("#idcategoria").html(r);
//     // $('#idcategoria').selectpicker('refresh');
//   });
// }


//Función limpiar
function limpiar()
{
	$(".modal-title").html('Nuevo Articulo ');
	$("#codigo").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#stock").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#print").hide();
	$("#idarticulo").val("");
	$("#caducidad").val("");
	$("#caduca").val("");
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$('#modalNuevoArticulo').modal('show')
		$("#btnGuardar").prop("disabled",false);
		$("#caducidad").prop("disabled",true);
		$("#stock").val(1);
	}
	else
	{
		$("#listadoregistros").show();
		$('#modalNuevoArticulo').modal('hide')
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
		"ajax":{
			url: '../ajax/articulo.php?op=listar',
			type : "get",
			contentType: "application/json; charset=utf-8",
			error: function(e){
				console.log(e.responseText);
			},
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
        ],
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
	$.ajax({
		url: "../ajax/articulo.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos)
	    {
	    	//console.log(datos);	    	
	    	//bootbox.alert(datos);
	    	if (datos == 0) {
	    		icono = 'success';
	    		mensaje = 'Artículo registrado';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'El artículo no se pudo registrar';
	    	}
	    	else if (datos == 2){
	    		icono = 'success';
	    		mensaje = 'Artículo actualizado';
	    	}
	    	else{
	    		icono = 'error';
	    		mensaje = 'El artículo no se pudo actualizar';
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

function mostrar(idarticulo)
{
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$(".modal-title").html('Editar Articulo ' + idarticulo);

		$("#idcategoria").val(data.idcategoria);
		$('#idcategoria').selectpicker('refresh');
		$("#codigo").val(data.codigo);
		$("#nombre").val(data.nombre);
		$("#stock").val(data.stock);
		$("#descripcion").val(data.descripcion);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
		$("#imagenactual").val(data.imagen);
 		$("#idarticulo").val(data.idarticulo);
 		// $("#caduca").val(data.caduca);
 		if(data.caduca==0) {
            $("#caducidad").prop("disabled",true);
            //$("#caduca").val(false);
            $('#caduca').prop('checked', false);
        }
        else{
        	$("#caducidad").prop("disabled",false);
        	//$("#caduca").val(true);
        	$('#caduca').prop('checked', true);
        }
 		$("#caducidad").val(data.fechacaducidad);
 		generarbarcode();

 	})
}

//Función para desactivar registros
function desactivar(idarticulo,idUsuarioCambio)
{
	swal({
	    title: '¿Está seguro de desactivar el artículo?',
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
			        url: "../ajax/articulo.php?op=desactivar",
			        data: {idarticulo : idarticulo, idUsuarioCambio:idUsuarioCambio},
			        cache: false,
			        success: function(response) {
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'Artículo desactivado';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'El artículo no se puede desactivar';
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
function activar(idarticulo,idUsuarioCambio)
{
	swal({
	    title: '¿Está seguro de activar el artículo?',
	    icon: "warning",
	    buttons: true,
	    showCancelButton: true,
	    buttons:{
		  cancel: {
		    text: "Cancelar",
		    value: "Cancelar",
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
		        url: "../ajax/articulo.php?op=activar",
		        data: {idarticulo : idarticulo, idUsuarioCambio:idUsuarioCambio},
		        cache: false,
		        success: function(response) {
		            if (response == 0) {
			    		icono = 'success';
			    		mensaje = 'Artículo activado';
			    	}
			    	else if (response == 1) {
			    		icono = 'error';
			    		mensaje = 'El artículo no se puede activar';
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

//función para generar el código de barras
function generarbarcode()
{
	codigo=$("#codigo").val();
	JsBarcode("#barcode", codigo);
	$("#print").show();
}

//Función para imprimir el Código de barras
function imprimir()
{
	$("#print").printArea();
}

init();

function mensaje(icono,mensaje){
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
    })
}