<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  //PAGINACION DE LOS USUARIOS
  if (!$_GET) {
    header('Location:usuarios.php?pagina=1');//para la paginacion
  }
  //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
  include('fredyNav.php');
  #TOMAMOS EL ID DEL USUARIO CON LA SESSION INICIADA
  $id = $_SESSION['user_id'];
  #TOMAMOS LA INFORMACION DEL USUARIO (PARA SABER A QUE AREA PERTENECE)
  $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$id"));
  if($user['area'] != "Administrador"){
    #SI NO ES DIFERENTE A UN ADMINISTRADOR LE MUESTRA MENSAJE DE NEGACION Y REDIRECCIONA A LA PAGINA PRINCIPAL
    echo '<script>M.toast({html:"Permiso denegado. Direccionando a la página principal.", classes: "rounded"})</script>';
      #LLAMAR LA FUNCION admin() DEFINIDA EN EL ARCHIVO MODALS PARA REDIRECCIONAR
      echo '<script>home();</script>';
      #CERRAR LA CONEXION A LA BASE DE DATOS
    mysqli_close($conn);
    exit;
  }
  ?>
  <title>San Roman | Usuarios</title>
  <script>
    //FUNCION QUE ENVIA LA INFORMACION PARA ELIMINAR UN USUARIO(SE ACTIVA CON BOTON DE BORRAR)
    function eliminar(id){
      var answer = confirm("Deseas eliminar el usuario N°"+id+"?");
      if (answer) {
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_users.php"
        $.post("../php/control_users.php", { 
          accion: 3,
          valorId: id,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_users.php"
            $("#resultado_usuarios").html(mensaje);
        }); //FIN post
      };//FIN IF
    }//FIN function
    //FUNCION QUE MANDA LA INFORMACION PARA CAMBIAR DE ESTATUS AL USUARIO ACTIVO-DESACTIVADO (SE ACCIONA CON BOTON)
    function cambiar(estatus, id){
      //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_users.php"
      $.post("../php/control_users.php", { 
          accion: 2,
          valorId: id,
          valorEstatus: estatus,
        }, function(mensaje) {
          //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_users.php"
          $("#resultado_usuarios").html(mensaje);
        });  //FIN post
    };//FIN function
  </script>
</head>
<main>
<body>
  <div class="container">
    <div id="resultado_usuarios">
      <div class="row"><br>
        <h3>Usuarios</h3> 
        <a href="form_usuario.php" class="waves-effect waves-light btn pink right">Agregar Usuario<i class="material-icons right">send</i></a><br><br>
      </div>
      <div class="row">
        <table class="bordered highlight responsive-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre(s)</th>
                <th>Apellidos</th>
                <th>Usuario</th>
                <th>E-mail</th>
                <th>Rol</th>
                <th>Estatus</th>
                <?php echo ($user['area'] == 'Administrador' AND $user['usuarios'] == 1)? '<th>Cambiar</th><th>Eliminar</th><th>Permisos</th>': ''; ?>  
              </tr>
            </thead>
            <tbody>
              <?php
              $filas_x_pagina=20;//paginacion CUANTOS USUARIOS POR PAGINA
              $iniciar = ($_GET['pagina']-1)*$filas_x_pagina; //paginacion              
              $sql_tmp1 = mysqli_query($conn,"SELECT * FROM users");
              $sql_tmp = mysqli_query($conn,"SELECT * FROM users LIMIT $iniciar,$filas_x_pagina");//paginacion
              $filas = mysqli_num_rows($sql_tmp1);
              $paginas=$filas/$filas_x_pagina;//paginacion
              $paginas = ceil($paginas);//paginacion
              if($filas == 0){
                ?>  <h5 class="center">No hay usuarios</h5>  <?php
              }else{
                while($tmp = mysqli_fetch_array($sql_tmp)){
                ?>
                  <tr>
                    <td><?php echo $tmp['user_id']; ?></td>
                    <td><?php echo $tmp['firstname']; ?></td>
                    <td><?php echo $tmp['lastname']; ?></td>
                    <td><?php echo $tmp['user_name']; ?></td>
                    <td><?php echo $tmp['user_email']; ?></td>
                    <td><?php echo $tmp['area']; ?></td>
                    <td><?php echo ($tmp['Estatus'] == 1)? '<a class = "green-text"><b>ACTIVO</b></a>': '<a class = "red-text"><b>INIACTIVO</b></a>'; ?></td>
                    <?php
                    //CREAMOS EL BOTON DE CAMBIAR YA SEA ACTIVAR O DESACTIVAR
                     $BTN =($tmp['Estatus'] == 1)?'<a onclick="cambiar(0,'.$tmp['user_id'].');" class="btn-small waves-effect waves-light indigo">Desactivar</a>':'<a onclick="cambiar(1,'.$tmp['user_id'].');" class="btn-small waves-effect waves-light green">Activar</a';
                     // SI LOS USUARIOS SON ALFREDO Y GABRIEL MOSTRAR BONTONES DE CAMBIAR Y ELIMINAR
                     echo ($user['area'] == 'Administrador' AND $user['usuarios'] == 1)? '<td>'.$BTN.'</td><td><a onclick="eliminar('.$tmp['user_id'].');" class="btn-floating btn-tiny waves-effect waves-light red darken-1"><i class="material-icons">delete</i></a></td><td><form method="post" action="../views/permisos.php"><input id="id" name="id" type="hidden" value="'.$tmp['user_id'].'"><button class="btn-floating btn-tiny waves-effect waves-light pink"><i class="material-icons">edit</i></button></form></td>': ''; 
                     ?>                    
                  </tr>
                <?php
                }
              }
              mysqli_close($conn);
              ?>
            </tbody>
        </table>
        <ul class="pagination"> 
          <li class="waves-effect">
            <a href="usuarios.php?pagina= <?php echo $_GET['pagina']<=1 ?  $paginas:$_GET['pagina']-1 ?>"><i class="material-icons">chevron_left</i></a>
          </li>
          <?php for ($i=0; $i < $paginas; $i++) { ?>
            <li class="waves-effect 
              <?php echo $_GET['pagina']==$i+1 ? 'active':'' ?>">
              <a href="usuarios.php?pagina=<?php echo $i+1 ?>"><?php echo $i+1 ?></a>
            </li>
          <?php } ?>
          <li class="waves-effect">
            <a href="usuarios.php?pagina=<?php echo $_GET['pagina']>=$paginas ? 1:$_GET['pagina']+1 ?>"><i class="material-icons">chevron_right</i></a>
          </li>
        </ul><br><br>
      </div>      
    </div>
  </div>
</body>
</main>
</html>