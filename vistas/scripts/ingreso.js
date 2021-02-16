var tabla;
var icono;
var mensaje;

var idArticulo;
var articulo;
var precio_venta;
var codigoBarras;

var articuloId;
var codigoArticulo;

var cantidad;
var sumCantidad;
var fila;

var encontrado = false;
var numVenta;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});

	$("#formularioProveedor").on("submit",function(e)
	{
		guardarProveedor(e);	
	});

	$("#formularioComprobante").on("submit",function(e)
	{
		guardarComprobante(e);	
	});

	//Cargamos los items al select proveedor
	cargarProveedores();
	
	cargaComprobantes();

	//cargaImpuestos();

	$('#mCompras').addClass("treeview active");
    $('#lIngresos').addClass("active");

    $("#codigoBarras").on("change",function()
	{
		if ($(this).val()) {
			buscarArticuloCodigo($(this).val());
		}
	});

	fechaVenta();

}

function cargarProveedores(){
	//Cargamos los items al select cliente
	$.post("../ajax/ingreso.php?op=selectProveedor", function(r){
        $("#idproveedor").html(r);
        $('#idproveedor').selectpicker('refresh');
	});
}

function cargaComprobantes(){
	//Cargamos los items al select cliente
	$.post("../ajax/comprobante.php?op=select", function(r){
        $("#idcomprobante").html(r);
        $('#idcomprobante').selectpicker('refresh');
        $("#idcomprobante").change();
	});
}

function getNumIngreso(){
	//Cargamos los items al select cliente
	$.post("../ajax/ingreso.php?op=getNumIngreso", function(r){
		data = JSON.parse(r);
		if (data == null){
			numVenta = 1;
		}
		else{
			numVenta = parseInt(data.num_comprobante) + 1;
		}
        $("#num_comprobante").val(numVenta);
	});
}

//Función limpiar
function limpiar()
{
	$("#idproveedor").val("");
	$("#proveedor").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#impuesto").val("0");

	$("#total_compra").val("");
	$(".filas").remove();
	$("#total").html("0");
	
	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);

    //Marcamos el primer tipo_documento
    $("#idcomprobante").val("Boleta");
	$("#idcomprobante").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag, accion)
{
	//limpiar();
	if (flag)
	{
		$('#modalNuevoIngreso').modal('show');

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles=0;
		if (accion == 0) {
			getNumIngreso();
			habilitaControles();
		}
		else{
			deshabilitaControles();
		}
	}
	else
	{
		$("#listadoregistros").show();
		$('#modalNuevoIngreso').modal('hide');
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
			url: '../ajax/ingreso.php?op=listar',
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


//Función ListarArticulos
function listarArticulos()
{
	tabla=$('#tblArticulos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
		"ajax":{
			url: '../ajax/ingreso.php?op=listarArticulos',
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
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: "../ajax/ingreso.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos)
	    {
	    	console.log(datos);
          	if (datos == 0) {
	    		icono = 'success';
	    		mensaje = 'Ingreso registrado';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'No se pudieron registrar todos los datos del ingreso';
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
			listar();
	    }

	});
	limpiar();
}

function mostrar(idingreso)
{
	$.post("../ajax/ingreso.php?op=mostrar",{idingreso : idingreso}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$(".modal-title").html('Ingreso ' + idingreso);

		$("#idproveedor").val(data.idproveedor);
		$("#idproveedor").selectpicker('refresh');
		$("#idcomprobante").val(data.idcomprobante);
		$("#idcomprobante").selectpicker('refresh');
		$("#serie_comprobante").val(data.serie_comprobante);
		$("#num_comprobante").val(data.num_comprobante);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#idingreso").val(data.idingreso);

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 	$.post("../ajax/ingreso.php?op=listarDetalle&id="+idingreso,function(r){
	    $("#detalles").html(r);
	});
}

function mostrarImpuesto(idcomprobante)
{
 	$.post("../ajax/comprobante.php?op=mostrarImpuesto&id="+idcomprobante,function(data){
 		console.log(data);
 		data = JSON.parse(data);
 		var impuesto = 0;
 		if (data != null) {
 			impuesto = data.impuesto;
 		}
	    $("#impuesto").val(impuesto);
	});
}

//Función para anular registros
function anular(idingreso)
{
	swal({
	    title: '¿Está seguro de cancelar el ingreso?',
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
			        url: "../ajax/ingreso.php?op=anular",
			        data: {idingreso : idingreso},
			        cache: false,
			        success: function(response) {
			            if (response == 0) {
				    		icono = 'success';
				    		mensaje = 'Ingreso anulado';
				    	}
				    	else if (response == 1) {
				    		icono = 'error';
				    		mensaje = 'El ingreso no se puede anular';
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

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=18;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#idcomprobante").change(marcarImpuesto);

function marcarImpuesto()
{
	var idcomprobante=$("#idcomprobante option:selected").val();
	mostrarImpuesto(idcomprobante);
}

function agregarDetalle(idarticulo,articulo, codigoBarras,precio_compra)
{
	var cantidad=1;
	var precio_compra=1;
	var precio_venta=1;
	var descuento=0;

	if (idarticulo!="")
	{
		var subtotal=cantidad*precio_compra;
		var fila='<tr class="filas" id="fila'+cont+'">'+
		'<td><button type="button" class="btn btn-xs btn-danger" onclick="eliminarDetalle('+cont+')"><i class="fa fa-trash"></i></button></td>'+
		'<td><input class="idArticulo"type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
		'<td><input type="hidden" name="codigoBarras[]" value="'+codigoBarras+'">'+codigoBarras+'</td>'+
		'<td><input class="cantidad" type="hidden" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"><span class="spanCantidad">'+cantidad+'</span></td>'+
		'<td><input class="precioCompra" type="number" name="precio_compra[]" id="precio_compra[]" value="'+precio_compra+'"></td>'+
		'<td><input class="precioVenta"  type="number" name="precio_venta[]" value="'+precio_venta+'"></td>'+
		'<td><input class="descuento" type="hidden" name="descuento[]" value="'+descuento+'">'+descuento+'</td>'+
		'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
		//'<td><button type="button" onclick="modificarSubototales()" class="btn btn-xs btn-info"><i class="fa fa-refresh"></i></button></td>'+
		'</tr>';
		cont++;
		detalles=detalles+1;
		$('#detalles').append(fila);
		//modificarSubototales();
	}
	else
	{
		//alert("Error al ingresar el detalle, revisar los datos del artículo");

		Swal.fire({
	      position: 'top-end',
	      icon: 'error',
	      title: 'Error al ingresar el detalle, revisar los datos del artículo',
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
	}
}

function modificarSubototales()
{
	var cant = document.getElementsByName("cantidad[]");
	var prec = document.getElementsByName("precio_compra[]");
	var sub = document.getElementsByName("subtotal");

	for (var i = 0; i <cant.length; i++) {
		var inpC=cant[i];
		var inpP=prec[i];
		var inpS=sub[i];

		inpS.value=inpC.value * inpP.value;
		document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
	}
	calcularTotales();

}

function calcularTotales()
{
	var sub = document.getElementsByName("subtotal");
	var total = 0.0;

	for (var i = 0; i <sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}

	//var simboloMoneda = $("#simboloMoneda").html();
	$("#total").html(parseFloat(total).toFixed(2));
	$("#total_compra").val(total);
	evaluar();
}

function evaluar(){
	if (detalles>0)
	{
	  $("#btnGuardar").show();
	}
	else
	{
	  $("#btnGuardar").hide(); 
	  cont=0;
	}
}

function eliminarDetalle(indice){
	$("#fila" + indice).remove();
	calcularTotales();
	detalles=detalles-1;
	evaluar();
}

function guardarProveedor(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardarProveedor").prop("disabled",true);
	var formData = new FormData($("#formularioProveedor")[0]);

	$.ajax({
		url: "../ajax/persona.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos)
	    {                    
	      //bootbox.alert(datos);
	      	if (datos == 0) {
	    		icono = 'success';
	    		mensaje = 'Proveedor guardado';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'El proveedor no se pudo registrar';
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

			//mostrarform(false);
			$('#modalNuevoProveedor').modal('hide');
			cargarProveedores();
			//tabla.ajax.reload();
	    }

	});
	limpiarProveedor();
}

function guardarComprobante(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardarComprobate").prop("disabled",true);
	var formData = new FormData($("#formularioComprobante")[0]);

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
	    		mensaje = 'Comprobante guardado';
	    	}
	    	else if (datos == 1) {
	    		icono = 'error';
	    		mensaje = 'El comprobante no se pudo registrar';
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

			//mostrarform(false);
			$('#modalNuevocomprobante').modal('hide');
			cargaComprobantes();
			//tabla.ajax.reload();
	    }

	});
	limpiarComprobante();
}

function limpiarProveedor()
{
	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#idpersona").val("");
}

function limpiarComprobante()
{
	$("#nombreComprobante").val("");
	$("#descripcionComprobante").val("");
}

function buscarArticuloBarCode(codigoBarras){
	console.log(codigoBarras);
    $.post("../ajax/ingreso.php?op=buscarArticuloBarCode",{codigoBarras : codigoBarras}, function(data, status)
	{
		data = JSON.parse(data);
		console.log(data);
		if (data == null || data == '' || data == 'null') {
			Swal.fire({
	            position: 'top-end',
	            icon: 'error',
	            title: '¡Artículo no encontrado!',
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
		}
		else
		{
			idArticulo = data.idarticulo;
			articulo = data.nombre;
			precio_compra = 100;
			codigoBarras = data.codigo;

			var rowCount = $('#detalles tbody tr').length;

			if (rowCount <= 0) {
				agregarDetalle(idArticulo,articulo,codigoBarras,precio_compra);
			}
			else{
				encontrado = false;
				$('#detalles > tbody > tr').each(function(index, tr) {
					articuloId = $(this).find(".idArticulo").val();
					console.log(idArticulo);
					console.log(articuloId);
					if (idArticulo == articuloId) {
						cantidad = parseFloat($(this).find("td:eq(3) input").val());
						sumCantidad = cantidad + 1;
						fila = $(this).attr('id');
						encontrado = true;
					}
				});

				if (encontrado == true) {
					//console.log('Encontrado');
					$('#detalles > tbody >' + '#' + fila).find("td:eq(3) input").val(sumCantidad);
					$('#detalles > tbody >' + '#' + fila).find("td:eq(3) span").html(sumCantidad);
				}
				else{
					//console.log('No encontrado');
					agregarDetalle(idArticulo,articulo,codigoBarras,precio_compra);
				}
			}

			modificarSubototales();
		}
 	})
}

function buscarArticuloId(idproducto){
	console.log(idproducto);
    $.post("../ajax/ingreso.php?op=buscarArticuloId",{idproducto : idproducto}, function(data, status)
	{
		data = JSON.parse(data);
		console.log(data);
		if (data == null || data == '' || data == 'null') {
			Swal.fire({
	            position: 'top-end',
	            icon: 'error',
	            title: '¡Artículo no encontrado!',
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
		}
		else
		{
			idArticulo = data.idarticulo;
			articulo = data.nombre;
			precio_compra = 100;
			codigoBarras = data.codigo;

			var rowCount = $('#detalles tbody tr').length;

			if (rowCount <= 0) {
				agregarDetalle(idArticulo,articulo,codigoBarras,precio_compra);
			}
			else{
				encontrado = false;
				$('#detalles > tbody > tr').each(function(index, tr) {
					articuloId = $(this).find(".idArticulo").val();
					console.log(idArticulo);
					console.log(articuloId);
					if (idArticulo == articuloId) {
						cantidad = parseFloat($(this).find("td:eq(3) input").val());
						sumCantidad = cantidad + 1;
						fila = $(this).attr('id');
						encontrado = true;
					}
				});

				if (encontrado == true) {
					//console.log('Encontrado');
					$('#detalles > tbody >' + '#' + fila).find("td:eq(3) input").val(sumCantidad);
					$('#detalles > tbody >' + '#' + fila).find("td:eq(3) span").html(sumCantidad);
				}
				else{
					//console.log('No encontrado');
					agregarDetalle(idArticulo,articulo,codigoBarras,precio_compra);
				}
			}

			modificarSubototales();
		}
 	})
}

function fechaVenta(){
    var today = new Date();
    var fecha = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    //console.log(fecha);
    $('#fecha_hora').val(fecha);
}


function deshabilitaControles(){
	$("#btnAgregarProveedor").prop('disabled', true);
	$(".dropdown-toggle").prop('disabled', true);
	$("#codigoBarras").attr('readonly', true);
	$("#serie_comprobante").attr('readonly', true);
	$("#impuesto").attr('readonly', true);
	$("#impuesto").attr('readonly', true);
}


function habilitaControles(){
	$("#btnAgregarProveedor").prop('disabled', false);
	$(".dropdown-toggle").prop('disabled', false);
	$("#codigoBarras").attr('readonly', false);
	$("#serie_comprobante").attr('readonly', false);
	$("#impuesto").attr('readonly', false);
	$("#impuesto").attr('readonly', false);
}
init();