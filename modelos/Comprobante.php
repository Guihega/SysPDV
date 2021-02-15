<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Comprobante
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$descripcion,$idimpuesto,$idusuariocambio)
	{
		$sql="INSERT INTO comprobante (nombre,descripcion,idimpuesto,condicion,idusuariocambio)
		VALUES ('$nombre','$descripcion',$idimpuesto,1,'$idusuariocambio')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcomprobante,$nombre,$descripcion,$idimpuesto,$idusuariocambio)
	{
		$sql="UPDATE comprobante SET nombre='$nombre',descripcion='$descripcion',idimpuesto= $idimpuesto,idusuariocambio='$idusuariocambio' WHERE idcomprobante='$idcomprobante'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idcomprobante, $idusuariocambio)
	{
		$sql="UPDATE comprobante SET condicion=0, idusuariocambio='$idusuariocambio' WHERE idcomprobante='$idcomprobante'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idcomprobante, $idusuariocambio)
	{
		$sql="UPDATE comprobante SET condicion=1, idusuariocambio='$idusuariocambio' WHERE idcomprobante='$idcomprobante'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcomprobante)
	{
		//$sql="SELECT * from comprobante WHERE idcomprobante='$idcomprobante'";
		$sql="SELECT c.idcomprobante, c.nombre, c.descripcion, i.idimpuesto FROM comprobante c INNER JOIN impuesto i ON c.idimpuesto = i.idimpuesto WHERE idcomprobante='$idcomprobante'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM comprobante";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM comprobante where condicion=1";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrarImpuesto($idcomprobante)
	{
		$sql="SELECT i.valor as impuesto FROM impuesto i INNER JOIN comprobante c ON c.idimpuesto = i.idimpuesto WHERE idcomprobante='$idcomprobante'";
		return ejecutarConsultaSimpleFila($sql);
	}
}

?>