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
		              <img src="../img/cliente.png" alt="" class="circle">
		              <span class="title"><b>DETALLES DEL CLIENTE</b></span><br><br>
		              <?php
						if (isset($_POST['cliente'])) {
							echo "Mostrar informacion del cliente";
						}else{
							$NOMBRE = ''; $DIRECCION = ''; $COLONIA = ''; $LOCALIDAD = ''; $CP = '';
							$EMAIL = ''; $TELEFONO = ''; $LIMPIEZA = ''; $IdCliente = 0;
						}
					  ?>
		              <p class="row col s12"><b>
		              	<input type="hidden" id="id_cliente" value="<?php echo $IdCliente;?>">
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>NOMBRE: </b>
		              		<div class="col s12 m8">
				              <input id="nombreCliente" type="text" class="validate" data-length="100" value="<?php echo $NOMBRE;?>" required>	
				            </div>
		              	</div>
		              	<div class="col s12" id="clienteBusqueda"><br><br></div>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>DIRECCION: </b>
		              		<div class="col s12 m8">
				              <input id="direccion" type="text" class="validate" data-length="100" value="<?php echo $DIRECCION;?>" required>	
				            </div>
		              	</div>     
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>COLONIA: </b>
		              		<div class="col s12 m8">
				              <input id="colonia" type="text" class="validate" data-length="20" value="<?php echo $COLONIA;?>" required>	
				            </div>
		              	</div>   
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>LOCALIDAD: </b>
		              		<div class="col s12 m8">
				              <input id="localidad" type="text" class="validate" data-length="20" value="<?php echo $LOCALIDAD;?>" required>	
				            </div>
		              	</div>  
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4">CODIGO POSTAL: </b>
		              		<div class="col s12 m8">
				              <input id="cp" type="text" class="validate" data-length="6" value="<?php echo $CP;?>" required>	
				            </div>
		              	</div>  
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>EMAIL: </b>
		              		<div class="col s12 m8">
				              <input id="email" type="text" value="<?php echo $EMAIL;?>" required>	
				            </div>
		              	</div>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>TELEFONO: </b>
		              		<div class="col s12 m8">
				              <input id="telefono" type="text" class="validate" data-length="10" value="<?php echo $TELEFONO;?>" required>	
				            </div>
		              	</div>
		              	<div class="col s12">
		              		<b class="indigo-text col s12 m4"><br>LIMPIEZA: </b>
		              		<div class="col s12 m8">
				              <input id="limpieza" type="text" class="validate" data-length="100" value="<?php echo $LIMPIEZA;?>" required>	
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
		              		<b class="indigo-text col s12 m5"><br>HABITACION: </b>
		              		<div class="col s12 m6">
					            <select id="habitacion" name="habitacion" class="validate">              
					              <!--OPTION PARA QUE LA SELECCION QUEDE POR DEFECTO-->
					              <?php
			              			if (isset($_POST['habitacion'])){
										echo '<option value="'.$_POST['habitacion'].'" select>N° '.$_POST['habitacion'].'</option>';
									}else{
										echo '<option value="0" select>Habitaciones:</option>';
									}
									$consulta = mysqli_query($conn,"SELECT * FROM habitaciones");	
					                //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
					                if (mysqli_num_rows($consulta) == 0) {
					                    echo '<script>M.toast({html:"No se encontraron habitaciones.", classes: "rounded"})</script>';
					                } else {
					                    //RECORREMOS UNO A UNO LOS ARTICULOS CON EL WHILE
					                    while($habitacion = mysqli_fetch_array($consulta)) {
					                      //Output
					                      ?>
					                      <option value="<?php echo $habitacion['id'];?>">N° <?php echo $habitacion['id'].' - '.$habitacion['tipo'];?></option>
					                      <?php
					                    }//FIN while
					                }//FIN else
					                ?>
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