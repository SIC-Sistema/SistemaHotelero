<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    ?>
    <title>San Roman | Unidades de Medida</title>
    <script>

      function buscar_unidad(){

        var texto = $("input#busqueda").val();

        $.post("../php/control_unidades.php", {
 
            texto: texto,
            accion: 1,
          }, function(mensaje){
            $("#unidadesALL").html(mensaje);
        });//FIN post
      };//FIN function
      
    </script>
  </head>
  <main>
  <body onload="buscar_unidad();">
    <div class="container"><br><br>

      <!-- <a href="add_impuesto.php" class="waves-effect waves-light btn grey darken-3 left right">Agregar Impuesto<i class="material-icons prefix left">add</i></a><br><br> -->
      <div class="row">
        <!--    //////    TITULO    ///////   -->
        <h3 class="hide-on-med-and-down col s12 m6 l6">Unidades de Medida</h3>
        <h5 class="hide-on-large-only col s12 m6 l6">Unidades de Medida</h5>
        <!--    //////    INPUT DE EL BUSCADOR    ///////   -->
        <a   href="add_unidad.php" class="waves-effect waves-light btn grey darken-3 left right">Agregar Unidad<i class="material-icons prefix left">add</i></a>
      </div>

      <div class="row">
        <table class="bordered highlight">
          <thead>
            <tr>
              
              <th>No.</th>
              <th>Tipo</th>
              <th>Clave</th>
              <th>Nombre</th>
              <th>Acciones</th>

            </tr>
          </thead>

          <tbody id="unidadesALL">
          </tbody>
        </table>
      </div><br><br>
    </div>
  </body>
  </main>
</html>