<!DOCTYPE html>
<html lang="en">
  <head>
    <title>San Roman | Atender Mantenimiento</title>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');   

    //VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL ARTICULO
    if (isset($_POST['id']) == false) {
      ?>
      <script>    
        M.toast({html:"Regresando a mantenimientos.", classes: "rounded"});
        setTimeout("location.href='mantenimientos.php'", 500);
      </script>
      <?php
    }else{
      $id = $_POST['id'];// POR EL METODO POST RECIBIMOS EL ID DEL MANTENIMIENTO
      //CONSULTA PARA SACAR LA INFORMACION DE LA mantenimiento Y ASIGNAMOS EL ARRAY A UNA VARIABLE $mantenimiento
      $mantenimiento = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `mantenimientos` WHERE id=$id"));
      $id_habitacion = $mantenimiento['id_habitacion'];
      $habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id_habitacion"));
      if ($habitacion['estatus'] == 2) {
        $estatus = '<span class="new badge blue" data-badge-caption="Limpieza"></span>';
      }else{
        $estatus = ($habitacion['estatus'] == 1)? '<span class="new badge red" data-badge-caption="Ocupada"></span>': '<span class="new badge green" data-badge-caption="Disponible"></span>';
      }
    ?>
    <script>
      //FUNCION QUE MUESTRA EL MODAL PARA  EDITAR UNA NOTA
      function update_mto(id, estatus){          
        var Solucion = $("textarea#solucion").val();

        if (Solucion == '') {
          M.toast({html:"Porfavor ingrese una solucion o diagnostico", classes: "rounded"});
        }else{
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_habitaciones.php" PARA MOSTRAR EL MODAL
          $.post("../php/control_habitaciones.php", {
            //Cada valor se separa por una ,
              id: id,
              estatus: estatus,
              solucion: Solucion,
              accion: 7,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
                $("#update").html(mensaje);
          });//FIN post
        }//FIN else
      }//FIN function
    </script>
  </head>
  <main>
  <body>
    <div class="container"><br>  
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "update"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="update"></div>
      <div class="row">
        <!--    //////    TITULO    ///////   -->
        <h3 class="hide-on-med-and-down">Atender Mantenimiento N° <?php echo $id; ?></h3>
        <h5 class="hide-on-large-only">Atender Mantenimiento N° <?php echo $id; ?></h5><br>
        <ul class="collection">
            <li class="collection-item avatar">
              <div class="hide-on-large-only"><br><br></div>
              <img src="../img/hotel.png" alt="" class="circle">
              <span class="title"><b>DETALLES DE HABITACION</b></span><br><br>
              <p class="row col s12"><b>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">N° HABITACION: </b><?php echo $id_habitacion;?></div>
                  <div class="col s12"><b class="indigo-text">DESCRIPCION: </b><?php echo $habitacion['descripcion'];?></div> 
                </div>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">TIPO DE HABITACION: </b><?php echo $habitacion['tipo'];?></div>    
                  <div class="col s12"><b class="indigo-text">NIVEL / PISO: </b><?php echo $habitacion['piso'];?></div>    
                </div>
                <div class="col s12 m4">
                  <div class="col s12 m11 l9"><b class="indigo-text">ESTADO: </b><?php echo $estatus;?></div>             
                </div>
              </b></p><br><br><br><div class="hide-on-med-and-up"><br><br><br></div>
              <hr><br>
              <b> MANTENIMIENTO: </b> <?php echo $mantenimiento['descripcion'];?><br><br>
            </li>
        </ul>
      </div> 
      <div class="row"><br>
        <div class="input-field col s12 m6">
          <i class="material-icons prefix">done</i>
          <textarea id="solucion"  class="materialize-textarea validate" data-length="150" required><?php echo $mantenimiento['solucion']; ?></textarea>
          <label for="solucion">Solución o Diagnostico: </label>
        </div>
        <div><br>
          <a onclick="update_mto(<?php echo $id;?>, 1);" class="waves-effect waves-light btn green right"><i class="material-icons right">check</i>TERMINAR</a> 
          <a onclick="update_mto(<?php echo $id;?>, 0);" class="waves-effect waves-light btn grey darken-3 right"><i class="material-icons right">save</i>GUARDAR</a>  
        </div>
      </div> 
      <div class="row"><br>
        <h3>Mantenimientos Atendidos</h3>
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
            <?php
              $sql = "SELECT * FROM `mantenimientos` WHERE id_habitacion = $id_habitacion";
              // REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
              $consulta = mysqli_query($conn, $sql);
              //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
              if (mysqli_num_rows($consulta) == 0) {
                echo '<h4>No se encontraron mantenimientos.</h4>';
              } else {
                while ($mantenimineto = mysqli_fetch_array($consulta)) {
                  $id_user_r = $mantenimineto['usuario'];
                  $user_r = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user_r"));
                  $id_user_a = $mantenimineto['atendio'];
                  if ($id_user_a == 0) {
                    $user_a['firstname'] = '';
                  }else{
                    $user_a = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user_a"));
                  }
                  $estatus = ($mantenimineto['estatus'] == 1)? '<span class="new badge green" data-badge-caption="Terminado"></span>': '<span class="new badge red" data-badge-caption="Pendiente"></span>';
                    ?>
                    <tr>
                        <td><?php echo $mantenimineto['id']; ?></td>
                        <td><?php echo $mantenimineto['descripcion']; ?></td>
                        <td><?php echo $mantenimineto['fecha']; ?></td>
                        <td><?php echo $user_r['firstname']; ?></td>
                        <td><?php echo $estatus; ?></td>
                        <td><?php echo $mantenimineto['solucion']; ?></td>
                        <td><?php echo $mantenimineto['fecha_atendio']; ?></td>
                        <td><?php echo $user_a['firstname']; ?></td>
                    </tr>
                    <?php
                }//FIN while
              }//FIN ELSE
            ?>    
            </tbody>
        </table>
      </div>   
    </div>
  </body>
  <?php
    }
  ?>
  </main>
</html>
