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
      require_once "../modelos/Consultas.php";
      $consulta = new Consultas();
      $rsptac = $consulta->totalcomprahoy();
      $regc=$rsptac->fetch_object();
      $totalc=$regc->total_compra;

      $rsptav = $consulta->totalventahoy();
      $regv=$rsptav->fetch_object();
      $totalv=$regv->total_venta;

      $rsptastock = $consulta->stockhoy();
      $regstock=$rsptastock->fetch_object();
      $totalstock=$regstock->stock;

      $rsptacaducidad = $consulta->caducidadhoy();
      $regcaducidad=$rsptacaducidad->fetch_object();
      $totalcaducidad=$regcaducidad->caducidad;

      //Datos para mostrar el gráfico de barras de las compras
      $compras10 = $consulta->comprasultimos_10dias();
      $fechasc='';
      $totalesc='';
      while ($regfechac= $compras10->fetch_object()) {
        $fechasc=$fechasc.'"'.$regfechac->fecha .'",';
        $totalesc=$totalesc.$regfechac->total .','; 
      }

      //Quitamos la última coma
      $fechasc=substr($fechasc, 0, -1);
      $totalesc=substr($totalesc, 0, -1);

       //Datos para mostrar el gráfico de barras de las ventas
      // $ventas12 = $consulta->ventasultimos_12meses();
      $ventas10 = $consulta->ventasultimos_10dias();
      $fechasv='';
      $totalesv='';
      while ($regfechav= $ventas10->fetch_object()) {
        $fechasv=$fechasv.'"'.$regfechav->fecha .'",';
        $totalesv=$totalesv.$regfechav->total .','; 
      }

      //Quitamos la última coma
      $fechasv=substr($fechasv, 0, -1);
      $totalesv=substr($totalesv, 0, -1);

      $ventasMeses = $consulta->ventasultimos_12meses();
      $fechasvMeses='';
      $totalesvMeses='';
      while ($regfechavMeses= $ventasMeses->fetch_object()) {
        $fechasvMeses=$fechasvMeses.'"'.$regfechavMeses->fecha .'",';
        $totalesvMeses=$totalesvMeses.$regfechavMeses->total .','; 
      }

      //Quitamos la última coma
      $fechasvMeses=substr($fechasvMeses, 0, -1);
      $totalesvMeses=substr($totalesvMeses, 0, -1);

      $masvendido = $consulta->productosmasvendidos();
      $nombremasvendido='';
      $cantidadmasvendido='';
      while ($regmasvendido= $masvendido->fetch_object()) {
        $nombremasvendido=$nombremasvendido.'"'.$regmasvendido->nombre .'",';
        $cantidadmasvendido=$cantidadmasvendido.$regmasvendido->cantidad .','; 
      }

      //Quitamos la última coma
      $nombremasvendido=substr($nombremasvendido, 0, -1);
      $cantidadmasvendido=substr($cantidadmasvendido, 0, -1);

      $stock = $consulta->stockproductos();
      $nombrestock='';
      $cantidadstock='';
      while ($regstock= $stock->fetch_object()) {
        $nombrestock=$nombrestock.'"'.$regstock->nombre .'",';
        $cantidadstock=$cantidadstock.$regstock->stock .','; 
      }

      //Quitamos la última coma
      $nombrestock=substr($nombrestock, 0, -1);
      $cantidadstock=substr($cantidadstock, 0, -1);
    ?>
    <!--Contenido-->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h1 class="box-title">Escritorio </h1>
                  <div class="box-tools pull-right">
                  </div>
                </div>
                <!-- /.box-header -->
                <!-- centro -->
                <div class="panel-body">
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <div class="small-box bg-green">
                      <div class="inner">
                        <h4 style="font-size:17px;">
                          <strong>$ <?php echo $totalv; ?></strong>
                        </h4>
                        <p>Ventas</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                      </div>
                      <a href="venta.php" class="small-box-footer">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4 style="font-size:17px;">
                          <strong>$ <?php echo $totalc; ?></strong>
                        </h4>
                        <p>Compras</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-bag"></i>
                      </div>
                      <a href="ingreso.php" class="small-box-footer">Compras <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <div class="small-box bg-yellow">
                      <div class="inner">
                        <h4 style="font-size:17px;">
                          <strong><?php echo $totalstock; ?></strong>
                        </h4>
                        <p>Stock</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                      </div>
                      <a href="venta.php" class="small-box-footer">Stock <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <div class="small-box bg-red">
                      <div class="inner">
                        <h4 style="font-size:17px;">
                          <strong><?php echo $totalcaducidad; ?></strong>
                        </h4>
                        <p>Perecederos</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-calendar"></i>
                      </div>
                      <a href="venta.php" class="small-box-footer">Perecederos <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        <h3 class="box-title">Compras de los últimos 10 días</h3>
                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-xs btn btn-xs-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn btn-xs-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                      <div class="box-body">
                        <canvas id="compras" width="300" height="150"></canvas>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        <h3 class="box-title">Ventas de los últimos 10 días</h3>
                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-xs btn btn-xs-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn btn-xs-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                      <div class="box-body">
                        <canvas id="ventas" width="300" height="150"></canvas>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <!-- AREA CHART -->
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        <h3 class="box-title">Ventas de los últimos 12 meses</h3>
                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                      <div class="box-body">
                        <div class="chart">
                          <canvas id="areaChart" style="height: 250px; width: 511px;" height="250" width="511"></canvas>
                        </div>
                      </div>
                      <!-- /.box-body -->
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <!-- DONUT CHART -->
                    <div class="box box-danger">
                      <div class="box-header with-border">
                        <h3 class="box-title">Productos más vendidos</h3>
                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                      <div class="box-body">
                        <canvas id="pieChart" style="height: 250px; width: 511px;" height="250" width="511"></canvas>
                      </div>
                      <!-- /.box-body -->
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <!-- LINE CHART -->
                    <div class="box box-info">
                      <div class="box-header with-border">
                        <h3 class="box-title">Stock de productos</h3>
                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                      <div class="box-body">
                        <div class="chart">
                          <canvas id="lineChart" style="height: 250px; width: 511px;" height="250" width="511"></canvas>
                        </div>
                      </div>
                      <!-- /.box-body -->
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <!-- BAR CHART -->
                    <div class="box box-success">
                      <div class="box-header with-border">
                        <h3 class="box-title">Caducidad de productos</h3>
                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                      <div class="box-body">
                        <div class="chart">
                          <canvas id="doughnut" style="height: 250px; width: 511px;" height="250" width="511"></canvas>
                        </div>
                      </div>
                      <!-- /.box-body -->
                    </div>
                  </div>
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

    <script src="../public/js/Chart.min.js"></script>
    <script src="../public/js/Chart.bundle.min.js"></script> 
    <script type="text/javascript">
      var ctx = document.getElementById("compras").getContext('2d');
      var compras = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: [<?php echo $fechasc; ?>],
            datasets: [{
              label: 'Compras en $ de los últimos 10 días',
              data: [<?php echo $totalesc; ?>],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
              ],
              borderColor: [
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)'
              ],
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero:true
                }
              }]
            }
          }
      });

      var ctx = document.getElementById("ventas").getContext('2d');
        var ventas = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: [<?php echo $fechasv; ?>],
            datasets: [{
              label: 'Ventas en $ de los últimos 10 días',
              data: [<?php echo $totalesv; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
              ],
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero:true
                }
              }]
            }
          }
      });

      var ctx = document.getElementById('areaChart').getContext('2d');
        var line = new Chart(ctx, {
          type: 'line',
          data: {
            labels: [<?php echo $fechasvMeses; ?>],
            datasets: [{
              label: 'Ventas en $ de los últimos 12 Meses',
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              borderColor: 'rgba(255, 99, 132, 0.2)',
              data: [<?php echo $totalesvMeses; ?>],
              fill: true,
            }]
          },
          options: {
            responsive: true,
            title: {
              display: false,
              text: 'Chart.js Line Chart'
            },
            tooltips: {
              mode: 'index',
              intersect: false,
            },
            hover: {
              mode: 'nearest',
              intersect: true
            },
            scales: {
              xAxes: [{
                display: true,
                scaleLabel: {
                  display: true,
                  labelString: 'Month'
                }
              }],
              yAxes: [{
                display: true,
                scaleLabel: {
                  display: true,
                  labelString: 'Value'
                }
              }]
            }
          }
      });



      var ctx = document.getElementById('pieChart').getContext('2d');
        var line = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: [<?php echo $nombremasvendido; ?>],
          datasets: [{
            label: "Productos más vendidos",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [<?php echo $cantidadmasvendido; ?>],
          }]
        },
        options: {
          title: {
            display: false,
            text: 'Predicted world population (millions) in 2050'
          }
        }
      });

      var ctx = document.getElementById('lineChart').getContext('2d');
        var line = new Chart(ctx, {
        type: 'line',
        data: {
          labels: [<?php echo $nombrestock; ?>],
          datasets: [{
            label: 'Productos más vendidos',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 0.2)',
            data: [<?php echo $cantidadstock; ?>],
            fill: false,
            borderDash: [5, 5],
            pointHoverRadius: 10
          }]
        },
        options: {
          responsive: true,
          title: {
            display: false,
            text: 'Chart.js Line Chart'
          },
          tooltips: {
            mode: 'index',
            intersect: false,
          },
          hover: {
            mode: 'index',
            intersect: true
          },
          scales: {
            xAxes: [{
              display: true,
              scaleLabel: {
                display: true,
                labelString: 'Month'
              }
            }],
            yAxes: [{
              display: true,
              scaleLabel: {
                display: true,
                labelString: 'Value'
              }
            }]
          }
        }
      });

      var ctx = document.getElementById('doughnut').getContext('2d');
        var line = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
            datasets: [{
              label: "Population (millions)",
              backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
              data: [2478,5267,734,784,433]
            }]
          },
          options: {
            title: {
              display: true,
              text: 'Predicted world population (millions) in 2050'
            }
          }
      });
    </script>

  <?php
  }
}
ob_end_flush();
?>


