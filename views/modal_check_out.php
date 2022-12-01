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
	      		NOTAS:<br>
	      		<?php
                  $notas = mysqli_query($conn,"SELECT * FROM notas WHERE id_reservacion = $id"); 
                  //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
                  if (mysqli_num_rows($notas) == 0) {
                    echo '<h5>No se encontraron notas.</h5>';
                  } else {
                  	$aux = 0;
                    while ($nota = mysqli_fetch_array($notas)) {
                      $aux ++;
                      $id_usuario = $nota['usuario'];// ID DEL USUARIO REGISTRO
                      //Obtenemos la informacion del Usuario
                      $usuario = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_usuario"));
                      echo $aux.'. <b>Descripcion:</b> '.$nota['descripcion'].' <b>Fecha:</b> '.$nota['fecha'].'<br>';
                    }//FIN while
                  }//FIN ELSE
                ?>
	      	</div>
	        <div class="input-field col s6 m3">
	            <i class="material-icons prefix">monetization_on</i>
	            <input type="text" class="validate" required  id="liquidacionR" name="liquidacionR" value="<?php echo sprintf('%.2f', $reservacion['total']-$reservacion['anticipo']);?>">
	        </div>
	        <div class="col s6 m2">
              <p>
                <br>
                <label>
                  <input type="checkbox" id="bancoR"  onchange="javascript:showContent()"/>
                  <span for="bancoR">Banco</span>
                </label>
              </p>
            </div>
            <div class="col s6 m2 l2" id="content1" style="display: block;">
              <p>
                <br>
                <label>
                  <input type="checkbox" id="creditoR" />
                  <span for="creditoR">Credito</span>
                </label>
              </p>
            </div>
            <div class="col s6 m2 l2" id="content2" style="display: none;">
              <div class="input-field">
                  <input id="referenciaB" type="text" class="validate" required>
                  <label for="referenciaB">Referencia:</label>
              </div>
            </div>
	        <div>
		        <a onclick="check_out(<?php echo $reservacion['id'] ?>);" class="btn waves-effect waves-light grey darken-4 right">Check-out<i class="material-icons left">exit_to_app</i></a>
		        <a class="right white-text">- - -</a>
		        <a href="#" class="modal-action modal-close waves-effect waves-green btn green right">Regresar<i class="material-icons left">close</i></a><br>
	    	</div>
	      </form>
	    </div>
	</div>