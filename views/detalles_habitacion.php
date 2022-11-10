
<!DOCTYPE html>
<html>
<head>
    <title>San Roman | Habitacion</title>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
	//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL ARTICULO
	if (isset($_GET['id']) == false) {
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
		if ($habitacion['estatus'] == 2) {
	        $estatus = '<span class="new badge blue" data-badge-caption="Limpieza"></span>';
	    }else{
	        $estatus = ($habitacion['estatus'] == 1)? '<span class="new badge red" data-badge-caption="Ocupada"></span>': '<span class="new badge green" data-badge-caption="Disponible"></span>';
	    }
	}// FIN else
	?>
	<script>
	  	//FUNCION QUE BORRA HABITACIONES (SE ACTIVA AL INICIAR EL BOTON BORRAR)
      function borrar_habitacion(id){
        var answer = confirm("Deseas eliminar la habitacion N°"+id+"?");
        if (answer) {
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_habitaciones.php"
          $.post("../php/control_habitaciones.php", {
            //Cada valor se separa por una ,
            id: id,
            accion: 2,
          }, function(mensaje) {
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
            $("#borrarHabitacion").html(mensaje);
          }); //FIN post
        }//FIN IF
      };//FIN function

      //FUNCION QUE MUESTRA EL MODAL PARA CAMBIAR AGREGAR UNA NOTA
      function modal_nota(id){
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "modal_nota.php" PARA MOSTRAR EL MODAL
        $.post("modal_nota.php", {
          //Cada valor se separa por una ,
            id:id,
          }, function(mensaje){
            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "modal_nota.php"
            $("#modal").html(mensaje);
        });//FIN post
      }//FIN function

      //FUNCION QUE MUESTRA EL MODAL PARA  AGREGAR UNA NOTA
      function nueva_nota(id){          
				var DescripcionNota = $("input#descripcionNota").val();
				if (DescripcionNota == '') {
					M.toast({html:"Porfavor ingrese una descripción a la nota", classes: "rounded"});
				}else{
					//MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_reservaciones.php" PARA MOSTRAR EL MODAL
	        $.post("../php/control_reservaciones.php", {
		       //Cada valor se separa por una ,
		        id: id,
		        valorNota: DescripcionNota,
		        accion: 6,
	        }, function(mensaje){
	            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
	            $("#modal").html(mensaje);
	        });//FIN post
        }//FIN else
      }//FIN function

      function crear_mto() {
      	var DescripcionMto = $("textarea#descripcionMto").val();
				if (DescripcionMto == '') {
					M.toast({html:"Porfavor ingrese una descripción al mantenimiento.", classes: "rounded"});
				}else{
					//MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_habitaciones.php" PARA MOSTRAR EL MODAL
	        $.post("../php/control_habitaciones.php", {
		       //Cada valor se separa por una ,
		        id: <?php echo $id; ?>,
		        descripcionMto: DescripcionMto,
		        accion: 3,
	          }, function(mensaje){
	            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
	            $("#modal").html(mensaje);
	          });//FIN post
        }//FIN else
      }
      function crear_limpieza() {
      	var limpieza = $("select#limpieza").val();
				if (limpieza == '') {
					M.toast({html:"Porfavor seleccione una opcion.", classes: "rounded"});
				}else{
					//MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_habitaciones.php" PARA MOSTRAR EL MODAL
	        $.post("../php/control_habitaciones.php", {
		       //Cada valor se separa por una ,
		        id_habitacion: <?php echo $id; ?>,
		        limpieza: limpieza,
		        accion: 8,
	          }, function(mensaje){
	            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_habitaciones.php"
	            $("#modal").html(mensaje);
	          });//FIN post
        }//FIN else
      }
	</script>
</head>
<body>
	<div class="container">
		<div class="row"><br>
		  <!-- CREAMOS UN DIV EL CUAL TENGA id = "modal"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
	      <div id="modal"></div>
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
              		<div class="col s12 m11 l9"><b class="indigo-text">ESTADO: </b><?php echo $estatus;?></div>          
              		<div class="col s12"><b class="indigo-text">PRECIO: </b><?php echo '$'.sprintf('%.2f', $habitacion['precio']);?></div>          		
              	</div>
              </b></p><br><br>
            </li>
          </ul>
        </div>
	    <!-- CREAMOS UN DIV EL CUAL TENGA id = "borrarHabitacion"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      	<div id="borrarHabitacion"></div>
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
	        			<th>Limp.</th>
	        			<th>Editar</th>
	        			<th>Borrar</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		<tr>
	        			<td><form method="post" action="../views/reservacion.php"><input id="habitacion" name="habitacion" type="hidden" value="<?php echo $id;?>"><button class="btn-small green waves-effect waves-light"><i class="material-icons">event</i></button></form></td>
	        			<td><a onclick="modal_nota(<?php echo $id;?>)" class="btn-small amber waves-effect waves-light"><i class="material-icons">note_add</i></a></td>
	        			<td><a onclick="" class="btn-small indigo waves-effect waves-light tooltipped modal-trigger" data-position="bottom" data-tooltip="Reporte Mantenimiento" href="#modalMantenimiento"><i class="material-icons">settings</i></a></td>
	        			<td><a onclick="" class="btn-small waves-effect waves-light tooltipped modal-trigger" href="#modalLimpieza" data-position="bottom" data-tooltip="Reporte Limpieza"><i class="material-icons">photo_filter</i></a></td>
	        			<td><a href="edit_habitacion.php?id=<?php echo $id;?>" class="btn-small grey darken-3 waves-effect waves-light"><i class="material-icons">edit</i></a></td>
	        			<td><a onclick="borrar_habitacion(<?php echo $id;?>);" class="btn-small red darken-1 waves-effect waves-light"><i class="material-icons">delete</i></a></td>
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
	                <th>N°</th>
		              <th>Cliente</th>
		              <th>Responsable</th>
		              <th>Fecha Entrada</th>
		              <th>Fecha Salida</th>
		              <th>Observacion</th>
		              <th>Total</th>
		              <th>Estatus</th>
		              <th>Registro</th>
		              <th>Fecha Registro</th>
	                </tr>
	              </thead>
	              <tbody>
	              	<?php
					// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
					$consulta = mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id_habitacion = $id");
					//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
					if (mysqli_num_rows($consulta) == 0) {
						echo '<h4>No se encontraron reservaciones.</h4>';
					} else {
						while ($reservacion = mysqli_fetch_array($consulta)) {
							$id_user = $reservacion['usuario'];
							$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
							$id_cliente = $reservacion['id_cliente'];
							$cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id_cliente"));
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
							    <td><?php echo $cliente['id'].'. '.$cliente['nombre']; ?></td>
							    <td><?php echo $reservacion['nombre']; ?></td>
							    <td><?php echo $reservacion['fecha_entrada']; ?></td>
							    <td><?php echo $reservacion['fecha_salida']; ?></td>
							    <td><?php echo $reservacion['observacion']; ?></td>
							    <td>$<?php echo sprintf('%.2f', $reservacion['total']); ?></td>
							    <td><?php echo $estatus; ?></td>
							    <td><?php echo $user['firstname']; ?></td>
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
	            <table class="bordered centered highlight">
	              <thead>
	                <tr>
	                  <th>N°</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Registro</th> 
                    <th>Estatus</th> 
                    <th>Solución</th>
                    <th>Fecha Atendido</th>
                    <th>Atendio</th> 
	                </tr>
	              </thead>
	              <tbody>
	               <?php
		              $sql = "SELECT * FROM `mantenimientos` WHERE id_habitacion = $id";
		              // REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		              $consulta = mysqli_query($conn, $sql);
		              //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		              if (mysqli_num_rows($consulta) == 0) {
		                echo '<h4>No se encontraron mantenimientos.</h4>';
		              } else {
		                while ($mantenimineto = mysqli_fetch_array($consulta)) {
		                  $id_user_r = $mantenimineto['usuario'];
		                  $user_r = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user_r"));
		                  $id_user_a = $mantenimineto['atendio'];
		                  if ($id_user_a == 0) {
		                    $user_a['firstname'] = '';
		                  }else{
		                    $user_a = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user_a"));
		                  }
		                  $estatus = ($mantenimineto['estatus'] == 1)? '<span class="new badge green" data-badge-caption="Terminado"></span>': '<span class="new badge red" data-badge-caption="Pendiente"></span>';
		                    ?>
		                    <tr>
		                        <td><?php echo $mantenimineto['id']; ?></td>
		                        <td><?php echo $mantenimineto['descripcion']; ?></td>
		                        <td><?php echo $mantenimineto['fecha']; ?></td>
		                        <td><?php echo $user_r['firstname']; ?></td>
		                        <td><?php echo $estatus; ?></td>
		                        <td><?php echo $mantenimineto['solucion']; ?></td>
		                        <td><?php echo $mantenimineto['fecha_atendio']; ?></td>
		                        <td><?php echo $user_a['firstname']; ?></td>
		                    </tr>
		                    <?php
		                }//FIN while
		              }//FIN ELSE
		            ?>    
	              </tbody>
	            </table>
	        </div>
	      </div>
	    </div>
	</div>
</body>
</html>