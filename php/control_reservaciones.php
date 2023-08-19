

<?php
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');
//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
include('is_logged.php');
//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL
$Hora = date('H:i:s');

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 12 PARA VER QUE ACCION HACER (busca Habit = 0, busca cliente = 1, muestra cliente = 2, insert reservacion = 3, busca check-in = 4, cancelar reservacion = 5, insert nota = 6, buscar cuentas = 7, update nota = 8, borrar nota = 9, busca check-out = 10, realiza check-in = 11, realiza check-out = 12)
$Accion = $conn->real_escape_string($_POST['accion']);
//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (busca Habit = 0, busca cliente = 1, muestra cliente = 2, insert reservacion = 3, busca check-in = 4, cancelar reservacion = 5, insert nota = 6, buscar cuentas = 7, update nota = 8, borrar nota = 9, busca check-out = 10, realiza check-in = 11, realiza check-out = 12)
//echo "hola aqui estoy";
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:

    	//CON POST RECIBIMOS EL ID DE LA HABITACION DEL FORMULARIO POR EL SCRIPT "reservacion.php" QUE NESECITAMOS PARA BUSCAR
    	$id = $conn->real_escape_string($_POST['habitacion']);  
    	if ($id != 0) {
            //HACEMOS LA CONSULTA DE LA HABITACION Y MOSTRAMOS LA INFOR EN FORMATO HTML
            $habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id"));            
            ?>
            	<div class="row col s12" id="infoHabitacion"><b>
		              	<div class="col s12 m6"><br>
		              		<b class="indigo-text col s12 m5">ESTADO: </b>
		              		<span class="new badge <?php echo ($habitacion['estatus'] == 0)?'green':'red';?> prefix" data-badge-caption=""><?php echo ($habitacion['estatus'] == 0)?'Disponible':'Ocupada';?></span>
		              	</div> 
		              	<div class="col s12 m6"><br>
		              		<b class="indigo-text col s12 m5">HASTA: </b>
		              		<?php echo 'Por Definir';?>
		              	</div>      
		              	<div class="col s12 m6"><br>
		              		<b class="indigo-text col s12 m5">NIVEL / PISO: </b>
		              		<?php echo $habitacion['piso'];?>
		              	</div>           		
		              	<div class="col s12 m6"><br>
		              		<b class="indigo-text col s12 m5">DESCRIPCION: </b>
		              		<?php echo $habitacion['descripcion'];?>
		              	</div>
		              	<div class="col s12 m6"><br>
		              		<b class="indigo-text col s12 m6">TIPO DE HABITACION: </b>
		              		<?php echo $habitacion['tipo'];?>
		              	</div> 
		              	<div class="col s12 m6"><br>
		              		<b class="indigo-text col s12 m5">PRECIO POR DIA: $</b>
		              		<div class="col s12 m6">
				              <input id="precioXDia" type="text" class="validate" data-length="100" value="<?php echo sprintf('%.2f', $habitacion['precio']);?>" required onchange="total();">	
				            </div>   		
		              	</div>

		        </b></div>
            <?php
            echo '<script>total();</script>';
        }
        break;
    case 1:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 1 realiza:
		
        //CON POST RECIBIMOS EL ID DEL CLIENTE DEL FORMULARIO POR EL SCRIPT "reservacion.php" QUE NESECITAMOS PARA BUSCAR
    	$id_cliente = $conn->real_escape_string($_POST['id_cliente']);  
    	if ($id_cliente != 0) {
            //HACEMOS LA CONSULTA DEL CLIENTE Y MOSTRAMOS LA INFOR EN FORMATO HTML
            $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id_cliente"));
			?>
			
			<div class="row col s12" id="infoCliente">
					        <b>		
					            <div class="col s12 m6">
					              	<b class="indigo-text col s12 m4"><br>NOMBRE: </b>
					              	<div class="col s12 m8">
							        	<input id="nombreCliente" type="text" class="validate" data-length="100"   value="<?php echo $cliente['nombre'];?>" onkeyup="buscarClientes()" required>	
									</div>
					        	</div>
					            <div class="col s12 m6" id="clienteBusqueda"></div> 
					            <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente;?>">             	
					            
								<div class="col s12 m6">
					              	<b class="indigo-text col s12 m4"><br>TELÉFONO: </b>
					              	<div class="col s12 m8">
							            <input id="telefono" type="number" class="validate" value="<?php echo $cliente['telefono'];?>" data-length="10">	
							        </div>
					            </div>
								<div class="col s12 m6">
					              	<b class="indigo-text col s12 m4"><br>EMAIL: </b>
					              	<div class="col s12 m8">
							            <input id="email" type="text" class="validate" value="<?php echo $cliente['email'];?>" data-length="100">	
							        </div>
					            </div>
					            	            		
					        </b>
				        </div>
       	<?php
        }//FIN IF
       	break;
    case 2:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 2 realiza:

    	//CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO de "reservacion.php"
        $Texto = $conn->real_escape_string($_POST['texto']);
        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {
			//MOSTRARA LOS ARTICULOS QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = mysqli_query($conn,"SELECT * FROM `clientes` WHERE id = 'Texto' OR nombre LIKE '%$Texto%' OR rfc LIKE '%$Texto%' LIMIT 1");
			if (mysqli_num_rows($sql) == 0) {
				
        	} else {
        		$clienteIgual = mysqli_fetch_array($sql);
        		?>
        		<table>
        			<thead>
        				<tr>
        					<th><?php echo $clienteIgual['nombre'] ?></th>
        					<th><?php echo $clienteIgual['telefono'] ?></th>
        					<th><a onclick="mostrarCliente(<?php echo $clienteIgual['id'] ?>)" class="btn-small green waves-effect waves-light">Elegir</a></th>
        				</tr>
        			</thead>
        		</table>
        		<?php
        	}	
		}else{//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
			
		}//FIN else $Texto VACIO O NO
    	// code...
    	break;
    case 3:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 3 realiza:
    

    	//CON POST RECIBIMOS UN rango de fechas de "reservacion.php"
        $Entrada = $conn->real_escape_string($_POST['valorFE']);
        $Salida = $conn->real_escape_string($_POST['valorFS']);
        $habitacion = $conn->real_escape_string($_POST['valorHabitacion']);

        //SELECCIONAMOS TODAS LAS RESERVACIONES PENDIENTES O EN CURSO DE LA HABITACION EN TURNO
        $reservaciones = mysqli_query($conn,"SELECT * FROM reservaciones WHERE id_habitacion = $habitacion AND (estatus = 0 OR estatus = 1)"); 
        $disponible = true;

        function getRangeDate($date_ini, $date_end, $format = 'Y-m-d') {

		    $dt_ini = DateTime::createFromFormat($format, $date_ini);
		    $dt_end = DateTime::createFromFormat($format, $date_end);
		    $period = new DatePeriod(
		        $dt_ini,
		        new DateInterval('P1D'),
		        $dt_end,
		    );
		    $range = [];
		    foreach ($period as $date) {
		        $range[] = $date->format($format);
		    }
		    $range[] = $date_end;
		    return $range;
		}
		$SalidaMenos =  date("Y-m-d",strtotime($Salida."- 1 days"));// LE RESTAMOS UN DIA A LA SALIDA
        if (mysqli_num_rows($reservaciones)>0) {
        	if ($Entrada == $SalidaMenos) {
        		$Lista_buscar = array($Entrada);
        	}else{ $Lista_buscar = getRangeDate($Entrada, $SalidaMenos);}
			
			while ($reservacion = mysqli_fetch_array($reservaciones)){
				$inicio = $reservacion['fecha_entrada'];
				$fin = $reservacion['fecha_salida'];
				$finMenos =  date("Y-m-d",strtotime($fin."- 1 days"));// LE RESTAMOS UN DIA A LA SALIDA DE LA RESERVACION
				if ($inicio == $finMenos) {
        			$Lista_ocupadas = array($inicio);
        		}else{ $Lista_ocupadas = getRangeDate($inicio, $finMenos);}
        		
        		foreach ($Lista_buscar as $busca) {
				    if (in_array($busca, $Lista_ocupadas)) {
        				$disponible = false;
				        break;
				    }
				}// FIN FOREACH
			}
        }

        //AQUI SE VA A VERIFICAR SI LA FECHA ESTA DISPONIBLE
        if (!$disponible) { //SI $disponible == true QUIERE DECIR QUE NO HAY PROBLEMA Y REGISTRE LA RESERVACION
        	// SI NO ESTA DISPONIBLE MOSTRAR MENSAJE ALERTA
        	echo '<script >M.toast({html:"Fecha NO disponible...", classes: "rounded"})</script>';
        	echo '<script >M.toast({html:"Checar disponibilidad en CALENDARIO.", classes: "rounded"})</script>';
        }else{
        	// SI ESTA DISPONIBLE REGISTRAMOS LA RESERVACION

        	$cliente = $conn->real_escape_string($_POST['valorCliente']);
			
        	if ($cliente == '') {
        		// SI ESTA VACIO QUIERE DECIR QUE ES UN CLIENTE NUEVO O NO REGISTRADO Y HAY QUE REGISTRARLO EN LA BD
        		//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "add_cliente.php" QUE NESECITAMOS PARA INSERTAR
		    	$Nombre = $conn->real_escape_string($_POST['valorNombre']);
				$calle = "";
				$numeroExterior = "";
				$numeroInterior = "";
				$Colonia = "";
				$Localidad = "";
				$municipio = "";
				$estadoMex = "";
				$CP = "";
				$Telefono = $conn->real_escape_string($_POST['valorTelefono']);
				$Email = $conn->real_escape_string($_POST['valorEmail']);
				$RFC = "";
				$Limpieza = $conn->real_escape_string($_POST['valorLimpieza']);
				$direccion = "";
				//VERIFICAMOS QUE NO HALLA UN CLIENTE CON LOS MISMOS DATOS
				if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `clientes` WHERE(nombre='$Nombre' AND colonia='$Colonia' AND cp='$CP') OR email='$Email'"))>0){
			 		echo '<script >M.toast({html:"Ya se encuentra un cliente con los mismos datos registrados.", classes: "rounded"})</script>';
				}else{
			 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
			 		$sql = "INSERT INTO `clientes` (nombre, rfc, email, telefono, direccion, calle, numero_exterior, 
					numero_interior, colonia, localidad, municipio, estado_mex, cp, limpieza, usuario, fecha) 
						VALUES('$Nombre', '$RFC', '$Email', '$Telefono', '$direccion', '$calle', '$numeroExterior', '$numeroInterior',
						 '$Colonia', '$Localidad', '$municipio', '$estadoMex', '$CP', '$Limpieza', '$id_user','$Fecha_hoy')";
					//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
					if(mysqli_query($conn, $sql)){
						echo '<script >M.toast({html:"El cliente se dió de alta satisfactoriamente.", classes: "rounded"})</script>';
						$ultimoCliente =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id) AS id FROM clientes WHERE usuario = $id_user"));            
          				$cliente = $ultimoCliente['id'];
					}else{
						echo '<script >M.toast({html:"Ocurrio un error en el registro del cliente.", classes: "rounded"})</script>';	
					}//FIN else DE ERROR
			 	}// FIN else DE BUSCAR CLIENTE IGUAL
        	}

        	//VERIFICAMOS SI AHORA SI YA HAY CLIENTE
        	if ($cliente != '') {
        		// SE REALIZA LA INSERCION DE LA RESERVACION
        		$Responsable = $conn->real_escape_string($_POST['valorNomResp']);
        		$observacion = $conn->real_escape_string($_POST['valorObservacion']);
        		$total = $conn->real_escape_string($_POST['valorTotal']);
        		$anticipo = $conn->real_escape_string($_POST['valorAnticipo']);

        		//VERIFICAMOS QUE NO HALLA UNA RESERVACION CON LOS MISMOS DATOS
				if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE (id_cliente = $cliente AND id_habitacion = $habitacion AND fecha_entrada = '$Entrada' AND fecha_salida = '$Salida') AND estatus != 3"))>0){
			 		echo '<script >M.toast({html:"Ya se encuentra una reservacion con los mismos datos registrados.", classes: "rounded"})</script>';
			 	}else{
			 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
			 		$sql = "INSERT INTO `reservaciones` (`id_cliente`, `id_habitacion`, `nombre`, `fecha_entrada`, `fecha_salida`, `observacion`, `total`, `anticipo`, 
					`estatus`, `usuario`, `fecha_registro`) 	
					VALUES('$cliente', '$habitacion', '$Responsable', '$Entrada', '$Salida', '$observacion', '$total', '$anticipo', 0, '$id_user','$Fecha_hoy')";
					//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
					if(mysqli_query($conn, $sql)){
						echo '<script >M.toast({html:"La reservacion se dió de alta satisfactoriamente.", classes: "rounded"})</script>';
						$ultimaReserv =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id) AS id FROM reservaciones WHERE usuario = $id_user"));            
          				$reservacion = $ultimaReserv['id'];
						//SI HAY UN ANTICIPO REGISTRAR PAGO Y TICKET (SE HABRE EL CAJON SI ES PAGO EN EFECTIVO)
						if ($anticipo > 0) {
        					$tipo_cambio = $conn->real_escape_string($_POST['valortipo_cambio']);
        					$descripcion = 'Reservación N°'.$reservacion.' ('.$Entrada.' - '.$Salida.')';

        					if ($tipo_cambio == 'Credito') {
        						// CREAMOS LA DEUDA DE CREDITO AL CLIENTE
        						$mysql= "INSERT INTO deudas(id_cliente, cantidad, fecha_deuda, descripcion, usuario) VALUES ($cliente, '$anticipo', '$Fecha_hoy',  '$Descripcion', $id_user)";        
						        mysqli_query($conn,$mysql);
						        $ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_deuda) AS id FROM deudas WHERE id_cliente = $cliente"));            
						        $id_deuda = $ultimo['id'];
        					}else{
        						$id_deuda = 0;
        					}

							$transferencia = intval($conn->real_escape_string($_POST['valorTransferencia']));
							$tarjeta = intval($conn->real_escape_string($_POST['valorTarjeta']));
							if ($tarjeta == 0){
								$debito = 0;
								$credito = 0;
							}else{
								$debito = intval($conn->real_escape_string($_POST['valorDebito']));
								$credito = intval($conn->real_escape_string($_POST['valorCredito']));
							}

        					#--- CREAMOS EL SQL PARA LA INSERCION ---
      						$sql = "INSERT INTO pagos (id_cliente, descripcion, cantidad, fecha, hora, tipo, id_user, corte, tipo_cambio, id_deuda) VALUES ($cliente, '$descripcion', '$anticipo', '$Fecha_hoy', '$Hora', 'Anticipo', $id_user, 0, '$tipo_cambio', $id_deuda)";
      						#--- SE INSERTA EL PAGO -----------
					        if(mysqli_query($conn, $sql)){
					        	$ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_pago) AS id FROM pagos WHERE id_cliente = $cliente AND id_user = $id_user"));            
        						$id_pago = $ultimo['id'];
        						if ($tipo_cambio == 'Banco') {
        							$ReferenciaB = $conn->real_escape_string($_POST['referenciaB']);
        							mysqli_query($conn,  "INSERT INTO referencias (id_pago, descripcion, transferencia, tarjeta, credito, debito)
									VALUES ('$id_pago', '$ReferenciaB', $transferencia, $tarjeta, $credito, $debito)");
        						}
					        	echo '<script>M.toast({html:"El pago se dió de alta satisfcatoriamente.", classes: "rounded"})</script>';
					        	?>
							    <script>
							      var a = document.createElement("a");
							        a.target = "_blank";
							        a.href = "../php/imprimir.php?id="+<?php echo $id_pago; ?>;
							        a.click();
							    </script>
							    <?php
							}// FIN if pago
						}// FIN IF anticipo
            			echo '<script>checkIn();</script>';
					}else{
						echo '<script >M.toast({html:"Ocurrio un error en el registro de la reservacion.", classes: "rounded"})</script>';	
					}//FIN else DE ERROR
			 	}// FIN else DE BUSCAR CLIENTE IGUAL
        	}
        }
    	break;
    case 4:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 4 realiza:

    	//CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO DE "check_in.php"
    	$Texto = $conn->real_escape_string($_POST['texto']);

    	//VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {
			//MOSTRARA LAS RESERVACIONES QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = "SELECT * FROM `reservaciones` WHERE  (id_cliente = '$Texto' OR id_habitacion = '$Texto' OR nombre LIKE '%$Texto%') AND estatus = 0 ORDER BY fecha_entrada";	
		}else{
			//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
			$sql = "SELECT * FROM `reservaciones` WHERE estatus = 0  ORDER BY fecha_entrada limit 50";
		}//FIN else $Texto VACIO O NO

		// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		$consulta = mysqli_query($conn, $sql);		
		$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

		//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		if (mysqli_num_rows($consulta) == 0) {
				echo '<script>M.toast({html:"No se encontraron reservaciones pendientes.", classes: "rounded"})</script>';
		} else {
			//SI NO ESTA EN == 0 SI TIENE INFORMACION
			//RECORREMOS UNO A UNO LAS RESERVACIONES CON EL WHILE	
			while($reservacion = mysqli_fetch_array($consulta)) {
				$id_user = $reservacion['usuario'];
				$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
				$id_cliente = $reservacion['id_cliente'];
				$cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id_cliente"));
				if ($reservacion['fecha_entrada'] > $Fecha_hoy) {
					$color = 'blue-text';
				}else{
					$color = ($reservacion['fecha_entrada'] < $Fecha_hoy)? 'red-text':'green-text';	
				}			
				//Output
				$contenido .= '			
		          <tr>
		            <td>'.$reservacion['id'].'</td>
		            <td>'.$id_cliente.'. '.$cliente['nombre'].'</td>
		            <td>N°'.$reservacion['id_habitacion'].'</td>
		            <td>'.$reservacion['nombre'].'</td>
		            <td class = "'.$color.'"><b>'.$reservacion['fecha_entrada'].'</b></td>
		            <td>'.$reservacion['fecha_salida'].'</td>
		            <td>'.$reservacion['observacion'].'</td>
		            <td>'.$user['firstname'].'</td>
		            <td>'.$reservacion['fecha_registro'].'</td>
		            <td><a onclick="modal_check_in('.$reservacion['id'].')" class="btn-small green waves-effect waves-light"><i class="material-icons prefix">exit_to_app</i></a></td>
		            <td><a onclick="cancelar_reservacion('.$reservacion['id'].')" class="btn-small red waves-effect waves-light"><i class="material-icons">close</i></a></td>
		          </tr>';
			}//FIN while
		}//FIN else
		echo $contenido;// MOSTRAMOS LA INFORMACION HTML
    	break;
    case 5:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 5 realiza:

    	//CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "check_in.php" QUE NESECITAMOS PARA CANCELAR
    	$id = $conn->real_escape_string($_POST['id']);
    	//Obtenemos la informacion del Usuario
    	$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    	//SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE CANCELAR RESERVACIONES
    	if ($User['cancelar'] == 1) {
    		//CREAMO LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL CLIENTE Y LA GUARDAMOS EN UNA VARIABLE 3 = Cancelada
			$sql = "UPDATE `reservaciones` SET estatus = 3 WHERE id = '$id'";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				echo '<script >M.toast({html:"Cancelación correcta....", classes: "rounded"})</script>';	
				echo '<script>checkIn()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
					echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
			}//FIN else DE ERROR
	    }else{
			echo '<script >M.toast({html:"Permiso denegado.", classes: "rounded"});
			M.toast({html:"Comunicate con un administrador.", classes: "rounded"});</script>';
	    }   
    	break;
    case 6:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 6 realiza:
    
    	//CON POST RECIBIMOS LAS VARIABLES DEL BOTON POR EL SCRIPT DE "modal_nota.php" QUE NESECITAMOS PARA CREAR
    	$id = $conn->real_escape_string($_POST['id']);
    	$DescripcionNota = $conn->real_escape_string($_POST['valorNota']);
    	//VERIFICAMOS QUE NO HALLA UNA NOTA CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `notas` WHERE descripcion='$DescripcionNota' AND id_reservacion='$id'"))>0){
			echo '<script >M.toast({html:"Ya se encuentra una nota igual registrada.", classes: "rounded"})</script>';
		}else{
			// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
			$sql = "INSERT INTO `notas` (id_reservacion, descripcion, usuario, fecha) VALUES('$id', '$DescripcionNota', '$id_user','$Fecha_hoy')";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				echo '<script >M.toast({html:"La nota se dió de alta satisfactoriamente.", classes: "rounded"})</script>';
			}else{
				echo '<script >M.toast({html:"Ocurrio un error en el registro de la nota.", classes: "rounded"})</script>';	
			}//FIN else DE ERROR
		}// FIN else DE BUSCAR nota IGUAL
    	break;
    case 7:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 7 realiza:
    	//CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO DE "cuentas.php"
    	$Texto = $conn->real_escape_string($_POST['texto']);

    	//VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {
			//MOSTRARA LOS CLIENTES QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = "SELECT * FROM `reservaciones` WHERE  (id_habitacion = '$Texto' OR id_cliente = '$Texto' OR  nombre LIKE '%$Texto%') AND estatus = 0 OR estatus = 1 ORDER BY id";	
		}else{
			//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
			$sql = "SELECT * FROM `reservaciones` WHERE estatus = 0 OR estatus = 1";
		}//FIN else $Texto VACIO O NO

		// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		$consulta = mysqli_query($conn, $sql);		
		$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

		//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		if (mysqli_num_rows($consulta) == 0) {
				echo '<script>M.toast({html:"No se encontraron reservaciones pendientes.", classes: "rounded"})</script>';			
		} else {
			//SI NO ESTA EN == 0 SI TIENE INFORMACION
			//RECORREMOS UNO A UNO LAS RESERVACIONES CON EL WHILE	
			while($reservacion = mysqli_fetch_array($consulta)) {
				$id_user = $reservacion['usuario'];
				$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
				$id_cliente = $reservacion['id_cliente'];
				$cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id_cliente"));
				$estatus = ($reservacion['estatus'] == 0)? '<span class="new badge green " data-badge-caption="Pendiente"></span>':'<span class="new badge blue" data-badge-caption="Ocupada"></span>';
				//Output
				$contenido .= '			
		          <tr>
		            <td>'.$reservacion['id'].'</td>
		            <td>'.$id_cliente.'. '.$cliente['nombre'].'</td>
		            <td>N°'.$reservacion['id_habitacion'].'</td>
		            <td>'.$reservacion['nombre'].'</td>
		            <td>'.$estatus.'</td>
		            <td>'.$user['firstname'].'</td>
		            <td>'.$reservacion['fecha_registro'].'</td>
		            <td><a href = "../views/detalles_cuenta.php?id='.$reservacion['id'].'" class="btn-small grey darken-4 waves-effect waves-light"><i class="material-icons">list</i></a></td>
		          </tr>';
			}//FIN while
		}//FIN else
		echo $contenido;// MOSTRAMOS LA INFORMACION HTML
    	break;
    case 8:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 8 realiza:
    
    	//CON POST RECIBIMOS LAS VARIABLES DEL BOTON POR EL SCRIPT DE "modal_nota.php" QUE NESECITAMOS PARA ACTUALIZAR
    	$id_res = $conn->real_escape_string($_POST['id_res']);
    	$id_nota = $conn->real_escape_string($_POST['id_nota']);
    	$DescripcionNota = $conn->real_escape_string($_POST['valorNota']);
    	//VERIFICAMOS QUE NO HALLA UNA RESERVACION CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `notas` WHERE descripcion='$DescripcionNota' AND id_reservacion='$id_res'"))>0){
			echo '<script >M.toast({html:"Ya se encuentra una nota igual registrada.", classes: "rounded"})</script>';
		}else{
			// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
			$sql = "UPDATE `notas` SET descripcion = '$DescripcionNota', usuario = '$id_user', fecha = '$Fecha_hoy' WHERE id = $id_nota";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				echo '<script >M.toast({html:"La nota se actualizo satisfactoriamente.", classes: "rounded"})</script>';
				?>
		        <script>
		          var a = document.createElement("a");
		            a.href = "../views/detalles_cuenta.php?id="+<?php echo $id_res; ?>;
		            a.click();
		        </script>
		        <?php 
			}else{
				echo '<script >M.toast({html:"Ocurrio un error en la actualizacion de la nota.", classes: "rounded"})</script>';	
			}//FIN else DE ERROR
		}// FIN else DE BUSCAR nota IGUAL         
    	break;
    case 9:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 9 realiza:

        //CON POST RECIBIMOS LAS VARIABLES DEL BOTON POR EL SCRIPT DE "detalles_habitacion.php" QUE NESECITAMOS PARA BORRAR
        $id = $conn->real_escape_string($_POST['id']);
        $id_res = $conn->real_escape_string($_POST['id_res']);

        #VERIFICAMOS QUE SE BORRE CORRECTAMENTE LA NOTA DE `notas`
        if(mysqli_query($conn, "DELETE FROM `notas` WHERE `notas`.`id` = $id")){
            #SI ES ELIMINADO MANDAR MSJ CON ALERTA
            echo '<script >M.toast({html:"Nota borrada con exito.", classes: "rounded"})</script>';
            ?>
		    <script>
		      var a = document.createElement("a");
		        a.href = "../views/detalles_cuenta.php?id="+<?php echo $id_res; ?>;
		        a.click();
		    </script>
		    <?php 
        }else{
            echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
        }        
        break;
    case 10:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 10 realiza:

        //CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO DE "check_out.php"
    	$Texto = $conn->real_escape_string($_POST['texto']);
    	//VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {
			//MOSTRARA LAS RESERVACIONES QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = "SELECT * FROM `reservaciones` WHERE  (id_cliente = '$Texto' OR id_habitacion = '$Texto' OR nombre LIKE '%$Texto%') AND estatus = 1 AND fecha_salida <= '$Fecha_hoy' ORDER BY fecha_entrada";	
		}else{
			//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
			$sql = "SELECT * FROM `reservaciones` WHERE estatus = 1 AND fecha_salida <= '$Fecha_hoy' ORDER BY fecha_entrada limit 50";
		}//FIN else $Texto VACIO O NO

		// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		$consulta = mysqli_query($conn, $sql);		
		$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

		//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		if (mysqli_num_rows($consulta) == 0) {
				echo '<script>M.toast({html:"No se encontraron reservaciones para check-out.", classes: "rounded"})</script>';			
		} else {
			//SI NO ESTA EN == 0 SI TIENE INFORMACION
			//RECORREMOS UNO A UNO LAS RESERVACIONES CON EL WHILE	
			while($reservacion = mysqli_fetch_array($consulta)) {
				$id_user = $reservacion['usuario'];
				$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
				$id_cliente = $reservacion['id_cliente'];
				$cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id_cliente"));
				$color = ($reservacion['fecha_salida'] < $Fecha_hoy)? 'red-text':'green-text';				
				//Output
				$contenido .= '			
		          <tr>
		            <td>'.$reservacion['id'].'</td>
		            <td>'.$id_cliente.'. '.$cliente['nombre'].'</td>
		            <td>N°'.$reservacion['id_habitacion'].'</td>
		            <td>'.$reservacion['nombre'].'</td>
		            <td>'.$reservacion['fecha_entrada'].'</td>
		            <td class = "'.$color.'"><b>'.$reservacion['fecha_salida'].'</b></td>
		            <td>'.$user['firstname'].'</td>
		            <td>'.$reservacion['fecha_registro'].'</td>
		            <td><a onclick="modal_check_out('.$reservacion['id'].')" class="btn-small green waves-effect waves-light"><i class="material-icons prefix">exit_to_app</i></a></td>
		          </tr>';
			}//FIN while
		}//FIN else
		echo $contenido;// MOSTRAMOS LA INFORMACION HTML  
        break; 
    case 11:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 11 realiza:

    	//CON POST RECIBIMOS EL ID DE LA RESERVACION Y EL ID DE LA HABITACION DE "check_in.php"
        $id = $conn->real_escape_string($_POST['id']);
        $reservacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id=$id"));
        $habitacion = $reservacion['id_habitacion'];

        //VERIFICAMOS SI NO HAY NINGUNA RESERVACION OCUPADA DE LA MISMA HABITACION
		if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id_habitacion=$habitacion AND estatus = 1"))>0) {
			echo '<script >M.toast({html:"No se realizo CHECK-IN", classes: "rounded"})</script>';	
			echo '<script >M.toast({html:"Esta habitacion ya tiene una reservacion ocupada.", classes: "rounded"})</script>';	
		}else{
			//CREAMO LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL CLIENTE Y LA GUARDAMOS EN UNA VARIABLE 1 = Ocupada
			$sql = "UPDATE `reservaciones` SET estatus = 1 WHERE id = '$id'";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				mysqli_query($conn, "UPDATE `habitaciones` SET estatus = 1 WHERE id = '$habitacion'");

				echo '<script >M.toast({html:"CHECK-IN se realizo con exito", classes: "rounded"})</script>';
				#CON POST RECIBIMOS LAS VARIABLES Y VERIFICAMOS SI HAY QUE REGISTRAR PAGO
        		$Abono = $conn->real_escape_string($_POST['abonoR']);
        		if ($Abono > 0) {
        			$tipo_cambio = $conn->real_escape_string($_POST['tipo_cambio']);
        			$descripcion = 'Reservación N°'.$id.' ('.$reservacion['fecha_entrada'].' - '.$reservacion['fecha_salida'].')';
        			$cliente = $reservacion['id_cliente'];

        			if ($tipo_cambio == 'Credito') {
        				// CREAMOS LA DEUDA DE CREDITO AL CLIENTE
        				$mysql= "INSERT INTO deudas(id_cliente, cantidad, fecha_deuda, descripcion, usuario) VALUES ($cliente, '$Abono', '$Fecha_hoy',  '$descripcion', $id_user)";        
						mysqli_query($conn,$mysql);
						$ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_deuda) AS id FROM deudas WHERE id_cliente = $cliente"));            
						$id_deuda = $ultimo['id'];
        			}else{
        				$id_deuda = 0;
        			}

					$transferencia = intval($conn->real_escape_string($_POST['valorTransferencia']));
					$tarjeta = intval($conn->real_escape_string($_POST['valorTarjeta']));
					if ($tarjeta == 0){
						$debito = 0;
						$credito = 0;
					}else{
						$debito = intval($conn->real_escape_string($_POST['valorDebito']));
						$credito = intval($conn->real_escape_string($_POST['valorCredito']));
					}
        			#--- CREAMOS EL SQL PARA LA INSERCION ---
      				$sql = "INSERT INTO pagos (id_cliente, descripcion, cantidad, fecha, hora, tipo, id_user, corte, tipo_cambio, id_deuda) VALUES ($cliente, '$descripcion', '$Abono', '$Fecha_hoy', '$Hora', 'Abono', $id_user, 0, '$tipo_cambio', $id_deuda)";
      				#--- SE INSERTA EL PAGO -----------
					if(mysqli_query($conn, $sql)){
					    mysqli_query($conn, "UPDATE `reservaciones` SET anticipo = anticipo+$Abono WHERE id = '$id'");
					    $ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_pago) AS id FROM pagos WHERE id_cliente = $cliente AND id_user = $id_user"));            
        				$id_pago = $ultimo['id'];
        				if ($tipo_cambio == 'Banco') {
        					$ReferenciaB = $conn->real_escape_string($_POST['referenciaB']);
        					mysqli_query($conn,  "INSERT INTO referencias (id_pago, descripcion, transferencia, tarjeta, debito, credito)
							VALUES ('$id_pago', '$ReferenciaB', $transferencia, $tarjeta, $debito, $credito)");
        				}
					    echo '<script>M.toast({html:"El pago se dió de alta satisfcatoriamente.", classes: "rounded"})</script>';
					    ?>
						<script>
							var a = document.createElement("a");
							    a.target = "_blank";
							    a.href = "../php/imprimir.php?id="+<?php echo $id_pago; ?>;
							    a.click();
						</script>
						<?php
					}// FIN if pago
        		}// FIN if abono
				echo '<script>checkIn()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
					echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
			}//FIN else DE ERROR
		}		
    	break;
    case 12:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 12 realiza:

    	//CON POST RECIBIMOS EL ID DE LA RESERVACION Y EL ID DE LA HABITACION DE "check_out.php"
        $id = $conn->real_escape_string($_POST['id']);
        $reservacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id=$id"));
        $habitacion = $reservacion['id_habitacion'];

		//CREAMO LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL CLIENTE Y LA GUARDAMOS EN UNA VARIABLE 2 = Terminada
		$sql = "UPDATE `reservaciones` SET estatus = 2 WHERE id = '$id'";
		//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
		if(mysqli_query($conn, $sql)){
			//CAMBIAMOS LA HABITACION A ESTATUS DE LIMPIEZA estatus = 2
			mysqli_query($conn, "UPDATE `habitaciones` SET estatus = 2 WHERE id = '$habitacion'");
			//Generamos el reporte de limpieza
			mysqli_query($conn, "INSERT INTO `limpieza` (id_habitacion, descripcion, fecha, usuario) 
						VALUES('$habitacion', 'LIMPIEZA GENERAL (CHECK-OUT)', '$Fecha_hoy', '$id_user')");

			echo '<script >M.toast({html:"CHECK-OUT se realizo con exito", classes: "rounded"})</script>';
			#CON POST RECIBIMOS LAS VARIABLES Y VERIFICAMOS SI HAY QUE REGISTRAR PAGO
        	$Liquidacion = $conn->real_escape_string($_POST['liquidacion']);
        	if ($Liquidacion > 0) {
        		$tipo_cambio = $conn->real_escape_string($_POST['tipo_cambio']);
        		$descripcion = 'Reservación N°'.$id.' ('.$reservacion['fecha_entrada'].' - '.$reservacion['fecha_salida'].')';
        		$cliente = $reservacion['id_cliente'];

        		if ($tipo_cambio == 'Credito') {
        			// CREAMOS LA DEUDA DE CREDITO AL CLIENTE
        			$mysql= "INSERT INTO deudas(id_cliente, cantidad, fecha_deuda, descripcion, usuario) VALUES ($cliente, '$Liquidacion', '$Fecha_hoy',  '$descripcion', $id_user)";        
					mysqli_query($conn,$mysql);
					$ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_deuda) AS id FROM deudas WHERE id_cliente = $cliente AND usuario = $id_user"));            
					$id_deuda = $ultimo['id'];
        		}else{
        			$id_deuda = 0;
        		}

				$transferencia = intval($conn->real_escape_string($_POST['valorTransferencia']));
				$tarjeta = intval($conn->real_escape_string($_POST['valorTarjeta']));
				if ($tarjeta == 0){
					$debito = 0;
					$credito = 0;
				}else{
					$debito = intval($conn->real_escape_string($_POST['valorDebito']));
					$credito = intval($conn->real_escape_string($_POST['valorCredito']));
				}
        		#--- CREAMOS EL SQL PARA LA INSERCION ---
      			$sql = "INSERT INTO pagos (id_cliente, descripcion, cantidad, fecha, hora, tipo, id_user, corte, tipo_cambio, id_deuda) VALUES ($cliente, '$descripcion', '$Liquidacion', '$Fecha_hoy', '$Hora', 'Liquidacion', $id_user, 0, '$tipo_cambio', $id_deuda)";
      			#--- SE INSERTA EL PAGO -----------
				if(mysqli_query($conn, $sql)){
					$ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_pago) AS id FROM pagos WHERE id_cliente = $cliente"));            
        			$id_pago = $ultimo['id'];
        			if ($tipo_cambio == 'Banco') {
        				$ReferenciaB = $conn->real_escape_string($_POST['referenciaB']);
        				mysqli_query($conn,  "INSERT INTO referencias (id_pago, descripcion, transferencia, tarjeta, debito, credito)
						VALUES ('$id_pago', '$ReferenciaB', $transferencia, $tarjeta, $debito, $credito)");
        			}
					echo '<script>M.toast({html:"El pago se dió de alta satisfcatoriamente.", classes: "rounded"})</script>';
					?>
					<script>
						var a = document.createElement("a");
							a.target = "_blank";
							a.href = "../php/imprimir.php?id="+<?php echo $id_pago; ?>;
							a.click();
					</script>
					<?php
				}// FIN if pago
        	}// FIN if abono
			echo '<script>checkout()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
		}else{
			echo '<script >M.toast({html:"Ocurrio un error en check-out...", classes: "rounded"})</script>';	
		}//FIN else DE ERROR			
    	break;
    case 13:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 13 realiza:

    	//CON POST RECIBIMOS EL ID DE LA RESERVACION  "detalles_habitacion.php" o"detalles_cliente.php"
        $id = $conn->real_escape_string($_POST['id']);
        $reservacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id=$id"));

        //SACAREMOS LOS DIAS DE LA ESTANCIA CON LA DIFERENCIA ENTRE FECHAS
        $Entrada = $reservacion['fecha_entrada'];// SACAMOS LA ENTRADA DE LA RESERVACION SELECCIONADA
        $SalidaOld = $reservacion['fecha_salida'];// SACAMOS LA SALIDA DE LA RESERVACION SELECCIONADA
        $SalidaN = $conn->real_escape_string($_POST['salida']);// CON EL METODO POST RESIBIMOS LA NUEVA FECHA SALIDA
        $diasOld = (strtotime($SalidaOld) - strtotime($Entrada)) / 86400;
        $diasNuevos = (strtotime($SalidaN) - strtotime($Entrada)) / 86400;
        $PrecioXDia = $reservacion['total']/$diasOld;
        // CALCULAMOS EL COSTO SEGUN EL PRECIO DE LA HABITACION POR DIA Y LOS DIAS DE ESTANCIA
        $id_habitacion = $reservacion['id_habitacion'];
        $habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id_habitacion"));
        $total = $diasNuevos*$PrecioXDia;
        echo $PrecioXDia.' - '.$diasNuevos. ' - '.$total;
		//CREAMO LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DE LA RESERVACION
		$sql = "UPDATE `reservaciones` SET fecha_salida = '$SalidaN', total = '$total' WHERE id = '$id'";
		//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
		if(mysqli_query($conn, $sql)){		
			echo '<script >M.toast({html:"Fecha actualizada con exito.", classes: "rounded"})</script>';
        	$Ruta = $conn->real_escape_string($_POST['ruta']);// A DONDE SE DIRIGIRA
			?>
			<script>
				var a = document.createElement("a");
					a.href = "../views/"+<?php echo $Ruta; ?>;
					a.click();
			</script>
			<?php	
		}else{
			echo '<script >M.toast({html:"Ocurrio un error ...", classes: "rounded"})</script>';	
		}//FIN else DE ERROR			
    	break;
}// FIN switch
mysqli_close($conn);    
?>