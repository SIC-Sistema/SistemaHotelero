<html>
  <head>
  	<title>SIC | Salida (Productos)</title>
    <?php 
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL USUARIO Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos_user
    $user_id = $_SESSION['user_id'];
    $datos_user = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id=$user_id"));
    ?>
    <script>
      function tmp_articulos(insert, id_art = 0){

        if (insert) {
          //PEDIMOS VARIABLES Y CONDICIONES PARA INSERTAR ARTICULO A TMP
          M.toast({html: 'Insertar articulo N° '+id_art, classes: 'rounded'});
          //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_salidas.php"
          $.post("../php/control_salidas.php", {
            //Cada valor se separa por una ,
              accion: 5,
              insert: insert,
              id_art: id_art,
            }, function(mensaje) {
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_salidas.php"
                $("#articulosSalida").html(mensaje);
            }); 
        }else{

          //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_salidas.php"
          $.post("../php/control_salidas.php", {
            //Cada valor se separa por una ,
              accion: 5,
              insert: insert,
            }, function(mensaje) {
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_salidas.php"
                $("#articulosSalida").html(mensaje);
            }); 
        }//FIN ELSE insert
      }// FIN function

      //FUNCION QUE BORRA LOS ARTICULOS TMP (SE ACTIVA AL INICIAR EL BOTON BORRAR)
      function borrar_lista_articulo(id){
        var answer = confirm("Deseas eliminar el artículo N°"+id+" de la lista ?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_salidas.php"
          $.post("../php/control_salidas.php", {
            //Cada valor se separa por una ,
            accion: 7,
            id: id,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_salidas.php"
            $("#articulosSalida").html(mensaje);
          }); //FIN post
        }//FIN IF
      };//FIN function

      //FUNCION QUE BORRA TODOS LOS ARTICULOS DE TMP (SE ACTIVA AL INICIAR EL BOTON BORRAR)
      function borrar_lista_all(usuario){
        var answer = confirm("Deseas cancelar la compra?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_salidas.php"
          $.post("../php/control_salidas.php", {
            //Cada valor se separa por una ,
            accion: 8,
            usuario: usuario,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_salidas.php"
            $("#articulosSalida").html(mensaje);
          }); //FIN post
        }//FIN IF
      };//FIN function

      //FUNCION QUE HACE LA BUSQUEDA DE ARTICULOS (SE ACTIVA AL INICIAR EL ARCHIVO O AL ECRIBIR ALGO EN EL BUSCADOR)
      function buscar_articulos(){
        
        //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO EL TEXTO REQUERIDO Y LO ASIGNAMOS A UNA VARIABLE
        var texto = $("input#busquedaArticulo").val();
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_salidas.php"
        $.post("../php/control_salidas.php", {
            //Cada valor se separa por una ,
            accion: 6,
            texto: texto,
          }, function(mensaje){
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_salidas.php"
              $("#tablaArticulo").html(mensaje);
        });//FIN post
      }//FIN function


      //FUNCION QUE HACE LA INSERCION DE LA VENTA (SE ACTIVA AL PRECIONAR UN BOTON)
      function insert_compra() {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_salidas.php"
        $.post("../php/control_salidas.php", {
            //Cada valor se separa por una ,
              accion: 10,
            }, function(mensaje) {
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_salidas.php"
                $("#resultado_insert").html(mensaje);
            }); 
      };//FIN function 
      //FUNCION QUE MODIFICARA LOS VALORES DE LOS TOTALES
      function totales(id_art){
        //RECIBIMOS LOS VALORES DE LOS INPUTS AFECTADOS
        var CantidadA = $("input#cantidadA"+id_art).val();
        
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_salidas.php"
        $.post("../php/control_salidas.php", {
            //Cada valor se separa por una ,
            accion: 9,
            valorIdArt: id_art,
            valorCantidadA: CantidadA,
          }, function(mensaje){
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_salidas.php"
              $("#resultado_insert").html(mensaje);
        });//FIN post
      }
    </script>
  </head>
  <main>
  <body onload="tmp_articulos(0);">
    <!-- DENTRO DE ESTE DIV VA TODO EL CONTENIDO Y HACE QUE SE VEA AL CENTRO DE LA PANTALLA.-->
    <div class="container" >
      <!--    //////    TITULO    ///////   -->
      <div class="row" >
        <h3 class="hide-on-med-and-down">Salida (Productos)</h3>
        <h5 class="hide-on-large-only">Salida (Productos)</h5>
      </div>
      <div class="row" >
        <!-- CREAMOS UN DIV EL CUAL TENGA id = "resultado_insert"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
        <div class="row" id="resultado_insert"></div>
        <!-- FORMULARIO EL CUAL SE MUETRA EN PANTALLA .-->
        <form class="row col s12" name="formSalidas">
  
            <hr>            
            <a href="#modal_addArticulo" class="waves-effect waves-light btn-small modal-trigger pink right">Agregar Artículo<i class="material-icons left">add</i></a><br><br>
            <!-- CREAMOS UN DIV EL CUAL TENGA id = "articulosSalida"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
            <div class="row" id="articulosSalida">
            </div>
        </form>
      </div> 
    </div><br>
  </body>
  </main>
</html>