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
		require_once "../modelos/Permiso.php";

		$permiso=new Permiso();

		switch ($_GET["op"]){
			
			case 'listar':
				$rspta=$permiso->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idpermiso.')"><i class="fa fa-edit"></i></button>',
		 				"1"=>$reg->idpermiso,
		 				"2"=>$reg->nombre,
		 				"3"=>$reg->alias
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