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
          <section class="content-header">
            <div class="row">
              <div class="col-md-6">
                <h1>Permiso</h1>
              </div>
              <div class="col-md-6">
                <div class="box-tools pull-right exportButtons">
                </div>
                <div class="box-tools pull-right btnAcciones">
                    <button class="btn btn-sm btn-success" id="agregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                    <a href="../reportes/rptarticulos.php" target="_blank">
                      <button class="btn btn-sm btn-info"><i class="fa fa-clipboard"></i> Reporte</button>
                    </a>
                </div>
              </div>
            </div>
          </section>        
            <!-- Main content -->
            <section class="content">
              <div class="row">
                <div class="col-md-12">
                  <div class="box">
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                          <th>Opciones</th>
                          <th>Permiso</th>
                          <th>Nombre</th>
                          <th>Alias</th>
                        </thead>
                        <tbody>                            
                        </tbody>
                        <tfoot>
                          <th>Opciones</th>
                          <th>Permiso</th>
                          <th>Nombre</th>
                          <th>Alias</th>
                        </tfoot>
                      </table>
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
    <script type="text/javascript" src="scripts/permiso.js"></script>
    <?php
  }
}
ob_end_flush();
?>