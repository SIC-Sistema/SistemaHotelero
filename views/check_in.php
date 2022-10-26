<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    ?>
    <title>San Roman | Check-in</title>
    <script>
      //FUNCION QUE HACE LA BUSQUEDA DE CLIENTES (SE ACTIVA AL INICIAR EL ARCHIVO O AL ECRIBIR ALGO EN EL BUSCADOR)
      function buscar_reservaciones(){
        //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO EL TEXTO REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
        var texto = $("input#busqueda").val();
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_reservaciones.php"
        $.post("../php/control_reservaciones.php", {
          //Cada valor se separa por una ,
            texto: texto,
            accion: 4,
          }, function(mensaje){
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
              $("#reservacionesPendientes").html(mensaje);
        });//FIN post
      };//FIN function

      //FUNCION QUE BORRA EL CLIENTES (SE ACTIVA AL INICIAR EL BOTON BORRAR)
      function cancelar_reservacion(id){
        var answer = confirm("Deseas cancelar la reservacion N°"+id+"?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_reservaciones.php"
          $.post("../php/control_reservaciones.php", { 
            //Cada valor se separa por una ,
              id: id,
              accion: 5,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
            $("#cancelarRe").html(mensaje);
          }); //FIN post
        }//FIN IF
      };//FIN function

      //FUNCION QUE MUESTRA EL MODAL HACER CHECK IN
      function modal_check_in(id){
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "modal_check_in.php" PARA MOSTRAR EL MODAL
          $.post("modal_check_in.php", {
            //Cada valor se separa por una ,
              id:id,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "modal_check_in.php"
                $("#modal").html(mensaje);
          });//FIN post
      }//FIN function

      function check_in(id) {
        var answer = confirm("Deseas realizar Check-in a la reservacion N°"+id+"?");
        if (answer) {
          var textoAbono = $("input#abonoR").val();

          if(document.getElementById('bancoR').checked==true){
              tipo_cambio = 'Banco';
          }else if (document.getElementById('creditoR').checked==true) {
              tipo_cambio = 'Credito';
          }else{
              tipo_cambio = 'Efectivo';
          }
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_reservaciones.php"
          $.post("../php/control_reservaciones.php", { 
            //Cada valor se separa por una ,
              id: id,
              abonoR: textoAbono,
              tipo_cambio: tipo_cambio,
              accion: 11,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
            $("#modal").html(mensaje);
          }); //FIN post
        }//FIN IF
      }
    </script>
  </head>
  <main>
  <body onload="buscar_reservaciones();">
    <div class="container"><br><br>      
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "cancelarRe"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="cancelarRe"></div>
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "modal"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="modal"></div>
      <div class="row">
        <!--    //////    TITULO    ///////   -->
        <h3 class="hide-on-med-and-down col s12 m6 l6">Check-in</h3>
        <h5 class="hide-on-large-only col s12 m6 l6">Check-in</h5>
        <!--    //////    INPUT DE EL BUSCADOR    ///////   -->
        <form class="col s12 m6 l6"><br><br>
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">search</i>
              <input id="busqueda" name="busqueda" type="text" class="validate" onkeyup="buscar_reservaciones();">
              <label for="busqueda">Buscar(N° Cliente, N° Habitacion , Nombre Responsable)</label>
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
              <th>Cliente</th>
              <th>Habitacion</th>
              <th>Responsable</th>
              <th>Fecha Entrada</th>
              <th>Fecha Salida</th>
              <th>Observacion</th>
              <th>Registro</th>
              <th>Fecha Registro</th>
              <th>Check-in</th>
              <th>Cancelar</th>
            </tr>
          </thead>
          <!-- DENTRO DEL tbody COLOCAMOS id = "reservacionesPendientes"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION buscar_reservaciones() -->
          <tbody id="reservacionesPendientes">
          </tbody>
        </table>
      </div><br><br>
    </div>
  </body>
  </main>
</html>