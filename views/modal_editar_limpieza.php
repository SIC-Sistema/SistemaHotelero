<?php
include('../php/conexion.php');
$id = $conn->real_escape_string($_POST['id']);
$limpieza = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `limpieza` WHERE id = $id"));
    ?>
    <script>
        $(document).ready(function(){
            $('#modalLimpEdit').modal();
            $('#modalLimpEdit').modal('open'); 
         });
    </script>
    <div id="modalLimpEdit" class="modal">
        <div class="modal-content row">
          <h5 class="red-text center"><b>¡Editar reporte de limpieza!</b></h5><br>
          <h5 class="blue-text"><b>Descripcion:</b></h5>
          <form class="row">
            <div class="row">
              <div class="input-field col s12 m6 l6">
                <i class="material-icons prefix">edit</i>
                <input id="descripcionLimp" type="text" class="validate" data-length="150" required value="<?php echo $limpieza['descripcion']; ?>">
              </div>
            </div>
            <a onclick="editar_limp(<?php echo $id; ?>);" class="btn waves-effect waves-light grey darken-4 right">Guardar<i class="material-icons left">save</i></a>
            <a href="#" class="modal-action modal-close waves-effect waves-green btn red accent-2 right">Cancelar<i class="material-icons left">close</i></a>
          </form>
        </div>
    </div>