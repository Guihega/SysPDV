<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Grupo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$idUsuarioCambio,$permisos)
	{
		$sql="INSERT INTO grupo (nombre,condicion,idUsuarioCambio)
		VALUES ('$nombre',1,'$idUsuarioCambio')";
		// return ejecutarConsulta($sql);
		$idgruponew=ejecutarConsulta_retornarID($sql);

		$num_elementos=0;
		$sw=true;

		$permisosGrupo = explode("|", $permisos);

		//idUsuarioCambio='$idUsuarioCambio'
		for ($i=0; $i < count($permisosGrupo); $i++) {
			$permisoGrupo = explode(",", $permisosGrupo[$i]);
			$sql_detalle = "INSERT INTO grupo_permiso(idgrupo, idpermiso,condicion,idUsuarioCambio) VALUES('$idgruponew', '$permisoGrupo[0]', '$permisoGrupo[1]', '$idUsuarioCambio')";
			ejecutarConsulta($sql_detalle) or $sw = false;
		}
		return $sw;
	}

	//Implementamos un método para editar registros
	public function editar($idgrupo,$nombre,$idUsuarioCambio,$permisos)
	{
		$sql="UPDATE grupo SET nombre='$nombre',idUsuarioCambio='$idUsuarioCambio' WHERE idgrupo='$idgrupo'";
		ejecutarConsulta($sql);

		$num_elementos=0;
		$sw=true;

		$permisosGrupo = explode("|", $permisos);

		//idUsuarioCambio='$idUsuarioCambio'
		for ($i=0; $i < count($permisosGrupo); $i++) {
			$permisoGrupo = explode(",", $permisosGrupo[$i]);
			$sql_detalle = "UPDATE grupo_permiso SET condicion='$permisoGrupo[1]', idUsuarioCambio='$idUsuarioCambio' WHERE idgrupo='$idgrupo' AND  idpermiso='$permisoGrupo[0]'";
			//				UPDATE `grupo_permiso` SET `condicion`=0,`idUsuarioCambio`=1 WHERE `idgrupo`=1 AND `idpermiso`=1;
			ejecutarConsulta($sql_detalle) or $sw = false;
		}

		return $sw;
	}

	//Implementamos un método para desactivar grupo
	public function desactivar($idgrupo, $idUsuarioCambio)
	{
		$sql="UPDATE grupo SET condicion=0, idUsuarioCambio='$idUsuarioCambio' WHERE idgrupo='$idgrupo'";
		return ejecutarConsulta($sql);
		//ejecutarConsulta($sql);
		//return $sql;
	}

	//Implementamos un método para activar grupo
	public function activar($idgrupo, $idUsuarioCambio)
	{
		$sql="UPDATE grupo SET condicion=1, idUsuarioCambio='$idUsuarioCambio' WHERE idgrupo='$idgrupo'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idgrupo)
	{
		$sql="SELECT * FROM grupo WHERE idgrupo='$idgrupo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM grupo";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarActivos()
	{
		$sql="SELECT * FROM grupo where condicion=1";
		return ejecutarConsulta($sql);
	}
}

?>