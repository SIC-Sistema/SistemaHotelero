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

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 6 PARA VER QUE ACCION HACER (busca Habit = 0)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (busca Habit = 0)
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
		              	<div class="col s12"><br><br>
		              		<b class="indigo-text col s12 m5">ESTADO: </b>
		              		<span class="new badge <?php echo ($habitacion['estatus'] == 0)?'green':'red';?> prefix" data-badge-caption=""><?php echo ($habitacion['estatus'] == 0)?'Disponible':'Ocupada';?></span>
		              	</div> 
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">HASTA: </b>
		              		<?php echo 'Por Definir';?>
		              	</div>      
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">NIVEL / PISO: </b>
		              		<?php echo $habitacion['piso'];?>
		              	</div>           		
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">DESCRIPCION: </b>
		              		<?php echo $habitacion['descripcion'];?>
		              	</div>
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">TIPO DE HABITACION: </b>
		              		<br><?php echo $habitacion['tipo'];?>
		              	</div> 
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">PRECIO POR DIA: $</b>
		              		<div class="col s10 m5">
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
			            <div class="col s12">
			              	<b class="indigo-text col s12 m4"><br>NOMBRE: </b>
			              	<div class="col s12 m8">
					        	<input id="nombreCliente" type="text" class="validate" data-length="100" value="<?php echo $cliente['nombre'];?>" onkeyup = 'buscarClientes()' required>	
							</div>
			        	</div>
			            <div class="col s12" id="clienteBusqueda"><br><br></div>
					    <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente;?>">           	
			            <div class="col s12">
			              	<b class="indigo-text col s12 m4"><br>DIRECCION: </b>
			              	<div class="col s12 m8">
					            <input id="direccion" type="text" class="validate" data-length="100" value="<?php echo $cliente['direccion'];?>" required>	
					        </div>
			            </div>     
			            <div class="col s12">
			              	<b class="indigo-text col s12 m3"><br>COLONIA: </b>
			              	<div class="col s12 m5">
					            <input id="colonia" type="text" class="validate" data-length="20" value="<?php echo $cliente['colonia'];?>" required>	
					        </div>
					        <b class="indigo-text col s12 m1"><br>CP: </b>
			              	<div class="col s12 m3">
					            <input id="cp" type="text" class="validate" data-length="6" value="<?php echo $cliente['cp'];?>" required>	
					        </div>
			            </div>   
			            <div class="col s12">
			              	<b class="indigo-text col s12 m4"><br>LOCALIDAD: </b>
			              	<div class="col s12 m8">
					            <input id="localidad" type="text" class="validate" data-length="20" value="<?php echo $cliente['localidad'];?>" required>	
					        </div>
			            </div>  
			            <div class="col s12">
			              	<b class="indigo-text col s12 m4"><br>RFC: </b>
			              	<div class="col s12 m8">
					            <input id="rfc" type="text" class="validate" data-length="6" value="<?php echo $cliente['rfc'];?>" required>	
					        </div>
			            </div>  
			            <div class="col s12">
			              	<b class="indigo-text col s12 m4"><br>EMAIL: </b>
			              	<div class="col s12 m8">
					            <input id="email" type="text" value="<?php echo $cliente['email'];?>" required>	
					        </div>
			            </div>
			            <div class="col s12">
			              	<b class="indigo-text col s12 m4"><br>TELEFONO: </b>
			              	<div class="col s12 m8">
					            <input id="telefono" type="text" class="validate" data-length="10" value="<?php echo $cliente['telefono'];?>" required>	
					        </div>
			            </div>
			            <div class="col s12">
			              	<b class="indigo-text col s12 m4"><br>LIMPIEZA: </b>
			              	<div class="col s12 m8">
					            <input id="limpieza" type="text" class="validate" data-length="100" value="<?php echo $cliente['limpieza'];?>" required>	
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
				echo '<br><br>';
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
			echo '<br><br>';
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
				$Telefono = $conn->real_escape_string($_POST['valorTelefono']);
				$Email = $conn->real_escape_string($_POST['valorEmail']);
				$RFC = $conn->real_escape_string($_POST['valorRFC']);
				$Direccion = $conn->real_escape_string($_POST['valorDireccion']);
				$Colonia = $conn->real_escape_string($_POST['valorColonia']);
				$Localidad = $conn->real_escape_string($_POST['valorLocalidad']);
				$CP = $conn->real_escape_string($_POST['valorCP']);
				$Limpieza = $conn->real_escape_string($_POST['valorLimpieza']);

				//VERIFICAMOS QUE NO HALLA UN CLIENTE CON LOS MISMOS DATOS
				if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `clientes` WHERE(nombre='$Nombre' AND direccion='$Direccion' AND colonia='$Colonia' AND cp='$CP') OR rfc='$RFC' OR email='$Email'"))>0){
			 		echo '<script >M.toast({html:"Ya se encuentra un cliente con los mismos datos registrados.", classes: "rounded"})</script>';
			 	}else{
			 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
			 		$sql = "INSERT INTO `clientes` (nombre, telefono, direccion, colonia, cp, rfc, email, localidad, limpieza, usuario, fecha) 
						VALUES('$Nombre', '$Telefono', '$Direccion', '$Colonia', '$CP', '$RFC', '$Email', '$Localidad', '$Limpieza', '$id_user','$Fecha_hoy')";
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
				if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE (id_cliente = $cliente AND id_habitacion = $habitacion AND fecha_entrada = '$Entrada' AND fecha_salida = '$Salida')"))>0){
			 		echo '<script >M.toast({html:"Ya se encuentra una reservacion con los mismos datos registrados.", classes: "rounded"})</script>';
			 	}else{
			 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
			 		$sql = "INSERT INTO `reservaciones` (`id_cliente`, `id_habitacion`, `nombre`, `fecha_entrada`, `fecha_salida`, `observacion`, `total`, `anticipo`, `estatus`, `usuario`, `fecha_registro`) 	VALUES('$cliente', '$habitacion', '$Responsable', '$Entrada', '$Salida', '$observacion', '$total', '$anticipo', 0, '$id_user','$Fecha_hoy')";
					//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
					if(mysqli_query($conn, $sql)){
						echo '<script >M.toast({html:"La reservacion se dió de alta satisfactoriamente.", classes: "rounded"})</script>';
						//SI HAY UN ANTICIPO REGISTRAR PAGO Y TICKET (SE HABRE EL CAJON SI ES PAGO EN EFECTIVO)
						if ($anticipo > 0) {
							// code...
        					$tipo_cambio = $conn->real_escape_string($_POST['valortipo_cambio']);
        					$descripcion = 'Reservación Habitacion N°'.$habitacion.' ('.$Entrada.' - '.$Salida.')';

        					if ($tipo_cambio == 'Credito') {
        						// CREAMOS LA DEUDA DE CREDITO AL CLIENTE
        					}

        					#--- CREAMOS EL SQL PARA LA INSERCION ---
      						$sql = "INSERT INTO pagos (id_cliente, descripcion, cantidad, fecha, hora, tipo, id_user, corte, tipo_cambio) VALUES ($cliente, '$descripcion', '$anticipo', '$Fecha_hoy', '$Hora', 'Anticipo', $id_user, 0, '$tipo_cambio')";
      						#--- SE INSERTA EL PAGO -----------
					        if(mysqli_query($conn, $sql)){
					        	echo '<script>M.toast({html:"El pago se dió de alta satisfcatoriamente.", classes: "rounded"})</script>';
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

}// FIN switch
mysqli_close($conn);
    
?>