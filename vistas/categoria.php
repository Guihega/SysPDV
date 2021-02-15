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
                    <h1>Categoría</h1>
                  </div>
                  <div class="col-md-6">
                    <div class="box-tools pull-right exportButtons">
                    </div>
                    <div class="box-tools pull-right btnAcciones">
                        <button class="btn btn-sm btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                        <a href="../reportes/rptarticulos.php" target="_blank">
                          <button class="btn btn-sm btn-info"><i class="fa fa-clipboard"></i> Reporte</button>
                        </a>
                    </div>
                  </div>
                </div>
            </section>
            <section class="content">
                <div class="row">
                  <div class="col-md-12">
                      <div class="box">
                        <!-- centro -->
                        <div class="panel-body table-responsive" id="listadoregistros">
                            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                              <thead>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                              </thead>
                              <tbody>                            
                              </tbody>
                              <tfoot>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
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
       <!--Fin-Contenido--> 
      <div class="modal fade" id="modalNuevaCategoria" aria-modal="true">
        <form name="formulario" id="formulario" method="POST">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nueva Categoria</h4>
              </div>
              <div class="modal-body">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Nombre(*):</label>
                  <input type="hidden" name="idcategoria" id="idcategoria">
                  <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Nombre" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Descripción:</label>
                  <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-sm btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </form>
      </div>
    <?php
    }
    else
    {
      require 'noacceso.php';
    }

    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/categoria.js"></script>
    <?php
  }
}
ob_end_flush();
?>


