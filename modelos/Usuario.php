<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idUsuarioCambio,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$idgrupo)
	{
		$sql="INSERT INTO usuario (nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion,idgrupo,idUsuarioCambio)
		VALUES ('$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen',1,'$idgrupo','$idUsuarioCambio')";
		return ejecutarConsulta($sql);

	}

	//Implementamos un método para editar registros
	public function editar($idusuario,$idUsuarioCambio,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$imagen,$idgrupo)
	{
		$sql="UPDATE usuario SET nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',imagen='$imagen', idgrupo='$idgrupo', idUsuarioCambio='$idUsuarioCambio'  WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idusuario,$idUsuarioCambio)
	{
		$sql="UPDATE usuario SET condicion=0, idUsuarioCambio='$idUsuarioCambio' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idusuario,$idUsuarioCambio)
	{
		$sql="UPDATE usuario SET condicion=1, idUsuarioCambio='$idUsuarioCambio' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idusuario)
	{
		$sql="SELECT * FROM usuario WHERE idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM usuario";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los permisos marcados
	public function listarmarcados($idusuario)
	{
		$sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
    {
    	$sql="SELECT idusuario,nombre,tipo_documento,num_documento,telefono,email,cargo,imagen,login,idgrupo FROM usuario WHERE login='$login' AND clave='$clave' AND condicion='1'"; 
    	return ejecutarConsulta($sql);  
    }

    public function actualizarPassword($idusuario,$idUsuarioCambio,$email,$login,$clave)
	{
		$sql="UPDATE usuario SET clave='$clave', idUsuarioCambio='$idUsuarioCambio' WHERE idusuario='$idusuario' AND email='$email' AND login='$login'";
		return ejecutarConsulta($sql);
		//return $sql;
	}
}

?>