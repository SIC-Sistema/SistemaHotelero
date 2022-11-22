<!DOCTYPE html>
<html>
<head>
	<title>San Roman | Resportes</title>
<?php 
include('fredyNav.php');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
//Obtenemos la informacion del Usuario
$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
$id = 1;
if ($User['area'] != 'Administrador') {
  ?>
  <script>  
    M.toast({html: "NO TIENES ACCESO!...", classes: "rounded"});
    setTimeout("location.href='home.php'", 1000);
  </script>
<?php }  ?>
<script>
  function buscar_reportes(n) {
      var textoDe = $("input#fecha_de"+n).val();
      var textoA = $("input#fecha_a"+n).val();
    M.toast({html: "NO TIENES ACCESO!..."+textoA, classes: "rounded"});

        $.post("../php/control_dinero.php", {
            valorDe: textoDe,
            valorA: textoA,
            accion: n,
          }, function(mensaje) {
              $("#resultado_reportes"+n).html(mensaje);
          }); 
  };
</script>
</head>
<body onload="buscar_reportes(4);">
  <div class="container"><br><br>
    <h4>Historial:</h4><br>
      <div class="row">
      <!-- ----------------------------  TABs o MENU  ---------------------------------------->
        <div class="col s12">
          <ul id="tabs-swipe-demo" class="tabs">
            <li class="tab col s4" ><a class="active black-text" href="#test-swipe-1" onclick="buscar_reportes(4);">ENTRADAS</a></li>
            <li class="tab col s4"><a class="black-text" href="#test-swipe-2" onclick="buscar_reportes(5);">SALIDAS</a></li>
            <li class="tab col s4"><a class="black-text" href="#test-swipe-3" onclick="buscar_reportes(6);">GANANCIAS</a></li>
          </ul>
        </div>
        <!-- ----------------------------  FORMULARIO 1 Tabs  ---------------------------------------->
        <div  id="test-swipe-1" class="col s12">
          <div class="row">
            <div class="row"><br><br>
              <div class="col s12 l5 m5">
                  <label for="fecha_de4">De:</label>
                  <input id="fecha_de4" type="date">    
              </div>
              <div class="col s12 l5 m5">
                  <label for="fecha_a4">A:</label>
                  <input id="fecha_a4"  type="date">
              </div>
              <br><br><br>
              <div>
                  <button class="btn waves-light waves-effect right grey darken-3" onclick="buscar_reportes(4);"><i class="material-icons prefix left">search</i>BUSCAR</button>
              </div>
            </div>
            <div  id="resultado_reportes4">
              <table class="bordered centered highlight">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Cliente</th>
                    <th>Responsable</th>
                    <th>Fecha Entrada</th>
                    <th>Fecha Salida</th>
                    <th>Observacion</th>
                    <th>Total</th>
                    <th>Estatus</th>
                    <th>Registro</th>
                    <th>Fecha Registro</th>
                  </tr>
                </thead>
                <tbody>
                               
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- ----------------------------  FORMULARIO 2 Tabs  ---------------------------------------->
        <div  id="test-swipe-2" class="col s12">
          <div class="row">
            <div class="row"><br><br>
              <div class="col s12 l5 m5">
                  <label for="fecha_de5">De:</label>
                  <input id="fecha_de5" type="date">    
              </div>
              <div class="col s12 l5 m5">
                  <label for="fecha_a5">A:</label>
                  <input id="fecha_a5"  type="date">
              </div>
              <br><br><br>
              <div>
                  <button class="btn waves-light waves-effect right grey darken-3" onclick="buscar_reportes(5);"><i class="material-icons prefix left">search</i>BUSCAR</button>
              </div>
            </div>
              <table class="bordered centered highlight" id="resultado_reportes5">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Registro</th> 
                    <th>Estatus</th> 
                    <th>Solución</th>
                    <th>Fecha Atendido</th>
                    <th>Atendio</th> 
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
          </div>
        </div>
        <!-- ----------------------------  FORMULARIO 2 Tabs  ---------------------------------------->
        <div  id="test-swipe-3" class="col s12">
          <div class="row">
            <div class="row"><br><br>
              <div class="col s12 l5 m5">
                  <label for="fecha_de6">De:</label>
                  <input id="fecha_de6" type="date">    
              </div>
              <div class="col s12 l5 m5">
                  <label for="fecha_a6">A:</label>
                  <input id="fecha_a6"  type="date">
              </div>
              <br><br><br>
              <div>
                <button class="btn waves-light waves-effect right grey darken-3" onclick="reportes_buscar(6);"><i class="material-icons prefix left">search</i>BUSCAR</button>
              </div>
            </div>
              <table class="bordered centered highlight" id="resultado_reportes6">
                <thead>
                  <tr>
                    <th>N° 3</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Registro</th> 
                    <th>Estatus</th> 
                    <th>Solución</th>
                    <th>Fecha Atendido</th>
                    <th>Atendio</th> 
                  </tr>
                </thead>
                <tbody> 
                </tbody>
              </table>
          </div>
        </div>
      </div> 
  </div>
</body>
</html>