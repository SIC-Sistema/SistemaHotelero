<?php
include('../php/conexion.php');
$id = $conn->real_escape_string($_POST['id']);
$reservacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id=$id"));
$id_cliente = $reservacion['id_cliente'];
$cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id_cliente"));
$estatus = ($reservacion['estatus'] == 0)? '<span class="new badge green" data-badge-caption="Pendiente"></span>':'<span class="new badge blue" data-badge-caption="Ocupada"></span>';
?>
	<script>
		$(document).ready(function(){
		    $('#modalCheck-in').modal();
		    $('#modalCheck-in').modal('open'); 
		 });
	</script>
	<div id="modalCheck-in" class="modal">
	    <div class="modal-content row">
	      <h5 class="red-text center"><b>Editar la Fecha de Salida</b></h5>
	      <ul class="collection">
            <li class="collection-item avatar">
              <img src="../img/cliente.png" alt="" class="circle">
              <span class="title"><b>DETALLES DE LA RESERVACION</b></span><br>
              <p class="row col s12"><b>
                <div class="col s12 m6">
                  <div class="col s12"><b class="indigo-text">N° </b><?php echo $id;?></div>
                  <div class="col s12"><b class="indigo-text">CLIENTE: </b><?php echo $cliente['nombre'];?></div>      
                  <div class="col s12"><b class="indigo-text">RESPONSABLE:  </b><?php echo $reservacion['nombre'];?></div> 
                  <div class="col s12"><b class="indigo-text">FECHA ENTRADA: </b><?php echo $reservacion['fecha_entrada'];?></div>
                  <div class="col s12"><b class="indigo-text">FECHA SALIDA: </b><?php echo $reservacion['fecha_salida'];?></div>   
                </div>
                <div class="col s12 m6"><br>                	
                  <div class="col s12"><b class="indigo-text">HABITACION N°: </b><?php echo $reservacion['id_habitacion'];?></div>
                  <div class="col s10 m10 l9"><b class="indigo-text">ESTATUS: </b><?php echo $estatus;?></div>     
                  <div class="col s10"><b class="indigo-text">TOTAL: </b><div class="right blue-text">$<?php echo sprintf('%.2f', $reservacion['total']);?></div></div>              
                  <div class="col s10"><b class="indigo-text">DEJO O ABONO: </b><div class="right green-text">-$<?php echo sprintf('%.2f', $reservacion['anticipo']);?></div></div><br><br><br><br>
                  <hr>            
                  <div class="col s10"><b class="indigo-text">RESTA: </b><div class="right red-text">$<?php echo sprintf('%.2f', $reservacion['total']-$reservacion['anticipo']);?></div></div>              
                </div>
              </b></p>
            </li>
          </ul>
	      <form class="row">
	      	<div class="col s12 m6">
	      		<h4>Fecha Salida</h4>	      		
	      	</div>
	        <div class="input-field col s12 m4">
	          <label for="fecha_salida">Fecha Salida:</label><br>
            <input id="fecha_salida" type="date" value="<?php echo $reservacion['fecha_salida'];?>">
	        </div>
	        <div>
          <div class="row col s12"><br>
		        <a onclick="update_fecha_salida(<?php echo $reservacion['id'] ?>);" class="btn waves-effect waves-light grey darken-4 right">Editar<i class="material-icons left">edit</i></a>
		        <a class="right white-text">- - -</a>
		        <a href="#" class="modal-action modal-close waves-effect waves-green btn green right">Cancelar<i class="material-icons left">close</i></a><br>
          </div>
	    	</div>
	      </form>
	    </div>
	</div>