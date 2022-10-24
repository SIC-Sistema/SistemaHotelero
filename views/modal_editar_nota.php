<?php
include('../php/conexion.php');
$id_hab = $conn->real_escape_string($_POST['id_hab']);
$id_nota = $conn->real_escape_string($_POST['id_nota']);
$reservacion_sql = mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id_habitacion = $id_hab AND estatus = 1");
if (mysqli_num_rows($reservacion_sql)<=0) {
	echo '<script >M.toast({html:"La habitacion N°'.$id.' no cuenta con una reservacion OCUPADA ahora", classes: "rounded"})</script>';	
}else{
	$reservacion = mysqli_fetch_array($reservacion_sql);
	$nota = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `notas` WHERE id = $id_nota"));
	?>
	<script>
		$(document).ready(function(){
		    $('#modalNotaEdit').modal();
		    $('#modalNotaEdit').modal('open'); 
		 });
	</script>
	<div id="modalNotaEdit" class="modal">
	    <div class="modal-content row">
	      <h5 class="red-text center"><b>¡Editar nota!</b></h5><br>
	      <h5 class="blue-text"><b>Reservación:</b></h5>
	      <h6 class="blue-text"><b>N°<?php echo $reservacion['id'] ?> | A Nombre: <?php echo $reservacion['nombre'] ?> | Fecha Entrada: <?php echo $reservacion['fecha_entrada'] ?> | Fecha Salida: <?php echo $reservacion['fecha_salida'] ?></b></h6><br>
	      <h6><b>Puede agregar cualquier tipo de nota a forma de recordatorio<br> (ej: Debe cuenta en restaurante, Se le presto una plancha, etc.)</b></h6><br>
	      <form class="row">
	        <div class="row">
	          <div class="input-field col s12 m6 l6">
                <i class="material-icons prefix">edit</i>
                <input id="descripcionNota" type="text" class="validate" data-length="150" required value="<?php echo $nota['descripcion']; ?>">
              </div>
	        </div>
	        <a onclick="editar_nota(<?php echo $reservacion['id'] ?>,<?php echo $id_nota; ?>);" class="btn waves-effect waves-light grey darken-4 right">Guardar<i class="material-icons left">save</i></a>
	        <a href="#" class="modal-action modal-close waves-effect waves-green btn red accent-2 right">Cancelar<i class="material-icons left">close</i></a>
	      </form>
	    </div>
	</div>
<?php
}
?>