<?php
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');
//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
include('is_logged.php');
//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL

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
					            <input id="cp" type="text" class="validate" data-length="6" value="<?php echo $cliente['rfc'];?>" required>	
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

    	//CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO de "articulos_punto_venta.php"
        $Texto = $conn->real_escape_string($_POST['texto']);
        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {
			//MOSTRARA LOS ARTICULOS QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = mysqli_query($conn,"SELECT * FROM `clientes` WHERE  nombre LIKE '%$Texto%' LIMIT 1");
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

    	break;

}// FIN switch
mysqli_close($conn);
    
?>