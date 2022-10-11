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
<body>
	<div class="container">
		<div class="row col s12">
			<div class="col s12 m6">
				<div class="row"><br>
		   		  <ul class="collection">
		            <li class="collection-item avatar">
		              <img src="../img/cliente.png" alt="" class="circle">
		              <span class="title"><b>DETALLES DEL CLIENTE</b></span><br><br>
		              <p class="row col s12"><b>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>NOMBRE: </b>
		              		<div class="col s12 m8">
				              <input id="nombreCliente" type="text" class="validate" data-length="100" value="<?php echo 'NOMBRE DEL cliente';?>" required>	
				            </div>
		              	</div>
		              	<div class="col s12" id="clienteBusqueda"><br><br></div>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>DIRECCION: </b>
		              		<div class="col s12 m8">
				              <input id="direccionCliente" type="text" class="validate" data-length="100" value="<?php echo 'DIRECCION DEL cliente';?>" required>	
				            </div>
		              	</div>     
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>COLONIA: </b>
		              		<div class="col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>   
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>LOCALIDAD: </b>
		              		<div class="col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>  
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4">CODIGO POSTAL: </b>
		              		<div class="col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>  
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>EMAIL: </b>
		              		<div class="col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>TELEFONO: </b>
		              		<div class="col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'colonia DEL cliente';?>" required>	
				            </div>
		              	</div>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>LIMPIEZA: </b>
		              		<div class="col s12 m8">
				              <input id="coloniaCliente" type="text" class="validate" data-length="100" value="<?php echo 'LIMPIAR ASI Y ASI';?>" required>	
				            </div>
		              	</div>		            		
		              </b></p>
		            </li>
		          </ul>
		        </div>
			</div>
			<div class="col s12 m6">
				<div class="row"><br>
		   		  <ul class="collection">
		            <li class="collection-item avatar"><br>
		              <img src="../img/hotel.png" alt="" class="circle">
		              <span class="title"><b>DETALLES DE HABITACION</b></span><br><br>
		              <p class="row col s12"><b>
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5"><br>N° HABITACION: </b>
		              		<div class="col s12 m7">
					            <select id="piso" name="piso" class="validate">              
					              <!--OPTION PARA QUE LA SELECCION QUEDE POR DEFECTO-->
					              <option value="0" select>Nievel / Piso</option>
					              <option value="Primer">Primer</option>
					              <option value="Segundo">Segundo</option>
					              <option value="Tercer">Tercer</option>
					              <option value="Cuarto">Cuarto</option>
					            </select>
					        </div>
		              	</div>
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">ESTADO: </b>
		              		<span class="new badge <?php echo (0 == 0)?'green':'red';?> prefix" data-badge-caption=""><?php echo (0 == 0)?'Disponible':'Ocupada';?></span>
		              	</div>      
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">NIVEL / PISO: </b>
		              		<?php echo 'Primer piso';?>
		              	</div> 
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">HASTA: </b>
		              		<?php echo 'Por Definir';?>
		              	</div>           		
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">DESCRIPCION: </b>
		              		<?php echo 'ESTA HABITACION TIENE UNA GRAN COSA';?>
		              	</div>
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">TIPO DE HABITACION: </b>
		              		<br><?php echo '$habitacion[]';?>
		              	</div> 
		              	<div class="col s12"><br><br>
		              		<b class="indigo-text col s12 m5">PRECIO POR DIA: </b>
		              		<div class="col s10 m6">
				              <input id="precioXDia" type="text" class="validate" data-length="100" value="<?php echo '$'.sprintf('%.2f', 10);?>" required>	
				            </div>   		
		              	</div>
		              </b></p>
		            </li>
		          </ul>
		        </div>        
			</div>
		</div>
		<div class="row">
		    <h4>Reservar</h4>
			<hr>
		    <div class="col s12 m3">Nombre</div>
		    <div class="col s12 m3">De</div>
		    <div class="col s12 m3">Hasta</div>
		    <div class="col s12 m3">Total</div>
		</div>
		<div class="right">BOTON</div><br><br><br>
	   
	</div>

</body>
</html>