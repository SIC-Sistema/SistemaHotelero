<?php
include('../php/conexion.php');
$id_hab = $conn->real_escape_string($_POST['id_hab']);
$id_mto = $conn->real_escape_string($_POST['id_mto']);

$mantenimiento = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `mantenimientos` WHERE id = $id_mto"));
	?>
	<script>
		$(document).ready(function(){
		    $('#modalNotaEdit').modal();
		    $('#modalNotaEdit').modal('open'); 
		 });
	</script>
	<div id="modalNotaEdit" class="modal">
	    <div class="modal-content row">
	      <h5 class="red-text center"><b>¡Editar mantenimineto!</b></h5><br>
	      <h5 class="blue-text"><b>Descripcion:</b></h5>
	      <form class="row">
	        <div class="row">
	          <div class="input-field col s12 m6 l6">
                <i class="material-icons prefix">edit</i>
                <input id="descripcionMto" type="text" class="validate" data-length="150" required value="<?php echo $mantenimiento['descripcion']; ?>">
              </div>
	        </div>
	        <a onclick="editar_mto(<?php echo $id_hab ?>,<?php echo $id_mto; ?>);" class="btn waves-effect waves-light grey darken-4 right">Guardar<i class="material-icons left">save</i></a>
	        <a href="#" class="modal-action modal-close waves-effect waves-green btn red accent-2 right">Cancelar<i class="material-icons left">close</i></a>
	      </form>
	    </div>
	</div>