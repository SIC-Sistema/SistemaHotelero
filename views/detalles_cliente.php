<!DOCTYPE html>
<html>
<head>
    <title>San Roman | Cliente</title>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
		$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
    //Obtenemos la informacion del Usuario
    $User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));

		//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL ARTICULO
		if (isset($_POST['id']) == false OR $User['clientes'] == 0) {
		  ?>
		  <script>    
		    M.toast({html:"Regresando a clientes.", classes: "rounded"});
		    setTimeout("location.href='clientes.php'", 500);
		  </script>
		  <?php
		}else{
			$id = $_POST['id'];// POR EL METODO POST RECIBIMOS EL ID DEL cliente
		  //CONSULTA PARA SACAR LA INFORMACION DE LA cliente Y ASIGNAMOS EL ARRAY A UNA VARIABLE $cliente
		  $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id"));
		}
		?>
		<script>
			function verificar_eliminar(IdPago){ 
        var textoIdCliente = $("input#id_cliente").val();  
        $.post("../views/verificar_eliminar_pago.php", {
              valorIdPago: IdPago,
              redireciona: 0,
            }, function(mensaje) {
                $("#editPagos").html(mensaje);
            }); 
       };
		</script>
</head>
<body>
	<div class="container">
		<div class="row"><br>
   		  <ul class="collection">
            <li class="collection-item avatar">
              <div class="hide-on-large-only"><br><br></div>
              <img src="../img/cliente.png" alt="" class="circle">
              <span class="title"><b>DETALLES DEL CLIENTE</b></span><br><br>
              <p class="row col s12"><b>
              	<div class="col s12 m4">
              		<div class="col s12"><b class="indigo-text">N° CLIENTE: </b><?php echo $id;?></div>
              		<div class="col s12"><b class="indigo-text">NOMBRE: </b><?php echo $cliente['nombre'];?></div>           		
              		<div class="col s12"><b class="indigo-text">RFC: </b><?php echo $cliente['rfc'];?></div>           		
              		<div class="col s12"><b class="indigo-text">TELEFONO: </b><?php echo $cliente['telefono'];?></div>            		
              	</div>
              	<div class="col s12 m4">
              		<div class="col s12"><b class="indigo-text">EMAIL: </b><?php echo $cliente['email'];?></div>           		
              		<div class="col s12"><b class="indigo-text">LIMPIEZA: </b><?php echo $cliente['limpieza'];?></div>           		
              		<div class="col s12"><b class="indigo-text">CODIGO POSTAL: </b><?php echo $cliente['cp'];?></div>           		
              	</div>
              	<div class="col s12 m4">       
              		<div class="col s12"><b class="indigo-text">DIRECCION: </b><?php echo $cliente['direccion'];?></div>          		
              		<div class="col s12"><b class="indigo-text">COLONIA: </b><?php echo $cliente['colonia'];?></div>          		
              		<div class="col s12"><b class="indigo-text">LOCALIDAD: </b><?php echo $cliente['localidad'];?></div>          		
              	</div>
              </b></p><br><br><br><br>
            </li>
          </ul>
        </div>
        
	    <h4>Historial:</h4>
	    <div class="row">
	    <!-- ----------------------------  TABs o MENU  ---------------------------------------->
	      <div class="col s12">
	        <ul id="tabs-swipe-demo" class="tabs">
	          <li class="tab col s6"><a class="active black-text" href="#test-swipe-1">RESERVACIONES</a></li>
	          <li class="tab col s6"><a class="black-text" href="#test-swipe-2">PAGOS</a></li>
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
	                  <th>Habitacion</th>
	                  <th>Responsable</th>
	                  <th>Fecha Entrada</th>            
	                  <th>Fecha Salida</th>
	                  <th>Total</th>
	                  <th>Estatus</th>
	                  <th>Registro</th>
	                  <th>Fecha Registro</th>
	                </tr>
	              </thead>
	              <tbody>
	              	<?php
	              	$reservaciones = mysqli_query($conn,"SELECT * FROM reservaciones WHERE id_cliente = $id"); 
						      //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
						      if (mysqli_num_rows($reservaciones) == 0) {
						        echo '<h4>No se encontraron reservaciones.</h4>';
						      } else {
						        while ($reservacion = mysqli_fetch_array($reservaciones)) {
						        	$id_usuario = $reservacion['usuario'];// ID DEL USUARIO REGISTRO
									    //Obtenemos la informacion del Usuario
									    $usuario = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_usuario"));
						        	if($reservacion['estatus'] == 3){
												$estatus = '<span class="new badge red" data-badge-caption="Cancelada"></span>';
											}elseif ($reservacion['estatus'] == 2) {
												$estatus = '<span class="new badge black" data-badge-caption="Terminada"></span>';
											}else{
												$estatus = ($reservacion['estatus'] == 1)? '<span class="new badge blue" data-badge-caption="Ocupada"></span>': '<span class="new badge green" data-badge-caption="Pendiente"></span>';
											}
						        	?>
						        	<tr>
						        		<td><?php echo $reservacion['id']; ?></td>
						        		<td>N°<?php echo $reservacion['id_habitacion']; ?></td>
						        		<td><?php echo $reservacion['nombre']; ?></td>
						        		<td><?php echo $reservacion['fecha_entrada']; ?></td>
						        		<td><?php echo $reservacion['fecha_salida']; ?></td>
						        		<td>$<?php echo sprintf('%.2f', $reservacion['total']); ?></td>
						        		<td><?php echo $estatus; ?></td>
						        		<td><?php echo $usuario['firstname']; ?></td>
						        		<td><?php echo $reservacion['fecha_registro']; ?></td>
						        	</tr>
						        	<?php
						        }//FIN while
						      }//FIN ELSE
	                ?>
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
	                  <th>Cantidad</th>
	                  <th>Tipo</th>
	                  <th>Descripcion</th>
	                  <th>Usuario</th>
	                  <th>Fecha</th>
	                  <th>Cambio</th> 
	                  <th>Borrar</th> 
	                </tr>
	              </thead>
                <div id="editPagos"></div>
	              <tbody>
	              	<?php
	              	$pagos = mysqli_query($conn,"SELECT * FROM pagos WHERE id_cliente = $id"); 
						      //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
						      if (mysqli_num_rows($pagos) == 0) {
						        echo '<h4>No se encontraron pagos.</h4>';
						      } else {
						        while ($pago = mysqli_fetch_array($pagos)) {
						        	$id_usuario = $pago['id_user'];// ID DEL USUARIO REGISTRO
									    //Obtenemos la informacion del Usuario
									    $usuario = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_usuario"));
						        	?>
						        	<tr>
						        		<td><?php echo $pago['id_pago']; ?></td>
						        		<td>$<?php echo sprintf('%.2f', $pago['cantidad']); ?></td>
						        		<td><?php echo $pago['tipo']; ?></td>
						        		<td><?php echo $pago['descripcion']; ?></td>
						        		<td><?php echo $usuario['firstname']; ?></td>
						        		<td><?php echo $pago['fecha'].' '.$pago['hora']; ?></td>
						        		<td><?php echo $pago['tipo_cambio']; ?></td>
						        		<td><a onclick="verificar_eliminar(<?php echo $pago['id_pago']; ?>)" class="btn-small red waves-effect waves-light"><i class="material-icons">delete</i></a></td>
						        	</tr>
						        	<?php
	                	}//FIN while
						      }//FIN ELSE
	                ?>
	              </tbody>
	            </table>
	          </div></p>
	        </div>
	      </div>
	    </div>
	</div>

</body>
</html>