<?php
#INCLUIMOS EL PHP DONDE VIENE LA INFORMACION DEL INICIO DE SESSION
include('../php/is_logged.php');
#INCLUIMOS EL ARCHIVO CON LOS DATOS Y CONEXXION A LA BASE DE DATOS
include('../php/conexion.php');
#GENERAMOS UNA FECHA DEL DIA EN CURSO REFERENTE A LA ZONA HORARIA
$Hoy = date('Y-m-d');
$cuentas = mysqli_fetch_array(mysqli_query($conn,"SELECT count(*) FROM `reservaciones` WHERE estatus = 0 OR estatus = 1"));
$mantenimientos = mysqli_fetch_array(mysqli_query($conn,"SELECT count(*) FROM `mantenimientos` WHERE estatus = 0"));
$check_in = mysqli_fetch_array(mysqli_query($conn,"SELECT count(*) FROM `reservaciones` WHERE estatus = 0"));
$check_out = mysqli_fetch_array(mysqli_query($conn,"SELECT count(*) FROM `reservaciones` WHERE estatus = 1 AND fecha_salida <= '$Hoy' "));
$notificacion = 0;
$sql_art_stock = mysqli_query($conn,"SELECT * FROM `articulos`");
if (mysqli_num_rows($sql_art_stock)>0) {
	while ($art_stock = mysqli_fetch_array($sql_art_stock)) {
		$id = $art_stock['id'];
		$sql_existe = mysqli_query($conn, "SELECT cantidad FROM `inventario` WHERE  id_articulo = $id");
		if (mysqli_num_rows($sql_existe)>0) {
			$existe = mysqli_fetch_array($sql_existe);
			if ($art_stock['stock_minimo'] >= $existe['cantidad']) {
				$notificacion ++;
			}
		}
	}
}
?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<!--Import material-icons.css-->
      <link href="css/material-icons.css" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	  <link rel="shortcut icon" href="../img/logo.png" type="image/png" />
      <style rel="stylesheet">
		.dropdown-content{  overflow: visible;	}
	  </style>
	<div class="navbar-fixed">
	<nav class="grey darken-4">
		<div class="nav-wrapper container">
			<a  class="brand-logo" href="home.php"><img  class="responsive-img" style="width: 55px; height: 60px;" src="../img/logo.png"></a>
			<a href="#" data-target="menu-responsive" class="sidenav-trigger">
				<i class="material-icons">menu</i>
			</a>
			<ul class="right hide-on-med-and-down">
				<li><a class='dropdown-button' data-target='dropdown1'><i class="material-icons left">input</i><b>Recepción</b> <span class="new badge pink" data-badge-caption=""><?php echo $check_in['count(*)']+$check_out['count(*)'];?></span><i class="material-icons right">arrow_drop_down</i></a></li>
				<ul id='dropdown1' class='dropdown-content'>
					<li><a href = "reservacion.php" class="black-text"><i class="material-icons">date_range</i>Reservar </a></li>
				    <li><a href = "empresas.php" class="black-text"><i class="material-icons">location_city</i>Empresas </a></li>
				    <li><a href = "clientes.php" class="black-text"><i class="material-icons">people</i>Clientes </a></li>
					<li><a href = "habitaciones.php" class="black-text"><i class="material-icons">hotel</i>Habitaciones </a></li>
				    <li><a href = "check_in.php" class="black-text"><i class="material-icons">assignment_turned_in</i>Check In <span class="new badge pink" data-badge-caption=""><?php echo $check_in['count(*)'];?></span></a></li>
					<li><a href = "check_out.php" class="black-text"><i class="material-icons">assignment_return</i>Check Out <span class="new badge pink" data-badge-caption=""><?php echo $check_out['count(*)'];?></span></a></li>   			 
 				 </ul>
				<li><a class='dropdown-button' data-target='dropdown2'><i class="material-icons left">hotel</i><b>Hotel</b><span class="new badge pink" data-badge-caption=""><?php echo $cuentas['count(*)']+$mantenimientos['count(*)'];?></span><i class="material-icons right">arrow_drop_down</i></a></li>
				<ul id='dropdown2' class='dropdown-content'>  
					<li><a href = "cuentas.php" class="black-text"><i class="material-icons">list</i>Cuentas<span class="new badge pink" data-badge-caption=""><?php echo $cuentas['count(*)'];?></span></a></li>	
					<li><a href = "mantenimientos.php" class="black-text"><i class="material-icons">settings</i>Mantenimientos<span class="new badge pink" data-badge-caption=""><?php echo $mantenimientos['count(*)'];?></span></a></li>
					<li><a href = "salida.php" class="black-text"><i class="material-icons">money_off</i>Salida (Egreso)</a></li>	
					<li><a href = "limpieza.php" class="black-text"><i class="material-icons">photo_filter</i>Limpieza</a></li>	
					<li><a href = "articulos.php" class="black-text"><i class="material-icons">dashboard</i>Articulos</a></li>	
					<li><a href = "inventario.php" class="black-text"><i class="material-icons">list</i>Inventario<span class="new badge pink" data-badge-caption=""><?php echo $notificacion;?></span></a></li>	
					<li><a href = "salida_productos.php" class="black-text"><i class="material-icons">exit_to_app</i>Salida (Productos)</a></li>	
 				</ul>

				<li><a class='dropdown-button' data-target='dropdown6'><i class="material-icons left">book</i><b>Facturación</b> <i class="material-icons right">arrow_drop_down</i></a></li>
				<ul id='dropdown6' class='dropdown-content'>
					<li><a href = "emisor_sat.php" class="black-text"><i class="material-icons">account_box</i>Emisor</a></li> 
					<li><a href = "receptores_sat.php" class="black-text"><i class="material-icons">assignment_ind</i>Receptores</a></li>
					<li><a href = "reimprimir_cfdi.php" class="black-text"><i class="material-icons">print</i>Reimprimir</a></li> 
					<li><a href = "unidades.php" class="black-text"><i class="material-icons">loupe</i>Unidades</a></li> 
				</ul>
				
 				<li><a class='dropdown-button' data-target='dropdown5'><i class="material-icons left">person_pin</i><b>Admin</b> <i class="material-icons right">arrow_drop_down</i></a></li>
				<ul id='dropdown5' class='dropdown-content'>
					<li><a href = "usuarios.php" class="black-text"><i class="material-icons">people</i>Usuarios </a></li> 
					<li><a href = "en_caja.php" class="black-text"><i class="material-icons">inbox</i>En Caja </a></li> 
					<li><a href = "cajas.php" class="black-text"><i class="material-icons">list</i>Cajas </a></li> 
					<li><a href = "credito.php" class="black-text"><i class="material-icons">credit_card</i>Credito </a></li> 
					<li><a href = "historial_cortes.php" class="black-text"><i class="material-icons">content_cut</i>Historial Cortes </a></li> 
					<li><a href = "reportes.php" class="black-text"><i class="material-icons">assessment</i>Reportes </a></li>
				</ul>
 				<li><a class='dropdown-button' data-target='dropdown4'><b><?php echo $_SESSION['user_name'];?> </b><i class="material-icons right">arrow_drop_down</i></a></li>
				<ul id='dropdown4' class='dropdown-content'>				    
				    <li><a href="perfil_user.php" class="black-text"><i class="material-icons">account_circle</i>Perfil </a></li>
				    <li><a href="../php/cerrar_sesion.php" class="black-text"><i class="material-icons">exit_to_app</i>Cerrar Sesión</a></li>
 				 </ul>
			</ul>
			<ul class="right hide-on-large-only hide-on-small-only">
				<li><a class='dropdown-button' data-target='dropdown10'><b><?php echo $_SESSION['user_name'];?> </b><i class="material-icons right">arrow_drop_down</i></a></li>
				<ul id='dropdown10' class='dropdown-content'>					
				    <li><a href="perfil_user.php" class="black-text"><i class="material-icons">account_circle</i>Perfil </a></li>
				    <li><a href="../php/cerrar_sesion.php" class="black-text"><i class="material-icons">exit_to_app</i>Cerrar Sesión</a></li>
 				 </ul>
			</ul>
			<ul class="right hide-on-med-and-up">
		        <li><a class='dropdown-button' data-target='dropdown8'><i class="material-icons left">account_circle</i><b>></b></a></li>
				<ul id='dropdown8' class='dropdown-content'>					
				    <li><a href="perfil_user.php" class="black-text"><i class="material-icons">account_circle</i>Perfil </a></li>
				   <li><a href="../php/cerrar_sesion.php" class="black-text"><i class="material-icons">exit_to_app</i>Cerrar Sesión</a></li>
 				</ul>
		    </ul>			
		</div>		
	</nav>
	</div>
	<!-- BARRA DE NAVEGACION DE LA IZQUIERDA MOBILES Y TABLETAS --->
	<ul class="sidenav indigo lighten-5" id="menu-responsive" style="width: 270px;">
		<h4>.  <<   Menú   >></h4>
    	<li><div class="divider"></div></li><br>
		<li>
	    	<ul class="collapsible collapsible-accordion">
	    		<li>
	    			<div class="collapsible-header"><i class="material-icons">input</i>Recepción <span class="new badge pink" data-badge-caption=""><?php echo $check_in['count(*)']+$check_out['count(*)'];?></span><i class="material-icons right">arrow_drop_down</i></div>
		      		<div class="collapsible-body indigo lighten-5">
		      		    <span>
		      			  <ul>
							<li><a href = "reservacion.php"><i class="material-icons">date_range</i>Reservar </a></li>
				    		<li><a href = "empresas.php"><i class="material-icons">location_city</i>Empresas </a></li>
						    <li><a href = "clientes.php"><i class="material-icons">people</i>Clientes </a></li>
							<li><a href = "habitaciones.php"><i class="material-icons">hotel</i>Habitaciones </a></li>
						    <li><a href = "check_in.php"><i class="material-icons">assignment_turned_in</i>Check In <span class="new badge pink" data-badge-caption=""><?php echo $check_in['count(*)'];?></span></a></li>
							<li><a href = "check_out.php"><i class="material-icons">assignment_return</i>Check Out <span class="new badge pink" data-badge-caption=""><?php echo $check_out['count(*)'];?></span></a></li>  			 
					      </ul>
					    </span>
		      		</div>    			
	    		</li>	    			
	    	</ul>	     				
	    </li>
		<li>
	    	<ul class="collapsible collapsible-accordion">
	    		<li>
	    			<div class="collapsible-header"><i class="material-icons">hotel</i>Hotel <span class="new badge pink" data-badge-caption=""><?php echo $cuentas['count(*)']+$mantenimientos['count(*)'];?></span><i class="material-icons right">arrow_drop_down</i></div>
		      		<div class="collapsible-body indigo lighten-5">
		      			<span>
		      			  <ul>
							<li><a href = "cuentas.php"><i class="material-icons">list</i>Cuentas<span class="new badge pink" data-badge-caption=""><?php echo $cuentas['count(*)'];?></span></a></li>
							<li><a href = "mantenimientos.php"><i class="material-icons">settings</i>Mantenimientos<span class="new badge pink" data-badge-caption=""><?php echo $mantenimientos['count(*)'];?></span></a></li>	
							<li><a href = "salida.php"><i class="material-icons">money_off</i>Salida (Egreso)</a></li>
							<li><a href = "limpieza.php"><i class="material-icons">photo_filter</i>Limpieza</a></li>	
							<li><a href = "articulos.php"><i class="material-icons">dashboard</i>Articulos</a></li>	
							<li><a href = "inventario.php"><i class="material-icons">list</i>Inventario</a></li>
							<li><a href = "salida_productos.php"><i class="material-icons">exit_to_app</i>Salida (Productos)</a></li>	
					      </ul>
					    </span>
		      		</div>    			
	    		</li>	    			
	    	</ul>	     				
	    </li>
		
		<li>
				<ul class="collapsible collapsible-accordion">
					<li>
						<div class="collapsible-header"><i class="material-icons">person_pin</i>Admin <i class="material-icons right">arrow_drop_down</i></div>
						<div class="collapsible-body  indigo lighten-5">
							<span>
							<ul>
								<li><a href="usuarios.php"><i class="material-icons">people</i>Usuarios</a></li>
								<li><a href = "en_caja.php"><i class="material-icons">inbox</i>En Caja </a></li> 
								<li><a href = "cajas.php"><i class="material-icons">list</i>Cajas </a></li> 
								<li><a href = "credito.php"><i class="material-icons">credit_card</i>Credito </a></li> 
								<li><a href = "historial_cortes.php"><i class="material-icons">content_cut</i>Historial Cortes </a></li> 
								<li><a href = "reportes.php"><i class="material-icons">assessment</i>Reportes </a></li> 
							</ul>				      
							</span>
						</div>    			
					</li>	    			
				</ul>	     				
		</li>
		<li>
		<ul class="collapsible collapsible-accordion">
	    		<li>
	    			<div class="collapsible-header"><i class="material-icons">account_balance_wallet</i>Facturación <i class="material-icons right">arrow_drop_down</i></div>
		      		<div class="collapsible-body  indigo lighten-5">
		      			<span>
		      			  <ul>
		      				<li><a href="emisor_sat.php"><i class="material-icons">account_box</i>Emisor</a></li>
							<li><a href = "receptores_sat.php"><i class="material-icons">assignment_ind</i>Receptores </a></li> 
							<li><a href = "reimprimir_cfdi.php"><i class="material-icons">print</i>Reimprimir </a></li> 
							<li><a href = "unidades.php"><i class="material-icons">loupe</i>Unidades </a></li>  
						  </ul>				      
					    </span>
		      		</div>    			
	    		</li>	    			
	    	</ul>	     				
	    </li>
	</ul>	
</li>
	</ul>
	<?php 
	include('../views/modals.php');
	?>
	<script src="js/jquery-3.1.1.js"></script>
	<!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="js/materialize.min.js"></script>
	<script>
    	$(document).ready(function() {	    
	 	$('.dropdown-button').dropdown({
	      	  inDuration: 500,
	          outDuration: 500, 
	          constrainWidth: false, // Does not change width of dropdown to that of the activator
	          coverTrigger: false, 
	    });
	    $('.dropdown-btn').dropdown({
	      	  inDuration: 500,
	          outDuration: 500,
	          hover: true,
	          constrainWidth: true, // Does not change width of dropdown to that of the activator
	          coverTrigger: false, 
	    });
	    $('.dropdown-btn1').dropdown({
	      	  inDuration: 500,
	          outDuration: 500,
	          alignment: 'left',
	          hover: true,
	          constrainWidth: true, // Does not change width of dropdown to that of the activator
	          coverTrigger: false, 
	    });
	    $('tooltipped').tooltip();
	    });
		document.addEventListener('DOMContentLoaded', function(){
			M.AutoInit();
		});
		document.addEventListener('DOMContentLoaded', function() {
		    var elems = document.querySelectorAll('.fixed-action-btn');
		    var instances = M.FloatingActionButton.init(elems, {
		      direction: 'left'
		    });
		});
		$('.dropdown-button2').dropdown({
		      inDuration: 300,
		      outDuration: 225,
		      constrain_width: false, // Does not change width of dropdown to that of the activator
		      hover: true, // Activate on hover
		      gutter: ($('.dropdown-content').width()*3)/2.5 + 5, // Spacing from edge
		      belowOrigin: false, // Displays dropdown below the button
		      alignment: 'left' // Displays dropdown with edge aligned to the left of button
		    }
		);
		$('.button-collapse').sideNav({
		      menuWidth: 347, 
		      edge: 'left',
		      closeOnClick: false,
		      draggable: true 
		    }
		  );

		$('.modal').modal();

		$(document).ready(function(){
    		$('.slider').slider();
		});
		$(document).ready(function(){
		  $('.materialboxed').materialbox();
		});  

	    var toastElement = document.querySelector('.toast');
	    var toastInstance = M.Toast.getInstance(toastElement);
	    toastInstance.dismiss(); 
  
		M.AutoInit();
        var options={
        };
        document.addEventListener('DOMContentLoaded', function () {
            var elems = document.querySelectorAll('.carousel');
            var instances = M.Carousel.init(elems, options);
        });
	</script>