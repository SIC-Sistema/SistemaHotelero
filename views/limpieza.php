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
              <th>Habiación</th>
              <th>Descripción</th>
              <th>Fecha</th>
              <th>Registro</th>
              <th>Estatus</th>
              <th>Accion</th>
            </tr>
          </thead>
          <tbody>
            <?php            
            // REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
            $consulta = mysqli_query($conn, "SELECT * FROM `limpieza` WHERE estatus = 0 ORDER BY id_habitacion");
            //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
            if (mysqli_num_rows($consulta) == 0) {
              echo '<h4>No se encontraron reportes de limpieza.</h4>';
            } else {
              while ($limpar = mysqli_fetch_array($consulta)) {
                $id_user = $limpar['usuario'];
                $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
                $id_habitacion = $limpar['id_habitacion'];
                $habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id_habitacion"));
                ?>
                <tr>
                    <td><?php echo $limpar['id']; ?></td>
                    <td><?php echo $habitacion['id'].'. '.$habitacion['descripcion'].'('.$habitacion['piso'].' piso)'; ?></td>
                    <td><?php echo $limpar['descripcion']; ?></td>
                    <td><?php echo $limpar['fecha']; ?></td>
                    <td><?php echo $user['firstname']; ?></td>
                    <td><?php echo ($limpar['estatus'] == 0)? '<span class="new badge red" data-badge-caption="Pendiente"></span>': ''; ?></td>
                    <td><a onclick="verificar_eliminar(<?php echo $limpar['id']; ?>)" class="btn-small grey darken-4 waves-effect waves-light tooltipped" data-position="bottom" data-tooltip="Limpieza Realizada"><i class="material-icons">photo_filter</i></a></td>
                </tr>
                <?php
                }//FIN while
            }//FIN ELSE
            ?>  
          </tbody>
        </table>
      </div><br><br>
    </div>
  </body>
  </main>
</html>