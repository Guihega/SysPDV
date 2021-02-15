<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Permiso
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	
	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM permiso";
		return ejecutarConsulta($sql);		
	}

	public function select($idpermiso)
	{
		$sql="SELECT * FROM permiso WHERE idpermiso='$idpermiso'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarmarcados($idgrupo)
	{
		$sql="SELECT * FROM grupo_permiso WHERE idgrupo='$idgrupo' AND condicion=1";
		return ejecutarConsulta($sql);
	}
	
}

?>