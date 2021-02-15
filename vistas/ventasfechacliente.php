<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.php");
}
else
{
  require_once "../modelos/Configuracion.php";
  $configuracion = new Configuracion();
  $rsptaConfig = $configuracion->listarActiva();
  $regConfig=$rsptaConfig->fetch_object();
  //$config=$regConfig->total_venta;
  //echo $regConfig->empresa;

  if (isset($regConfig))
  {
    //Declaramos las variables de sesión para la configuración
    $empresa=$regConfig->empresa;
    $_SESSION["empresa"] = $regConfig->empresa;
  }
  else{
    $empresa=="Nombre de la empresa";
    $_SESSION["empresa"] =  null;
  }
  
  if (!isset($_SESSION["empresa"]))
  {
    header("Location: configuracion.php");
  }
  else
      {
    require 'header.php';

    //Obtenemos el nombre de la vista
    $archivo_actual = basename(__FILE__, ".php");

    require_once "../modelos/Vista.php";
    $vista=new Vista();
    //recuperamos el permiso asignado para la vista
    $rsptavista = $vista->permisoVista($archivo_actual);
    $regvista=$rsptavista->fetch_object();
    $permisovista=$regvista->alias;

    //Verificamos que el permiso de la vista este activado
    if ($_SESSION[$permisovista]==1)
    {
    ?>
    <!--Contenido-->
          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">        
            <!-- Main content -->
            <section class="content-header">
              <div class="row">
                <div class="col-md-6">
                  <h1>Consulta de ventas</h1>
                </div>
                <div class="col-md-6">
                  <div class="box-tools pull-right exportButtons">
                  </div>
                </div>
              </div>
            </section>
            <section class="content">
              <div class="row">
                <div class="col-md-12">
                  <div class="box">
                    <div class="box-header">
                      <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label>Fecha Inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                      </div>
                      <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label>Fecha Fin</label>
                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                      </div>
                      <div class="form-inline col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label>Cliente</label>
                        <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required></select>
                      </div>
                      <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label>Acción</label>
                        <button class="btn btn-sm btn-success form-control" onclick="listar()">Consultar</button>
                      </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="box-body">
                      <div class="panel-body table-responsive" id="listadoregistros">
                          <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                              <th>Fecha</th>
                              <th>Usuario</th>
                              <th>Cliente</th>
                              <th>Comprobante</th>
                              <th>Número</th>
                              <th>Total Venta</th>
                              <th>Impuesto</th>
                              <th>Estado</th>
                            </thead>
                            <tbody>                            
                            </tbody>
                            <tfoot>
                              <th>Fecha</th>
                              <th>Usuario</th>
                              <th>Cliente</th>
                              <th>Comprobante</th>
                              <th>Número</th>
                              <th>Total Venta</th>
                              <th>Impuesto</th>
                              <th>Estado</th>
                            </tfoot>
                          </table>
                      </div>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
          </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->
    <?php
    }
    else
    {
      require 'noacceso.php';
    }

    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/ventasfechacliente.js"></script>
    <?php 
  }
}
ob_end_flush();
?>


