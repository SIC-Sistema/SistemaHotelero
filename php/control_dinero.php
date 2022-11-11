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

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 3 PARA VER QUE ACCION HACER (insert salida = 0, corte = 1)
$Accion = $conn->real_escape_string($_POST['accion']);
//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (insert salida = 0, corte = 1)
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:

    	//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "salida.php" QUE NESECITAMOS PARA INSERTAR
    	$Cantidad = $conn->real_escape_string($_POST['valorCantidad']);
		$Motivo = $conn->real_escape_string($_POST['valorMotivo']);

		//VERIFICAMOS QUE NO HALLA UN CLIENTE CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `salidas` WHERE (cantidad='$Cantidad' AND motivo='$Motivo' AND fecha='$Fecha_hoy')"))>0){
	 		echo '<script >M.toast({html:"Ya se encuentra un salida con los mismos datos registrados hoy.", classes: "rounded"})</script>';
	 	}else{
	 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
	 		$sql = "INSERT INTO `salidas` (cantidad, motivo, fecha, hora, usuario) 
				VALUES('$Cantidad', '$Motivo', '$Fecha_hoy', '$Hora', '$id_user')";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				echo '<script >M.toast({html:"La salida se dió de alta satisfactoriamente.", classes: "rounded"})</script>';	
				echo '<script>en_caja()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
				echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
			}//FIN else DE ERROR
	 	}// FIN else DE BUSCAR CLIENTE IGUAL

        break;
    case 1:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 1 realiza:

    	#RECIBIMOS EL LA VARIABLE valorClave CON EL METODO POST DEL DOCUMENTO corte_pagos.php DEL MODAL PARA CREAR EL CORTE
		$Clave = $conn->real_escape_string($_POST['valorClave']);
		#SEPARAMOS LA VARIABLE $Clave EN DOS PARTES ($ic) ES LA SEPARACION 
		$Partes = explode('-', $Clave);
		#SELECCIONAMOS DE LA TABLA config LA CONTRASEÑA 
		$Pass_check = mysqli_fetch_array(mysqli_query($conn, "SELECT pass FROM config"));
		//VERIFICAMOS SI LA CLAVE Y EL ID DEL USUARIO COINCIDEN
		echo $Partes[0].'-'.$Partes[1];
		if ($Partes[0] == $Pass_check['pass'] AND $Partes[1] == $id_user) {
			//PROCEDEMOS A CREAR EL CORTE
			$usuario = $conn->real_escape_string($_POST['valorUsuario']);
			$entradas = $conn->real_escape_string($_POST['valorEntradas']);
			$salidas = $conn->real_escape_string($_POST['valorSalidas']);
			$banco = $conn->real_escape_string($_POST['valorBanco']);
			$credito = $conn->real_escape_string($_POST['valorCredito']);

	        #SELECCIONAMOS UN CORTE QUE YA TENGA LOS MISMOS VALORES
	        $sql_check = mysqli_query($conn, "SELECT id_corte FROM cortes WHERE usuario = '$usuario' AND fecha = '$Fecha_hoy' AND entradas = '$entradas' AND salidas = '$salidas' AND banco = '$banco' AND credito =  '$credito' AND realizo = '$id_user'");
	        $corte = 0;//DEFINIMOS EL CORTE EN 0 PARA NO TENER ERROR
	        #VERIFICAMOS SI EXISTE YA UN CORTE CON ESTOS MISMO VALORES YA CREADO
	        if (mysqli_num_rows($sql_check)>0) {
	            #SI YA EXISTE UN CORTE TOMAMOS EL ID DE ESTE
	            $ultimo = mysqli_fetch_array($sql_check);
	            $corte = $ultimo['id_corte'];//TOMAMOS EL ID DEL CORTE
	        }else{
	            #SI NO EXISTE CREAMOS EL CORTE.....  /////////////       IMPORTANTE               /////////////
	            if (mysqli_query($conn,"INSERT INTO cortes (usuario, fecha, hora, entradas, salidas, banco, credito, realizo) VALUES ($usuario, '$Fecha_hoy', '$Hora', '$entradas', '$salidas', '$banco', '$credito', $id_user)")) {
	                #SELECCIONAMOS EL ULTIMO CORTE CREADO
	                $ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_corte) AS id FROM cortes WHERE usuario = $usuario AND realizo = $id_user"));           
	                $corte = $ultimo['id'];//TOMAMOS EL ID DEL ULTIMO CORTE
	            }// FIN IF CREAR CORTE
	        }//FIN ELSE
	        #VERIFICAMOS QUE EL ID DEL NO ESTE VACIO.
	        if ($corte != 0) {  
	            //////////////////////         MUY IMPORTANTE !!               //////////////////
	            //// CREAMOS EL DETALLE DE EL CORTE CON TODOS LOS PAGOS DEL USUARIO CON CORTE EN 0
	            $sql_pagos = mysqli_query($conn, "SELECT * FROM pagos WHERE id_user=$usuario AND corte = 0");
	            // AGREGAMOS UNO A UNO LOS PAGOS AL DETALLE DEL CORTE
	            if (mysqli_num_rows($sql_pagos)>0) {                
	                while($pago = mysqli_fetch_array($sql_pagos)){
	                    //insertar pagos de corte...
	                    $id_pago = $pago['id_pago'];
	                    mysqli_query($conn,"INSERT INTO detalles(id_corte, id_pago) VALUES ($corte, $id_pago )");
	                }
	                //// MODIFICAMOS TODOS LOS PAGOS A 1 QUE SIGNIFICA QUE SE LE HIZO CORTE
	                mysqli_query($conn,"UPDATE pagos SET corte = 1 WHERE id_user = $usuario AND corte = 0");
	            } // Fin IF PAGOS

	            //// CREAMOS EL DETALLE DE EL CORTE CON TODOS LAS SALIDAS DEL USUARIO CON CORTE EN 0
	            $sql_salidas = mysqli_query($conn, "SELECT * FROM salidas WHERE usuario=$usuario AND corte = 0");
	            // AGREGAMOS UNO A UNO LAS SALIDAS AL DETALLE DEL CORTE
	            if (mysqli_num_rows($sql_salidas)>0) {                
	                while($salida = mysqli_fetch_array($sql_salidas)){
	                    //insertar salida de corte...
	                    $id_salida = $salida['id'];
	                    mysqli_query($conn,"INSERT INTO detalles(id_corte, id_salida) VALUES ($corte, $id_salida )");
	                }
	                //// MODIFICAMOS TODAS LAS SALIDAS A 1 QUE SIGNIFICA QUE SE LE HIZO CORTE
	                mysqli_query($conn,"UPDATE salidas SET corte = 1 WHERE usuario = $id_user AND corte = 0");
	            }// FIN IF SALIDAS
	            ?>
	            <script>	                
	                var a = document.createElement("a");
	                    a.target = "_blank";
	                    a.href = "../php/imprimir_corte.php?id="+<?php echo $corte; ?>;
	                    a.click();
	                //RECARGAMOS LA PAGINA cortes_pagos.php EN 1500 Milisegundos = 1.5 SEGUNDOS
	                setTimeout("location.href='../views/cajas.php'", 1500);
	            </script>
	            <?php                  
	        }
		}else{
		    #SI LA CLAVE NO ES IGUAL A LA CONTRASEÑA SELECCIONADA DEL USUARIO MANDAR ALERTA
		    echo '<script>M.toast({html:"Clave no admitida intente nuevamente...", classes: "rounded"})</script>';
		}
        break;
    case 2:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 2 realiza:

    	//Obtenemos la informacion del Usuario
    	$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    	//SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE EDITAR CLIENTES
    	if ($User['clientes'] == 1) {

	    	//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "editar_cliente.php" QUE NESECITAMOS PARA ACTUALIZAR
	    	$id = $conn->real_escape_string($_POST['id']);
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
			if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `clientes` WHERE (telefono = '$Telefono' OR rfc='$RFC' OR email='$Email') AND id != $id"))>0){
		 		echo '<script >M.toast({html:"El RFC, Telefono o Email ya se encuentra registrados en la BD.", classes: "rounded"})</script>';
		 	}else{
				//CREAMO LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL CLIENTE Y LA GUARDAMOS EN UNA VARIABLE
				$sql = "UPDATE `clientes` SET nombre = '$Nombre', telefono = '$Telefono', email = '$Email', rfc = '$RFC', direccion = '$Direccion', colonia = '$Colonia', localidad = '$Localidad', cp = '$CP', limpieza = '$Limpieza' WHERE id = '$id'";
				//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
				if(mysqli_query($conn, $sql)){
					echo '<script >M.toast({html:"El cliente se actualizo con exito.", classes: "rounded"})</script>';	
					echo '<script>recargar_clientes()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
				}else{
					echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
				}//FIN else DE ERROR
			}// FIN else Validacion
		}//FIN IF permiso
        break;
    case 3:
        // $Accion es igual a 3 realiza:
    	//CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "clientes.php" QUE NESECITAMOS PARA BORRAR
    	$id = $conn->real_escape_string($_POST['id']);
    	//Obtenemos la informacion del Usuario
    	$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    	//SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR CLIENTES
    	if ($User['clientes'] == 1) {
    		#SELECCIONAMOS LA INFORMACION A BORRAR
    		$cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id = $id"));
    		#CREAMOS EL SQL DE LA INSERCION A LA TABLA  `pv_borrar_cliente` PARA NO PERDER INFORMACION
			$sql = "INSERT INTO `pv_borrar_cliente` (id_cliente, nombre, telefono, direccion, colonia, cp, rfc, email, localidad, registro, borro, fecha_borro) 
				VALUES($id, '".$cliente['nombre']."', '".$cliente['telefono']."', '".$cliente['direccion']."', '".$cliente['colonia']."', '".$cliente['cp']."', '".$cliente['rfc']."', '".$cliente['email']."', '".$cliente['localidad']."', '".$cliente['usuario']."', '$id_user','$Fecha_hoy')";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				//SI DE CREA LA INSERCION PROCEDEMOS A BORRRAR DE LA TABLA `clientes`
	    		#VERIFICAMOS QUE SE BORRE CORRECTAMENTE EL CLIENTE DE `clientes`
				if(mysqli_query($conn, "DELETE FROM `clientes` WHERE `clientes`.`id` = $id")){
				  #SI ES ELIMINADO MANDAR MSJ CON ALERTA
				  echo '<script >M.toast({html:"Cliente borrado con exito.", classes: "rounded"})</script>';
				  echo '<script>recargar_clientes()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
				}else{
				  #SI NO ES BORRADO MANDAR UN MSJ CON ALERTA
				  echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
				}
			}
	    }else{
			echo '<script >M.toast({html:"Permiso denegado.", classes: "rounded"});
			M.toast({html:"Comunicate con un administrador.", classes: "rounded"});</script>';
	    }   
    	break;
    case 4:
        // $Accion es igual a 4 realiza:
    	//CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "clientes.php" QUE NESECITAMOS PARA BORRAR
    	$id = $conn->real_escape_string($_POST['id']);
    	//Obtenemos la informacion del Usuario
    	$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    	//SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR INGRESOS/EGRESOS
    	if ($User['borrar'] == 1) {
    		$motivo = $conn->real_escape_string($_POST['motivo']);
    		#SELECCIONAMOS LA INFORMACION A BORRAR
    		$pago = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `pagos` WHERE id_pago = $id"));
    		#CREAMOS EL SQL DE LA INSERCION A LA TABLA  `pagos_borrados` PARA NO PERDER INFORMACION
			$sql = "INSERT INTO `pagos_borrados` (cliente, cantidad, descripcion, realizo, tipo_cambio, fecha_hora_registro, motivo, borro, fecha_borrado) 
				VALUES('".$pago['id_cliente']."', '".$pago['cantidad']."', '".$pago['descripcion']." (".$pago['tipo'].")', '".$pago['id_user']."', '".$pago['tipo_cambio']."', '".$pago['fecha']." ".$pago['hora']."', '".$motivo."', '$id_user','$Fecha_hoy')";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				//SI DE CREA LA INSERCION PROCEDEMOS A BORRRAR DE LA TABLA `pagos`
	    		#VERIFICAMOS QUE SE BORRE CORRECTAMENTE EL PAGO DE `pagos`
				if(mysqli_query($conn, "DELETE FROM `pagos` WHERE `pagos`.`id_pago` = $id")){
				  #SI ES ELIMINADO MANDAR MSJ CON ALERTA
				  echo '<script >M.toast({html:"Pago/Ingreso borrado con exito.", classes: "rounded"})</script>';
    			  $redireciona = $conn->real_escape_string($_POST['redireciona']);
    			  if ($redireciona == 0) {
    			  	echo '<script>recargar_clientes()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
    			  }else{
    			  	?>
			        <script>
			          var a = document.createElement("a");
			            a.href = "../views/detalles_cuenta.php?id="+<?php echo $redireciona; ?>;
			            a.click();
			        </script>
			        <?php
    			  }
				}else{
				  #SI NO ES BORRADO MANDAR UN MSJ CON ALERTA
				  echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
				}
			}
	    }else{
			echo '<script >M.toast({html:"Permiso denegado.", classes: "rounded"});
			M.toast({html:"Comunicate con un administrador.", classes: "rounded"});</script>';
	    }   
    	break;
}// FIN switch
mysqli_close($conn);