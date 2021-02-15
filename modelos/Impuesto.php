<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Impuesto
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//	nombre	valor	condicion	idUsuarioCambio
	//Implementamos un método para insertar registros
	public function insertar($nombre,$valor,$idusuariocambio)
	{
		$sql="INSERT INTO impuesto (nombre,valor,condicion,idusuariocambio)
		VALUES ('$nombre','$valor',1,$idusuariocambio)";
		return ejecutarConsulta($sql);
		//return $sql;
	}

	//Implementamos un método para editar registros
	public function editar($idimpuesto,$nombre,$valor,$idusuariocambio)
	{
		$sql="UPDATE impuesto SET nombre='$nombre',valor='$valor', idusuariocambio='$idusuariocambio' WHERE idimpuesto='$idimpuesto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar impuesto
	public function desactivar($idimpuesto, $idusuariocambio)
	{
		$sql="UPDATE impuesto SET condicion=0, idusuariocambio='$idusuariocambio' WHERE idimpuesto='$idimpuesto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar impuesto
	public function activar($idimpuesto, $idusuariocambio)
	{
		$sql="UPDATE impuesto SET condicion=1, idusuariocambio='$idusuariocambio' WHERE idimpuesto='$idimpuesto'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idimpuesto)
	{
		$sql="SELECT * FROM impuesto WHERE idimpuesto='$idimpuesto'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM impuesto";
		return ejecutarConsulta($sql);
	}

	public function listarActivos()
	{
		$sql="SELECT * FROM impuesto WHERE condicion=1";
		return ejecutarConsulta($sql);
	}
}

?>