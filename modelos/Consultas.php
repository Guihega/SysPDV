<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consultas
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function comprasfecha($fecha_inicio,$fecha_fin)
	{
		$sql="SELECT DATE(i.fecha_hora) as fecha,u.nombre as usuario, p.nombre as proveedor,i.idcomprobante as comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.condicion FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin'";
		return ejecutarConsulta($sql);		
	}

	public function ventasfechacliente($fecha_inicio,$fecha_fin,$idcliente)
	{
		$sql="SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente,v.idcomprobante as comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.condicion FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente'";
		return ejecutarConsulta($sql);		
	}

	public function totalcomprahoy()
	{
		$sql="SELECT IFNULL(SUM(total_compra),0) as total_compra FROM ingreso WHERE DATE(fecha_hora)=curdate()";
		return ejecutarConsulta($sql);
	}

	public function totalventahoy()
	{
		$sql="SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta WHERE DATE(fecha_hora)=curdate()";
		return ejecutarConsulta($sql);
	}

	public function comprasultimos_10dias()
	{
		$sql="SELECT CONCAT(DAY(fecha_hora),'/',MONTHNAME(fecha_hora),'/',YEAR(fecha_hora)) as fecha,SUM(total_compra) as total FROM ingreso GROUP by fecha_hora ORDER BY fecha_hora DESC limit 0,10";
		return ejecutarConsulta($sql);
	}

	public function ventasultimos_12meses()
	{
		$sql="SELECT DATE_FORMAT(fecha_hora,'%M') as fecha,SUM(total_venta) as total FROM venta GROUP by MONTHNAME(fecha_hora) ORDER BY fecha_hora DESC limit 0,10";
		return ejecutarConsulta($sql);
	}

	public function ventasultimos_10dias()
	{
		$sql="SELECT CONCAT(DAY(fecha_hora),'/',MONTHNAME(fecha_hora),'/',YEAR(fecha_hora)) as fecha,SUM(total_venta) as total FROM venta GROUP by fecha_hora ORDER BY fecha_hora DESC limit 0,10";
		return ejecutarConsulta($sql);
	}

	public function productosmasvendidos()
	{
		$sql="SELECT SUM(d.cantidad) as cantidad,a.nombre as nombre FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo = a.idarticulo group by a.nombre";
		return ejecutarConsulta($sql);
	}

	public function stockproductos()
	{
		$sql="SELECT a.idarticulo,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1' ORDER BY stock ASC";
		return ejecutarConsulta($sql);
	}

	public function caducidadproductos()
	{
		$sql="SELECT a.idarticulo,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.fechacaducidad,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1' ORDER BY fechacaducidad ASC";
		return ejecutarConsulta($sql);
	}


	public function stockhoy()
	{
		$sql="SELECT COUNT(stock) as stock FROM articulo WHERE stock <=5";
		return ejecutarConsulta($sql);
	}

	public function caducidadhoy()
	{
		$sql="SELECT COUNT(fechacaducidad) as caducidad FROM articulo WHERE DATE(fechacaducidad) >= CURDATE()  AND DATE(fechacaducidad) <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
		return ejecutarConsulta($sql);
	}
}

?>