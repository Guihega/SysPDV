<?php
  ob_start();
  session_start();
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
    $empresa="Nombre de la empresa";
    $_SESSION["empresa"] =  null;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MHEGA VENTAS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/animate.min.css">

    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
   
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../public/css/blue.css">

    <link rel="stylesheet" href="../public/css/sweetalert2.min.css">
    
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="../index.php"><b class="empresa"><strong><?php echo $empresa; ?></strong></b></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Ingrese sus datos de Acceso</p>
        <form method="post" id="frmAcceso">
          <div class="form-group has-feedback">
            <input type="text" id="logina" name="logina" class="form-control" placeholder="Usuario" required>
            <span class="fa fa-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" id="clavea" name="clavea" class="form-control" placeholder="Password" required>
            <!-- <span class="fa fa-key form-control-feedback"></span> -->
            <span id="eye" class="fa fa-eye-slash form-control-feedback showPass" onclick="showHidePwd();"></span>
          </div>
          <div class="text-right form-group">
              <button type="submit" class="btn btn-success">Ingresar</button>
          </div>
        </form>
        

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery -->
    <script src="../public/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    
    <script src="../public/js/bootstrap.min.js"></script>
     <!-- Bootbox -->
    <script src="../public/js/bootbox.min.js"></script>

    <script type="text/javascript" src="scripts/login.js"></script>

    <script src="../public/js/sweetalert.min.js"></script>

    <script src="../public/js/sweetalert2.js"></script>
  </body>
</html> 
