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
      function update_limpieza(id, habitacion){
        var answer = confirm("Deseas terminar limpieza de la habitación N°"+id+"?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_habitaciones.php"
          $.post("../php/control_habitaciones.php", { 
            //Cada valor se separa por una ,
              id: id,
              habitacion: habitacion,
              accion: 10,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
            $("#update").html(mensaje);
          }); //FIN post
        }//FIN IF
      };//FIN function

       //FUNCION QUE MUESTRA EL MODAL PARA EDITAR UNA NOTA
      function modal_limpieza_edit(id){
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "modal_editar_limpieza.php" PARA MOSTRAR EL MODAL
          $.post("modal_editar_limpieza.php", {
            //Cada valor se separa por una ,
              id:id,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "modal_editar_limpieza.php"
                $("#modal").html(mensaje);
          });//FIN post
      }//FIN function

      //FUNCION QUE MUESTRA EL MODAL PARA  EDITAR UNA NOTA
      function editar_limp(id){          
        var descripcionLimp = $("input#descripcionLimp").val();

        if (descripcionLimp == '') {
          M.toast({html:"Porfavor ingrese una descripción al reporte de limpieza", classes: "rounded"});
        }else{
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_habitaciones.php" PARA MOSTRAR EL MODAL
          $.post("../php/control_habitaciones.php", {
            //Cada valor se separa por una ,
              id: id,
              valorDescripcion: descripcionLimp,
              accion: 9,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
                $("#modal").html(mensaje);
          });//FIN post
        }//FIN else
      }//FIN function
    </script>
  </head>
  <main>
  <body onload="buscar_clientes();">
    <div class="container"><br><br>
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "update"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="update"></div>
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "modal"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="modal"></div>
      <div class="row">
        <!--    //////    TITULO    ///////   -->
        <h3 class="hide-on-med-and-down col s12 m6 l6">Lista de Limpieza</h3>
        <h5 class="hide-on-large-only col s12 m6 l6">Lista de Limpieza</h5><br><br>
        <!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPR HAGA LO QUE LA FUNCION CONTENGA -->
        <a href="../php/imprimir_limpieza.php" target="blank" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons left">picture_as_pdf</i>Imprimir</a>
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
              <th class="center">Acción </th>
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
                    <td><a onclick="modal_limpieza_edit(<?php echo $limpar['id']; ?>)" class="btn-small green waves-effect waves-light tooltipped" data-position="bottom" data-tooltip="Editar"><i class="material-icons">edit</i></a>   <a onclick="update_limpieza(<?php echo $limpar['id']; ?>, <?php echo $habitacion['id']; ?>)" class="btn-small grey darken-4 waves-effect waves-light tooltipped" data-position="bottom" data-tooltip="Limpieza Realizada"><i class="material-icons">photo_filter</i></a></td>
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