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
    <script>
    	//FUNCION QUE BUCARA EN LA BASE DE DATOS CLIENTES CON EL MISMO NOMBRE MOSTRARA Y DARA A ELEGIR
    	function buscarClientes() {
      		var texto = $("input#nombreCliente").val();
      		//MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_reservaciones.php"
		      $.post("../php/control_reservaciones.php", {
		        //Cada valor se separa por una ,
		          texto: texto,
		          accion: 2,
		        }, function(mensaje){
		            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
		            $("#clienteBusqueda").html(mensaje);
		      });//FIN post
   		
    	}//FIN function    	

    	//FUNICION QUE MUESTRA LA INFORMACION DEL CLIENTE SI SELECCIONAMOS ALGUNO O VACIO PARA NUEVO
    	function mostrarCliente(id_cliente) {
    		//MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_reservaciones.php"
	      	$.post("../php/control_reservaciones.php", {
		        //Cada valor se separa por una ,
		            accion: 1,
		            id_cliente: id_cliente,
		        }, function(mensaje) {
		            //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
		            $("#infoCliente").html(mensaje);
		        }); 
    	}//FIN function 

    	//FUNCION QUE MUESTRA LA INFORMACION DEL A HABITACION SI SE SELECCIONA UNA
    	function mostrarHabitacion() {
      		var habitacion = $("select#habitacion").val();
      		if(habitacion == 0){
		        M.toast({html:"Por favor seleccione una Habitación.", classes: "rounded"});
		    }else{
		        //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
		        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_reservaciones.php"
	      		$.post("../php/control_reservaciones.php", {
		          //Cada valor se separa por una ,
		            accion: 0,
		            habitacion: habitacion,
		          }, function(mensaje) {
		              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_reservaciones.php"
		              $("#infoHabitacion").html(mensaje);
		          }); 
    		}//FIN else
    	}
    </script>
</head>
<body onload="mostrarHabitacion();">
	<div class="container">
		<div class="row col s12">
			<div class="col s12 m6">
				<div class="row"><br>
		   		  <ul class="collection">
		            <li class="collection-item avatar">
		              <img src="../img/cliente.png" alt="" class="circle">
		              <span class="title"><b>DETALLES DEL CLIENTE</b></span><br>
		              <?php
						if (isset($_POST['cliente'])) {
							// SACAMOS LA INFORMACION DEL CLIENTE Y LA MOSTRAMOS
							$IdCliente = $_POST['cliente'];
							echo '<script>mostrarCliente('.$IdCliente.')</script>';
						}
					  ?>					  	
		                <div class="row col s12" id="infoCliente">
					        <b>		
					            <div class="col s12">
					              	<b class="indigo-text col s12 m4"><br>NOMBRE: </b>
					              	<div class="col s12 m8">
							        	<input id="nombreCliente" type="text" class="validate" data-length="100"  onkeyup="buscarClientes()" required>	
									</div>
					        	</div>
					            <div class="col s12" id="clienteBusqueda"><br><br></div>              	
					            <div class="col s12">
					              	<b class="indigo-text col s12 m4"><br>DIRECCION: </b>
					              	<div class="col s12 m8">
							            <input id="direccion" type="text" class="validate" data-length="100" required>	
							        </div>
					            </div>     
					            <div class="col s12">
					              	<b class="indigo-text col s12 m3"><br>COLONIA: </b>
					              	<div class="col s12 m5">
							            <input id="colonia" type="text" class="validate" data-length="20"  required>	
							        </div>
							        <b class="indigo-text col s12 m1"><br>CP: </b>
					              	<div class="col s12 m3">
							            <input id="cp" type="text" class="validate" data-length="6"  required>	
							        </div>
					            </div>   
					            <div class="col s12">
					              	<b class="indigo-text col s12 m4"><br>LOCALIDAD: </b>
					              	<div class="col s12 m8">
							            <input id="localidad" type="text" class="validate" data-length="20" required>	
							        </div>
					            </div>  
					            <div class="col s12">
					              	<b class="indigo-text col s12 m4"><br>RFC: </b>
					              	<div class="col s12 m8">
							            <input id="cp" type="text" class="validate" data-length="6"  required>	
							        </div>
					            </div>  
					            <div class="col s12">
					              	<b class="indigo-text col s12 m4"><br>EMAIL: </b>
					              	<div class="col s12 m8">
							            <input id="email" type="text"  required>	
							        </div>
					            </div>
					            <div class="col s12">
					              	<b class="indigo-text col s12 m4"><br>TELEFONO: </b>
					              	<div class="col s12 m8">
							            <input id="telefono" type="text" class="validate" data-length="10"  required>	
							        </div>
					            </div>
					            <div class="col s12">
					              	<b class="indigo-text col s12 m4"><br>LIMPIEZA: </b>
					              	<div class="col s12 m8">
							            <input id="limpieza" type="text" class="validate" data-length="100"  required>	
							        </div>
					            </div>		            		
					        </b>
				        </div>
		            </li>
		          </ul>
		        </div>
			</div>
			<div class="col s12 m6">
				<div class="row"><br>
		   		  <ul class="collection">
		            <li class="collection-item avatar">
		              <img src="../img/hotel.png" alt="" class="circle"><br>
		              <span class="title"><b>DETALLES DE HABITACION</b></span><br><br>
		              <div class="col s12"><br>
		              		<b class="indigo-text col s12 m5"><br>HABITACION: </b>
		              		<div class="col s12 m6">
					            <select id="habitacion" name="habitacion" class="validate" onchange="mostrarHabitacion();">              
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
		              <div class="row" id="infoHabitacion"><b>		              	
		              	<div class="col s12"><br><br>
		              		<b class="indigo-text col s12 m5">ESTADO: </b>		              		
		              	</div>      
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">HASTA: </b>		              		
		              	</div>  
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">NIVEL / PISO: </b>		              		
		              	</div> 		              	         		
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">DESCRIPCION: </b><br>		              		
		              	</div>
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">TIPO DE HABITACION: </b><br>
		              	</div> 
		              	<div class="col s12"><br><br>
		              		<b class="indigo-text col s12 m5">PRECIO POR DIA: <br><br></b>		              				
		              	</div>
		              </b></div>
		            </li>
		          </ul>
		        </div>        
			</div>
		</div>
		<div class="row">
		    <h4>Reservar</h4>
			<hr>
		    <div class="col s12 m3">
		    	<div class="input-field">
		            <i class="material-icons prefix">people</i>
		            <input id="nombreReservacion" type="text" class="validate" data-length="50" required>
		            <label for="nombreReservacion">Nombre del Responsable:</label>
		        </div>
		    </div>
		    <div class="col s12 m3">
		    	<label for="fecha_llegada">Fecha Llegada:</label>
                <input id="fecha_llegada" type="date" >
		    </div>
		    <div class="col s12 m3">
		    	<label for="fecha_salida">Fecha Salida:</label>
                <input id="fecha_salida" type="date" >
		    </div>
		    <div class="col s12 m3"><br>
		    	<h5><b>TOTAL</b></h5>	
		    </div>
		</div>
		<!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPT HAGA LO QUE LA FUNCION CONTENGA -->
      <a onclick="insert_reservacion();" class="waves-effect waves-light btn pink right"><i class="material-icons right">add</i>Registrar</a><br><br><br><br>
	   
	</div>

</body>
</html>