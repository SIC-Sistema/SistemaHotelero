
<!DOCTYPE html>
<html>
<head>
    <title>San Roman | Cliente</title>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
		$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
    //Obtenemos la informacion del Usuario
    $User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));

		//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL ARTICULO
		if (isset($_POST['id']) == false OR $User['clientes'] == 0) {
		  ?>
		  <script>    
		    M.toast({html:"Regresando a clientes.", classes: "rounded"});
		    setTimeout("location.href='clientes.php'", 500);
		  </script>
		  <?php
		}else{
			$id = $_POST['id'];// POR EL METODO POST RECIBIMOS EL ID DEL cliente
		    //CONSULTA PARA SACAR LA INFORMACION DE LA cliente Y ASIGNAMOS EL ARRAY A UNA VARIABLE $cliente
		    $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id"));
		}
		?>
</head>
<body>
	<div class="container">
		<div class="row"><br>
   		  <ul class="collection">
            <li class="collection-item avatar">
              <div class="hide-on-large-only"><br><br></div>
              <img src="../img/cliente.png" alt="" class="circle">
              <span class="title"><b>DETALLES DEL CLIENTE</b></span><br><br>
              <p class="row col s12"><b>
              	<div class="col s12 m4">
              		<div class="col s12"><b class="indigo-text">N° CLIENTE: </b><?php echo $id;?></div>
              		<div class="col s12"><b class="indigo-text">NOMBRE: </b><?php echo $cliente['nombre'];?></div>           		
              		<div class="col s12"><b class="indigo-text">RFC: </b><?php echo $cliente['rfc'];?></div>           		
              		<div class="col s12"><b class="indigo-text">TELEFONO: </b><?php echo $cliente['telefono'];?></div>            		
              	</div>
              	<div class="col s12 m4">
              		<div class="col s12"><b class="indigo-text">EMAIL: </b><?php echo $cliente['email'];?></div>           		
              		<div class="col s12"><b class="indigo-text">LIMPIEZA: </b><?php echo $cliente['limpieza'];?></div>           		
              		<div class="col s12"><b class="indigo-text">CODIGO POSTAL: </b><?php echo $cliente['cp'];?></div>           		
              	</div>
              	<div class="col s12 m4">       
              		<div class="col s12"><b class="indigo-text">DIRECCION: </b><?php echo $cliente['direccion'];?></div>          		
              		<div class="col s12"><b class="indigo-text">COLONIA: </b><?php echo $cliente['colonia'];?></div>          		
              		<div class="col s12"><b class="indigo-text">LOCALIDAD: </b><?php echo $cliente['localidad'];?></div>          		
              	</div>
              </b></p><br><br>
            </li>
          </ul>
        </div>
        
	    <h4>Historial:</h4>
	    <div class="row">
	    <!-- ----------------------------  TABs o MENU  ---------------------------------------->
	      <div class="col s12">
	        <ul id="tabs-swipe-demo" class="tabs">
	          <li class="tab col s6"><a class="active black-text" href="#test-swipe-1">RESERVACIONES</a></li>
	          <li class="tab col s6"><a class="black-text" href="#test-swipe-2">PAGOS</a></li>
	        </ul>
	      </div>
	      <!-- ----------------------------  FORMULARIO 1 Tabs  ---------------------------------------->
	      <div  id="test-swipe-1" class="col s12">
	        <div class="row">
	          <p><div>
	            <table class="bordered centered highlight">
	              <thead>
	                <tr>
	                  <th>N°</th>
	                  <th>Cliente</th>
	                  <th>Precio</th>
	                  <th>Fecha Entrada</th>            
	                  <th>Fecha Salida</th>
	                  <th>Estatus</th>
	                </tr>
	              </thead>
	              <tbody>
	                
	              </tbody>
	            </table>
	          </div></p>
	        </div>
	      </div>
	      <!-- ----------------------------  FORMULARIO 2 Tabs  ---------------------------------------->
	      <div  id="test-swipe-2" class="col s12">
	        <div class="row">
	          <p><div>
	            <table class="bordered centered highlight">
	              <thead>
	                <tr>
	                  <th>N°</th>
	                  <th>Descripcion</th>
	                  <th>Fecha</th>
	                  <th>Estatus</th> 
	                </tr>
	              </thead>
	              <tbody>
	                
	              </tbody>
	            </table>
	          </div></p>
	        </div>
	      </div>
	    </div>
	</div>

</body>
</html>