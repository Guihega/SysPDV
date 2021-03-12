<?php 
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"]))
{
  header("Location: ../vistas/login.php");//Validamos el acceso solo a los usuarios logueados al sistema.
}
else
{
	//Obtenemos el nombre de la vista
	$archivo_actual = basename(__FILE__, ".php");

	require_once "../modelos/Vista.php";
	$vista=new Vista();
	//recuperamos el permiso asignado para la vista
	$rsptavista = $vista->permisoVista($archivo_actual);
	$regvista=$rsptavista->fetch_object();
	$permisovista=$regvista->alias;
	//Verificamos que el permiso de la vista este activado
	if ($_SESSION[$permisovista]==1)
	{
		require_once "../modelos/Consultas.php";

		$consulta=new Consultas();

		switch ($_GET["op"]){
			case 'comprasfecha':
				$fecha_inicio=$_REQUEST["fecha_inicio"];
				$fecha_fin=$_REQUEST["fecha_fin"];

				$rspta=$consulta->comprasfecha($fecha_inicio,$fecha_fin);
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>$reg->fecha,
		 				"1"=>$reg->usuario,
		 				"2"=>$reg->proveedor,
		 				"3"=>$reg->idcomprobante,
		 				"4"=>$reg->serie_comprobante.' '.$reg->num_comprobante,
		 				"5"=>$reg->total_compra,
		 				"6"=>$reg->impuesto,
		 				"7"=>($reg->condicion)?'<span class="label bg-green">Aceptado</span>':
		 				'<span class="label bg-red">Anulado</span>'
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;

			case 'ventasfechacliente':
				$fecha_inicio=$_REQUEST["fecha_inicio"];
				$fecha_fin=$_REQUEST["fecha_fin"];
				$idcliente=$_REQUEST["idcliente"];

				$rspta=$consulta->ventasfechacliente($fecha_inicio,$fecha_fin,$idcliente);
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>$reg->fecha,
		 				"1"=>$reg->usuario,
		 				"2"=>$reg->cliente,
		 				"3"=>$reg->idcomprobante,
		 				"4"=>$reg->serie_comprobante.' '.$reg->num_comprobante,
		 				"5"=>$reg->total_venta,
		 				"6"=>$reg->impuesto,
		 				"7"=>($reg->condicion)?'<span class="label bg-green">Aceptado</span>':
		 				'<span class="label bg-red">Anulado</span>'
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;

			case 'consultastock':
				$rspta=$consulta->stockproductos();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>$reg->idarticulo,
		 				"1"=>$reg->categoria,
		 				"2"=>$reg->codigo,
		 				"3"=>$reg->nombre,
		 				"4"=>$reg->stock,
		 				"5"=>$reg->descripcion,
		 				"6"=>$reg->imagen,
		 				"7"=>($reg->condicion)?'<span class="label bg-green">Aceptado</span>':
		 				'<span class="label bg-red">Anulado</span>'
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;

			case 'consultacaducidad':
				$rspta=$consulta->caducidadproductos();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>$reg->idarticulo,
		 				"1"=>$reg->categoria,
		 				"2"=>$reg->codigo,
		 				"3"=>$reg->nombre,
		 				"4"=>$reg->stock,
		 				"5"=>$reg->fechacaducidad,
		 				"6"=>$reg->descripcion,
		 				"7"=>$reg->imagen,
		 				"8"=>($reg->condicion)?'<span class="label bg-green">Aceptado</span>':
		 				'<span class="label bg-red">Anulado</span>'
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;
		}
	//Fin de las validaciones de acceso
	}
	else
	{
		require 'noacceso.php';
	}
}
ob_end_flush();
?> 