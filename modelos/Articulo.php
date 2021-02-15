<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Articulo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen,$caduca,$caducidad,$idUsuarioCambio)
	{
		if($caduca != 1 && empty($caducidad)){
			$sql="INSERT INTO articulo (idcategoria,codigo,nombre,stock,descripcion,imagen,condicion,idUsuarioCambio)
			VALUES ('$idcategoria','$codigo','$nombre',$stock,'$descripcion','$imagen',1,$idUsuarioCambio)";
		}
		else{
			$sql="INSERT INTO articulo (idcategoria,codigo,nombre,stock,descripcion,imagen,caduca,fechacaducidad,condicion,idUsuarioCambio)
			VALUES ('$idcategoria','$codigo','$nombre',$stock,'$descripcion','$imagen',$caduca,'$caducidad',1,$idUsuarioCambio)";
		}

		return ejecutarConsulta($sql);
		//return $sql;
	}

	//Implementamos un método para editar registros
	public function editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen,$caduca,$caducidad,$idUsuarioCambio)
	{
		if($caduca != 1 && empty($caducidad)){
			$sql="UPDATE articulo SET idcategoria='$idcategoria',codigo='$codigo',nombre='$nombre',stock='$stock',descripcion='$descripcion',imagen='$imagen',idUsuarioCambio=$idUsuarioCambio WHERE idarticulo='$idarticulo'";
		}
		else{
			$sql="UPDATE articulo SET idcategoria='$idcategoria',codigo='$codigo',nombre='$nombre',stock='$stock',descripcion='$descripcion',imagen='$imagen',caduca='$caduca',fechacaducidad='$caducidad', idUsuarioCambio=$idUsuarioCambio WHERE idarticulo='$idarticulo'";
		}

		//$sql="UPDATE articulo SET idcategoria='$idcategoria',codigo='$codigo',nombre='$nombre',stock='$stock',descripcion='$descripcion',imagen='$imagen',caduca='$caduca',fechacaducidad='$caducidad', idUsuarioCambio=$idUsuarioCambio WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
		//return $sql;
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idarticulo,$idUsuarioCambio)
	{
		$sql="UPDATE articulo SET condicion=0,idUsuarioCambio='$idUsuarioCambio' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idarticulo,$idUsuarioCambio)
	{
		$sql="UPDATE articulo SET condicion=1,idUsuarioCambio='$idUsuarioCambio' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idarticulo)
	{
		$sql="SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
	public function listarActivosVenta()
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,(SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
		return ejecutarConsulta($sql);		
	}

	public function buscarArticuloBarCode($codigoBarras)
	{
		//$sql="SELECT * FROM articulo WHERE codigo='$codigoBarras'";
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,(SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1' AND a.codigo ='$codigoBarras'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function buscarArticuloId($idproducto)
	{
		//$sql="SELECT * FROM articulo WHERE codigo='$codigoBarras'";
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,(SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1' AND a.idarticulo ='$idproducto'";
		return ejecutarConsultaSimpleFila($sql);
	}
}

?>