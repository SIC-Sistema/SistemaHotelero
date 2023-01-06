<script>
  function recargar_clientes() {
    setTimeout("location.href='clientes.php'", 800);
  }
  function recargar_habitaciones() {
    setTimeout("location.href='habitaciones.php'", 800);
  }
  function cerrar_sesion() {
    setTimeout("location.href='../php/cerrar_sesion.php'", 1000);
  }
  function en_caja() {
    setTimeout("location.href='../views/en_caja.php'", 1000);
  }
  function recargar_proveedores() {
    setTimeout("location.href='proveedores_punto_venta.php'", 800);
  }
  function recargar_usuarios() {
    setTimeout("location.href='usuarios.php'", 800);
  }
  function recargar_articulo() {
    setTimeout("location.href='../views/articulos.php'", 800);
  }
  function recargar_inventario() {
    setTimeout("location.href='../views/inventario.php'", 800);
  }
  function home() {
    setTimeout("location.href='home.php'", 1000);
  }
  function checkIn() {
    setTimeout("location.href='check_in.php'", 1000);
  }
  function checkout() {
    setTimeout("location.href='check_out.php'", 1000);
  }
  function recargar_mantenimientos() {
    setTimeout("location.href='mantenimientos.php'", 1000);
  }
  function recargar_limpieza() {
    setTimeout("location.href='limpieza.php'", 800);
  }
  function recargar_empresas() {
    setTimeout("location.href='../views/empresas.php'", 800);
  }
</script>
<!-- Modal AGREGAR ARTICULOS IMPOTANTE! -->
<div id="modal_addArticulo" class="modal"><br>
  <div class="modal-content">
    <div class="row">
      <h6 class="col s12 m5 l5"></h6>
      <!--    //////    INPUT DE EL BUSCADOR    ///////   -->
      <form class="col s12 m7 l7">
        <div class="row">
          <div class="input-field col s12">
            <i class="material-icons prefix">search</i>
            <input id="busquedaArticulo" name="busquedaArticulo" type="text" class="validate" autocomplete="off" onKeyUp="buscar_articulos();" autofocus="true" required>
            <label for="busquedaArticulo">Buscar Artículo(Código, Nombre)</label>
          </div>
        </div>
      </form>
      <div id="tablaArticulo"></div>
    </div>
  </div>
  <div class="modal-footer">
    <a href="#" class="modal-action modal-close waves-effect waves-green red btn-small">Cerrar<i class="material-icons left">close</i></a>
  </div><br>
</div>
<!--Cierre modal AGREGAR ARTICULOS COMPRA IMPORTANTE! -->

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
      </form>
  </div>
  <div class="modal-footer">
      <a onclick="recargar_corte()" class="modal-action modal-close waves-effect waves-green btn-flat">Aceptar</a>
      <a href="#" class="modal-action modal-close waves-effect waves-red btn-flat">Cerrar<i class="material-icons right">close</i></a>
  </div>
</div>
<!--Cierre modal Cortes-->

<!--MODAL MATENIMIENTO-->
<div id="modalMantenimiento" class="modal">
    <div class="modal-content row">
        <h5 class="red-text center"><b>Nuevo Mantenimiento</b></h5><br>
        <h6><b>Describa que mantenimiento hay que realizarle a la habitacion</b></h6><br>
        <form class="row">
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <i class="material-icons prefix">comment</i>
              <textarea id="descripcionMto" class="materialize-textarea"></textarea>
              <label for="descripcionMto">Descripcion del mantenimiento:</label>
            </div>
          </div>
          <a onclick="crear_mto();" class="btn waves-effect waves-light grey darken-3 right">Agregar<i class="material-icons left">save</i></a>
          <a href="#" class="modal-action modal-close waves-effect waves-green btn red accent-2 right">Cancelar<i class="material-icons left">close</i></a>
        </form>
    </div>
</div>
<!--Cierre modal mantenimiento-->

<!--MODAL LIMPIEZA-->
<div id="modalLimpieza" class="modal">
    <div class="modal-content row">
        <h5 class="red-text center"><b>Reporte de Limpieza</b></h5><br>
        <h6><b>Seleccione una un tipo de limpieza a realizar</b></h6><br>
        <form class="row">
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <select class="browser-default" id="limpieza">
                <option value="" selected>Selecciona una opcion</option>
                <option value="CAMBIAR TODO">CAMBIAR TODO</option>
                <option value="CAMBIO DE BLANCOS">CAMBIO DE BLANCOS</option>
                <option value="TENDER CAMAS">TENDER CAMAS</option>
              </select>
            </div>
          </div>
          <a onclick="crear_limpieza();" class="btn waves-effect waves-light grey darken-3 right">Agregar<i class="material-icons left">save</i></a>
          <a href="#" class="modal-action modal-close waves-effect waves-green btn red accent-2 right">Cancelar<i class="material-icons left">close</i></a>
        </form>
    </div>
</div>
<!--Cierre modal LIMPIEZA-->
