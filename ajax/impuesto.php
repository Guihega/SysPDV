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
	//Obtenemos el nombre de la impuesto
	$archivo_actual = basename(__FILE__, ".php");

	require_once "../modelos/Vista.php";
	$vista=new Vista();
	//recuperamos el permiso asignado para la impuesto
	$rsptavista = $vista->permisoVista($archivo_actual);
	$regvista = $rsptavista->fetch_object();
	$permisovista=$regvista->alias;

	//Verificamos que el permiso de la impuesto este activado
	if ($_SESSION[$permisovista]==1)
	{
		require_once "../modelos/Impuesto.php";

		$impuesto=new Impuesto();

		$idimpuesto=isset($_POST["idimpuesto"])? limpiarCadena($_POST["idimpuesto"]):"";
		$nombre=isset($_POST["nombreimpuesto"])? limpiarCadena($_POST["nombreimpuesto"]):"";
		$valor=isset($_POST["valor"])? limpiarCadena($_POST["valor"]):"";
		$idusuariocambio = $_SESSION['idusuario'];

		switch ($_GET["op"]){
			case 'guardaryeditar':
				if (empty($idimpuesto)){
					$rspta=$impuesto->insertar($nombre,$valor,$idusuariocambio);
					echo $rspta ? 0 : 1;
				}
				else {
					$rspta=$impuesto->editar($idimpuesto,$nombre,$valor,$idusuariocambio);
					echo $rspta ? 2 : 3;
				}
			break;

			case 'desactivar':
				$rspta=$impuesto->desactivar($idimpuesto, $idusuariocambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'activar':
				$rspta=$impuesto->activar($idimpuesto, $idusuariocambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'mostrar':
				$rspta=$impuesto->mostrar($idimpuesto);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listar':
				$rspta=$impuesto->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idimpuesto.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
	 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idimpuesto.')"  data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>':
	 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idimpuesto.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idimpuesto.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->valor,
		 				"3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

			case 'selectImpuesto':
				require_once "../modelos/Impuesto.php";

				$impuesto=new Impuesto();

				$rspta=$impuesto->listarActivos();
				//echo '<option value=0>Seleccione</option>';
				//echo '<option value="" selected disabled hidden>Seleccione</option>';
		 		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idimpuesto . '>' . $reg->nombre . '</option>';
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