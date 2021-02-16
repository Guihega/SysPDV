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
		require_once "../modelos/Articulo.php";

		$articulo=new Articulo();

		$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
		$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
		$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
		$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
		$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
		$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
		$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
		$caduca=isset($_POST["caduca"])? limpiarCadena($_POST["caduca"]):"";
		$caducidad=isset($_POST["caducidad"])? limpiarCadena($_POST["caducidad"]):"";
		$idUsuarioCambio = $_SESSION['idusuario'];

		switch ($_GET["op"]){
			case 'guardaryeditar':

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
						move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
					}
				}
				if (empty($idarticulo)){
					$rspta=$articulo->insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen,$caduca,$caducidad,$idUsuarioCambio);
					echo $rspta ? 0 : 1;
				}
				else {
					$rspta=$articulo->editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen,$caduca,$caducidad,$idUsuarioCambio);
					echo $rspta ? 2 : 3;
				}
			break;

			case 'desactivar':
				$rspta=$articulo->desactivar($idarticulo, $idUsuarioCambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'activar':
				$rspta=$articulo->activar($idarticulo, $idUsuarioCambio);
		 		echo $rspta ? 0 : 1;
			break;

			case 'mostrar':
				$rspta=$articulo->mostrar($idarticulo);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listar':
				$rspta=$articulo->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idarticulo.')" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></button>'.
		 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idarticulo.')"  data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>':
		 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idarticulo.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
		 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idarticulo.')"data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->categoria,
		 				"3"=>$reg->codigo,
		 				"4"=>$reg->stock,
		 				"5"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
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

			case "selectCategoria":
				require_once "../modelos/Categoria.php";
				$categoria = new Categoria();

				$rspta = $categoria->select();

				while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
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