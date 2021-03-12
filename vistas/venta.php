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
                  <h1>Venta</h1>
                </div>
                <div class="col-md-6">
                  <?php 
                    if($_SESSION['reportes']){
                      echo '<div class="box-tools pull-right exportButtons"></div>';
                    }
                  ?>
                  <div class="box-tools pull-right btnAcciones">
                      <button class="btn btn-sm btn btn-success" id="btnagregar" onclick="mostrarform(true,0)"><i class="fa fa-plus-circle"></i> Agregar</button>
                      <?php 
                        if($_SESSION['reportes']){
                          echo '<a href="../reportes/rptventas.php" target="_blank">
                            <button class="btn btn-sm btn btn-info"><i class="fa fa-clipboard"></i> Reporte</button>
                          </a>';
                        }
                      ?>
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
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Usuario</th>
                                <th>Documento</th>
                                <th>Número</th>
                                <th>Total Venta</th>
                                <th>Estado</th>
                              </thead>
                              <tbody>                            
                              </tbody>
                              <tfoot>
                                <th>Opciones</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Usuario</th>
                                <th>Documento</th>
                                <th>Número</th>
                                <th>Total Venta</th>
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
      <div class="modal fade" id="modalNuevaVenta" aria-modal="true">
        <form name="formulario" id="formulario" method="POST">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nueva Venta</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group col-lg-6 col-md-6 col-sm-3 col-xs-12">
                      <label>Cliente(*):</label>
                      <input type="hidden" name="idventa" id="idventa">
                      <div class="input-group">
                        <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required></select>
                        <span class="input-group-btn">
                          <?php 
                            if($_SESSION['configuracion']){
                              echo '<button class="btn btn-default" id="btnAgregarCliente" type="button" data-toggle="modal" data-target="#modalNuevoCliente"><i class="fa fa-plus"></i></button>';
                            }
                            else{
                              echo '<button class="btn btn-default" id="btnAgregarCliente" type="button" data-toggle="modal" data-target="#modalNuevoCliente" disabled><i class="fa fa-plus"></i></button>';
                            }
                          ?>
                        </span>
                      </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Tipo Comprobante(*):</label>
                      <div class="input-group">
                        <select name="idcomprobante" id="idcomprobante" class="form-control selectpicker" required=""></select>
                        <span class="input-group-btn">
                          <?php 
                            if($_SESSION['configuracion']){
                              echo '<button class="btn btn-default" id="btnAgregarComprobante" type="button" data-toggle="modal" data-target="#modalNuevocomprobante"><i class="fa fa-plus"></i></button>';
                            }
                            else{
                              echo '<button class="btn btn-default" id="btnAgregarComprobante" type="button" data-toggle="modal" data-target="#modalNuevocomprobante" disabled><i class="fa fa-plus"></i></button>';
                            }
                          ?>
                        </span>
                      </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Fecha(*):</label>
                      <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label>Articulos:</label>
                      <input type="text" class="form-control codigoBarrasTbl" name="codigoBarras" id="codigoBarras" placeholder="Codigo">
                      <a class="modalArticulos" data-toggle="modal" href="#modalArticulos">           
                        <button id="btnAgregarArt" type="button" class="btn btn-xs btn-primary" onclick="listarArticulos()"> <span class="fa fa-search"></span></button>
                      </a>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                      <label>Serie:</label>
                      <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                      <label>Número:</label>
                      <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="Número" required="" readonly>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                      <label>Impuesto:</label>
                      <input type="text" class="form-control" name="impuesto" id="impuesto" required="">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#A9D0F5">
                            <th>Opciones</th>
                            <th>Articulo</th>
                            <th>Codigo</th>
                            <th>Cantidad</th>
                            <th>Precio Venta</th>
                            <th>Descuento</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><span id="simboloMoneda"><?php echo $_SESSION['simbolo'].' '?></span><label id="total"> 0.00</label><input type="hidden" name="total_venta" id="total_venta"></th> 
                        </tfoot>
                    </table>
                  </div>
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

      <!-- Modal -->
      <div class="modal fade" id="modalArticulos" tabindex="-1" role="dialog" aria-labelledby="modalArticulosLabel" aria-hidden="true" >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Seleccione un Artículo</h4>
            </div>
            <div class="modal-body">
              <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Categoría</th>
                  <th>Código</th>
                  <th>Stock</th>
                  <th>Precio Venta</th>
                  <th>Imagen</th>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Categoría</th>
                  <th>Código</th>
                  <th>Stock</th>
                  <th>Precio Venta</th>
                  <th>Imagen</th>
                </tfoot>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
            </div>        
          </div>
        </div>
      </div>  
      <!-- Fin modal -->

      <!-- Modal -->
      <div class="modal fade" id="modalNuevoCliente" aria-modal="true">
        <form name="formularioCliente" id="formularioCliente" method="POST">
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
                  <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                    <option value="DNI">DNI</option>
                    <option value="RUC">RUC</option>
                    <option value="CEDULA">CEDULA</option>
                  </select>
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
                  <button id="btnGuardarCliente" class="btn btn-sm btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                  <button id="btnCancelarCliente" class="btn btn-sm btn-danger" data-dismiss="modal" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </form>
      </div>
      <!-- Fin modal -->

      <div class="modal fade" id="modalNuevocomprobante" aria-modal="true">
        <form name="formularioComprobante" id="formularioComprobante" method="POST">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title">Nuevo Comprobante</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Nombre(*):</label>
                    <input type="text" class="form-control" name="nombreComprobante" id="nombreComprobante" maxlength="50" placeholder="Nombre" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Descripción:</label>
                    <input type="text" class="form-control" name="descripcionComprobante" id="descripcionComprobante" maxlength="256" placeholder="Descripción">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Tipo Comprobante:</label>
                    <select class="form-control select-picker" name="tipocomprobante" id="tipocomprobante" required>
                      <option value="Operación">Operación</option>
                      <option value="Identificación">Identificación</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Impuesto:</label>
                    <div class="input-group">
                      <select name="impuestonombre" id="impuestonombre" class="form-control selectpicker" required=""></select>
                      <span class="input-group-btn">
                        <button class="btn btn-default" id="btnAgregarImpuesto" type="button" data-toggle="modal" data-target="#modalNuevoImpuesto"><i class="fa fa-plus"></i></button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-sm btn-primary" type="submit" id="btnGuardarComprobante"><i class="fa fa-save"></i> Guardar</button>
                  <button class="btn btn-sm btn-danger" id="btnCancelarComprobante" data-dismiss="modal" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
    <script type="text/javascript" src="scripts/venta.js"></script>
    <?php
  }
}
ob_end_flush();
?>


