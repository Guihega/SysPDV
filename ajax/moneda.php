<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"]))
{
	header("Location: ../vistas/login.php");
}
else
{
	//Obtenemos el nombre de la moneda
	$archivo_actual = basename(__FILE__, ".php");

	require_once "../modelos/Vista.php";
	$vista=new Vista();
	//recuperamos el permiso asignado para la moneda
	$rsptavista = $vista->permisoVista($archivo_actual);
	$regvista = $rsptavista->fetch_object();
	$permisovista=$regvista->alias;

	//Verificamos que el permiso de la moneda este activado
	if ($_SESSION[$permisovista]==1)
	{
		require_once "../modelos/Moneda.php";

		$moneda=new Moneda();

		$idmoneda=isset($_POST["idmoneda"])? limpiarCadena($_POST["idmoneda"]):"";
		$nombre=isset($_POST["nombremoneda"])? limpiarCadena($_POST["nombremoneda"]):"";
		$alias=isset($_POST["alias"])? limpiarCadena($_POST["alias"]):"";
		$decimales=isset($_POST["decimales"])? limpiarCadena($_POST["decimales"]):"";
		$simbolo=isset($_POST["simbolo"])? limpiarCadena($_POST["simbolo"]):"";
		$presicion=isset($_POST["presicion"])? limpiarCadena($_POST["presicion"]):"";
		$separadormiles=isset($_POST["separadormiles"])? limpiarCadena($_POST["separadormiles"]):"";
		$separadordecimal=isset($_POST["separadordecimal"])? limpiarCadena($_POST["separadordecimal"]):"";
		$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
		$idusuariocambio = $_SESSION['idusuario'];

		switch ($_GET["op"]){
			case 'guardaryeditar':
				if (empty($idmoneda)){
					$rspta=$moneda->insertar($nombre,$alias,$decimales,$simbolo,$presicion,$separadormiles,$separadordecimal,$codigo,$idusuariocambio);
					echo $rspta ? 0 : 1;
				}
				else {
					$rspta=$moneda->editar($idmoneda,$nombre,$alias,$decimales,$simbolo,$presicion,$separadormiles,$separadordecimal,$codigo,$idusuariocambio);
					echo $rspta ? 2 : 3;
				}
			break;

			case 'desactivar':
				$rspta=$moneda->desactivar($idmoneda, $idusuariocambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'activar':
				$rspta=$moneda->activar($idmoneda, $idusuariocambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'mostrar':
				$rspta=$moneda->mostrar($idmoneda);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listar':
				$rspta=$moneda->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idmoneda.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
	 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idmoneda.')"  data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>':
	 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idmoneda.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idmoneda.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->alias,
		 				"3"=>$reg->decimales,
		 				"4"=>$reg->simbolo,
		 				"5"=>$reg->presicion,
		 				"6"=>$reg->separadormiles,
		 				"7"=>$reg->separadordecimal,
		 				"8"=>$reg->codigo,
		 				"9"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
		 				'<span class="label bg-red">Desactivado</span>'
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;

			case 'selectMoneda':
				require_once "../modelos/Moneda.php";

				$moneda=new Moneda();
				$rspta=$moneda->listarActivos();

		 		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idmoneda . '>' . $reg->nombre . '</option>';
				}
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