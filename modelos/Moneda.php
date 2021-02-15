<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Moneda
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//Implementamos un método para insertar registros
	public function insertar($nombre,$alias,$decimales,$simbolo,$presicion,$separadormiles,$separadordecimal,$codigo,$idusuariocambio)
	{
		$sql="INSERT INTO moneda (nombre,alias,decimales,simbolo,presicion,separadormiles,separadordecimal,codigo,condicion,idusuariocambio)
		VALUES ('$nombre','$alias','$decimales','$simbolo','$presicion','$separadormiles','$separadordecimal','$codigo',1,$idusuariocambio)";
		return ejecutarConsulta($sql);
		//return $sql;
	}

	//Implementamos un método para editar registros
	public function editar($idmoneda,$nombre,$alias,$decimales,$simbolo,$presicion,$separadormiles,$separadordecimal,$codigo,$idusuariocambio)
	{
		$sql="UPDATE moneda SET nombre='$nombre',alias='$alias',decimales='$decimales',simbolo='$simbolo',presicion='$presicion',separadormiles='$separadormiles',separadordecimal='$separadordecimal',codigo='$codigo',idusuariocambio='$idusuariocambio' WHERE idmoneda='$idmoneda'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar moneda
	public function desactivar($idmoneda, $idusuariocambio)
	{
		$sql="UPDATE moneda SET condicion=0, idusuariocambio='$idusuariocambio' WHERE idmoneda='$idmoneda'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar moneda
	public function activar($idmoneda, $idusuariocambio)
	{
		$sql="UPDATE moneda SET condicion=1, idusuariocambio='$idusuariocambio' WHERE idmoneda='$idmoneda'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idmoneda)
	{
		$sql="SELECT * FROM moneda WHERE idmoneda='$idmoneda'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM moneda";
		return ejecutarConsulta($sql);
	}

	public function listarActivos()
	{
		$sql="SELECT * FROM moneda WHERE condicion=1";
		return ejecutarConsulta($sql);
	}
}

?>