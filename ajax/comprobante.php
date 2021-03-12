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
	// if ($_SESSION[$permisovista]==1)
	// {
		require_once "../modelos/Comprobante.php";

		$comprobante=new Comprobante();

		$idcomprobante=isset($_POST["idcomprobante"])? limpiarCadena($_POST["idcomprobante"]):"";
		$nombre=isset($_POST["nombreComprobante"])? limpiarCadena($_POST["nombreComprobante"]):"";
		$descripcion=isset($_POST["descripcionComprobante"])? limpiarCadena($_POST["descripcionComprobante"]):"";
		$idimpuesto=isset($_POST["impuestonombre"])? limpiarCadena($_POST["impuestonombre"]):"";
		$tipocomprobante =isset($_POST["tipocomprobante"])? limpiarCadena($_POST["tipocomprobante"]):"";
		$idusuariocambio = $_SESSION['idusuario'];

		switch ($_GET["op"]){
			case 'guardaryeditar':
				if ($_SESSION[$permisovista]==1)
				{
					if (empty($idcomprobante)){
						$rspta=$comprobante->insertar($nombre,$descripcion,$tipocomprobante,$idimpuesto,$idusuariocambio);
						echo $rspta ? 0 : 1;
					}
					else {
						$rspta=$comprobante->editar($idcomprobante,$nombre,$descripcion,$tipocomprobante,$idimpuesto,$idusuariocambio);
						echo $rspta ? 2 : 3;
					}
				}
				else
				{
				  require 'noacceso.php';
				}
			break;

			case 'desactivar':
				if ($_SESSION[$permisovista]==1)
				{
					$rspta=$comprobante->desactivar($idcomprobante, $idusuariocambio);
			 		echo $rspta ? 0 : 1;
				}
				else
				{
				  require 'noacceso.php';
				}
			break;

			case 'activar':
				if ($_SESSION[$permisovista]==1)
				{
					$rspta=$comprobante->activar($idcomprobante, $idusuariocambio);
			 		echo $rspta ? 0 : 1;
				}
				else
				{
				  require 'noacceso.php';
				}
			break;

			case 'mostrar':
				if ($_SESSION[$permisovista]==1)
				{
					$rspta=$comprobante->mostrar($idcomprobante);
			 		//Codificar el resultado utilizando json
			 		echo json_encode($rspta);
				}
				else
				{
				  require 'noacceso.php';
				}
			break;

			case 'select':
				$rspta = $comprobante->select();

				while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcomprobante . '>' . $reg->nombre . '</option>';
				}
			break;

			case 'selectIdentificacion':
				$rspta = $comprobante->selectIdentificacion();

				while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcomprobante . '>' . $reg->nombre . '</option>';
				}
			break;

			case 'listar':
				if ($_SESSION[$permisovista]==1)
				{
					$rspta=$comprobante->listar();
			 		//Vamos a declarar un array
			 		$data= Array();

			 		while ($reg=$rspta->fetch_object()){
			 			$data[]=array(
		 					"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idcomprobante.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
		 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idcomprobante.')"  data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>':
		 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idcomprobante.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
		 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idcomprobante.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
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
				}
				else
				{
				  require 'noacceso.php';
				}
			break;

			case 'mostrarImpuesto':
				$id=$_GET['id'];
				$rspta = $comprobante->mostrarImpuesto($id);
				echo json_encode($rspta);
			break;
		}
	//Fin de las validaciones de acceso
	// }
	// else
	// {
	//   require 'noacceso.php';
	// }
}
ob_end_flush();
?>