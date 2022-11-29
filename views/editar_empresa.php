<?php
//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL CLIENTE
if (isset($_POST['id']) == false) {
  ?>
  <script>    
    M.toast({html: "Regresando a listado de empresas.", classes: "rounded"});
    setTimeout("location.href='empresas.php'", 800);
  </script>
  <?php
}else{
?>
  <html>
  <head>
  	<title>San Roman | Editar Empresa</title>
    <?php 
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    $id = $_POST['id'];// POR EL METODO POST RECIBIMOS EL ID DEL CLIENTE
    //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL CLIENTE Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
    $datos = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `empresas` WHERE id=$id"));
    ?>
    <script>

      //FUNCION QUE HACE LA INSERCION DEL CLIENTE (SE ACTIVA AL PRECIONAR UN BOTON)
      function update_empresa(id) {

        //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO LA INFORMCION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
        var textoRazonSocial = $("input#razon_social").val();//ej:LA VARIABLE "textorazon_social" GUARDAREMOS LA INFORMACION QUE ESTE EN EL INPUT QUE TENGA EL id = "razon_social"
        var textoRFC = $("input#rfc").val();
        var textoDireccion = $("input#direccion").val();
        var textoCP = $("input#cp").val();

        // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
        //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
        if (textoRazonSocial == "") {
          M.toast({html: 'El campo Razon Social se encuentra vacío.', classes: 'rounded'});
        }else if(textoRFC.length < 12){
          M.toast({html: 'El RFC tiene que tener al menos 12 dijitos.', classes: 'rounded'});
        }else if(textoCP == ""){
          M.toast({html: 'El campo Codigo Postal se encuentra vacío.', classes: 'rounded'});
        }else{
          //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_empresas.php"
          $.post("../php/control_empresas.php", {
            //Cada valor se separa por una ,
              accion: 2,
              id: id,
              valorRazonSocial: textoRazonSocial,
              valorRFC: textoRFC,
              valorDireccion: textoDireccion,
              valorCP: textoCP,
            }, function(mensaje) {
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_empresas.php"
                $("#resultado_insert").html(mensaje);
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
        <h3 class="hide-on-med-and-down">Editar Empresa N°<?php echo $id; ?></h3>
        <h5 class="hide-on-large-only">Editar Empresa N°<?php echo $id; ?></h5>
      </div>
      <div class="row" >
       <!-- CREAMOS UN DIV EL CUAL TENGA id = "resultado_insert"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
       <div id="resultado_insert"></div>
       <div class="row">
        <!-- FORMULARIO EL CUAL SE MUETRA EN PANTALLA .-->
        <form class="row col s12">
          <!-- DIV QUE SEPARA A DOBLE COLUMNA PARTE IZQ.-->
          <div class="col s12 m6 l6">
            <br>
            <div class="input-field">
              <i class="material-icons prefix">people</i>
              <input id="razon_social" type="text" class="validate" data-length="50" required value="<?php echo $datos['razon_social']; ?>" >
              <label for="razon_social">Razon Social:</label>
            </div>   
            <div class="input-field">
              <i class="material-icons prefix">person</i>
              <input id="rfc" type="text" class="validate" data-length="15" required value="<?php echo $datos['rfc']; ?>">
              <label for="rfc">RFC:</label>
            </div>      
          </div>
          <!-- DIV DOBLE COLUMNA EN ESCRITORIO PARTE DERECHA -->
          <div class="col s12 m6 l6">
            <br>
            <div class="input-field">
              <i class="material-icons prefix">location_on</i>
              <input id="direccion" type="text"  class="validate" data-length="100" required value="<?php echo $datos['direccion']; ?>">
              <label for="direccion">Direccion:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">location_on</i>
              <input id="cp" type="number" class="validate" data-length="6" required value="<?php echo $datos['cp']; ?>">
              <label for="cp">Codigo Postal:</label>
            </div>
          </div>
        </form>
        <!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPR HAGA LO QUE LA FUNCION CONTENGA -->
        <a onclick="update_empresa(<?php echo $id; ?>);" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">save</i>Guardar</a>
      </div> 
    </div><br>
  </body>
  </main>
  </html>
<?php
}
?>