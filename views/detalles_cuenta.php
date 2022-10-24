<!DOCTYPE html>
<html lang="en">
  <head>
    <title>San Roman | Detalle de Cuenta</title>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');   

    //VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL ARTICULO
    if (isset($_GET['id']) == false) {
      ?>
      <script>    
        M.toast({html:"Regresando a cuentas.", classes: "rounded"});
        setTimeout("location.href='cuentas.php'", 500);
      </script>
      <?php
    }else{
      $id = $_GET['id'];// POR EL METODO POST RECIBIMOS EL ID DE LA RESERVACION
      //CONSULTA PARA SACAR LA INFORMACION DE LA reservacion Y ASIGNAMOS EL ARRAY A UNA VARIABLE $reservacion
      $reservacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id=$id"));
      $id_cliente = $reservacion['id_cliente'];
      $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id_cliente"));
      $estatus = ($reservacion['estatus'] == 0)? '<span class="new badge green" data-badge-caption="Pendiente"></span>':'<span class="new badge blue" data-badge-caption="Ocupada"></span>';
    ?>
    <script>
      //FUNCION QUE MUESTRA EL MODAL PARA AGREGAR UNA NOTA
      function modal_nota(id){
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "modal_nota.php" PARA MOSTRAR EL MODAL
          $.post("modal_nota.php", {
            //Cada valor se separa por una ,
              id:id,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "modal_nota.php"
                $("#modal").html(mensaje);
          });//FIN post
      }//FIN function

      //FUNCION QUE MUESTRA EL MODAL PARA  AGREGAR UNA NOTA
      function nueva_nota(id){          
        var DescripcionNota = $("input#descripcionNota").val();

        if (DescripcionNota == '') {
          M.toast({html:"Porfavor ingrese una descripción a la nota", classes: "rounded"});
        }else{
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_reservaciones.php" PARA MOSTRAR EL MODAL
          $.post("../php/control_reservaciones.php", {
            //Cada valor se separa por una ,
              id: id,
              valorNota: DescripcionNota,
              accion: 6,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
                $("#modal").html(mensaje);
          });//FIN post
        }//FIN else
      }//FIN function

      //FUNCION QUE MUESTRA EL MODAL PARA EDITAR UNA NOTA
      function modal_nota_edit(id_hab, id_nota){
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "modal_nota.php" PARA MOSTRAR EL MODAL
          $.post("modal_editar_nota.php", {
            //Cada valor se separa por una ,
              id_hab:id_hab,
              id_nota:id_nota,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "modal_nota.php"
                $("#modal").html(mensaje);
          });//FIN post
      }//FIN function

      //FUNCION QUE MUESTRA EL MODAL PARA  EDITAR UNA NOTA
      function editar_nota(id_res, id_nota){          
        var DescripcionNota = $("input#descripcionNota").val();

        if (DescripcionNota == '') {
          M.toast({html:"Porfavor ingrese una descripción a la nota", classes: "rounded"});
        }else{
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_reservaciones.php" PARA MOSTRAR EL MODAL
          $.post("../php/control_reservaciones.php", {
            //Cada valor se separa por una ,
              id_res: id_res,
              id_nota: id_nota,
              valorNota: DescripcionNota,
              accion: 8,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
                $("#editNotas").html(mensaje);
          });//FIN post
        }//FIN else
      }//FIN function

      //FUNCION QUE BORRAR NOTAS (SE ACTIVA AL INICIAR EL BOTON BORRAR)
      function borrar_nota(id_res,id){
        var answer = confirm("Deseas eliminar la nota N°"+id+"?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_reservaciones.php"
          $.post("../php/control_reservaciones.php", {
            //Cada valor se separa por una ,
            id_res: id_res,
            id: id,
            accion: 9,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
            $("#editNotas").html(mensaje);
          }); //FIN post
        }//FIN IF
      };//FIN function

      function verificar_eliminar(id_res, IdPago){ 
        var textoIdCliente = $("input#id_cliente").val();  
        $.post("../views/verificar_eliminar_pago.php", {
              valorIdPago: IdPago,
              redireciona: id_res,
            }, function(mensaje) {
                $("#editPagos").html(mensaje);
            }); 
       };
    </script>
  </head>
  <main>
  <body>
    <div class="container"><br><br>   
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "modal"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="modal"></div>
      <div class="row"><br>
        <ul class="collection">
            <li class="collection-item avatar">
              <div class="hide-on-large-only"><br><br></div>
              <img src="../img/cliente.png" alt="" class="circle">
              <span class="title"><b>DETALLES DE LA CUENTA</b></span><br><br>
              <p class="row col s12"><b>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">N° RESERVACION: </b><?php echo $id;?></div>
                  <div class="col s12"><b class="indigo-text">Cliente: </b><?php echo $cliente['nombre'];?></div>              
                  <div class="col s12"><b class="indigo-text">HABITACION N°: </b><?php echo $reservacion['id_habitacion'];?></div>         
                  <div class="col s12"><b class="indigo-text">RESPONSABLE: </b><?php echo $reservacion['nombre'];?></div>                
                </div>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">FECHA ENTRADA: </b><?php echo $reservacion['fecha_entrada'];?></div>              
                  <div class="col s12"><b class="indigo-text">FECHA SALIDA: </b><?php echo $reservacion['fecha_salida'];?></div>              
                  <div class="col s10 m10 l9"><b class="indigo-text">ESTATUS: </b><?php echo $estatus;?></div>               
                </div>
                <div class="col s12 m4">       
                  <div class="col s10"><b class="indigo-text">TOTAL: </b><div class="right blue-text">$<?php echo sprintf('%.2f', $reservacion['total']);?></div></div>              
                  <div class="col s10"><b class="indigo-text">DEJO O ABONO: </b><div class="right green-text">-$<?php echo sprintf('%.2f', $reservacion['anticipo']);?></div></div><br><br><hr>            
                  <div class="col s10"><b class="indigo-text">RESTA: </b><div class="right red-text">$<?php echo sprintf('%.2f', $reservacion['total']-$reservacion['anticipo']);?></div></div>              
                </div>
              </b></p><br><br><br><br>
            </li>
        </ul>
      </div>
      <h4>Historial:</h4>
      <div class="row">
      <!-- ----------------------------  TABs o MENU  ---------------------------------------->
        <div class="col s12">
          <ul id="tabs-swipe-demo" class="tabs">
            <li class="tab col s6"><a class="active black-text" href="#test-swipe-1">ANTICIPOS y/o ABONOS</a></li>
            <li class="tab col s6"><a class="black-text" href="#test-swipe-2">NOTAS</a></li>
          </ul>
        </div>
        <!-- ----------------------------  FORMULARIO 1 Tabs  ---------------------------------------->
        <div  id="test-swipe-1" class="col s12">
         <div class="row"><br>
            <p><div>
              <table class="bordered centered highlight">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Cantidad</th>
                    <th>Tipo</th>
                    <th>Descripcion</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Cambio</th> 
                    <th>Borrar</th> 
                  </tr>
                </thead>
                <div id="editPagos"></div>
                <tbody>
                  <?php
                  $descripcion = 'Reservación N°'.$id.' ';
                  $id_cliente = $cliente['id'];
                  $pagos = mysqli_query($conn,"SELECT * FROM pagos WHERE id_cliente = '$id_cliente' AND descripcion LIKE '%$descripcion%'"); 
                  //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
                  if (mysqli_num_rows($pagos) == 0) {
                    echo '<h4>No se encontraron pagos.</h4>';
                  } else {
                    while ($pago = mysqli_fetch_array($pagos)) {
                      $id_usuario = $pago['id_user'];// ID DEL USUARIO REGISTRO
                      //Obtenemos la informacion del Usuario
                      $usuario = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_usuario"));
                      ?>
                      <tr>
                        <td><?php echo $pago['id_pago']; ?></td>
                        <td>$<?php echo sprintf('%.2f', $pago['cantidad']); ?></td>
                        <td><?php echo $pago['tipo']; ?></td>
                        <td><?php echo $pago['descripcion']; ?></td>
                        <td><?php echo $usuario['firstname']; ?></td>
                        <td><?php echo $pago['fecha'].' '.$pago['hora']; ?></td>
                        <td><?php echo $pago['tipo_cambio']; ?></td>
                        <td><a onclick="verificar_eliminar(<?php echo $id; ?>,<?php echo $pago['id_pago']; ?>)" class="btn-small red waves-effect waves-light"><i class="material-icons">delete</i></a></td>
                      </tr>
                      <?php
                    }//FIN while
                  }//FIN ELSE
                  ?>
                </tbody>
              </table>
            </div></p>
          </div>
        </div>
        <!-- ----------------------------  FORMULARIO 2 Tabs  ---------------------------------------->
        <div  id="test-swipe-2" class="col s12">
           <div class="row"><br><br>
            <a onclick="modal_nota(<?php echo $reservacion['id_habitacion'];?>)" class="btn-small grey darken-3  <?php echo ($reservacion['estatus'] == 0)? 'disabled': ''; ?>  waves-effect waves-light right"><i class="material-icons prefix left">note_add</i>Nueva Nota</a>
            <p><div>
              <table class="bordered centered highlight">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Descripción</th>
                    <th>Usuario</th>
                    <th>Fecha</th>            
                    <th>Editar</th>
                    <th>Borrar</th>
                  </tr>
                </thead>
                <div id="editNotas"></div>
                <tbody>
                  <?php
                  $notas = mysqli_query($conn,"SELECT * FROM notas WHERE id_reservacion = $id"); 
                  //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
                  if (mysqli_num_rows($notas) == 0) {
                    echo '<h4>No se encontraron notas.</h4>';
                  } else {
                    while ($nota = mysqli_fetch_array($notas)) {
                      $id_usuario = $nota['usuario'];// ID DEL USUARIO REGISTRO
                      //Obtenemos la informacion del Usuario
                      $usuario = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_usuario"));
                      ?>
                      <tr>
                        <td><?php echo $nota['id']; ?></td>
                        <td><?php echo $nota['descripcion']; ?></td>
                        <td><?php echo $usuario['firstname']; ?></td>
                        <td><?php echo $nota['fecha']; ?></td>
                        <td><a onclick="modal_nota_edit(<?php echo $reservacion['id_habitacion'];?>,<?php echo $nota['id']; ?>)" class="btn-small indigo waves-effect waves-light"><i class="material-icons">edit</i></a></td>
                        <td><a onclick="borrar_nota(<?php echo $id; ?>,<?php echo $nota['id']; ?>)" class="btn-small red waves-effect waves-light"><i class="material-icons">delete</i></a></td>
                      </tr>
                      <?php
                    }//FIN while
                  }//FIN ELSE
                  ?>
                </tbody>
              </table>
            </div></p>
          </div>
        </div>
      </div>      
    </div>
  </body>
  <?php
    }
  ?>
  </main>
</html>
