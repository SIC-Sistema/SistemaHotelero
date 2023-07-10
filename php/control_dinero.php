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

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 3 PARA VER QUE ACCION HACER (insert salida = 0, corte = 1, insert abono = 2, buscar cortes = 3)
$Accion = $conn->real_escape_string($_POST['accion']);
//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (insert salida = 0, corte = 1, insert abono = 2, buscar cortes = 3)
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
				$ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id) AS id FROM salidas WHERE usuario = $id_user"));            
        		$id = $ultimo['id'];
				?>
	            <script>	                
	                var a = document.createElement("a");
	                    a.target = "_blank";
	                    a.href = "../php/ticket_salida.php?id="+<?php echo $id; ?>;
	                    a.click();
	            </script>
	            <?php    
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
		if ($Partes[0] == $Pass_check['pass'] AND $Partes[1] == $id_user) {
			//PROCEDEMOS A CREAR EL CORTE
			$usuario = $conn->real_escape_string($_POST['valorUsuario']);
			$entradas = $conn->real_escape_string($_POST['valorEntradas']);
			$salidas = $conn->real_escape_string($_POST['valorSalidas']);
			$banco = $conn->real_escape_string($_POST['valorBanco']);
			$credito = $conn->real_escape_string($_POST['valorCredito']);
			$montoEnCaja = $conn->real_escape_string($_POST['valorMontoEnCaja']);
	        #SELECCIONAMOS UN CORTE QUE YA TENGA LOS MISMOS VALORES
	        $sql_check = mysqli_query($conn, "SELECT id_corte FROM cortes WHERE usuario = '$usuario' AND fecha = '$Fecha_hoy' AND entradas = '$entradas' AND salidas = '$salidas' AND banco = '$banco' AND credito =  '$credito' AND realizo = '$id_user' AND corte = 0");
	        $corte = 0;//DEFINIMOS EL CORTE EN 0 PARA NO TENER ERROR
	        #VERIFICAMOS SI EXISTE YA UN CORTE CON ESTOS MISMO VALORES YA CREADO
			$tipo = $conn->real_escape_string($_POST['tipo']);
	        if (mysqli_num_rows($sql_check)>0 AND $tipo != 2) {
	            #SI YA EXISTE UN CORTE TOMAMOS EL ID DE ESTE
	            $ultimo = mysqli_fetch_array($sql_check);
	            $corte = $ultimo['id_corte'];//TOMAMOS EL ID DEL CORTE
	        }else{
				
	            #SI NO EXISTE CREAMOS EL CORTE.....  /////////////       IMPORTANTE               /////////////
				$ultimoCorte 	= mysqli_fetch_array(mysqli_query($conn,"SELECT  MAX(id_corte) AS id  FROM cortes"));
				$ultimoIdCorte 	= $ultimoCorte['id'];
				$enCajaInicio	= mysqli_fetch_array(mysqli_query($conn, "SELECT  en_caja FROM cortes WHERE id_corte ='$ultimoIdCorte'"));
				$efectivo 		= $enCajaInicio['en_caja'] + $entradas - $montoEnCaja;
	            if (mysqli_query($conn,"INSERT INTO cortes (usuario, fecha, hora, entradas, salidas, banco, credito, realizo, en_caja) VALUES ($usuario, '$Fecha_hoy', '$Hora', '$efectivo', '$salidas', '$banco', '$credito', $id_user, '$montoEnCaja')")) {
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
	                    if(mysqli_query($conn,"INSERT INTO `detalles_corte` (`id_corte`, `id_pago`) VALUES ($corte, $id_pago )")){
	                    		#echo "SIMON pagos<br>";
	                    }
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
	                    if(mysqli_query($conn,"INSERT INTO `detalles_corte` (`id_corte`, `id_salida`) VALUES ($corte, $id_salida )")){
	                    	#echo "SIMON salida<br>";
	                    }
	                }
	                //// MODIFICAMOS TODAS LAS SALIDAS A 1 QUE SIGNIFICA QUE SE LE HIZO CORTE
	                mysqli_query($conn,"UPDATE salidas SET corte = 1 WHERE usuario = $usuario AND corte = 0");
	            }// FIN IF SALIDAS

	            if ($tipo == 2 AND $usuario == $id_user) {
	            	//// CREAMOS EL DETALLE DE EL CORTE CON TODOS LAS SALIDAS DEL USUARIO CON CORTE EN 0
		            $sql_cort = mysqli_query($conn, "SELECT * FROM cortes WHERE realizo=$id_user AND corte = 0");
		            // AGREGAMOS UNO A UNO LAS SALIDAS AL DETALLE DEL CORTE
		            if (mysqli_num_rows($sql_cort)>0) {                
		                while($cort = mysqli_fetch_array($sql_cort)){
		                    //insertar corte de corte...
		                    $id_corte = $cort['id_corte'];
		                    if ($corte != $id_corte){
		                    	mysqli_query($conn,"INSERT INTO `detalles_corte` (`id_corte`, `corte`) VALUES ($corte, $id_corte )");
		                    }//FIN IF
		                }//FIN WHILE
		                //// MODIFICAMOS TODAS LAS SALIDAS A 1 QUE SIGNIFICA QUE SE LE HIZO CORTE
		                mysqli_query($conn,"UPDATE cortes SET corte = 1 WHERE realizo = $id_user AND corte = 0");
		            }// FIN IF SALIDAS
	            }
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

    	//OBTENEMOS LA INFORMACION PARA HACER EL ABONO detalles_credito.php
    	$Tipo_Campio = $conn->real_escape_string($_POST['valorTipo_Campio']);
		$Cantidad = $conn->real_escape_string($_POST['valorCantidad']);
		$Descripcion = $conn->real_escape_string($_POST['valorDescripcion']);
		$IdCliente = $conn->real_escape_string($_POST['valorIdCliente']);
    	//SE VERIFICA QUE NO HALLA UN PAGOO CON LOS MISMOS VALORES
    	if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pagos WHERE id_cliente = $IdCliente AND descripcion = '$Descripcion' AND cantidad='$Cantidad' AND fecha='$Fecha_hoy'"))>0){
			echo '<script>M.toast({html:"Ya se encuentra un abono registrado con los mismos valores el día de hoy.", classes: "rounded"})</script>';
		}else{ 
			//INSERTAMOS EL PAGO TIPO ABONO CREDITO
			$sql = "INSERT INTO pagos (id_cliente, cantidad, fecha, hora, descripcion , tipo_cambio, id_user, tipo, corte) VALUES ($IdCliente, '$Cantidad', '$Fecha_hoy', '$Hora', '$Descripcion', '$Tipo_Campio', '$id_user', 'Abono Credito', 0)";
			if(mysqli_query($conn, $sql)){
				$ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_pago) AS id FROM pagos WHERE id_cliente = $IdCliente"));            
        		$id_pago = $ultimo['id'];
        		if ($Tipo_Campio == 'Banco') {
        			$ReferenciaB = $conn->real_escape_string($_POST['referenciaB']);
        			echo $id_pago.' '.$ReferenciaB;
        			mysqli_query($conn,  "INSERT INTO referencias (id_pago, descripcion) VALUES ('$id_pago', '$ReferenciaB')");
        		}
				$Deuda_check = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM deudas WHERE id_cliente = $IdCliente AND liquidada=0 limit 1"));
     
			    // SACAMOS LA SUMA DE TODAS LAS DEUDAS QUE ESTAN LIQUIDADDAS Y TODOS LOS ABONOS ....
			    $deuda = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(cantidad) AS suma FROM deudas WHERE id_cliente = $IdCliente AND liquidada = 1"));
			    $abono = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(cantidad) AS suma FROM pagos WHERE id_cliente = $IdCliente AND tipo = 'Abono Credito'"));
			    $deuda['suma'] = ($deuda['suma'] == "")? 0 : $deuda['suma'];
			    $abono['suma'] = ($abono['suma'] == "")? 0 : $abono['suma'];
			    
			    $Resta = $abono['suma']-$deuda['suma'];// SACAMO LA DIFERENCIA PARA VER CUANTAS DEUDAS ALCANZA A LIQUIDAR CON ESTA CANTIDAD

			    $Entra = False;// SI ENTRA = false no ejecuta el while y no cambia nunguna deuda a liquidada = 1
			    //VERIFICAMOS QUE LA DEUDA A COMPRAR TENGA ALGUN VALOR MAYOR A 0
			    if ($Deuda_check['cantidad'] <=0) {
			      $Entra = False;
			    }else if ($Deuda_check['cantidad'] <= $Resta) {//AHORA VERIFICAMOS SI LA DEUDA SELECCIONADA ES MENOR O IGUAL A LA CANTIDAD DE RESTA
			      // SI ES MENOS O IGUAL QUIERE DECIR QUE LA DEUDA ALCANZA A SER LIQUIDADA Y LE DAMOS ENTRADA = true PARA QUE ENTRE AL WHILE
			      $Entra = True;  
			    }
			    $id_deuda = $Deuda_check['id_deuda'];// SACAMOS EL ID DE LA DEUDA SELECCIONADA
			    while ($Entra) {
			      //VERIFICAMOS QUE SE CAMBIE EL ESTATUS DE LA DEUDA
			      if (mysqli_query($conn, "UPDATE deudas SET liquidada = 1 WHERE id_deuda = $id_deuda")) {
			        echo '<script>M.toast({html:"Deuda liquidada.", classes: "rounded"})</script>';
			      }  
			      //COMO SE CAMBIO EL ESTATUS DE LA DEUDA VOLVEMOS A SACAR LA SUMA DE LAS DEUDAS LIQUIDADAS Y MODIFICAR LA CANTIDAD $Resta
			      $deuda = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(cantidad) AS suma FROM deudas WHERE id_cliente = $IdCliente AND liquidada = 1"));
			      $deuda['suma'] = ($deuda['suma'] == "")? 0 : $deuda['suma'];

			      $Resta = $abono['suma']-$deuda['suma'];
			      //SELECCIONAMOS OTRA DEUDA
			      $Deuda_check = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM deudas WHERE id_cliente = $IdCliente AND liquidada=0 limit 1"));

			      $Entra = False;// SI EL VALOR ES FALSE EL WHILE TERMINA 
			      //VERIFICAMOS QUE LA DEUDA A COMPRAR TENGA ALGUN VALOR MAYOR A 0
			      if ($Deuda_check['cantidad'] <=0) {
			        $Entra = False;
			      }else if ($Deuda_check['cantidad'] <= $Resta) {//AHORA VERIFICAMOS SI LA DEUDA SELECCIONADA ES MENOR O IGUAL A LA CANTIDAD DE RESTA
			        // SI ES MENOS O IGUAL QUIERE DECIR QUE LA DEUDA ALCANZA A SER LIQUIDADA Y LE DAMOS ENTRADA = true PARA QUE CONTINUE EL WHILE
			        $Entra = True;  
			      } 
			      $id_deuda = $Deuda_check['id_deuda'];// SACAMOS EL ID DE LA OTRA DEUDA SELECCIONADA
			    }//FIN WHILE
				echo '<script>M.toast({html:"El abono se dió de alta satisfcatoriamente.", classes: "rounded"})</script>';	
				$ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id_pago) AS id FROM pagos WHERE id_cliente = $IdCliente AND id_user = $id_user"));            
        		$id_pago = $ultimo['id'];
				echo '<script>M.toast({html:"El pago se dió de alta satisfcatoriamente.", classes: "rounded"})</script>';
				?>
				<script>
				  var a = document.createElement("a");
					a.target = "_blank";
					a.href = "../php/imprimir.php?id="+<?php echo $id_pago; ?>;
					a.click();
				
			      var a = document.createElement("a");
			        a.href = "../views/detalles_credito.php?id_cte="+<?php echo $IdCliente; ?>;
			        a.click();
			    </script>
			    <?php    		
			}// FIN IF  INSERT
		}//FIN esle validacion
        break;
    case 3:
        // $Accion es igual a 3 realiza:
    	//CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "historial_cortes.php" QUE NESECITAMOS PARA BORRAR
    	$ValorDe = $conn->real_escape_string($_POST['valorDe']);
		$ValorA = $conn->real_escape_string($_POST['valorA']);

    	//Obtenemos la informacion del Usuario
    	$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    	//SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR CLIENTES
    	if ($User['cortes'] == 1) {
    		?>
	    	<table class="bordered highlight responsive-table">
				<thead>
					<tr>
						<th>Id Corte</th>
						<th>Usuarios</th>				        
				        <th>Entradas</th>
			            <th>Salidas</th>
			            <th>Efectivo</th>
			            <th>Banco</th>
			            <th>Credito</th>
			            <th>Realizo</th>
			            <th>Mtos</th>
			            <th>Detalles</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$resultado_cortes = mysqli_query($conn, "SELECT * FROM cortes WHERE fecha>='$ValorDe' AND fecha<='$ValorA' ORDER BY usuario DESC");
				$aux = mysqli_num_rows($resultado_cortes);
				if($aux>0){
					$totalmovimientos = 0;
					$totalefectivo= 0;
					$totalbanco = 0;
					$totalcredito = 0;
					while($cortes = mysqli_fetch_array($resultado_cortes)){
						$id_corte =$cortes['id_corte'];
						$movimientos = mysqli_fetch_array(mysqli_query($conn,"SELECT count(*) FROM detalles_corte WHERE id_corte = $id_corte"));
						$id_usuario = $cortes['usuario'];
						$usuario = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id = $id_usuario"));
						$id_realizo = $cortes['realizo'];
						$realizo = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id = $id_realizo"));
						?>
						  <tr>
						    <td><b><?php echo $id_corte;?></b></td>
						    <td><?php echo $usuario['firstname'] ?></td>
						    <td>$<?php echo sprintf('%.2f', $cortes['entradas']);?></td>
						    <td>$<?php echo sprintf('%.2f', $cortes['salidas']); ?></td>
						    <td>$<?php echo sprintf('%.2f', $cortes['entradas']-$cortes['salidas']); ?></td>
						    <td><?php echo sprintf('%.2f', $cortes['banco']);?></td>
						    <td><?php echo sprintf('%.2f', $cortes['credito']);?></td>
						    <td><?php echo $realizo['firstname'];?></td>
						    <td><?php echo $movimientos['count(*)'];?></td>
						    <td><form method="post" action="../views/detalle_corte_cut.php"><input id="id_corte" name="id_corte" type="hidden" value="<?php echo $cortes['id_corte']; ?>"><button class="btn-small btn-tiny waves-effect waves-light grey darken-4"><i class="material-icons">credit_card</i></button></form></td>
						  </tr>
						  <?php
						  $totalefectivo += $cortes['entradas']-$cortes['salidas'];
						  $totalbanco += $cortes['banco'];
						  $totalcredito += $cortes['credito'];
						  $totalmovimientos += $movimientos['count(*)'];
						  $aux--;
					}//FIN WHILE
					?>
					  <tr>
					  	<td colspan="4" class="center"><h5>TOTAL:</h5></td>
					  	<td><h5>$<?php echo sprintf('%.2f', $totalefectivo); ?></h5></td>
					  	<td><h5>$<?php echo sprintf('%.2f', $totalbanco); ?></h5></td>
					  	<td><h5>$<?php echo sprintf('%.2f', $totalcredito); ?></h5></td>
					  	<td><h5>TOTAL:</h5></td>
					  	<td><h5><?php echo $totalmovimientos;?></h5></td>
					  	<td></td>
					  </tr>
					<?php
				}else{
				  echo "<center><b><h5>No se encontraron cortes para estas fechas</h5></b></center>";
				}// FIN WHILE
				?>	
				</tbody>
			</table><br><br>
			<?php
		}// FIN IF CORTES
    	break;
    case 4:
        // $Accion es igual a 3 realiza:

    	//Obtenemos la informacion del Usuario
    	$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    	//SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR CLIENTES
    	if ($User['area'] == 'Administrador') {
    		?>
	    	<table>
				<thead>
					<tr>
					  <th>N°</th>
	                  <th>Cliente</th>
	                  <th>Tipo</th>
	                  <th>Descripcion</th>
	                  <th>Usuario</th>
	                  <th>Fecha y Hora</th>
	                  <th>Cambio</th> 
	                  <th>Cantidad</th>
					</tr>
				</thead>
				<tbody>
				<?php
				//CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "reportes.php" QUE NESECITAMOS PARA BORRAR
		    	$ValorDe = $conn->real_escape_string($_POST['valorDe']);
				$ValorA = $conn->real_escape_string($_POST['valorA']);
				if ($ValorA == '' OR $ValorDe == '') {
					echo '<script >M.toast({html:"Seleccione un rango de fechas.", classes: "rounded"})</script>';
				}else{
					$resultado_entradas = mysqli_query($conn, "SELECT * FROM pagos WHERE fecha>='$ValorDe' AND fecha<='$ValorA' AND tipo_cambio != 'Credito' ORDER BY fecha, hora DESC");
					$aux = mysqli_num_rows($resultado_entradas);
					if($aux>0){
						$total = 0;
						while($entrada = mysqli_fetch_array($resultado_entradas)){
							$id_usuario = $entrada['id_user'];
							$usuario = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id = $id_usuario"));
							$id_cliente = $entrada['id_cliente'];
							$cliente = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM clientes WHERE id = $id_cliente"));
							?>
							  <tr>
							    <td><b><?php echo $entrada['id_pago'];?></b></td>
							    <td><?php echo $cliente['nombre'] ?></td>
							    <td><?php echo $entrada['tipo'];?></td>
							    <td><?php echo $entrada['descripcion'];?></td>
							    <td><?php echo $usuario['firstname'] ?></td>
							    <td><?php echo $entrada['fecha'].' '.$entrada['hora'];?></td>
							    <td><?php echo $entrada['tipo_cambio'] ?></td>
							    <td>$<?php echo sprintf('%.2f', $entrada['cantidad']);?></td>
							  </tr>
							  <?php
							  $total += $entrada['cantidad'];
						}//FIN WHILE
						?>
						  <tr>
						  	<td colspan="7" class="center"><h5>TOTAL:</h5></td>
						  	<td><h5>$<?php echo sprintf('%.2f', $total); ?></h5></td>
						  	<td></td>
						  </tr>
						<?php
					}else{
					  echo '<script >M.toast({html:"No se encontraron entradas.", classes: "rounded"})</script>';
					}// FIN else
				}
				?>	
				</tbody>
			</table><br><br>
			<?php
		}// FIN IF CORTES
    	break;
    case 5:
        // $Accion es igual a 3 realiza:

    	//Obtenemos la informacion del Usuario
    	$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    	//SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR CLIENTES
    	if ($User['area'] == 'Administrador') {
    		?>
	    	<table>
				<thead>
					<tr>
					  <th>N°</th>
	                  <th>Motivo</th>
	                  <th>Usuario</th>
	                  <th>Fecha y Hora</th>
	                  <th>Cantidad</th>
					</tr>
				</thead>
				<tbody>
				<?php
				//CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "reportes.php" QUE NESECITAMOS PARA BORRAR
		    	$ValorDe = $conn->real_escape_string($_POST['valorDe']);
				$ValorA = $conn->real_escape_string($_POST['valorA']);
				if ($ValorA == '' OR $ValorDe == '') {
					echo '<script >M.toast({html:"Seleccione un rango de fechas.", classes: "rounded"})</script>';
				}else{
					$resultado_salidas = mysqli_query($conn, "SELECT * FROM salidas WHERE fecha>='$ValorDe' AND fecha<='$ValorA' ORDER BY fecha, hora DESC");
					$aux = mysqli_num_rows($resultado_salidas);
					if($aux>0){
						$total = 0;
						while($salida = mysqli_fetch_array($resultado_salidas)){
							$id_usuario = $salida['usuario'];
							$usuario = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id = $id_usuario"));
							?>
							 <tr>
							    <td><b><?php echo $salida['id'];?></b></td>
							    <td><?php echo $salida['motivo'];?></td>
							    <td><?php echo $usuario['firstname'] ?></td>
							    <td><?php echo $salida['fecha'].' '.$salida['hora'];?></td>
							    <td>$<?php echo sprintf('%.2f', $salida['cantidad']);?></td>
							 </tr>
							  <?php
							  $total += $salida['cantidad'];
						}//FIN WHILE
						?>
						  <tr>
						  	<td colspan="4" class="center"><h5>TOTAL:</h5></td>
						  	<td><h5>$<?php echo sprintf('%.2f', $total);?></h5></td>
						  </tr>
						<?php
					}else{
					  echo '<script >M.toast({html:"No se encontraron salidas.", classes: "rounded"})</script>';
					}// FIN WHILE
				}
				?>	
				</tbody>
			</table><br><br>
			<?php
		}// FIN IF CORTES
    	break;
    case 6:
        // $Accion es igual a 3 realiza:

    	//Obtenemos la informacion del Usuario
    	$User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    	//SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR CLIENTES
    	if ($User['area'] == 'Administrador') {
    	  //CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "reportes.php" QUE NESECITAMOS PARA BORRAR
		  $ValorDe = $conn->real_escape_string($_POST['valorDe']);
		  $ValorA = $conn->real_escape_string($_POST['valorA']);
		  if ($ValorA == '' OR $ValorDe == '') {
			echo '<script >M.toast({html:"Seleccione un rango de fechas.", classes: "rounded"})</script>';
	      }else{
    		?>
    		<div class="row col s12">
    			<div class="col s12 m6">
    				<table>
						<thead>
							<tr>
							  <th>N°</th>
			                  <th>Tipo</th>
			                  <th>Usuario</th>
			                  <th>Fecha</th>
			                  <th>Cambio</th> 
			                  <th>Cantidad</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$resultado_entradas = mysqli_query($conn, "SELECT * FROM pagos WHERE fecha>='$ValorDe' AND fecha<='$ValorA' AND tipo_cambio != 'Credito' ORDER BY fecha, hora DESC");
						$aux = mysqli_num_rows($resultado_entradas);
						if($aux>0){
							$total = 0;
							while($entrada = mysqli_fetch_array($resultado_entradas)){
								$id_usuario = $entrada['id_user'];
								$usuario = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id = $id_usuario"));
								?>
								  <tr>
								    <td><b><?php echo $entrada['id_pago'];?></b></td>
								    <td><?php echo $entrada['tipo'];?></td>
								    <td><?php echo $usuario['firstname'] ?></td>
								    <td><?php echo $entrada['fecha'].' '.$entrada['hora'];?></td>
								    <td><?php echo $entrada['tipo_cambio'] ?></td>
								    <td>$<?php echo sprintf('%.2f', $entrada['cantidad']);?></td>
								  </tr>
								  <?php
								  $total += $entrada['cantidad'];
							}//FIN WHILE
							?>
							  <tr>
							  	<td colspan="5" class="center"><h5>TOTAL:</h5></td>
							  	<td><h5>$<?php echo sprintf('%.2f', $total); ?></h5></td>
							  	<td></td>
							  </tr>
							<?php
						}else{
						  echo '<script >M.toast({html:"No se encontraron entradas.", classes: "rounded"})</script>';
						}// FIN WHILE
						?>	
						</tbody>
					</table>
    			</div>
    			<div class="col s12 m6">
    				<table>
						<thead>
							<tr>
							  <th>N°</th>
			                  <th>Motivo</th>
			                  <th>Usuario</th>
			                  <th>Fecha y Hora</th>
			                  <th>Cantidad</th> 
							</tr>
						</thead>
						<tbody>
						<?php				
						$resultado_salidas = mysqli_query($conn, "SELECT * FROM salidas WHERE fecha>='$ValorDe' AND fecha<='$ValorA' ORDER BY fecha, hora DESC");
						$aux = mysqli_num_rows($resultado_salidas);
						if($aux>0){
							$total = 0;
							while($salida = mysqli_fetch_array($resultado_salidas)){
								$id_usuario = $salida['usuario'];
								$usuario = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id = $id_usuario"));
								?>
								 <tr>
								    <td><b><?php echo $salida['id'];?></b></td>
								    <td><?php echo $salida['motivo'];?></td>
								    <td><?php echo $usuario['firstname'] ?></td>
								    <td><?php echo $salida['fecha'].' '.$salida['hora'];?></td>
								    <td>$<?php echo sprintf('%.2f', $salida['cantidad']);?></td>
								 </tr>
								  <?php
								  $total += $salida['cantidad'];
							}//FIN WHILE
							?>
							  <tr>
							  	<td colspan="4" class="center"><h5>TOTAL:</h5></td>
							  	<td><h5>$<?php echo sprintf('%.2f', $total);?></h5></td>
							  </tr>
							<?php
						}else{
						  echo '<script >M.toast({html:"No se encontraron salidas.", classes: "rounded"})</script>';
						}// FIN else					
						?>
						</tbody>
					</table>
    			</div>   			
    		</div>
			<?php
          }//FIN IF FECHAS
		}// FIN IF AMINISTRADOR
    	break;
}// FIN switch
mysqli_close($conn);