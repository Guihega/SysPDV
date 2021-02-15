<?php 
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"]))
{
  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
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
		require_once "../modelos/Categoria.php";

		$categoria=new Categoria();

		$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
		$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
		$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
		$idUsuarioCambio = $_SESSION['idusuario'];


		switch ($_GET["op"]){
			case 'guardaryeditar':
				if (empty($idcategoria)){
					$rspta=$categoria->insertar($nombre,$descripcion, $idUsuarioCambio);
					echo $rspta ? 0 : 1;
				}
				else {
					$rspta=$categoria->editar($idcategoria,$nombre,$descripcion, $idUsuarioCambio);
					echo $rspta ? 2 : 3;
				}
			break;

			case 'desactivar':
				$rspta=$categoria->desactivar($idcategoria, $idUsuarioCambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'activar':
				$rspta=$categoria->activar($idcategoria, $idUsuarioCambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'mostrar':
				$rspta=$categoria->mostrar($idcategoria);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listar':
				$rspta=$categoria->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-edit"></i></button>'.
		 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idcategoria.','.$idUsuarioCambio.')"><i class="fa fa-close"></i></button>':
		 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-eye"></i></button>'.
		 					' <button class="btn  btn-xs btn-primary" onclick="activar('.$reg->idcategoria.','.$idUsuarioCambio.')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->descripcion,
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