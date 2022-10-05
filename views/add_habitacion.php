<html>
<head>
  <title>San Roman | Agregar Habitación</title>
  <?php 
  //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
  include('fredyNav.php');
  ?>
  <script>
    //FUNCION QUE HACE LA INSERCION DEL CLIENTE (SE ACTIVA AL PRECIONAR UN BOTON)
    function insert_habitacion() {

      //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO LA INFORMCION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
      var textoNo = $("input#numero").val();//ej:LA VARIABLE "textoNombre" GUARDAREMOS LA INFORMACION QUE ESTE EN EL INPUT QUE TENGA EL id = "nombre"
      var textoDescripcion = $("input#descripcion").val();// ej: TRAE LE INFORMACION DEL INPUT FILA 95 (id="telefono")
      var textoPiso = $("select#piso").val();

      // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
      //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
      if(textoNo < 0 || textoNo == ''){
        M.toast({html: 'Por favor ingrese un numero de habitación.', classes: 'rounded'});
      }else if(textoDescripcion == ""){
        M.toast({html:"Por favor ingrese una Descripción.", classes: "rounded"});
      }else if (textoPiso == 0) {
        M.toast({html:"Por favor seleccione un Piso.", classes: "rounded"});
      }else{
        //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_habitaciones.php"
        $.post("../php/control_habitaciones.php", {
          //Cada valor se separa por una ,
            accion: 0,
            valorNo: textoNo,
            valorDescripcion: textoDescripcion,
            valorPiso: textoPiso,
          }, function(mensaje) {
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
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
      <h3 class="hide-on-med-and-down">Registrar Habitación</h3>
      <h5 class="hide-on-large-only">Registrar Habitación</h5>
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
            <i class="material-icons prefix">key</i>
            <input id="numero" type="number" class="validate"  required>
            <label for="numero">N° Habitación:</label>
          </div>      
          <div class="input-field">
            <i class="material-icons prefix">edit</i>
            <input id="descripcion" type="text" class="validate" data-length="100" required>
            <label for="descripcion">Descripción:</label>
          </div>         
        </div>
        <!-- DIV DOBLE COLUMNA EN ESCRITORIO PARTE DERECHA -->
        <div class="col s12 m6 l6">
          <br>
          <div class="input-field">
            <i class="material-icons prefix">location_city</i>
            <select id="piso" name="piso" class="validate">              
              <!--OPTION PARA QUE LA SELECCION QUEDE POR DEFECTO-->
              <option value="0" select>Seleccione un piso</option>
              <option value="Primer">Primer</option>
              <option value="Segundo">Segundo</option>
              <option value="Tercer">Tercer</option>
              <option value="Cuarto">Cuarto</option>
            </select>
          </div>
        </div>
      </form>
      <!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPR HAGA LO QUE LA FUNCION CONTENGA -->
      <a onclick="insert_habitacion();" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">add</i>Agregar</a>
    </div> 
  </div><br>
</body>
</main>
</html>
