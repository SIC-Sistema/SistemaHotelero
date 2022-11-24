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

       //FUNCION QUE MUESTRA EL MODAL PARA EDITAR UNA NOTA
      function modal_mto_edit(id_hab, id_mto){
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "modal_nota.php" PARA MOSTRAR EL MODAL
          $.post("modal_editar_mto.php", {
            //Cada valor se separa por una ,
              id_hab:id_hab,
              id_mto:id_mto,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "modal_nota.php"
                $("#modal").html(mensaje);
          });//FIN post
      }//FIN function

      //FUNCION QUE MUESTRA EL MODAL PARA  EDITAR UNA NOTA
      function editar_mto(id_hab, id_mto){          
        var DescripcionMto = $("input#descripcionMto").val();

        if (DescripcionMto == '') {
          M.toast({html:"Porfavor ingrese una descripción al mantenimiento", classes: "rounded"});
        }else{
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_habitaciones.php" PARA MOSTRAR EL MODAL
          $.post("../php/control_habitaciones.php", {
            //Cada valor se separa por una ,
              id_mto: id_mto,
              id_hab: id_hab,
              valorMto: DescripcionMto,
              accion: 6,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
                $("#modal").html(mensaje);
          });//FIN post
        }//FIN else
      }//FIN function


      //FUNCION QUE BORRA EL CLIENTES (SE ACTIVA AL INICIAR EL BOTON BORRAR)
      function borrar_mantenimiento(id){
        var answer = confirm("Deseas eliminar el Mantenimientos N°"+id+"?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_habitaciones.php"
          $.post("../php/control_habitaciones.php", { 
            //Cada valor se separa por una ,
              id: id,
              accion: 5,
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
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "modal"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="modal"></div>
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
      <a href="../php/imprimir_mtos.php" target="blank" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons left">picture_as_pdf</i>Imprimir</a>
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
              <th>Atender</th>
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