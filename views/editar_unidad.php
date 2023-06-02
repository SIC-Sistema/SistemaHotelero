<?php
//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL CLIENTE
if (isset($_POST['idunidad']) == false) {
  ?>
  <script>    
    M.toast({html: 'Regresando a unidades.', classes: 'rounded'});
    setTimeout("location.href='unidades.php'", 800);
  </script>
  <?php
}else{
?>
	<html>
	<head>
		<title>San Roman | Editar Unidad</title>
	  <?php 
	  //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
	  include('fredyNav.php');

	  $id = $_POST['idunidad'];// POR EL METODO POST RECIBIMOS EL ID DEL CLIENTE

      //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL CLIENTE Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
      $datos = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM unidades WHERE idunidad=$id"));
	  ?>

	  <script>
	    //FUNCION QUE HACE LA INSERCION DEL CLIENTE (SE ACTIVA AL PRECIONAR UN BOTON)
	    function update_unidad(id) {

	      	var nuevoTipo 	= $("input#uTipUnidad").val();
	      	var nuevoClave 	= $("input#uClaUnidad").val();
	      	var nuevoNombre = $("input#uNomUnidad").val();

	      // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
	      //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
	      if (nuevoTipo == "") {
	        M.toast({html: 'El campo Tipo se encuentra vacío.', classes: 'rounded'});
	      }else if(nuevoClave == ''){
	        M.toast({html: 'El campo Clave no puede quedar vacío.', classes: 'rounded'});
	      }else if(nuevoNombre == ''){
	        M.toast({html: 'El campo Nombre no puede quedar vacío.', classes: 'rounded'});
		  }else{
	        //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
	        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_inventario.php"
	        $.post("../php/control_unidades.php", {
	          //Cada valor se separa por una ,
	            accion: 2,
	            id: id,
	            valorTipo	: nuevoTipo,
	            valorClave	: nuevoClave,
	            valorNombre	: nuevoNombre,

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
	  <div class="container">
	    <!--    //////    TITULO    ///////   -->
	    <div class="row" >
	      <h3 class="hide-on-med-and-down">Editar Unidad</h3>
	      <h5 class="hide-on-large-only">Editar Unidad</h5>
	    </div>
	    <div class="row" >
	     <!-- CREAMOS UN DIV EL CUAL TENGA id = "resultado_update"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
	     <div id="resultado_update"></div>
	     <div class="row">
	      <!-- FORMULARIO EL CUAL SE MUETRA EN PANTALLA .-->
	      <form class="row col s12">
	        <!-- DIV QUE SEPARA A DOBLE COLUMNA PARTE IZQ.-->
	        <div class="col s12 m6 l6">
	          
	          
				<input hidden id="uIdUnidad" type="text" class="validate" data-length="50" required value="<?php echo $datos['idunidad']; ?>">
	            
				<div class="input-field">
					<i class="material-icons prefix">edit</i>
					<input id="uTipUnidad" type="text" class="validate" data-length="13" required value="<?php echo $datos['tipo']; ?>">
					<label for="uTipUnidad">Tipo:</label>
				</div>

				<div class="input-field">
					<i class="material-icons prefix">local_offer</i>
					<input id="uClaUnidad" type="text"  class="validate" data-length="100" required value="<?php echo $datos['clave']; ?>">
					<label for="uClaUnidad">Clave:</label>
				</div>

	        </div>
	        <!-- DIV DOBLE COLUMNA EN ESCRITORIO PARTE DERECHA -->
	        <div class="col s12 m6 l6">
	          
				<div class="input-field">
					<i class="material-icons prefix">loupe</i>
					<input id="uNomUnidad" type="text"  class="validate" data-length="100" required value="<?php echo $datos['nombre']; ?>">
					<label for="uNomUnidad">Nombre:</label>
				</div>

	        </div>
	      </form>
	      <!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPR HAGA LO QUE LA FUNCION CONTENGA -->
	      <a onclick="update_unidad(<?php echo $datos['idunidad']; ?>);" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">save</i>Actualizar</a>
	    </div> 
	  </div><br>
	</body>
	</main>
	</html>
<?php
}// FIN else POST
?>
