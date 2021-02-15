var tabla;

//Función que se ejecuta al inicio
function init(){
	listar();
	//Cargamos los items al select cliente
	$.post("../ajax/venta.php?op=selectCliente", function(r){
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');
	});
	$('#mConsulta').addClass("treeview active");
    $('#lConsultasV').addClass("active");
}


//Función Listar
function listar()
{
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idcliente = $("#idcliente").val();

	tabla=$('#tbllistado').dataTable(
	{
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
		"ajax":
		{
			url: '../ajax/consultas.php?op=ventasfechacliente',
			data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin, idcliente: idcliente},
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


init();