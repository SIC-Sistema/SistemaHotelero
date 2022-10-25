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
		    $('#modalCheck-out').modal();
		    $('#modalCheck-out').modal('open'); 
		 });
	</script>
	<div id="modalCheck-out" class="modal">
	    <div class="modal-content row">
	      <h5 class="red-text center"><b>¡Check-out!</b></h5>
	      <ul class="collection">
            <li class="collection-item avatar">
              <img src="../img/cliente.png" alt="" class="circle">
              <span class="title"><b>DETALLES DE LA RESERVACION</b></span><br>
              <p class="row col s12"><b>
                <div class="col s12 m6">
                  <div class="col s12"><b class="indigo-text">N° </b><?php echo $id;?></div>
                  <div class="col s12"><b class="indigo-text">CLIENTE: </b><?php echo $cliente['nombre'];?> MARTINEZ GSPAR</div>            
                  <div class="col s12"><b class="indigo-text">RESPONSABLE:  </b>MARTINEZ GSPAR<?php echo $reservacion['nombre'];?></div> 
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
	      	<div class="col s12 m6"> NOTAS:</div>
	        <div class="input-field col s6 m3">
	            <i class="material-icons prefix">monetization_on</i>
	            <input type="text" class="validate" required  id="liquidacionR" name="liquidacionR" value="<?php echo sprintf('%.2f', $reservacion['total']-$reservacion['anticipo']);?>">
	        </div>
	        <div class="col s6 m2">
              <p>
                <br>
                <label>
                  <input type="checkbox" id="bancoR" />
                  <span for="bancoR">Banco</span>
                </label>
              </p>
            </div>
            <div class="col s6 m2">
              <p>
                <label>
                  <input type="checkbox" id="creditoR" />
                  <span for="creditoR">Credito</span>
                </label>
              </p>
            </div>
	        <div>
		        <a onclick="nueva_nota(<?php echo $reservacion['id'] ?>);" class="btn waves-effect waves-light grey darken-3 right">Agregar<i class="material-icons left">save</i></a>
		        <a href="#" class="modal-action modal-close waves-effect waves-green btn red accent-2 right">Cancelar<i class="material-icons left">close</i></a><br>
	    	</div>
	      </form>
	    </div>
	</div>