
<!DOCTYPE html>
<html>
<head>
    <title>San Roman | Habitaciones</title>
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
	    M.toast({html:"Regresando a habitaciones.", classes: "rounded"});
	    setTimeout("location.href='habitaciones.php'", 500);
	  </script>
	  <?php
	}else{
		$id = $_GET['id'];// POR EL METODO POST RECIBIMOS EL ID DEL HABITACION
	    //CONSULTA PARA SACAR LA INFORMACION DE LA HABITACION Y ASIGNAMOS EL ARRAY A UNA VARIABLE $habitacion
	    $habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id"));
	}
	?>
	<script>
	
	</script>
</head>
<body>
	<div class="container">
		<div class="row"><br>
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
              		<div class="col s12 m11 l9"><b class="indigo-text">ESTADO: </b><span class="new badge <?php echo ($habitacion['estatus'] == 0)?'green':'red';?> prefix" data-badge-caption=""><?php echo ($habitacion['estatus'] == 0)?'Disponible':'Ocupada';?></span></div>          
              		<div class="col s12"><b class="indigo-text">PRECIO: </b><?php echo '$'.sprintf('%.2f', $habitacion['precio']);?></div>          		
              	</div>
              </b></p><br><br>
            </li>
          </ul>
        </div>
        <div id="modal"></div>
        <h5 class="center"><b>Acciones:</b></h5>
        <hr>
        <div class="row col s12">
        	<div class="hide-on-med-and-down row col s2 "><br></div>
        	<div class="hide-on-large-only hide-on-small-only row col s1"><br></div>
	        <table class="row col s12 m10 l8">
	        	<thead>
	        		<tr>
	        			<th>Reservar</th>
	        			<th>Nota</th>
	        			<th>Mto.</th>
	        			<th>Editar</th>
	        			<th>Borrar</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		<tr>
	        			<td><a onclick="" class="btn-small green waves-effect waves-light"><i class="material-icons">event</i></td>
	        			<td><a onclick="" class="btn-small amber waves-effect waves-light"><i class="material-icons">note_add</i></a></td>
	        			<td><a onclick="" class="btn-small indigo waves-effect waves-light"><i class="material-icons">settings</i></a></td>
	        			<td><a href="edit_habitacion.php?id=<?php echo $id;?>" class="btn-small grey darken-3 waves-effect waves-light"><i class="material-icons">edit</i></a></td>
	        			<td><a onclick="" class="btn-small red darken-1 waves-effect waves-light"><i class="material-icons">delete</i></a></td>
	        		</tr>
	        	</tbody>
	        </table>
	    </div>
	    <h4>Historial:</h4>
	    <div class="row">
	    <!-- ----------------------------  TABs o MENU  ---------------------------------------->
	      <div class="col s12">
	        <ul id="tabs-swipe-demo" class="tabs">
	          <li class="tab col s6"><a class="active black-text" href="#test-swipe-1">RESERVACIONES</a></li>
	          <li class="tab col s6"><a class="black-text" href="#test-swipe-2">MANTENIMIENTOS</a></li>
	        </ul>
	      </div>
	      <!-- ----------------------------  FORMULARIO 1 Tabs  ---------------------------------------->
	      <div  id="test-swipe-1" class="col s12">
	        <div class="row">
	          <p><div>
	            <table class="bordered centered highlight">
	              <thead>
	                <tr>
	                  <th>N°</th>
	                  <th>Cliente</th>
	                  <th>Precio</th>
	                  <th>Fecha Entrada</th>            
	                  <th>Fecha Salida</th>
	                  <th>Estatus</th>
	                </tr>
	              </thead>
	              <tbody>
	                
	              </tbody>
	            </table>
	          </div></p>
	        </div>
	      </div>
	      <!-- ----------------------------  FORMULARIO 2 Tabs  ---------------------------------------->
	      <div  id="test-swipe-2" class="col s12">
	        <div class="row">
	          <p><div>
	            <table class="bordered centered highlight">
	              <thead>
	                <tr>
	                  <th>N°</th>
	                  <th>Descripcion</th>
	                  <th>Fecha</th>
	                  <th>Estatus</th> 
	                </tr>
	              </thead>
	              <tbody>
	                
	              </tbody>
	            </table>
	          </div></p>
	        </div>
	      </div>
	    </div>
	</div>

</body>
</html>