<html>
<head>
  <title>San Roman | Generar Salida</title>
  <?php 
  //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
  include('fredyNav.php');
  ?>
  <script>
    //FUNCION QUE HACE LA INSERCION DE UNA SALIDA (SE ACTIVA AL PRECIONAR UN BOTON)
    function insert_salida() {

      //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO LA INFORMCION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
      var textoCantidad = $("input#cantidadSalida").val();//ej:LA VARIABLE "textoCantidad" GUARDAREMOS LA INFORMACION QUE ESTE EN EL INPUT QUE TENGA EL id = "cantidadSalida"
      var textoMotivo = $("input#motivoSalida").val();// 

      // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
      //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
      if(textoCantidad < 0 || textoCantidad == ''){
        M.toast({html: 'Por favor ingrese una cantidad valida.', classes: 'rounded'});
      }else if(textoMotivo == ""){
        M.toast({html:"Por favor ingrese una Descripción.", classes: "rounded"});
      }else{
        //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_dinero.php"
        $.post("../php/control_dinero.php", {
          //Cada valor se separa por una ,
            accion: 0,
            valorCantidad: textoCantidad,
            valorMotivo: textoMotivo,
          }, function(mensaje) {
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_dinero.php"
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
      <h3 class="hide-on-med-and-down">Registrar Salida/Egreso Efectivo</h3>
      <h5 class="hide-on-large-only">Registrar Salida/Egreso Efectivo</h5>
    </div>
    <div class="row" >
     <!-- CREAMOS UN DIV EL CUAL TENGA id = "resultado_insert"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
     <div id="resultado_insert"></div>
     <div class="row">
      <!-- FORMULARIO EL CUAL SE MUETRA EN PANTALLA .-->
      <form class="row col s12">
        <!-- DIV QUE SEPARA A DOBLE COLUMNA PARTE IZQ.-->
        <div class="col s12 m5">
          <br>
          <div class="input-field">
            <i class="material-icons prefix">attach_money</i>
            <input id="cantidadSalida" type="number" class="validate"  required>
            <label for="cantidadSalida">Cantidad:</label>
          </div>          
        </div>
        <!-- DIV DOBLE COLUMNA EN ESCRITORIO PARTE DERECHA -->
        <div class="col s12 m7"><br>
          <div class="input-field">
            <i class="material-icons prefix">edit</i>
            <input id="motivoSalida" type="text" class="validate" data-length="100" required>
            <label for="motivoSalida">Motivo(ej: Pago de...):</label>
          </div>
        </div>
        
      </form>
      <!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPR HAGA LO QUE LA FUNCION CONTENGA -->
      <a onclick="insert_salida();" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">add</i>Agregar</a>
    </div> 
  </div><br>
</body>
</main>
</html>
