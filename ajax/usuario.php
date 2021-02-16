<?php
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
//Obtenemos el nombre de la vista
$archivo_actual = basename(__FILE__, ".php");

require_once "../modelos/Vista.php";
$vista=new Vista();
//recuperamos el permiso asignado para la vista
$rsptavista = $vista->permisoVista($archivo_actual);
$regvista=$rsptavista->fetch_object();
$permisovista=$regvista->alias;

require_once "../modelos/Usuario.php";

$usuario=new Usuario();

$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$idusuarioPassword=isset($_POST["idusuarioPassword"])? limpiarCadena($_POST["idusuarioPassword"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
//$emailPassword=isset($_POST["emailPassword"])? limpiarCadena($_POST["emailPassword"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
//$loginPassword=isset($_POST["loginPassword"])? limpiarCadena($_POST["loginPassword"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
//$clavePassword=isset($_POST["clavePassword"])? limpiarCadena($_POST["clavePassword"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$idUsuarioCambio = $_SESSION['idusuario'];
$idgrupo = isset($_POST["idgrupo"])? limpiarCadena($_POST["idgrupo"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"]))
		{
			header("Location: ../vistas/login.php");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			if ($_SESSION[$permisovista]==1){
				if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
				{
					$imagen=$_POST["imagenactual"];
				}
				else 
				{
					$ext = explode(".", $_FILES["imagen"]["name"]);
					if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
					{
						$imagen = round(microtime(true)) . '.' . end($ext);
						move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
					}
				}
				//Hash SHA256 en la contraseña
				$clavehash=hash("SHA256",$clave);

				if (empty($idusuario)){
					$rspta=$usuario->insertar($idUsuarioCambio,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$idgrupo);
					echo $rspta ? 0: 1;
				}
				else {
					$rspta=$usuario->editar($idusuario,$idUsuarioCambio,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$imagen,$idgrupo);
					echo $rspta ? 2 : 3;
				}
			}
			else
			{
				require 'noacceso.php';
			}
		}
	break;

	case 'desactivar':
		if (!isset($_SESSION["nombre"]))
		{
			header("Location: ../vistas/login.php");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			if ($_SESSION[$permisovista]==1){
				$rspta=$usuario->desactivar($idusuario, $idUsuarioCambio);
		 		echo $rspta ? 0: 1;
			}
			else
			{
				require 'noacceso.php';
			}
		}
	break;

	case 'activar':
		if (!isset($_SESSION["nombre"]))
		{
		 	header("Location: ../vistas/login.php");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			if ($_SESSION[$permisovista]==1){
				$rspta=$usuario->activar($idusuario, $idUsuarioCambio );
		 		echo $rspta ? 0 : 1;
			}
			else
			{
				require 'noacceso.php';
			}
		}
	break;

	case 'mostrar':
		if (!isset($_SESSION["nombre"]))
		{
		 	header("Location: ../vistas/login.php");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			if ($_SESSION[$permisovista]==1){
				$rspta=$usuario->mostrar($idusuario);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			}
			else
			{
				require 'noacceso.php';
			}
		}
	break;

	case 'listar':
		if (!isset($_SESSION["nombre"]))
		{
		 	header("Location: ../vistas/login.php");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			if ($_SESSION[$permisovista]==1){
				$rspta=$usuario->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idusuario.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
	 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idusuario.')" data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>'.
	 					' <button class="btn btn-xs btn-success" onclick="mostrar('.$reg->idusuario.',2)" data-toggle="tooltip" data-placement="top" title="Cambiar contraseña"><i class="fa fa-key"></i></button>':
	 					' <button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idusuario.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idusuario.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->tipo_documento,
		 				"3"=>$reg->num_documento,
		 				"4"=>$reg->telefono,
		 				"5"=>$reg->email,
		 				"6"=>$reg->login,
		 				"7"=>"<img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px' >",
		 				"8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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
		}
	break;

	case 'verificar':
		$logina=$_POST['logina'];
	    $clavea=$_POST['clavea'];

	    //Hash SHA256 en la contraseña
		$clavehash=hash("SHA256",$clavea);
		$rspta=$usuario->verificar($logina, $clavehash);
		//$rspta=$usuario->verificar($logina, $clavea);

		$fetch=$rspta->fetch_object();

		if (isset($fetch))
	    {
	        //Declaramos las variables de sesión
	        $_SESSION['idusuario']=$fetch->idusuario;
	        $_SESSION['nombre']=$fetch->nombre;
	        $_SESSION['imagen']=$fetch->imagen;
	        $_SESSION['login']=$fetch->login;
	        $_SESSION['idgrupo']=$fetch->idgrupo;


	        //Obtenemos los permisos del usuario
	    	require_once "../modelos/Permiso.php";
			$permiso = new Permiso();
			$rspta = $permiso->listar();

			//Obtener los permisos asignados al usuario
			$id=$_SESSION['idgrupo'];
			$marcados = $permiso->listarmarcados($id);

	    	//Declaramos el array para almacenar todos los permisos marcados
			$valores=array();
			$nombres=array();

			//Almacenamos los permisos marcados en el array
			while ($gpo = $marcados->fetch_object())
			{
				array_push($valores, $gpo->idpermiso);
			}

			while ($row = $rspta->fetch_object())
			{
				in_array($row->idpermiso,$valores)?$_SESSION[$row->alias]=1:$_SESSION[$row->alias]=0;
			}

			// //Obtenemos loS datos de la configuración
			require_once "../modelos/Configuracion.php";
			$configuracion = new Configuracion();
			$rsptaConfig = $configuracion->listarActiva();
			$regConfig=$rsptaConfig->fetch_object();
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
				$_SESSION['moneda']=$regConfig->monedanombre;
				$_SESSION['simbolo']=$regConfig->monedasimbolo;
				$_SESSION['aliasmoneda']=$regConfig->monedaalias;
				$_SESSION['decimales']=$regConfig->monedadecimales;
				$_SESSION['logo']="../files/logotipos/".$regConfig->logo;
				$_SESSION['impuesto']=$regConfig->impuestonombre;
				$_SESSION['impuestovalor']=$regConfig->impuestovalor;
			}
	    }
	    echo json_encode($fetch);
	break;

	case 'actualizarPassword':
		if (!isset($_SESSION["nombre"]))
		{
		 	header("Location: ../vistas/login.php");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			if ($_SESSION[$permisovista]==1){
				$emailPassword=$_POST['emailPassword'];
				$usernamePassword=$_POST['loginPassword'];
		    	$passwordUpdate=$_POST['clavePassword'];
				$clavehash=hash("SHA256",$passwordUpdate);
				$rspta=$usuario->actualizarPassword($idusuarioPassword,$idUsuarioCambio,$emailPassword,$usernamePassword,$clavehash);
				echo $rspta ? 0 : 1;
				//echo $rspta ? $rspta : $rspta;
			}
			else
			{
				require 'noacceso.php';
			}
		}
	break;

	case 'salir':
		//Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");

	break;
}
ob_end_flush();
?>