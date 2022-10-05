<script>
  function recargar_clientes() {
    setTimeout("location.href='clientes_punto_venta.php'", 800);
  }
  function recargar_habitaciones() {
    setTimeout("location.href='habitaciones.php'", 800);
  }
  function cerrar_sesion() {
    setTimeout("location.href='../php/cerrar_sesion.php'", 1200);
  }
  function recargar_proveedores() {
    setTimeout("location.href='proveedores_punto_venta.php'", 800);
  }
  function recargar_usuarios() {
    setTimeout("location.href='usuarios.php'", 800);
  }
  function recargar_articulo() {
    setTimeout("location.href='articulos_punto_venta.php'", 800);
  }
  function recargar_categoria() {
    setTimeout("location.href='categorias_punto_venta.php'", 800);
  }
  function recargar_almacen_lista() {
    setTimeout("location.href='almacenes_punto_venta.php'", 800);
  }
  function home() {
    setTimeout("location.href='home.php'", 1000);
  }
</script>

<!--Modal cortes-->
<div id="corte" class="modal">
  <div class="modal-content">
    <h4 class="red-text center">! Advertencia !</h4><br>
    <h6 ><b>Una vez generado el corte se comenzara una nueva lista de pagos para el siguinete corte. </b></h6><br>
    <h5 class="red-text darken-2">¿DESEA CONTINUAR?</h5>
    <div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="material-icons prefix">lock</i>
        <input type="password" name="clave" id="clave">
        <label for="clave">Ingresar Clave</label>
    </div>
    </div>
    <h4>¿Desea agregar algun deducible?</h4>
      <form class="row">
      <div class="input-field col s12 m6 l4">
          <i class="material-icons prefix">attach_money</i>
          <input id="cantidadD" type="number" class="validate" data-length="30" value="0" required>
          <label for="cantidadD">Cantidad:</label>
      </div>
      <div class="input-field col s12 m6 l6">
          <i class="material-icons prefix">edit</i>
          <input id="descripcionD" type="text" class="validate" data-length="30" required>
          <label for="descripcionD">Descripcion:(ej: Viaticos para Marcos y Luis) </label>
      </div>
      </form>
  </div>
  <div class="modal-footer">
      <a onclick="recargar_corte()" class="modal-action modal-close waves-effect waves-green btn-flat">Aceptar</a>
      <a href="#" class="modal-action modal-close waves-effect waves-red btn-flat">Cerrar<i class="material-icons right">close</i></a>
  </div>
</div>
<!--Cierre modal Cortes-->
