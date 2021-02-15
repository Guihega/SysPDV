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
		require_once "../modelos/Configuracion.php";

		$configuracion=new Configuracion();

		$idconfiguracion=isset($_POST["idconfiguracion"])? limpiarCadena($_POST["idconfiguracion"]):"";
		$empresa=isset($_POST["nombreconfiguracion"])? limpiarCadena($_POST["nombreconfiguracion"]):"";
		$alias=isset($_POST["alias"])? limpiarCadena($_POST["alias"]):"";
		$abreviatura=isset($_POST["abreviatura"])? limpiarCadena($_POST["abreviatura"]):"";
		$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
		$cp=isset($_POST["cp"])? limpiarCadena($_POST["cp"]):"";
		$correo=isset($_POST["correo"])? limpiarCadena($_POST["correo"]):"";
		$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
		$rfc=isset($_POST["rfc"])? limpiarCadena($_POST["rfc"]):"";
		$idmoneda=isset($_POST["idmonedaselect"])? limpiarCadena($_POST["idmonedaselect"]):"";
		$logo=isset($_POST["logo"])? limpiarCadena($_POST["logo"]):"";
		$idimpuesto=isset($_POST["idimpuestoselect"])? limpiarCadena($_POST["idimpuestoselect"]):"";
		$idusuariocambio = $_SESSION['idusuario'];


		switch ($_GET["op"]){
			case 'guardaryeditar':

				if (!file_exists($_FILES['logo']['tmp_name']) || !is_uploaded_file($_FILES['logo']['tmp_name']))
				{
					$logo=$_POST["logoactual"];
				}
				else 
				{
					$ext = explode(".", $_FILES["logo"]["name"]);
					if ($_FILES['logo']['type'] == "image/jpg" || $_FILES['logo']['type'] == "image/jpeg" || $_FILES['logo']['type'] == "image/png")
					{
						$logo = round(microtime(true)) . '.' . end($ext);
						move_uploaded_file($_FILES["logo"]["tmp_name"], "../files/logotipos/" . $logo);
					}
				}

				if (empty($idconfiguracion)){
					$rspta=$configuracion->insertar($empresa,$alias,$abreviatura,$direccion,$cp,$correo,$telefono,$rfc,$idmoneda,$logo,$idimpuesto,$idusuariocambio);
					echo $rspta ? 0 : 1;
				}
				else {
					$rspta=$configuracion->editar($idconfiguracion,$empresa,$alias,$abreviatura,$direccion,$cp,$correo,$telefono,$rfc,$idmoneda,$logo,$idimpuesto,$idusuariocambio);
					echo $rspta ? 2 : 3;
				}
			break;

			case 'desactivar':
				$rspta=$configuracion->desactivar($idconfiguracion, $idusuariocambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'activar':
				$rspta=$configuracion->activar($idconfiguracion, $idusuariocambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'mostrar':
				$rspta=$configuracion->mostrar($idconfiguracion);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listar':
				$rspta=$configuracion->listar();
		 		//Vamos a declarar un array
		 		$data= Array();
		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				//idconfiguracion	empresa	alias	abreviatura	direccion	cp	correo	telefono	rfc	moneda	logo	impuesto	condicion
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idconfiguracion.')"><i class="fa fa-edit"></i></button>'.
		 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idconfiguracion.','.$idusuariocambio.')"><i class="fa fa-close"></i></button>':
		 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idconfiguracion.')"><i class="fa fa-eye"></i></button>'.
		 					' <button class="btn  btn-xs btn-primary" onclick="activar('.$reg->idconfiguracion.','.$idusuariocambio.')"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->empresa,
		 				"2"=>$reg->alias,
		 				"3"=>$reg->abreviatura,
		 				"4"=>$reg->direccion,
		 				"5"=>$reg->cp,
		 				"6"=>$reg->correo,
		 				"7"=>$reg->telefono,
		 				"8"=>$reg->rfc,
		 				"9"=>$reg->monedanombre,
		 				"10"=>$reg->logo,
		 				"11"=>$reg->impuesto,
		 				"12"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

			case 'listarActiva':
				$rspta=$configuracion->listarActiva();
		 		//Codificar el resultado utilizando json
		 		//echo json_encode($rspta);
		 		//Obtenemos loS datos de la configuración
				$regConfig=$rspta->fetch_object();
				//$config=$regConfig->total_venta;
				//echo $regConfig->empresa;

				if (isset($regConfig))
				{
					//Declaramos las variables de sesión para la configuración
					$_SESSION['empresa']=$regConfig->empresa;
					$_SESSION['alias']=$regConfig->alias;
					$_SESSION['abreviatura']=$regConfig->abreviatura;
					$_SESSION['direcion']=$regConfig->direccion;
					$_SESSION['cp']=$regConfig->cp;
					$_SESSION['correo']=$regConfig->correo;
					$_SESSION['telefono']=$regConfig->telefono;
					$_SESSION['rfc']=$regConfig->rfc;
					$_SESSION['monedanombre']=$regConfig->monedanombre;
					$_SESSION['monedasimbolo']=$regConfig->monedasimbolo;
					$_SESSION['monedaalias']=$regConfig->monedaalias;
					$_SESSION['monedadecimales']=$regConfig->monedadecimales;
					$_SESSION['logo']=$regConfig->logo;
					$_SESSION['impuesto']=$regConfig->impuesto;
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