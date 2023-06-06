<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
    include('fredyNav.php');
    ?>
    <title>San Roman | Receptores</title>
    <script>
      function buscar_receptores(){
        var texto = $("input#busqueda").val();
        $.post("../php/control_receptores.php", {
          texto: texto,
          accion: 3,
          }, function(mensaje){
              $("#tablaReceptoresSat").html(mensaje);
          });
      };
    </script>
  </head>
  <main>
    <body onload="buscar_receptores();">
      <div class="container"><br><br>
        <a href="add_receptor_sat.php" class="waves-effect waves-light btn grey darken-3 left right">Agregar receptor<i class="material-icons prefix left">add</i></a><br><br>
        <div id="borrarCliente"></div>
        <div class="row">
          <h3 class="hide-on-med-and-down col s12 m6 l6">Receptores fiscales</h3>
          <h5 class="hide-on-large-only col s12 m6 l6">Receptores fiscales</h5>
          <form class="col s12 m6 l6">
            <div class="row">
              <div class="input-field col s12">
                <i class="material-icons prefix">search</i>
                <input id="busqueda" name="busqueda" type="text" class="validate" onkeyup="buscar_receptores();">
                <label for="busqueda">Buscar(RFC, E-Razon Social)</label>
              </div>
            </div>
          </form>
        </div>
        <div class="row">
          <table class="bordered highlight responsive-table">
            <thead>
              <tr>
                <th>RFC</th>
                <th>Razón social</th>
                <th>Código postal</th>
                <th>Regimen</th>
                <th>Uso CFDI</th>
                <th>Detalles</th>
                <th>Editar</th>
              </tr>
            </thead>
            <tbody id="tablaReceptoresSat">
            </tbody>
          </table>
        </div><br><br>
      </div>
    </body>
  </main>
</html>