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
    <div class="container"><br>  
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "modal"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="modal"></div>

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
                  <div class="col s12"><b class="indigo-text">N° HABITACION: </b><?php echo $id;?></div>
                  <div class="col s12"><b class="indigo-text">DESCRIPCION: </b><?php echo $habitacion['descripcion'];?></div>               
                </div>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">TIPO DE HABITACION: </b><?php echo $habitacion['tipo'];?></div>               
                  <div class="col s12"><b class="indigo-text">NIVEL / PISO: </b><?php echo $habitacion['piso'];?></div>               
                </div>
                <div class="col s12 m4">
                  <div class="col s12 m11 l9"><b class="indigo-text">ESTADO: </b><?php echo $estatus;?></span></div>             
                </div>
              </b></p><br><br><br><div class="hide-on-med-and-up"><br><br><br></div>
              <hr><br>
              <b> MANTENIMIENTO: </b> <?php echo $mantenimiento['descripcion'];?><br><br>
            </li>
        </ul>
      </div>     
    </div>
  </body>
  <?php
    }
  ?>
  </main>
</html>
