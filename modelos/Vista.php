<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Vista
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$alias,$url,$idpermiso,$idusuariocambio)
	{
		$sql="INSERT INTO vista (nombre,alias,url,idpermiso,condicion,idUsuarioCambio)
		VALUES ('$nombre','$alias','$url','$idpermiso',1,'$idusuariocambio')";
		return ejecutarConsulta($sql);
		//return $sql;
	}

	//Implementamos un método para editar registros
	public function editar($idvista,$nombre,$alias,$url,$idpermiso,$idusuariocambio)
	{
		$sql="UPDATE vista SET nombre='$nombre',alias='$alias',url='$url',idpermiso='$idpermiso',idusuariocambio='$idusuariocambio' WHERE idvista='$idvista'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar vista
	public function desactivar($idvista, $idusuariocambio)
	{
		$sql="UPDATE vista SET condicion=0, idusuariocambio='$idusuariocambio' WHERE idvista='$idvista'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar vista
	public function activar($idvista, $idusuariocambio)
	{
		$sql="UPDATE vista SET condicion=1, idusuariocambio='$idusuariocambio' WHERE idvista='$idvista'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idvista)
	{
		$sql="SELECT * FROM vista WHERE idvista='$idvista'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		//$sql="SELECT * FROM vista";
		$sql="SELECT v.idvista as idvista, v.nombre as nombre, v.alias as alias, v.url as url, p.alias as permiso, v.condicion as condicion FROM vista v INNER JOIN permiso p ON v.idpermiso=p.idpermiso";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function permisoVista($alias)
	{
		$sql="SELECT p.alias as alias FROM permiso p INNER JOIN vista v ON p.idpermiso=v.idpermiso WHERE v.alias='$alias'";
		return ejecutarConsulta($sql);
	}
}

?>