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
              <h1>Artículo</h1>
            </div>
            <div class="col-md-6">
              <div class="box-tools pull-right exportButtons">
              </div>
              <div class="box-tools pull-right btnAcciones">
                <button class="btn btn-sm btn-success" id="btnagregar" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar articulo"><i class="fa fa-plus-circle"></i> Agregar</button>
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
                <div class="box-header">
                  <h3 class="box-title">Lista de articulos</h3>
                </div>
                <!-- centro -->
                <div class="box-body">
                  <div class="panel-body table-responsive" id="listadoregistros">
                    <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                      <thead>
                        <th>Opciones</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Código</th>
                        <th>Stock</th>
                        <th>Imagen</th>
                        <th>Estado</th>
                      </thead>
                      <tbody>                            
                      </tbody>
                      <tfoot>
                        <th>Opciones</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Código</th>
                        <th>Stock</th>
                        <th>Imagen</th>
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
      <div class="modal fade" id="modalNuevoArticulo" aria-modal="true">
        <form name="formulario" id="formulario" method="POST">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nuevo Articulo</h4>
              </div>
              <div class="modal-body">
                <div class="form-row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Nombre(*):</label>
                    <input type="hidden" name="idarticulo" id="idarticulo">
                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Descripción:</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Categoría(*):</label>
                    <select id="idcategoria" name="idcategoria" class="form-control selectpicker" data-live-search="true" required></select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Stock(*):</label>
                    <input type="number" class="form-control" name="stock" id="stock" min="1" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Imagen:</label>
                    <input type="file" class="form-control" name="imagen" id="imagen">
                    <input type="hidden" name="imagenactual" id="imagenactual">
                    <img src="" width="150px" height="120px" id="imagenmuestra">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                      <div class="col-md-10">
                        <label>Código:</label>
                        <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Código Barras">
                      </div>
                      <div class="col-md-2 codigoBarras">
                        <label>Opciones:</label>
                        <button class="btn btn-success" type="button" onclick="generarbarcode()"><i class="icon-plus-circle"></i></button>
                        <button class="btn btn-info" type="button" onclick="imprimir()"><i class="icon-printer"></i></button>
                      </div>
                      <div id="print">
                        <svg id="barcode"></svg>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-lg-1 col-md-1 col-sm-1 col-xs-1">
                    <label for="caduca">Caduca</label>
                    <br>
                    <input type="checkbox" id="caduca" name="caduca" value="0">
                  </div>
                  <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-5 offset-md-6">
                    <label>Fecha de caducidad:</label>
                    <input type="date" class="form-control" name="caducidad" id="caducidad" placeholder="Fecha de caducidad">
                  </div>
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
      <!--Fin-Contenido--> 
      <?php
    }
    else
    {
      require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
    <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
    <script type="text/javascript" src="scripts/articulo.js"></script>
    <?php
  }
}
ob_end_flush();
?>