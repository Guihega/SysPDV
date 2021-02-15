<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Categoria
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$descripcion,$idUsuarioCambio)
	{
		$sql="INSERT INTO categoria (nombre,descripcion,condicion,idUsuarioCambio)
		VALUES ('$nombre','$descripcion',1,'$idUsuarioCambio')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcategoria,$nombre,$descripcion,$idUsuarioCambio)
	{
		$sql="UPDATE categoria SET nombre='$nombre',descripcion='$descripcion',idUsuarioCambio='$idUsuarioCambio' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idcategoria, $idUsuarioCambio)
	{
		$sql="UPDATE categoria SET condicion=0, idUsuarioCambio='$idUsuarioCambio' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idcategoria, $idUsuarioCambio)
	{
		$sql="UPDATE categoria SET condicion=1, idUsuarioCambio='$idUsuarioCambio' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcategoria)
	{
		$sql="SELECT * FROM categoria WHERE idcategoria='$idcategoria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM categoria";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM categoria where condicion=1";
		return ejecutarConsulta($sql);		
	}
}

?>