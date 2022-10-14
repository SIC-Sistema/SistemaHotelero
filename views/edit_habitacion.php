<?php
//INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
include('fredyNav.php');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
//Obtenemos la informacion del Usuario
$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));

//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL ARTICULO
if (isset($_GET['id']) == false OR $User['habitaciones'] == 0) {
  ?>
  <script>    
    M.toast({html: "Regresando a lista de habitaciones.", classes: "rounded"});
    setTimeout("location.href='habitaciones.php'", 800);
  </script>
  <?php
}else{
?>
  <html>
  <head>
    <title>San Roman | Editar Habitación</title>
    <?php 
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    $id = $_GET['id'];// POR EL METODO POST RECIBIMOS EL ID DEL ARTICULO
    //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL ARTICULO Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
    $Habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id"));
    ?>
    <script>
      //FUNCION QUE HACE LA ACTUALIZACION DE LA CATEGORIA (SE ACTIVA AL PRECIONAR UN BOTON)
      function update_habitacion(id) {
        //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO LA INFORMCION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
      var textoNo = $("input#numero").val();//ej:LA VARIABLE "textoNo" GUARDAREMOS LA INFORMACION QUE ESTE EN EL INPUT QUE TENGA EL id = "numero"
      var textoDescripcion = $("input#descripcion").val();// ej: TRAE LE INFORMACION DEL INPUT FILA 95 (id="descripcion")
      var textoPrecio = $("input#precio").val();// 
      var textoPiso = $("select#piso").val();
      var textoTipo = $("select#tipo").val();

      // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
      //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
      if(textoNo < 0 || textoNo == ''){
        M.toast({html: 'Por favor ingrese un numero de habitación.', classes: 'rounded'});
      }else if(textoDescripcion == ""){
        M.toast({html:"Por favor ingrese una Descripción.", classes: "rounded"});
      }else if(textoPrecio < 0 || textoPrecio == ''){
        M.toast({html:"Por favor ingrese un precio.", classes: "rounded"});
      }else if (textoPiso == 0) {
        M.toast({html:"Por favor seleccione un Piso.", classes: "rounded"});
      }else if(textoTipo == 0){
        M.toast({html:"Por favor seleccione un Tipo Habitación.", classes: "rounded"});
      }else{
        //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_habitaciones.php"
        $.post("../php/control_habitaciones.php", {
          //Cada valor se separa por una ,
            accion: 1,
            id: id,
            valorNo: textoNo,
            valorDescripcion: textoDescripcion,
            valorPrecio: textoPrecio,
            valorPiso: textoPiso,
            valorTipo: textoTipo,
          }, function(mensaje) {
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
              $("#resultado_update").html(mensaje);
          }); 
      }//FIN else CONDICIONES
    };//FIN function 
    </script>
  </head>
  <main>
  <body>
    <!-- DENTRO DE ESTE DIV VA TODO EL CONTENIDO Y HACE QUE SE VEA AL CENTRO DE LA PANTALLA.-->
    <div class="container"><br><br>
      <!--    //////    TITULO    ///////   -->
      <div class="row" >
        <h3 class="hide-on-med-and-down">Editar Habitación N°<?php echo $id; ?></h3>
        <h5 class="hide-on-large-only">Editar Habitación N°<?php echo $id; ?></h5>
      </div>
      <div class="row" >
       <!-- CREAMOS UN DIV EL CUAL TENGA id = "resultado_update"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
       <div id="resultado_update"></div>
       <div class="row">
        <!-- FORMULARIO EL CUAL SE MUETRA EN PANTALLA .-->
        <form class="row col s12">
          <!-- DIV QUE SEPARA A DOBLE COLUMNA PARTE IZQ.-->
          <div class="col s12 m6 l6">
            <br>
            <div class="input-field">
              <i class="material-icons prefix">local_offer</i>
              <input id="numero" type="number" class="validate" value="<?php echo $id;?>" required>
              <label for="numero">N° Habitación:</label>
            </div>      
            <div class="input-field">
              <i class="material-icons prefix">edit</i>
              <input id="descripcion" type="text" class="validate" data-length="100" value="<?php echo $Habitacion['descripcion'];?>" required>
              <label for="descripcion">Descripción:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">monetization_on</i>
              <input id="precio" type="number" class="validate" value="<?php echo $Habitacion['precio'];?>"  required>
              <label for="precio">Precio:</label>
            </div>         
          </div>
          <!-- DIV DOBLE COLUMNA EN ESCRITORIO PARTE DERECHA -->
          <div class="col s12 m6 l6">
            <br>
            <div class="input-field">
              <i class="material-icons prefix">location_city</i>
              <select id="piso" name="piso" class="validate">              
                <!--OPTION PARA QUE LA SELECCION QUEDE POR DEFECTO-->
                <option value="<?php echo $Habitacion['piso'];?>" select><?php echo $Habitacion['piso'];?></option>
                <option value="Primer">Primer</option>
                <option value="Segundo">Segundo</option>
                <option value="Tercer">Tercer</option>
                <option value="Cuarto">Cuarto</option>
              </select>
            </div>
          </div>
          <div class="col s12 m6 l6">
            <br>
            <div class="input-field">
              <i class="material-icons prefix">hotel</i>
              <select id="tipo" name="tipo" class="validate">              
                <!--OPTION PARA QUE LA SELECCION QUEDE POR DEFECTO-->
                <option value="<?php echo $Habitacion['tipo'];?>" select><?php echo $Habitacion['tipo'];?></option>              
                <option value="Individual">Individual</option>
                <option value="Doble">Doble</option>
                <option value="King Size">King Size</option>
              </select>
            </div>
          </div>
        </form>
        <!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPT HAGA LO QUE LA FUNCION CONTENGA -->
        <a onclick="update_habitacion(<?php echo $id; ?>);" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">save</i>Guardar</a>
      </div> 
    </div><br>
  </body>
  </main>
  </html>
<?php
}// FIN else POST
?>