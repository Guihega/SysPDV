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
	//echo $archivo_actual;
	require_once "../modelos/Vista.php";
	$vista=new Vista();
	//recuperamos el permiso asignado para la vista
	$rsptavista = $vista->permisoVista($archivo_actual);
	$regvista=$rsptavista->fetch_object();
	$permisovista=$regvista->alias;
	//echo $permisovista;
	//Verificamos que el permiso de la vista este activado
	if ($_SESSION[$permisovista]==1)
	{
		require_once "../modelos/Persona.php";

		$persona=new Persona();

		$idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
		$tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
		$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
		$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
		$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
		$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
		$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
		$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
		$idUsuarioCambio = $_SESSION['idusuario'];

		switch ($_GET["op"]){
			case 'guardaryeditar':
				if (empty($idpersona)){
					$rspta=$persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email, $idUsuarioCambio );
					echo $rspta ?  0 : 1;
				}
				else {
					$rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email, $idUsuarioCambio );
					echo $rspta ?  2 : 3;
				}
			break;

			// case 'eliminar':
			// 	$rspta=$persona->eliminar($idpersona, $idUsuarioCambio );
		 // 		echo $rspta ?  0 : 1;
			// break;

			case 'desactivar':
				$rspta=$persona->desactivar($idpersona, $idUsuarioCambio);
				//echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
				echo $rspta ? 0: 1;
			break;
 
			case 'activar':
				$rspta=$persona->activar($idpersona, $idUsuarioCambio);
				//echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
				echo $rspta ? 0 : 1;	
			break;

			case 'mostrar':
				$rspta=$persona->mostrar($idpersona);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listarp':
				$rspta=$persona->listarp();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idpersona.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
	 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idpersona.')"  data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>':
	 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idpersona.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idpersona.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->tipo_documento,
		 				"3"=>$reg->num_documento,
		 				"4"=>$reg->telefono,
		 				"5"=>$reg->email,
		 				"6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

			case 'listarc':
				$rspta=$persona->listarc();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idpersona.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
	 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idpersona.')" data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>':
	 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idpersona.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idpersona.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->tipo_documento,
		 				"3"=>$reg->num_documento,
		 				"4"=>$reg->telefono,
		 				"5"=>$reg->email,
		 				"6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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