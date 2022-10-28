<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    ?>
    <title>San Roman | Mantenimientos</title>
    <script>
      //FUNCION QUE HACE LA BUSQUEDA DE CLIENTES (SE ACTIVA AL INICIAR EL ARCHIVO O AL ECRIBIR ALGO EN EL BUSCADOR)
      function buscar_mantenimientos(){
        //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO EL TEXTO REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
        var texto = $("input#busqueda").val();
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_habitaciones.php"
        $.post("../php/control_habitaciones.php", {
          //Cada valor se separa por una ,
            texto: texto,
            accion: 4,
          }, function(mensaje){
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
              $("#mantenimient").html(mensaje);
        });//FIN post
      };//FIN function

      //FUNCION QUE BORRA EL CLIENTES (SE ACTIVA AL INICIAR EL BOTON BORRAR)
      function borrar_mantenimiento(id){
        var answer = confirm("Deseas eliminar el Mantenimientos N°"+id+"?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_habitaciones.php"
          $.post("../php/control_habitaciones.php", { 
            //Cada valor se separa por una ,
              id: id,
              accion: 3,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
            $("#borrarMto").html(mensaje);
          }); //FIN post
        }//FIN IF
      };//FIN function
    </script>
  </head>
  <main>
  <body onload="buscar_mantenimientos();">
    <div class="container"><br><br>
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "borrarMto"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="borrarMto"></div>
      <div class="row">
        <!--    //////    TITULO    ///////   -->
        <h3 class="hide-on-med-and-down col s12 m6 l6">Mantenimientos</h3>
        <h5 class="hide-on-large-only col s12 m6 l6">Mantenimientos</h5>
        <!--    //////    INPUT DE EL BUSCADOR    ///////   -->
        <form class="col s12 m6 l6">
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">search</i>
              <input id="busqueda" name="busqueda" type="text" class="validate" onkeyup="buscar_mantenimientos();">
              <label for="busqueda">Buscar(N° Habitacion, Descripcion)</label>
            </div>
          </div>
        </form>
      </div>
      <!--    //////    TABLA QUE MUESTRA LA INFORMACION DE LOS CLIENTES    ///////   -->
      <div class="row">
        <table class="bordered highlight responsive-table">
          <thead>
            <tr>
              <th>N°</th>
              <th>Habitacion</th>
              <th>Descripción</th>
              <th>Fecha</th>
              <th>Registro</th>
              <th>Estatus</th>
              <th>Detalles</th>
              <th>Editar</th>
              <th>Borrar</th>
            </tr>
          </thead>
          <!-- DENTRO DEL tbody COLOCAMOS id = "mantenimient"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION buscar_mantenimientos() -->
          <tbody id="mantenimient">
          </tbody>
        </table>
      </div><br><br>
    </div>
  </body>
  </main>
</html>