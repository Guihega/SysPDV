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
                  <h1>Cliente</h1>
                </div>
                <div class="col-md-6">
                  <div class="box-tools pull-right exportButtons">
                  </div>
                  <div class="box-tools pull-right btnAcciones">
                      <button class="btn btn-sm btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                      <a href="../reportes/rptclientes.php" target="_blank">
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
                                <th>Documento</th>
                                <th>Número</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Estado</th>
                              </thead>
                              <tbody>                            
                              </tbody>
                              <tfoot>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Número</th>
                                <th>Teléfono</th>
                                <th>Email</th>
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
      <div class="modal fade" id="modalNuevoCliente" aria-modal="true">
        <form name="formulario" id="formulario" method="POST">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nuevo Cliente</h4>
              </div>
              <div class="modal-body">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Nombre:</label>
                  <input type="hidden" name="idpersona" id="idpersona">
                  <input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente">
                  <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del cliente" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Tipo Documento:</label>
                  <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required></select>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Número Documento:</label>
                  <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" placeholder="Documento">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Dirección:</label>
                  <input type="text" class="form-control" name="direccion" id="direccion" maxlength="70" placeholder="Dirección">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Teléfono:</label>
                  <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Email:</label>
                  <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                </div>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-sm btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                  <button id="btnCancelar" class="btn btn-sm btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
    <script type="text/javascript" src="scripts/cliente.js"></script>
    <?php
  }
}
ob_end_flush();
?>