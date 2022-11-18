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
  function buscar_cortes() {
      var textoDe = $("input#fecha_de").val();
      var textoA = $("input#fecha_a").val();
        $.post("../php/control_dinero.php", {
            valorDe: textoDe,
            valorA: textoA,
            accion: 3,
          }, function(mensaje) {
              $("#resultado_cortes").html(mensaje);
          }); 
  };
</script>
</head>
<body>
  <div class="container"><br><br>
    <h4>Historial:</h4><br>
      <div class="row">
      <!-- ----------------------------  TABs o MENU  ---------------------------------------->
        <div class="col s12">
          <ul id="tabs-swipe-demo" class="tabs">
            <li class="tab col s4"><a class="active black-text" href="#test-swipe-1">ENTRADAS</a></li>
            <li class="tab col s4"><a class="black-text" href="#test-swipe-2">SALIDAS</a></li>
            <li class="tab col s4"><a class="black-text" href="#test-swipe-3">GANANCIAS</a></li>
          </ul>
        </div>
        <!-- ----------------------------  FORMULARIO 1 Tabs  ---------------------------------------->
        <div  id="test-swipe-1" class="col s12">
          <div class="row">
            <div class="row"><br><br>
              <div class="col s12 l5 m5">
                  <label for="fecha_de1">De:</label>
                  <input id="fecha_de1" type="date">    
              </div>
              <div class="col s12 l5 m5">
                  <label for="fecha_a1">A:</label>
                  <input id="fecha_a1"  type="date">
              </div>
              <br><br><br>
              <div>
                  <button class="btn waves-light waves-effect right grey darken-3" onclick="reportes_buscar(1);"><i class="material-icons prefix left">search</i>BUSCAR</button>
              </div>
            </div>
            <p><div>
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
            </div></p>
          </div>
        </div>
        <!-- ----------------------------  FORMULARIO 2 Tabs  ---------------------------------------->
        <div  id="test-swipe-2" class="col s12">
          <div class="row">
            <div class="row"><br><br>
              <div class="col s12 l5 m5">
                  <label for="fecha_de2">De:</label>
                  <input id="fecha_de2" type="date">    
              </div>
              <div class="col s12 l5 m5">
                  <label for="fecha_a2">A:</label>
                  <input id="fecha_a2"  type="date">
              </div>
              <br><br><br>
              <div>
                  <button class="btn waves-light waves-effect right grey darken-3" onclick="reportes_buscar(2);"><i class="material-icons prefix left">search</i>BUSCAR</button>
              </div>
            </div>
              <table class="bordered centered highlight">
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
                  <label for="fecha_de3">De:</label>
                  <input id="fecha_de3" type="date">    
              </div>
              <div class="col s12 l5 m5">
                  <label for="fecha_a3">A:</label>
                  <input id="fecha_a3"  type="date">
              </div>
              <br><br><br>
              <div>
                  <button class="btn waves-light waves-effect right grey darken-3" onclick="reportes_buscar(3);"><i class="material-icons prefix left">search</i>BUSCAR</button>
              </div>
            </div>
              <table class="bordered centered highlight">
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