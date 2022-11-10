<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    ?>
    <title>San Roman | Limpieza</title>
    <script>
      //FUNCION QUE BORRA EL CLIENTES (SE ACTIVA AL INICIAR EL BOTON BORRAR)
      function update_limpieza(id){
        var answer = confirm("Deseas terminar limpieza de la habitación N°"+id+"?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_clientes.php"
          $.post("../php/control_clientes.php", { 
            //Cada valor se separa por una ,
              id: id,
              accion: 3,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_clientes.php"
            $("#borrarCliente").html(mensaje);
          }); //FIN post
        }//FIN IF
      };//FIN function
    </script>
  </head>
  <main>
  <body onload="buscar_clientes();">
    <div class="container"><br><br>
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "borrarCliente"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="borrarCliente"></div>
      <div class="row">
        <!--    //////    TITULO    ///////   -->
        <h3 class="hide-on-med-and-down col s12 m6 l6">Lista de Limpieza</h3>
        <h5 class="hide-on-large-only col s12 m6 l6">Lista de Limpieza</h5>
      </div>
      <!--    //////    TABLA QUE MUESTRA LA INFORMACION DE LOS CLIENTES    ///////   -->
      <div class="row">
        <table class="bordered highlight responsive-table">
          <thead>
            <tr>
              <th>N°</th>
              <th>Nombre</th>
              <th>Telefono</th>
              <th>RFC</th>
              <th>E-mail</th>
              <th>Registro</th>
              <th>Fecha</th>
              <th>Reserv.</th>
              <th>Detalles</th>
              <th>Editar</th>
              <th>Borrar</th>
            </tr>
          </thead>
          <!-- DENTRO DEL tbody COLOCAMOS id = "clientesALL"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION buscar_clientes() -->
          <tbody>
          </tbody>
        </table>
      </div><br><br>
    </div>
  </body>
  </main>
</html>