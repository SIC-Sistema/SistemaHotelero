<html>
  <head>
  <title> Timbrado</title>
    <?php 
    include('fredyNav.php');
    //ARCHIVO QUE RESTRINGE A QUE SOLO ALGUNOS USUARIOS PUEDAN ACCEDER
    //include('../php/cobrador.php');
    $idServicio = $_POST['id'];
    $porcentajeIva = 0.16000;
    $porcentajeIsh = 0.03;
    $porcentajeReduccion = 0.8403361344537815;
    ?>
    <script>
		function buscarReceptores(){
			var texto = $("input#busquedaReceptores").val();
			$.post("../php/control_receptores.php", {
						accion: 4,
						texto: texto,
					}, function(mensaje){
						$("#tablaReceptores").html(mensaje);
					});
		};
      
		function actualizaReceptor(id_receptor){
			idReceptor = id_receptor; 
			var textoRfc = $("input#rfc").val();
			var textoRazonSocial = $("input#razonSocial").val();
			var textoRegimen = $("select#regimen").val();
			var textoCodigoPostal = $("input#codigoPostal").val();
			  
			if (textoRfc == "") {
			  M.toast({html:"El RFC no puede ir vacío", classes: "rounded"});
			}else if (textoRfc.length > 13 || textoRfc.length <= 11) {
			  M.toast({html:"RFC mayor a 13 o menor a 12 dígitos", classes: "rounded"});
			}else if (textoRazonSocial == "") {
			  M.toast({html:"El campo razón social está vacío", classes: "rounded"});
			}else if (textoRegimen == "0") {
			  M.toast({html:"Seleccione un regimen fiscal", classes: "rounded"});
			}else if (textoCodigoPostal == "") {
			  M.toast({html:"Escriba un código postal", classes: "rounded"});
			}else if (textoCodigoPostal.length > 5 || textoCodigoPostal.length <= 4) {
			  M.toast({html:"El CP no puede ser menor o mayor a 5 dígitos", classes: "rounded"});
			}else {
			  $.post("../php/control_receptores.php", { 
				accion: 6,
				valorId: idReceptor,
				valorRfc: textoRfc,
				valorRazonSocial: textoRazonSocial,
				valorRegimen: textoRegimen,
				valorCodigoPostal: textoCodigoPostal,
			  }, function(mensaje) {
					$("#resultado_info").html(mensaje);   
				});
			  }
      };

		function timbrarCfdi(id_servicio){
			var idReceptorT = $("input#idReceptor").val();
			var textoFormaPago = $("select#formaPago").val();
			var textoMetodoPago = $("select#metodoPago").val();
			idServicio = id_servicio;
			if (typeof idReceptorT === "undefined") {
				M.toast({html:"Por favor, selecciona un receptor fiscal", classes: "rounded"});
			}else if(textoFormaPago == 0){
				M.toast({html:"Por favor, selecciona una forma de pago", classes: "rounded"});
			}else if(textoMetodoPago == 0){
				M.toast({html:"Por favor, selecciona un método de pago", classes: "rounded"});
			}else {
			  $.post("../php/PostCfdiRequest.php", {
				valorReceptor: idReceptorT,
				valorReservacion: idServicio,
				valorFormaPago: textoFormaPago,
				valorMetodoPago: textoMetodoPago
			  }, function(mensaje) {
			  $("#resultado_info").html(mensaje);
			  
			  })
			}   
      };

		function errorTimbrado(){
			M.toast({html:"Ocurrió un error al timbrar el CDFI", classes: "rounded"});
		}
      
		function imprimirCfdi(jsonEncoded){
			var pdf_newTab = window.open("");
			pdf_newTab.document.write(
			  "<html><head><title>CFDI</title></head><body><iframe title='CFDI'  width='100%' height='100%' src='data:application/pdf;base64, " +    encodeURI(jsonEncoded) + "'></iframe></body></html>"
			);
			M.toast({html:"Factura generada correctamente", classes: "rounded"});
			window.location.href = "../views/clientes.php";
		};  

		function recargarReceptor(id_receptor){
			idReceptor = id_receptor;
			$.post("../php/control_receptores.php", {
					  accion: 7,
					  receptor: idReceptor,
					}, function(mensaje) {
					  $("#resultado_info").html(mensaje);
			
				  })
		};
        
		function muestraReceptor(id_receptor) {
			idReceptor = id_receptor;
				  $.post("../php/control_receptores.php", {
					  accion: 5,
					  receptor: idReceptor,
					}, function(mensaje) {
					  $("#resultado_info").html(mensaje);
					  $('#modalBuscarReceptores').modal('close');
				  })
		 };

    </script>
    <style>
      .card-panel {
        padding-top:5px;
        padding-bottom:5px;
      }
    </style>  
  </head>
  <main>
    <body>
      <div class="container" >
        <div  class="row">
          <div class="col s12">
          <br>
          <b><div class="card-panel cyan lighten-5 center">SELECCIONAR RECEPTOR FISCAL</b></div>
          </div>
        </div>
        <div class="row" >
          <form class="row col s12" name="formCotizacion">
            <div class="row">
                <div class="input-field col s12 m12 l12">
                <a href="#modalBuscarReceptores" class="waves-effect waves-light btn-small modal-trigger  
                green darken-2 right">Buscar Receptor<i class="material-icons left">search</i></a>
                </div>   
                  <div id="infoCliente" class="col s12 m12 l12"><br>
                    <div id="resultado_info"></div>
                  </div>
                </div>
                <div  class="row">
                  <div class="col s12">
                  <b><div class="card-panel cyan lighten-5 center">CONCEPTOS</b></div>
                  </div>
                </div>
                <div class="row" id="conceptos">
                  <div class="row">
	                <p><div>
	                <table class="bordered centered highlight">
	                  <thead>
                      <tr>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Subtotal</th>
                        <th>IVA</th>            
                        <th>ISH</th>
                        <th>Total</th>
	                    </tr>
	                  </thead>
	                <tbody>
                    <?php
                    $reservacion = mysqli_query($conn,"SELECT * FROM reservaciones WHERE id = $idServicio");
                    if (mysqli_num_rows($reservacion) == 0) {
                      echo '<h4>No se encontró la reservación.</h4>';
                    } else {
                      while ($datosReservacion = mysqli_fetch_array($reservacion)) {
                        $idHabitacion = $datosReservacion['id_habitacion'];
                        $tipoHabitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` 
                        WHERE id = $idHabitacion"));
                    ?>
                    <tr>
                      <td><?php echo "1"; ?></td>
                      <td><?php echo $tipoHabitacion['producto']; ?></td>
                      <td><?php 
                        echo "$". round($datosReservacion['total'] * $porcentajeReduccion, 2); ?></td>
                      <td><?php 
                        echo "$". round($datosReservacion['total'] * $porcentajeReduccion * $porcentajeIva, 2); ?></td>
                      <td><?php 
                        echo "$". round($datosReservacion['total'] * $porcentajeReduccion * $porcentajeIsh, 2); ?></td>
                      <td><?php 
                        echo "$". $datosReservacion['total'] ?></td>
                    </tr>
                  <?php
                    }
                  }
                    ?>
	                </tbody>
	            </table>
	          </div></p>
            <br>
            <div class="input-field">
              <div class="row">
                <div class="col s12 m6 l6">
                  <select id="formaPago" name="formaPago" class="browser-default">
                    <option value="0" select>Seleccione la forma de pago:</option>               
                    <?php
                      $formaPago = mysqli_query($conn,"SELECT * FROM formas_pago_sat"); 
                      if (mysqli_num_rows($formaPago) == 0) {
                        echo '<script>M.toast({html:"No se encontraron datos.", classes: "rounded"})</script>';
                      } else {
                        while($formasPago = mysqli_fetch_array($formaPago)) {
                    ?>
                      <option value="<?php echo $formasPago['id'];?>"><?php echo $formasPago['descripcion'];?></option>
                    <?php
                        }
                      }
                    ?>
                  </select>
                </div>  
                <div class="col s12 m6 l6">
                  <select id="metodoPago" name="metodoPago" class="browser-default">
                    <option value="0" select>Seleccione el método de pago:</option>               
                    <?php
                      $metodoPago = mysqli_query($conn,"SELECT * FROM metodos_pago_sat"); 
                      if (mysqli_num_rows($metodoPago) == 0) {
                        echo '<script>M.toast({html:"No se encontraron datos.", classes: "rounded"})</script>';
                      } else {
                        while($metodosPago = mysqli_fetch_array($metodoPago)) {
                    ?>
                      <option value="<?php echo $metodosPago['id'];?>"><?php echo $metodosPago['descripcion'];?></option>
                    <?php
                        }
                      }
                    ?>
                  </select>
                </div>  
              </div>  
            </div> 
            <a onclick="timbrarCfdi(<?php echo $idServicio; ?>);" 
              class="waves-effect waves-light btn-small pink right">Timbrar</a></td>
	          </div>
            </div>
          </form>
        </div> 
      </div><br>
    </body>
  </main>
</html>