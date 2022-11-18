<!DOCTYPE html>
<html>
<head>
	<title>San Roman | Historial Cortes</title>
<?php 
include('fredyNav.php');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
//Obtenemos la informacion del Usuario
$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
if ($User['cortes'] == 0) {
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
  <div class="container">
    <div class="row">
      <h3 class="hide-on-med-and-down"> Cortes:</h3>
        <h5 class="hide-on-large-only"> Cortes:</h5>
    </div><br><br>
        <div class="row">
            <div class="col s12 l5 m5">
                <label for="fecha_de">De:</label>
                <input id="fecha_de" type="date">    
            </div>
            <div class="col s12 l5 m5">
                <label for="fecha_a">A:</label>
                <input id="fecha_a"  type="date">
            </div>
            <br><br><br>
            <div>
                <button class="btn waves-light waves-effect right grey darken-3" onclick="buscar_cortes();"><i class="material-icons prefix left">search</i>BUSCAR</button>
            </div>
        </div>
      <div id="resultado_cortes">
      </div>  
  </div>
</body>
</html>