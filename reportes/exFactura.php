<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['ventas']==1)
{
//Incluímos el archivo Factura.php
require('Factura.php');

//Establecemos los datos de la empresa
//$logo = "logo.jpg";
//$ext_logo = "jpg";
//$empresa = "Soluciones Innovadoras Perú S.A.C.";
// $documento = "20477157772";
// $direccion = "Chongoyape, José Gálvez 1368";
// $telefono = "931742904";
// $email = "jcarlos.ad7@gmail.com";


$logo = $_SESSION['logo'];
$ext_logo = "jpg";
$empresa = $_SESSION['empresa'];
$documento = "20477157772";
$direccion = $_SESSION['direccion'];
$telefono = $_SESSION['telefono'];
$email = $_SESSION['correo'];
$moneda = $_SESSION['aliasmoneda'];
$decimales = $_SESSION['decimales'];
$simbolo = $_SESSION['simbolo'];
$impuesto= $_SESSION['impuesto'];
$impuestovalor = $_SESSION['impuestovalor'];

//$_SESSION['empresa']=$regConfig->empresa;
// $_SESSION['alias']=$regConfig->alias;
// $_SESSION['abreviatura']=$regConfig->abreviatura;
// $_SESSION['direcion']=$regConfig->direccion;
// $_SESSION['cp']=$regConfig->cp;
// $_SESSION['correo']=$regConfig->correo;
// $_SESSION['telefono']=$regConfig->telefono;
// $_SESSION['rfc']=$regConfig->rfc;
// $_SESSION['moneda']=$regConfig->monedanombre;
// $_SESSION['simbolo']=$regConfig->monedasimbolo;
// $_SESSION['aliasmoneda']=$regConfig->monedaalias;
// $_SESSION['decimales']=$regConfig->monedadecimales;
// $_SESSION['logo']="../files/logotipos/".$regConfig->logo;
// $_SESSION['impuesto']=$regConfig->impuestonombre;
// $_SESSION['impuestovalor']=$regConfig->impuestovalor;

//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Venta.php";
$venta= new Venta();
$rsptav = $venta->ventacabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();

//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();

//Enviamos los datos de la empresa al método addSociete de la clase Factura
$pdf->addSociete(utf8_decode($empresa),
                  $documento."\n".
                  utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
                  utf8_decode("Teléfono: ").$telefono."\n" .
                  "Email : ".$email,$logo,$ext_logo);
$pdf->fact_dev( "$regv->tipo_comprobante ", "$regv->serie_comprobante-$regv->num_comprobante" );
$pdf->temporaire( "" );
$pdf->addDate( $regv->fecha);

//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
$pdf->addClientAdresse(utf8_decode($regv->cliente),"Domicilio: ".utf8_decode($regv->direccion),$regv->tipo_documento.": ".$regv->num_documento,"Email: ".$regv->email,"Telefono: ".$regv->telefono);

//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>23,
             "DESCRIPCION"=>78,
             "CANTIDAD"=>22,
             "P.U."=>25,
             "DSCTO"=>20,
             "SUBTOTAL"=>22);
$pdf->addCols( $cols);
$cols=array( "CODIGO"=>"L",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "P.U."=>"R",
             "DSCTO" =>"R",
             "SUBTOTAL"=>"C");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);
//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 89;

//Obtenemos todos los detalles de la venta actual
$rsptad = $venta->ventadetalle($_GET["id"]);

while ($regd = $rsptad->fetch_object()) {
  $line = array( "CODIGO"=> "$regd->codigo",
                "DESCRIPCION"=> utf8_decode("$regd->articulo"),
                "CANTIDAD"=> "$regd->cantidad",
                "P.U."=> "$regd->precio_venta",
                "DSCTO" => "$regd->descuento",
                "SUBTOTAL"=> "$regd->subtotal");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
}

//Convertimos el total en letras
require_once "Letras.php";
$V=new EnLetras(); 
$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,$moneda,$decimales));
$pdf->addCadreTVAs("---".$con_letra);

//Mostramos el impuesto
$pdf->addTVAs( $regv->impuesto, $regv->total_venta,$simbolo);
$pdf->addCadreEurosFrancs($impuesto." ".$impuestovalor."%" );
$pdf->Output('Reporte de Venta','I');

// require_once "Letras.php";
// $V=new EnLetras(); 
// $con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"NUEVOS SOLES"));
// $pdf->addCadreTVAs("---".$con_letra);

// //Mostramos el impuesto
// $pdf->addTVAs( $regv->impuesto, $regv->total_venta,"S/ ");
// $pdf->addCadreEurosFrancs("IGV"." $regv->impuesto %");
// $pdf->Output('Reporte de Venta','I');
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>