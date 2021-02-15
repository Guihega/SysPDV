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
    
    //Obtenemos el nombre de la moneda
    $archivo_actual = basename(__FILE__, ".php");

    require_once "../modelos/Vista.php";
    $vista=new Vista();
    //recuperamos el permiso asignado para la moneda
    $rsptvista = $vista->permisoVista($archivo_actual);
    $regvista=$rsptvista->fetch_object();
    $permisovista=$regvista->alias;

    //Verificamos que el permiso de la moneda este activado
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
                  <h1>Moneda</h1>
                </div>
                <div class="col-md-6">
                  <div class="box-tools pull-right exportButtons">
                  </div>
                  <div class="box-tools pull-right btnAcciones">
                      <button class="btn btn-sm btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                      <a href="../reportes/rptarticulos.php" target="_blank">
                        <button class="btn btn-sm btn btn-info"><i class="fa fa-clipboard"></i> Reporte</button>
                      </a>
                  </div>
                </div>
              </div>
            </section>
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
                          <th>Nombre</th>
                          <th>Alias</th>
                          <th>Decimales</th>
                          <th>No. Decimales</th>
                          <th>Simbolo</th>
                          <th>Separador Miles</th>
                          <th>Separador Decimales</th>
                          <th>Código</th>
                          <th>Estado</th>
                        </thead>
                        <tbody>                            
                        </tbody>
                        <tfoot>
                          <th>Opciones</th>
                          <th>Nombre</th>
                          <th>Alias</th>
                          <th>Decimales</th>
                          <th>No. Decimales</th>
                          <th>Simbolo</th>
                          <th>Separador Miles</th>
                          <th>Separador Decimales</th>
                          <th>Código</th>
                          <th>Estado</th>
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
      <!-- Modal -->
      <div class="modal fade" id="modalNuevaMoneda" aria-modal="true">
        <form name="formulario" id="formulario" method="POST">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nueva Moneda</h4>
              </div>
              <div class="modal-body">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Nombre(*):</label>
                  <input type="hidden" name="idmoneda" id="idmoneda">
                  <input type="text" class="form-control" name="nombremoneda" id="nombremoneda" maxlength="100" placeholder="Nombre" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Alias(*):</label>
                  <input type="text" class="form-control" name="alias" id="alias" maxlength="20" placeholder="Alias" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Decimales(*):</label>
                  <input type="text" class="form-control" name="decimales" id="decimales" maxlength="20" placeholder="Decimales" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Simbolo(*):</label>
                  <input type="text" class="form-control" name="simbolo" id="simbolo" maxlength="20" placeholder="Simbolo" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>No. Decimales(*):</label>
                  <input type="text" class="form-control" name="presicion" id="presicion" maxlength="100" placeholder="Presicion">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Separador de miles(*):</label>
                  <input type="text" class="form-control" name="separadormiles" id="separadormiles" maxlength="100" placeholder="Separador de miles" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Separador de decimales(*):</label>
                  <input type="text" class="form-control" name="separadordecimal" id="separadordecimal" maxlength="20" placeholder="Separador de decimales" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Código(*):</label>
                  <input type="text" class="form-control" name="codigo" id="codigo" maxlength="100" placeholder="Código">
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                <button id="btnCancelar" class="btn btn-sm btn-danger" onclick="cancelarform(1)" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </form>
      </div>
      <!-- Fin modal -->
    <?php
    }
    else
    {
      require 'noacceso.php';
    }
    require 'footer.php';
    ?>

    <script type="text/javascript" src="scripts/moneda.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9/sha256.js"></script>
    <?php
  }
}
ob_end_flush();
?>