<?php
include('../php/conexion.php');
$IdPago = $conn->real_escape_string($_POST['valorIdPago']);
$redireciona = $conn->real_escape_string($_POST['redireciona']);
?>
<script>
	$(document).ready(function(){
	    $('#modalverificarP').modal();
	    $('#modalverificarP').modal('open'); 
	 });
   function borrar(IdPago){
        var redireciona = $("input#redireciona").val();
        var textoMotivo = $("input#motivo").val();
        $.post("../php/control_clientes.php", { 
                accion: 4,
                id: IdPago,
                redireciona: redireciona,
                motivo: textoMotivo,
        }, function(mensaje) {
        $("#editPagos").html(mensaje);
        }); 
  };
</script>

<!-- Modal PAGOS IMPOTANTE! -->
<div id="modalverificarP" class="modal"><br>
  <div class="modal-content">
    <h5 class="red-text darken-2 center"><b>¿Estas seguro de borrar este pago?</b></h5><br>
     <h5>Motivo por el cual se eliminara:</h5> 
      <form id="respuesta">
      <div class="input-field col s12 m7 l7">
          <i class="material-icons prefix">create</i>
          <input id="motivo" type="text" class="validate" data-length="50" required>
          <label for="motivo">Motivo: Ej. (Pago duplicado o Error de captura)</label>
          <input id="redireciona" name="redireciona" type="hidden" value="<?php echo $redireciona ?>">
      </div>
      </form>
  </div><br>
  <div class="modal-footer">
      <a class="modal-action modal-close waves-effect waves-green grey darken-2 btn" onclick="borrar(<?php echo $IdPago ?>);">Eliminar<i class="material-icons right">delete</i></a>
      <a href="#" class="modal-action modal-close waves-effect waves-green red btn">Cancelar<i class="material-icons right">close</i></a>
  </div><br>
</div>
<!--Cierre modal PAGOS IMPOTANTE! -->