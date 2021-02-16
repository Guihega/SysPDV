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
		require_once "../modelos/Grupo.php";

		$grupo=new Grupo();

		$idgrupo=isset($_POST["idgrupo"])? limpiarCadena($_POST["idgrupo"]):"";
		$nombre=isset($_POST["nombregrupo"])? limpiarCadena($_POST["nombregrupo"]):"";
		$permisos=isset($_POST["permisos"])? limpiarCadena($_POST["permisos"]):"";
		$idUsuarioCambio = $_SESSION['idusuario'];


		switch ($_GET["op"]){
			case 'guardaryeditar':
				if (empty($idgrupo)){
					$rspta=$grupo->insertar($nombre,$idUsuarioCambio,$permisos);
					echo $rspta ? 0 : 1;
				}
				else {
					$rspta=$grupo->editar($idgrupo,$nombre,$idUsuarioCambio,$permisos);
					echo $rspta ? 2 : 3;
				}
			break;

			case 'desactivar':
				$rspta=$grupo->desactivar($idgrupo, $idUsuarioCambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'activar':
				$rspta=$grupo->activar($idgrupo, $idUsuarioCambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'mostrar':
				$rspta=$grupo->mostrar($idgrupo);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listar':
				$rspta=$grupo->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idgrupo.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
	 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idgrupo.')"  data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>':
	 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idgrupo.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idgrupo.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

			case 'permisos':
				//Obtenemos todos los permisos de la tabla permisos
				require_once "../modelos/Permiso.php";
				$permiso = new Permiso();
				$rspta = $permiso->listar();

				//Obtener los permisos asignados al usuario
				$id=$_GET['id'];
				$marcados = $permiso->listarmarcados($id);
				//Declaramos el array para almacenar todos los permisos marcados
				$valores=array();

				//Almacenar los permisos asignados al grupo en el array
				while ($per = $marcados->fetch_object())
				{
					array_push($valores, $per->idpermiso);
				}
				echo '<li class="form-check col-md-4">
                    <input class="form-check-input chkPermiso allCheckBox" id="todos" type="checkbox">
                    <label class="form-check-label lblPermiso" for="todos">Todos</label>
                  </li>';
				//Mostramos la lista de permisos en la vista y si están o no marcados
				while ($reg = $rspta->fetch_object())
				{
					$sw=in_array($reg->idpermiso,$valores)?'checked':'';
					echo '<li class="form-check col-md-4">
		                    <input class="form-check-input chkPermiso checkPermiso" '.$sw.' name="permiso[]"  value="'.$reg->idpermiso.'" id="'.$reg->idpermiso.'"type="checkbox">
		                    <label class="form-check-label lblPermiso" for="'.$reg->idpermiso.'">'.$reg->nombre.'</label>
		            	</li>';
				}
			break;

			case 'selectGrupo':
				$rspta=$grupo->listarActivos();
		 		//Vamos a declarar un array
		 		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idgrupo . '>' . $reg->nombre . '</option>';
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