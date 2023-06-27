<!DOCTYPE html>
<html lang="es">
  <head>
    <?php
    include('fredyNav.php');
    ?>
    <title>San Roman | Reimprimir</title>
    <script>
      function buscar_cfdis(){
        var texto = $("input#busqueda").val();
        $.post("../php/control_cfdis.php", {
            texto: texto,
            accion: 1,
          }, function(mensaje){
            $("#resultadoBusqueda").html(mensaje);
        });
      };

      function reimprimirCfdi(id_factura){
        var idFactura = id_factura;
          $.post("../php/control_cfdis.php", {
            accion: 2,
            valorIdFactura: idFactura
          }, function(mensaje) {
          $("#resultado_info").html(mensaje);
          
          })
      };  

      function imprimirCfdi(jsonEncoded){
        var pdf_newTab = window.open("");
        pdf_newTab.document.write(
          "<html><head><title>CFDI</title></head><body><iframe title='CFDI'  width='100%' height='100%' src='data:application/pdf;base64, " +    encodeURI(jsonEncoded) + "'></iframe></body></html>"
        );
        M.toast({html:"Factura reimpresa correctamente", classes: "rounded"});
        
      };  

    </script>
  </head>
  <main>
  <body>
    <div class="container"><br><br>
      <div class="row">
        <h3 class="hide-on-med-and-down col s12 m6 l6">Reimprimir facturas</h3>
        <h5 class="hide-on-large-only col s12 m6 l6">Reimprimir facturas</h5>
        <form class="col s12 m6 l6">
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">search</i>
              <input id="busqueda" name="busqueda" type="text" class="validate" onkeyup="buscar_cfdis();">
              <label for="busqueda">Buscar por RFC</label>
            </div>
          </div>
        </form>
      </div>
      <!--    //////    TABLA QUE MUESTRA LA INFORMACION DE LOS CLIENTES    ///////   -->
      <div class="row">
        <table class="bordered highlight responsive-table">
          <thead>
            <tr>
              <th>RFC</th>
              <th>Razon social</th>
              <th>Servicio</th>
              <th>Fecha y hora</th>
              <th>Monto</th>
              <th>Reimprimir</th>
            </tr>
          </thead>
          <tbody id="resultadoBusqueda">
          </tbody>
        </table>
        <div id="resultado_info"></div>
      </div><br><br>
    </div>
  </body>
  </main>
</html>