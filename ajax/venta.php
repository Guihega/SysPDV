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
		require_once "../modelos/Venta.php";

		$venta=new Venta();
		 
		$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
		$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
		$idusuario=$_SESSION["idusuario"];
		$idcomprobante=isset($_POST["idcomprobante"])? limpiarCadena($_POST["idcomprobante"]):"";
		$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
		$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
		$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
		$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
		$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
		$codigoBarras=isset($_POST["codigoBarras"])? limpiarCadena($_POST["codigoBarras"]):"";
		$idproducto=isset($_POST["idproducto"])? limpiarCadena($_POST["idproducto"]):"";

		switch ($_GET["op"]){
			case 'guardaryeditar':
				if (empty($idventa)){
					$rspta=$venta->insertar($idcliente,$idusuario,$idcomprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_venta,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]);
					echo $rspta ? 0 : 1;
				}
				else {
				}
			break;

			case 'anular':
				$rspta=$venta->anular($idventa, $idusuario);
		 		echo $rspta ? 0 : 1;
			break;

			case 'mostrar':
				$rspta=$venta->mostrar($idventa);
		 		//Codificar el resultado utilizando json
		 		//Ejecutar sp para restaurar stock y total de importe
		 		echo json_encode($rspta);
			break;

			case 'listarDetalle':
				//Recibimos el idingreso
				$id=$_GET['id'];

				$rspta = $venta->listarDetalle($id);
				$total=0;
				echo '<thead style="background-color:#A9D0F5">
		                <th>Opciones</th>
		                <th>Artículo</th>
		                <th>Cantidad</th>
		                <th>Precio Venta</th>
		                <th>Descuento</th>
		                <th>Subtotal</th>
		            </thead>';

				while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio_venta.'</td><td>'.$reg->descuento.'</td><td>'.$reg->subtotal.'</td></tr>';
					$total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
				}
				echo '<tfoot>
		                <th>TOTAL</th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th><span id="simboloMoneda">'.$_SESSION['simbolo'].'</span><label id="total"> '.$total.'</label><input type="hidden" name="total_venta" id="total_venta"></th>
		            </tfoot>';
			break;

			case 'listar':
				$rspta=$venta->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			if($reg->idcomprobante=='Ticket'){
		 				$url='../reportes/exTicket.php?id=';
		 			}
		 			else{
		 				$url='../reportes/exFactura.php?id=';
		 			}

		 			$data[]=array(
		 				"0"=>($reg->condicion)?'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idventa.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-danger" onclick="desactivar('.$reg->idventa.')" data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-close"></i></button>'.
	 					'<a target="_blank" href="'.$url.$reg->idventa.'"> <button class="btn btn-xs btn-info" data-toggle="tooltip" title="Comprobante"><i class="fa fa-file"></i></button></a>':
	 					'<button class="btn btn-xs btn-warning" onclick="mostrar('.$reg->idventa.')" data-toggle="tooltip" title="Ver"><i class="fa fa-eye"></i></button>'.
	 					' <button class="btn btn-xs btn-primary" onclick="activar('.$reg->idventa.')" data-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>'.
	 					'<a target="_blank" href="'.$url.$reg->idventa.'"> <button class="btn btn-xs btn-info" data-toggle="tooltip" title="Comprobante"><i class="fa fa-file"></i></button></a>',
		 				"1"=>$reg->fecha,
		 				"2"=>$reg->cliente,
		 				"3"=>$reg->usuario,
		 				"4"=>$reg->documento,
		 				"5"=>$reg->serie_comprobante.' '.$reg->num_comprobante,
		 				"6"=>$reg->total_venta,
		 				"7"=>($reg->condicion)?'<span class="label bg-green">Aceptado</span>':
		 				'<span class="label bg-red">Anulado</span>'
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;

			case 'selectCliente':
				require_once "../modelos/Persona.php";
				$persona = new Persona();

				$rspta = $persona->listarC();

				while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
				}
			break;

			case 'listarArticulosVenta':
				require_once "../modelos/Articulo.php";
				$articulo=new Articulo();

				$rspta=$articulo->listarActivosVenta();
		 		//Vamos a declarar un array
		 		$data= Array();
		 
		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				"0"=>'<button class="btn btn-xs btn-warning" onclick="buscarArticuloId('.$reg->idarticulo.')"><span class="fa fa-plus"></span></button>',
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->categoria,
		 				"3"=>$reg->codigo,
		 				"4"=>$reg->stock,
		 				"5"=>$reg->precio_venta,
		 				"6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);
			break;

			case 'buscarArticuloBarCode':
				require_once "../modelos/Articulo.php";
				$articulo=new Articulo();
				$rspta=$articulo->buscarArticuloBarCode($codigoBarras);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'buscarArticuloId':
				require_once "../modelos/Articulo.php";
				$articulo=new Articulo();
				$rspta=$articulo->buscarArticuloId($idproducto);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;
			
			case 'getNumVenta':
				$rspta=$venta->getNumVenta();
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
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