<?php
//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL CLIENTE
if (isset($_POST['id']) == false) {
  ?>
  <script>    
    M.toast({html: "Regresando a articulos.", classes: "rounded"});
    setTimeout("location.href='articulos.php'", 800);
  </script>
  <?php
}else{
?>
	<html>
	<head>
		<title>San Roman | Editar Articulo</title>
	  <?php 
	  //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
	  include('fredyNav.php');
	  $id = $_POST['id'];// POR EL METODO POST RECIBIMOS EL ID DEL CLIENTE
      //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL CLIENTE Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
      $datos = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `articulos` WHERE id=$id"));
	  ?>
	  <script>
	    //FUNCION QUE HACE LA INSERCION DEL CLIENTE (SE ACTIVA AL PRECIONAR UN BOTON)
	    function update_articulo(id) {

	      //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO LA INFORMCION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
	      var textoNombre = $("input#nombre").val();//ej:LA VARIABLE "textoNombre" GUARDAREMOS LA INFORMACION QUE ESTE EN EL INPUT QUE TENGA EL id = "nombre"
	      var textoCodigo = $("input#codigo").val();// ej: TRAE LE INFORMACION DEL INPUT (id="codigo")
	      var textoUnidad = $("input#unidad").val();

	      // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
	      //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
	      if (textoNombre == "") {
	        M.toast({html: 'El campo Nombre se encuentra vacío.', classes: 'rounded'});
	      }else if(textoUnidad == ''){
	        M.toast({html: 'El Unidad tiene que tener una unidad.', classes: 'rounded'});
	      }else{
	        //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
	        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_inventario.php"
	        $.post("../php/control_inventario.php", {
	          //Cada valor se separa por una ,
	            accion: 2,
	            id: id,
	            valorNombre: textoNombre,
	            valorCodigo: textoCodigo,
	            valorUnidad: textoUnidad,
	          }, function(mensaje) {
	              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_inventario.php"
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
	      <h3 class="hide-on-med-and-down">Registrar Articulo</h3>
	      <h5 class="hide-on-large-only">Registrar Articulo</h5>
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
	            <i class="material-icons prefix">vpn_key</i>
	            <input id="codigo" type="text" class="validate" data-length="50" required value="<?php echo $datos['codigo']; ?>">
	            <label for="codigo">Codigo:</label>
	          </div>      
	          <div class="input-field">
	            <i class="material-icons prefix">edit</i>
	            <input id="nombre" type="text" class="validate" data-length="13" required value="<?php echo $datos['nombre']; ?>">
	            <label for="nombre">Nombre:</label>
	          </div> 
	        </div>
	        <!-- DIV DOBLE COLUMNA EN ESCRITORIO PARTE DERECHA -->
	        <div class="col s12 m6 l6">
	          <br>
	          <div class="input-field">
	            <i class="material-icons prefix">local_offer</i>
	            <input id="unidad" type="text"  class="validate" data-length="100" required value="<?php echo $datos['unidad']; ?>">
	            <label for="unidad">Unidad (pz, kg, etc.):</label>
	          </div>
	        </div>
	      </form>
	      <!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPR HAGA LO QUE LA FUNCION CONTENGA -->
	      <a onclick="update_articulo(<?php echo $datos['id']; ?>);" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">save</i>Guardar</a>
	    </div> 
	  </div><br>
	</body>
	</main>
	</html>
<?php
}// FIN else POST
?>
