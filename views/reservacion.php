<?php
if (isset($_POST['cliente'])) {
	echo "Mostrar informacion del cliente";
}else{
	echo "Mostrar buscador selector de cliente";
}
if (isset($_POST['habitacion'])){
	echo "Mostrar informacion de habitacion";
}else{
	echo "Seleccionar habitacion y mostrar informacion";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>San Roman | Reservar</title>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
	$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
    //Obtenemos la informacion del Usuario
    $User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
    ?>
</head>
<body>
	<div class="container">
		<div class="row col s12">
			<div class="col s12 m6">
				<div class="row"><br>
		   		  <ul class="collection">
		            <li class="collection-item avatar">
		              <div class="hide-on-large-only"><br><br></div>
		              <img src="../img/cliente.png" alt="" class="circle">
		              <span class="title"><b>DETALLES DEL CLIENTE</b></span><br><br>
		              <p class="row col s12"><b>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>NOMBRE: </b>
		              		<div class="input-field col s12 m8">
				              <input id="nombreCliente" type="text" class="validate" data-length="100" value="<?php echo 'NOMBRE DEL cliente';?>" required>	
				            </div>
		              	</div>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>DIRECCION: </b>
		              		<div class="input-field col s12 m8">
				              <input id="direccionCliente" type="text" class="validate" data-length="100" value="<?php echo 'DIRECCION DEL cliente';?>" required>	
				            </div>
		              	</div>     
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>COLONIA: </b>
		              		<div class="input-field col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>   
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>LOCALIDAD: </b>
		              		<div class="input-field col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>  
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>CODIGO POSTAL: </b>
		              		<div class="input-field col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>  
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>EMAIL: </b>
		              		<div class="input-field col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>TELEFONO: </b>
		              		<div class="input-field col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>		            		
		              </b></p><br><br>
		            </li>
		          </ul>
		        </div>
			</div>
			<div class="col s12 m6">
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

			</div>
		</div>
		
	   
	</div>

</body>
</html>