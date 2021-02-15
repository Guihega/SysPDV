<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Configuracion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($empresa,$alias,$abreviatura,$direccion,$cp,$correo,$telefono,$rfc,$idmoneda,$logo,$idimpuesto,$idusuariocambio)
	{
		$sql="INSERT INTO configuracion (empresa,alias,abreviatura,direccion,cp,correo,telefono,rfc,idmoneda,logo,idimpuesto,condicion,idusuariocambio)
		VALUES ('$empresa','$alias','$abreviatura','$direccion','$cp','$correo','$telefono','$rfc','$idmoneda','$logo','$idimpuesto',1,'$idusuariocambio')";
		return ejecutarConsulta($sql);
		//return $sql;
	}

	//Implementamos un método para editar registros
	public function editar($idconfiguracion,$empresa,$alias,$abreviatura,$direccion,$cp,$correo,$telefono,$rfc,$idmoneda,$logo,$idimpuesto,$idusuariocambio)
	{
		$sql="UPDATE configuracion SET empresa='$empresa',alias='$alias',abreviatura='$abreviatura',direccion='$direccion',cp='$cp',correo='$correo',telefono='$telefono',rfc='$rfc',idmoneda='$idmoneda',logo='$logo',idimpuesto='$idimpuesto',idusuariocambio='$idusuariocambio'  WHERE idconfiguracion='$idconfiguracion'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar configuracion
	public function desactivar($idconfiguracion, $idusuariocambio)
	{
		$sql="UPDATE configuracion SET condicion=0, idusuariocambio='$idusuariocambio' WHERE idconfiguracion='$idconfiguracion'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar configuracion
	public function activar($idconfiguracion, $idusuariocambio)
	{
		$sql="UPDATE configuracion SET condicion=1, idusuariocambio='$idusuariocambio' WHERE idconfiguracion='$idconfiguracion'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idconfiguracion)
	{
		$sql="SELECT * FROM configuracion WHERE idconfiguracion='$idconfiguracion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		//$sql="SELECT * FROM configuracion";
		$sql="SELECT c.idconfiguracion as idconfiguracion, c.empresa as empresa, c.alias as alias, c.abreviatura as abreviatura, c.direccion as direccion, c.cp as cp, c.correo as correo, c.telefono as telefono, c.rfc as rfc, m.nombre as monedanombre, m.alias as aliasmoneda, m.decimales as decimalesmoneda, m.simbolo as monedasimbolo, c.logo as logo, i.nombre as impuestonombre, i.valor as impuestovalor,c.condicion as condicion FROM configuracion c INNER JOIN moneda m ON c.idmoneda=m.idmoneda INNER JOIN impuesto i ON c.idimpuesto=i.idimpuesto";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarActiva()
	{
		//$sql="SELECT * FROM configuracion";
		$sql="SELECT c.idconfiguracion as idconfiguracion, c.empresa as empresa, c.alias as alias, c.abreviatura as abreviatura, c.direccion as direccion, c.cp as cp, c.correo as correo, c.telefono as telefono, c.rfc as rfc, m.nombre as monedanombre, m.alias as monedaalias, m.decimales as monedadecimales, m.simbolo as monedasimbolo, c.logo as logo, i.nombre as impuestonombre, i.valor as impuestovalor,c.condicion as condicion FROM configuracion c INNER JOIN moneda m ON c.idmoneda=m.idmoneda INNER JOIN impuesto i ON c.idimpuesto=i.idimpuesto WHERE c.condicion = 1";
		return ejecutarConsulta($sql);
	}
}

?>