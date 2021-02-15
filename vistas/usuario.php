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
                  <h1>Usuario</h1>
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
                          <th>Nombre</th>
                          <th>Estado</th>
                        </thead>
                        <tbody>                            
                        </tbody>
                        <tfoot>
                          <th>Opciones</th>
                          <th>Nombre</th>
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
      <div class="modal fade" id="modalNuevoUsuario" aria-modal="true">
        <form name="formulario" id="formulario" method="POST">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nuevo usuario</h4>
              </div>
              <div class="modal-body">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Nombre(*):</label>
                  <input type="hidden" name="idusuario" id="idusuario">
                  <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Número(*):</label>
                  <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" placeholder="Documento" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Dirección:</label>
                  <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección" maxlength="70">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Teléfono:</label>
                  <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Email:</label>
                  <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Cargo:</label>
                  <input type="text" class="form-control" name="cargo" id="cargo" maxlength="20" placeholder="Cargo">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 login">
                  <label>Login (*):</label>
                  <input type="text" class="form-control" name="login" id="login" maxlength="20" placeholder="Login" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 has-feedback password">
                  <label>Clave (*):</label>
                  <input type="password" class="form-control" name="clave" id="clave" maxlength="64" placeholder="Clave" required>
                  <span id="eye" class="fa fa-eye-slash form-control-feedback showPass userPasss" onclick="showHidePwd();"></span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Tipo Documento(*):</label>
                  <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                    <option value="DNI">DNI</option>
                    <option value="RUC">RUC</option>
                    <option value="CEDULA">CEDULA</option>
                  </select>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Grupo(*):</label>
                  <div class="input-group">
                    <select id="idgrupo" name="idgrupo" class="form-control selectpicker" data-live-search="true" required></select>
                      <span class="input-group-btn">
                      <button class="btn btn-default" id="btnAgregarGrupo" type="button" data-toggle="modal" data-target="#modalNuevoGrupo"><i class="fa fa-plus"></i> Nuevo</button>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Imagen:</label>
                  <input type="file" class="form-control" name="imagen" id="imagen">
                  <input type="hidden" name="imagenactual" id="imagenactual">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <img src="" width="150px" height="150px" id="imagenmuestra" class="imagenmuestra">
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

      <div class="modal fade" id="modalCambiarPassword" aria-modal="true">
        <form name="formularioPass" id="formularioPass" method="POST">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Cambiar contraseña</h4>
              </div>
              <div class="modal-body">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Nombre(*):</label>
                  <input type="hidden" name="idusuarioPassword" id="idusuarioPassword">
                  <input type="text" class="form-control" name="nombrePassword" id="nombrePassword" maxlength="100" placeholder="Nombre" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Email:</label>
                  <input type="email" class="form-control" name="emailPassword" id="emailPassword" maxlength="50" placeholder="Email">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 login">
                  <label>Login (*):</label>
                  <input type="text" class="form-control" name="loginPassword" id="loginPassword" maxlength="20" placeholder="Login" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 has-feedback password">
                  <label>Clave (*):</label>
                  <input type="password" class="form-control" name="clavePassword" id="clavePassword" maxlength="64" placeholder="Clave" required>
                  <span id="eye" class="fa fa-eye-slash form-control-feedback showPass userPasss" onclick="showHidePwd();"></span>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="submit" id="btnActualizarPassword"><i class="fa fa-save"></i> Guardar</button>
                <button id="btnCancelarPassword" class="btn btn-sm btn-danger" onclick="cancelarform(2)" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </form>
      </div>

      <div class="modal fade" id="modalNuevoGrupo" aria-modal="true">
        <form name="formularioGrupo" id="formularioGrupo" method="POST">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nuevo Grupo</h4>
              </div>
              <div class="modal-body">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Nombre(*):</label>
                  <input type="text" class="form-control" name="nombregrupo" id="nombregrupo" maxlength="100" placeholder="Nombre" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Permisos:</label>
                  <ul style="list-style: none;" id="permisos">
                  </ul>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="submit" id="btnGuardarGrupo"><i class="fa fa-save"></i> Guardar</button>
                <button id="btnCancelarGrupo" class="btn btn-sm btn-danger" type="button" onclick="cancelarform(3)"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

    <script type="text/javascript" src="scripts/usuario.js"></script>
    <?php
  }
}
ob_end_flush();
?>