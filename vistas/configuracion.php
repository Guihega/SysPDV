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
            <h1>Configuración</h1>
          </div>
          <div class="col-md-6">
            <div class="box-tools pull-right exportButtons">
            </div>
            <div class="box-tools pull-right btnAcciones">
                <button class="btn btn-sm btn btn-success" id="btnagregar" onclick="mostrarform(true,1)"><i class="fa fa-plus-circle"></i> Agregar</button>
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
                      <th>Empresa</th>
                      <th>Alias</th>
                      <th>Abreviatura</th>
                      <th>Dirección</th>
                      <th>CP</th>
                      <th>Correo</th>
                      <th>Telefono</th>
                      <th>Rfc</th>
                      <th>Moneda</th>
                      <th>Logo</th>
                      <th>Impuesto</th>
                      <th>Estado</th>
                    </thead>
                    <tbody>                            
                    </tbody>
                    <tfoot>
                      <th>Opciones</th>
                      <th>Empresa</th>
                      <th>Alias</th>
                      <th>Abreviatura</th>
                      <th>Dirección</th>
                      <th>CP</th>
                      <th>Correo</th>
                      <th>Telefono</th>
                      <th>Rfc</th>
                      <th>Moneda</th>
                      <th>Logo</th>
                      <th>Impuesto</th>
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
    <div class="modal fade" id="modalNuevaconfiguracion" aria-modal="true">
      <form name="formulario" id="formulario" method="POST">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title">Nueva configuración</h4>
            </div>
            <div class="modal-body">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Nombre(*):</label>
                <input type="hidden" name="idconfiguracion" id="idconfiguracion">
                <input type="text" class="form-control" name="nombreconfiguracion" id="nombreconfiguracion" maxlength="100" placeholder="Nombre" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Alias(*):</label>
                <input type="text" class="form-control" name="alias" id="alias" maxlength="100" placeholder="Alias" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Abreviatura(*):</label>
                <input type="text" class="form-control" name="abreviatura" id="abreviatura" maxlength="100" placeholder="Abreviatura" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Dirección(*):</label>
                <input type="text" class="form-control" name="direccion" id="direccion" maxlength="100" placeholder="Dorección" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>CP(*):</label>
                <input type="text" class="form-control" name="cp" id="cp" maxlength="5" placeholder="CP" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Correo(*):</label>
                <input type="text" class="form-control" name="correo" id="correo" maxlength="100" placeholder="Correo" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Telefono(*):</label>
                <input type="text" class="form-control" name="telefono" id="telefono" maxlength="100" placeholder="Telefono" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>RFC(*):</label>
                <input type="text" class="form-control" name="rfc" id="rfc" maxlength="100" placeholder="RFC" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Moneda(*):</label>
                <div class="input-group">
                  <select id="idmonedaselect" name="idmonedaselect" class="form-control selectpicker" data-live-search="true" required></select>
                    <span class="input-group-btn">
                    <button class="btn btn-default" id="btnAgregarMoneda" type="button" data-toggle="modal" data-target="#modalNuevaMoneda"><i class="fa fa-plus"></i> Nuevo</button>
                  </span>
                </div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Impuesto(*):</label>
                <div class="input-group">
                  <select id="idimpuestoselect" name="idimpuestoselect" class="form-control selectpicker" data-live-search="true" required></select>
                    <span class="input-group-btn">
                    <button class="btn btn-default" id="btnAgregarImpuesto" type="button" data-toggle="modal" data-target="#modalNuevoImpuesto"><i class="fa fa-plus"></i> Nuevo</button>
                  </span>
                </div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Logotipo:</label>
                <div class="row">
                  <div class="col-md-12">
                    <input type="file" class="form-control inputfile inputfile1" name="logo" id="logo">
                    <label for="logo" class="form-control"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg> <span>Selecciona el archivo...</span></label>
                    <input type="hidden" name="logoactual" id="logoactual">
                  </div>
                </div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <img src="" width="150px" height="150px" id="logomuestra" class="logomuestra">
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

    <!-- Modal Moneda-->
    <div class="modal fade" id="modalNuevaMoneda" aria-modal="true">
      <form name="formularioMoneda" id="formularioMoneda" method="POST">
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
                <label>Simbolo(*):</label>
                <input type="text" class="form-control" name="simbolo" id="simbolo" maxlength="20" placeholder="Simbolo" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Presicion(*):</label>
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
              <button class="btn btn-sm btn-primary" type="submit" id="btnGuardarMoneda"><i class="fa fa-save"></i> Guardar</button>
              <button id="btnCancelarMoneda" class="btn btn-sm btn-danger" onclick="cancelarform(2)" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </form>
    </div>
    <!-- Fin Modal Moneda -->

    <!-- Modal Impuesto-->
    <div class="modal fade" id="modalNuevoImpuesto" aria-modal="true">
      <form name="formularioImpuesto" id="formularioImpuesto" method="POST">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title">Nuevo Impuesto</h4>
            </div>
            <div class="modal-body">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Nombre(*):</label>
                <input type="hidden" name="idimpuesto" id="idimpuesto">
                <input type="text" class="form-control" name="nombreimpuesto" id="nombreimpuesto" maxlength="100" placeholder="Nombre" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Valor(*):</label>
                <input type="text" class="form-control" name="valor" id="valor" maxlength="20" placeholder="Valor" required>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-primary" type="submit" id="btnGuardarImpuesto"><i class="fa fa-save"></i> Guardar</button>
              <button id="btnCancelarImpuesto" class="btn btn-sm btn-danger" onclick="cancelarform(3)" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </form>
    </div>
    <!-- Fin Modal Impuesto-->

  <?php
  }
  else
  {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>

  <script type="text/javascript" src="scripts/configuracion.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9/sha256.js"></script>
  <?php 
}
ob_end_flush();
?>