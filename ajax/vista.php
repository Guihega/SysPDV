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
		require_once "../modelos/Vista.php";

		$vista=new Vista();

		$idvista=isset($_POST["idvista"])? limpiarCadena($_POST["idvista"]):"";
		$nombre=isset($_POST["nombrevista"])? limpiarCadena($_POST["nombrevista"]):"";
		$alias=isset($_POST["alias"])? limpiarCadena($_POST["alias"]):"";
		$url=isset($_POST["url"])? limpiarCadena($_POST["url"]):"";
		$idpermiso=isset($_POST["idpermiso"])? limpiarCadena($_POST["idpermiso"]):"";
		$idusuariocambio = $_SESSION['idusuario'];


		switch ($_GET["op"]){
			case 'guardaryeditar':
				if (empty($idvista)){
					$rspta=$vista->insertar($nombre,$alias,$url,$idpermiso,$idusuariocambio);
					echo $rspta ? 0 : 1;
				}
				else {
					$rspta=$vista->editar($idvista,$nombre,$alias,$url,$idpermiso,$idusuariocambio);
					echo $rspta ? 2 : 3;
				}
			break;

			case 'desactivar':
				$rspta=$vista->desactivar($idvista, $idusuariocambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'activar':
				$rspta=$vista->activar($idvista, $idusuariocambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'mostrar':
				$rspta=$vista->mostrar($idvista);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listar':
				$rspta=$vista->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idvista.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
	 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idvista.')"  data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>':
	 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idvista.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idvista.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->alias,
		 				"3"=>$reg->url,
		 				"4"=>$reg->permiso,
		 				"5"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

			case 'selectPermiso':
				require_once "../modelos/Permiso.php";
				$permiso = new Permiso();

				$rspta = $permiso->listar();

				while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idpermiso . '>' . $reg->nombre . '</option>';
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