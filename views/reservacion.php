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

    	function total(){
    		var habitacion = $("select#habitacion").val();
      		if(habitacion != 0){
      			var textoDe = $("input#fecha_llegada").val();
        		var textoA = $("input#fecha_salida").val();
        		var precioDia_Aux = $("input#precioXDia").val();
        		var precioDia = parseFloat(precioDia_Aux);

        		if (textoDe == "" || textoA == ""){
		            document.getElementById("total").value = (precioDia).toFixed(2);
		        }else{
		        	var fechaInicio = new Date(textoDe).getTime();
					var fechaFin    = new Date(textoA).getTime();
					var diff = (fechaFin - fechaInicio)/86400000;
		        	document.getElementById("total").value = (precioDia*diff).toFixed(2);
		    	}		       
		    }else{
		    	document.getElementById("total").value = (0).toFixed(2);
		    }
    	}// FIN function total

    	function insert_reservacion() {
    		var habitacion = $("select#habitacion").val();
    		var cliente = $("input#id_cliente").val();
    		var NomResp = $("input#nombreReservacion").val();
    		var fechaEntrada = $("input#fecha_llegada").val();
    		var fechaSalida = $("input#fecha_salida").val();
    		var Observacion = $("input#observacionR").val();
    		var Total = $("input#total").val();
    		var Anticipo = $("input#anticipoR").val();

    		if(document.getElementById('bancoR').checked==true){
	            tipo_cambio = 'Banco';
	        }else if (document.getElementById('creditoR').checked==true) {
	            tipo_cambio = 'Credito';
	        }else{
	            tipo_cambio = 'Efectivo';
	        }
	        clienteEntra = true;
	        if (cliente == '') {
		          var textoNombre = $("input#nombreCliente").val();//ej:LA VARIABLE "textoNombre" GUARDAREMOS LA INFORMACION QUE ESTE EN EL INPUT QUE TENGA EL id = "nombreCliente"
			      var textoTelefono = $("input#telefono").val();// ej: TRAE LE INFORMACION DEL INPUT FILA 
			      var textoEmail = $("input#email").val();
			      var textoRFC = $("input#rfc").val();
			      var textoDireccion = $("input#direccion").val();
			      var textoColonia = $("input#colonia").val();
			      var textoLocalidad = $("input#localidad").val();
			      var textoCP = $("input#cp").val();
			      var textoLimpieza = $("input#limpieza").val();

			      // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
			      //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
			      if (textoNombre == "") {
			       	msj = 'El campo Nombre Cliente se encuentra vacío.';
			      }else if(textoTelefono.length < 10){
			       	msj = 'El Telefono tiene que tener al menos 10 dijitos.';
			      }else if(textoEmail == ""){
			        msj = "Por favor ingrese un Email.";
			      }else if (!validar_email(textoEmail)) {
			        msj = "Por favor ingrese un Email correcto.";
			      }else if(textoRFC.length < 12){
			       	msj = 'El RFC tiene que tener al menos 12 dijitos.';
			      }else if(textoDireccion == ""){
			       	msj = 'El campo Dirección se encuentra vacío.';
			      }else if(textoColonia == ""){
			       	msj = 'El campo Colonia se encuentra vacío.';
			      }else if(textoLocalidad == ""){
			       	msj = 'El campo Localidad se encuentra vacío.';
			      }else if(textoCP == ""){
			       	msj = 'El campo Codigo Postal se encuentra vacío.';
			      }else{
			      	clienteEntra = false;
			      }
	        }else{
	        	clienteEntra = false;
	        	var textoNombre = ''; var textoTelefono = ''; var textoEmail = ''; var textoRFC = ''; var textoDireccion = '';
			    var textoColonia = ''; var textoLocalidad = ''; var textoCP = ''; var textoLimpieza = '';
	        }

	        if (clienteEntra) {
		        M.toast({html:""+msj, classes: "rounded"});
	        }else if (habitacion == 0) {
		        M.toast({html:"Por favor seleccione una Habitación.", classes: "rounded"});
	        }else if (NomResp == '') {	        	
		        M.toast({html:"Por ingrese un nombre del responsable.", classes: "rounded"});
	        }else if (fechaEntrada == '') {
		        M.toast({html:"Por favor seleccione una Fecha de Entrada.", classes: "rounded"});
	        }else if (fechaSalida == '') {
		        M.toast({html:"Por favor seleccione una Fecha de Salida.", classes: "rounded"});
	        }else if(Total <= 0){
		        M.toast({html:"El total no es valido o aceptado.", classes: "rounded"});
	        }else{
	        	//SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
		        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_clientes.php"
		        $.post("../php/control_clientes.php", {
		          //Cada valor se separa por una ,
		            accion: 3,
		            valorNombre: textoNombre,
		            valorTelefono: textoTelefono,
		            valorEmail: textoEmail,
		            valorRFC: textoRFC,
		            valorDireccion: textoDireccion,
		            valorColonia: textoColonia,
		            valorLocalidad: textoLocalidad,
		            valorCP: textoCP,
		            valorLimpieza: textoLimpieza,
		            valorCliente: cliente,
		            valorNomResp: NomResp,
		            valorFE: fechaEntrada,
		            valorFS: fechaSalidaa,
		            valorObservacion: Observacion,
		            valorTotal: Total,
		            valorAnticipo: Anticipo,
		            valortipo_cambio: tipo_cambio,
		          }, function(mensaje) {
		              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_clientes.php"
		              $("#resultado_insert").html(mensaje);
		          }); 
		    }//FIN else CONDICIONES
    	}
    </script>
</head>
<body>
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
					            <input type="hidden" name="id_cliente" id="id_cliente" value="">             	
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
										echo '<script>mostrarHabitacion();</script>';
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
		<div class="row s12">
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
                <input id="fecha_llegada" type="date" onchange="total();">
		    </div>
		    <div class="col s12 m3">
		    	<label for="fecha_salida">Fecha Salida:</label>
                <input id="fecha_salida" type="date" onchange="total();">
		    </div>
		    <div class="col s12 m3"><br>
		    	<h5><b><div class="col s6">TOTAL $</div><input class="col s6" type="" id="total" value="0.00"></b></h5>	<br><br><br>    		
		    </div>
		    <div class="col s12 m4">
		    	<div class="input-field">
		            <i class="material-icons prefix">edit</i>
		            <input id="observacionR" type="text" class="validate" data-length="100" required>
		            <label for="observacionR">Observaciones:</label>
		        </div>
		    </div>
		    <div class="col s12 m3">
		    	<div class="input-field">
		            <i class="material-icons prefix">monetization_on</i>
		            <input id="anticipoR" type="number" class="validate" required>
		            <label for="anticipoR">Anticipo:</label>
		        </div>
		    </div>
		    <div class="col s6 m2 l2">
              <p>
                <br>
                <label>
                  <input type="checkbox" id="bancoR" />
                  <span for="bancoR">Banco</span>
                </label>
              </p>
            </div>
            <div class="col s6 m2 l2">
              <p>
                <br>
                <label>
                  <input type="checkbox" id="creditoR" />
                  <span for="creditoR">Credito</span>
                </label>
              </p>
            </div>
		</div>
		<!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPT HAGA LO QUE LA FUNCION CONTENGA -->
      <a onclick="insert_reservacion();" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">add</i>Registrar</a><br><br><br><br>
	   
	</div>

</body>
</html>