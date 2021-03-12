<?php
if (strlen(session_id()) < 1) 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MHEGA-VENTAS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/animate.min.css">

    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link href="../public/css/_all-skins.min.css" rel="stylesheet" type="text/css">
    <link href="../public/image/apple-touch-icon.png" rel="apple-touch-icon" >
    <link href="../public/image/favicon.ico" rel="shortcut icon" >
    
    <!-- DATATABLES -->
    <link href="../public/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css">
    <link href="../public/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    <link href="../public/plugins/morrisjs/morris.css" rel="stylesheet" type="text/css">

    <!-- sweetalert2 -->
    <link rel="stylesheet" href="../public/css/sweetalert2.min.css">

  </head>
  <body class="hold-transition skin-blue-light sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="escritorio.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>MHGV</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>MHEGA VENTAS</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <div class="p-dropdown">
            <a href="#" class="btn btn-light btn-round" data-toggle="dropdown">
              <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
              <!-- <i class="icon-user"></i> -->
            </a>
            <div class="p-dropdown-content">
              <div class="widget-myaccount">
                  <div class="d-block">
                    <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                  </div>
                  <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
                  <ul class="text-center">
                    <li><a href="../ajax/usuario.php?op=salir"><i class="icon-log-out"></i>Cerrar</a></li>
                  </ul>
              </div>
            </div>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">       
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu list-group">
            <?php 
            if ($_SESSION['escritorio']==1)
            {
              echo '<li class="list-group-item" id="Escritorio">
              <a href="escritorio.php">
                <i class="fa fa-tasks"></i> <span>Escritorio</span>
              </a>
            </li>';
            }
            ?>

            <?php 
            if ($_SESSION['almacen']==1)
            {
              echo '<li class="list-group-item"  id="mAlmacen" class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Almacén</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lArticulos"><a href="articulo.php"><i class="fa fa-circle-o"></i> Artículos</a></li>
                <li id="lCategorias"><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
              </ul>
            </li>';
            }
            ?>

            <?php 
            if ($_SESSION['compras']==1)
            {
              echo '<li class="list-group-item" id="mCompras" class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>Compras</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lIngresos"><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
                <li id="lProveedores"><a href="proveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
              </ul>
            </li>';
            }
            ?>

            <?php 
            if ($_SESSION['ventas']==1)
            {
              echo '<li class="list-group-item" id="mVentas" class="treeview">
              <a href="#">
                <i class="fa fa-shopping-cart"></i>
                <span>Ventas</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lClientes"><a href="cliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                <li id="lVentas"><a href="venta.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
              </ul>
            </li>';
            }
            ?>
                        
            <?php 
            if ($_SESSION['acceso']==1)
            {
              echo '<li class="list-group-item" id="mAcceso" class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Acceso</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lGrupos"><a href="grupo.php"><i class="fa fa-circle-o"></i> Grupos</a></li>
                <li id="lPermisos"><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                <li id="lUsuarios"><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
              </ul>
            </li>';
            }
            ?>

            <?php 
            if ($_SESSION['reportes']==1)
            {
              echo '<li class="list-group-item" id="mConsulta" class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Consultas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lConsultasC"><a href="comprasfecha.php"><i class="fa fa-circle-o"></i>Compras</a></li>
                <li id="lConsultasV"><a href="ventasfechacliente.php"><i class="fa fa-circle-o"></i>Ventas</a></li>
                <li id="lConsultasStock"><a href="consultastock.php"><i class="fa fa-circle-o"></i>Stock</a></li>
                <li id="lConsultasCaducidad"><a href="consultacaducidad.php"><i class="fa fa-circle-o"></i>Caducidad</a></li>
              </ul>
            </li>';
            }
            ?>

            <?php 
            if ($_SESSION['configuracion']==1)
            {
              echo '<li class="list-group-item" id="mConfiguracion" class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Configuración</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lGenerales"><a href="configuracion.php"><i class="fa fa-circle-o"></i> General</a></li>
                <li id="lImpuestos"><a href="impuesto.php"><i class="fa fa-circle-o"></i> Impuesto</a></li>
                <li id="lMonedas"><a href="moneda.php"><i class="fa fa-circle-o"></i> Moneda</a></li>
                <li id="lComprobante"><a href="comprobante.php"><i class="fa fa-circle-o"></i> Comprobante</a></li>
                <li id="lVistas"><a href="vista.php"><i class="fa fa-circle-o"></i> Vista</a></li>
              </ul>
            </li>';
            }
            ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
