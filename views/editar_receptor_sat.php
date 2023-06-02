<?php
//INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
include('fredyNav.php');
//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL CLIENTE
if ($_SESSION['user_id'] == false) {
  ?>
    <script>    
      M.toast({html: "Regresando a inicio.", classes: "rounded"})
      setTimeout("location.href='home.php'", 8000);
    </script>
  <?php
}else if (isset($_POST['id']) == false) {
  ?>
  <script>    
    M.toast({html: "Regresando a receptores.", classes: "rounded"});
    setTimeout("location.href='receptores_sat.php'", 800);
  </script>
  <?php
}else {
  $idReceptor = $_POST['id'];
  $datosReceptor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `receptores_sat` WHERE id=$idReceptor"));
?>
  <!DOCTYPE html>
  <html>
    <head>
    	<title>San Roman | Datos Emisor</title>
      <script>
        //FUNCION QUE AL USAR VALIDA LA VARIABLE QUE LLEVE UN FORMATO DE CORREO 
        function validar_email(email)   {
          var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          return regex.test(email) ? true : false;
        };
        //FUNCION QUE HACE LA ACTUALIZACION DEL USUARIO (SE ACTIVA AL PRECIONAR UN BOTON)
        function editar_receptor(id){
          var textoRfc = $("input#rfc").val();
          var textoRazonSocial = $("input#razonSocial").val();
          var textoRegimen = $("select#regimen").val();
          var textoEstadoMx = $("select#estadoMx").val();
          var textoMunicipio = $("input#municipio").val();
          var textoLocalidad = $("input#localidad").val();
          var textoColonia = $("input#colonia").val();
          var textoCalle = $("input#calle").val();
          var textoNumeroExterior = $("input#numeroExterior").val();
          var textoNumeroInterior = $("input#numeroInterior").val();
          var textoCodigoPostal = $("input#codigoPostal").val();
          var textoNombreCompleto = $("input#nombre").val();
          var textoEmail = $("input#emailReceptor").val();
          var textoUsoCfdi = $("select#usoCfdi").val();
          
          //Validación de campos 
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
          }else if (textoNombreCompleto == "") {
            M.toast({html:"Escriba el nombre completo", classes: "rounded"});
          }else if (!validar_email(textoEmail) && textoEmail != "") {
            M.toast({html:"Por favor ingrese un E-mail correcto.", classes: "rounded"});
          }else if (textoUsoCfdi == "") {
            M.toast({html:"Escriba un asunto para los correos", classes: "rounded"});
          }else {
            $.post("../php/control_receptores.php", { 
                accion: 2,
                valorId: id,
                valorRfc: textoRfc,
                valorRazonSocial: textoRazonSocial,
                valorRegimen: textoRegimen,
                valorEstadoMx: textoEstadoMx,
                valorMunicipio: textoMunicipio,
                valorLocalidad: textoLocalidad,
                valorColonia: textoColonia,
                valorCalle: textoCalle,
                valorNumeroExterior: textoNumeroExterior,
                valorNumeroInterior: textoNumeroInterior,
                valorCodigoPostal: textoCodigoPostal,
                valorNombreCompleto: textoNombreCompleto,
                valorEmail: textoEmail,
                valorUsoCfdi: textoUsoCfdi,
            }, function(mensaje) {
                $("#update_emisor").html(mensaje);   
            });
          }
        }
      </script>
    </head>
    <body>
      <div class="container" id="update_emisor">
        <br>
          <div class="row">
            <div class="card-panel cyan lighten-4">La información que aquí se muestra es la que se enviará al SAT
              para cuando se generan las facturas. Es importante que la información esté correctamente registrada,
              en caso contrario, el SAT rechazará las facturas.
            </div>
            <!-- Primera columna -->
            <div class="col s12 m6 l6">
              <div class="input-field">
                <i class="material-icons prefix">account_circle</i>
                <input id="rfc" type="text" class="validate" data-length="13" 
                value="<?php echo $datosReceptor['rfc'];?>" required>
                <label for="rfc">RFC:</label>
              </div> 
              <div class="input-field">
              <label>Selecciona un regimen fiscal</label>
              <br><br>
                <select id="regimen" name="regimen" class="browser-default">
                  <?php
                      $idRegimen = $datosReceptor['regimen'];
                      $consultaRegimen = mysqli_query($conn, "SELECT * FROM regimen_fiscal_sat WHERE clave=$idRegimen");
                      if (mysqli_num_rows($consultaRegimen) == 0){
                        echo '<script>M.toast({html:"Hubo un error al obtener la información del regimen.", classes: "rounded"})</script>';
                      }else{
                        while($seleccionRegimen =mysqli_fetch_array($consultaRegimen)){
                        ?>
                          <option value="<?php echo $seleccionRegimen['clave'];?>"><?php echo '('.$seleccionRegimen['clave'].') '.$seleccionRegimen['nombre'];?></option>
                        <?php
                        }
                      }
                    ?>                
                  <option value="0" select>Seleccione un regimen:</option>
                  <?php
                    $regimenesFiscales = mysqli_query($conn,"SELECT * FROM regimen_fiscal_sat"); 
                    if (mysqli_num_rows($regimenesFiscales) == 0) {
                      echo '<script>M.toast({html:"No se encontraron datos.", classes: "rounded"})</script>';
                    } else {
                      while($regimen = mysqli_fetch_array($regimenesFiscales)) {
                  ?>
                      <option value="<?php echo $regimen['clave'];?>"><?php echo '('.$regimen['clave'].') '.$regimen['nombre'];?></option>
                        <?php
                      }
                    }
                        ?>
                </select>
              </div> 
              <div class="input-field">
                <i class="material-icons prefix">add_location</i>
                <input id="municipio" type="text" class="validate" data-length="50" 
                value="<?php echo $datosReceptor['municipio'];?>"required>
                <label for="municipio">Municipio:</label>
              </div>
              <div class="input-field">
                <i class="material-icons prefix">edit_location</i>
                <input id="colonia" type="text" class="validate" data-length="50" 
                value="<?php echo $datosReceptor['colonia'];?>" required>
                <label for="colonia">Colonia:</label>
              </div>
              <div class="input-field">
                <i class="material-icons prefix">home</i>
                <input id="numeroExterior" type="text" class="validate" data-length="8" 
                value="<?php echo $datosReceptor['numero_exterior'];?>" required>
                <label for="numeroExterior">Número Exterior:</label>
              </div>
              <div class="input-field">
                <i class="material-icons prefix">location_city</i>
                <input id="codigoPostal" type="number" class="validate" data-length="5" 
                value="<?php echo $datosReceptor['codigo_postal'];?>" required>
                <label for="codigoPostal">Código Postal:</label>
              </div>
              <div class="input-field">
                <i class="material-icons prefix">email</i>
                <input id="emailReceptor" type="text" class="validate" data-length="50" 
                value="<?php echo $datosReceptor['email'];?>" required>
                <label for="emailReceptor">Email:</label>
              </div>
            </div>
            <!-- Segunda columna -->
            <div class ="col s12 m6 l6">
              <div class="input-field">
                  <i class="material-icons prefix">assignment_ind</i>
                  <input id="razonSocial" type="text" class="validate" data-length="60" 
                  value="<?php echo $datosReceptor['razon_social'];?>" required>
                  <label for="razonSocial">Razón social:</label>
                </div>
                <div class="input-field">
                <label>Selecciona un estado de la república</label>
                <br><br>
                <select id="estadoMx" name="estadoMx" class="browser-default">
                  <?php
                    $idEstado = $datosReceptor['estado_Mx'];
                    $consultaEstado = mysqli_query($conn, "SELECT * FROM estados_mex WHERE id=$idEstado");
                    if (mysqli_num_rows($consultaEstado) == 0){
                      echo '<script>M.toast({html:"Hubo un error al obtener la información del estado.", classes: "rounded"})</script>';
                    }else{
                      while($seleccionEstado =mysqli_fetch_array($consultaEstado)){
                      ?>
                        <option value="<?php echo $seleccionEstado['id'];?>"><?php echo $seleccionEstado['nombre'];?></option>
                      <?php
                      }
                    }
                  ?>
                  <option value="0" select>Seleccione un estado de la república:</option>               
                  <?php
                    $estadosMx = mysqli_query($conn,"SELECT * FROM estados_mex"); 
                    if (mysqli_num_rows($estadosMx) == 0) {
                      echo '<script>M.toast({html:"No se encontraron datos.", classes: "rounded"})</script>';
                    } else {
                      while($estados = mysqli_fetch_array($estadosMx)) {
                  ?>
                      <option value="<?php echo $estados['id'];?>"><?php echo $estados['nombre'];?></option>
                        <?php
                      }
                    }
                        ?>
                </select>
              </div> 
                <div class="input-field">
                  <i class="material-icons prefix">people</i>
                  <input id="localidad" type="text" class="validate" data-length="50" 
                  value="<?php echo $datosReceptor['localidad'];?>" required>
                  <label for="localidad">Localidad:</label>
                </div>
                <div class="input-field">
                  <i class="material-icons prefix">location_on</i>
                  <input id="calle" type="text" class="validate" data-length="50" 
                  value="<?php echo $datosReceptor['calle'];?>" required>
                  <label for="calle">Calle:</label>
                </div>
                <div class="input-field">
                  <i class="material-icons prefix">home</i>
                  <input id="numeroInterior" type="text" class="validate" data-length="8" 
                  value="<?php echo $datosReceptor['numero_interior'];?>" required>
                  <label for="numeroInterior">Número Interior:</label>
                </div>
                <div class="input-field">
                  <i class="material-icons prefix">account_box</i>
                  <input id="nombre" type="text" class="validate" data-length="100" 
                  value="<?php echo $datosReceptor['nombre'];?>" required>
                  <label for="nombre">Nombre completo:</label>
                </div>
                <div class="input-field">
                <select id="usoCfdi" name="usoCfdi" class="browser-default">
                  <?php
                      $idUsoCfdi = $datosReceptor['uso_cfdi'];
                      $consultaUsoCfdi = mysqli_query($conn, "SELECT * FROM uso_cfdi_sat WHERE clave='$idUsoCfdi'");
                      if (mysqli_num_rows($consultaUsoCfdi) == 0){
                        echo '<script>M.toast({html:"Hubo un error al obtener la información del estado.", classes: "rounded"})</script>';
                      }else{
                        while($usoCfdiBd =mysqli_fetch_array($consultaUsoCfdi)){
                        ?>
                          <option value="<?php echo $usoCfdiBd['clave'];?>"><?php echo '('.$usoCfdiBd['clave'].') '.$usoCfdiBd['nombre'];?></option>
                        <?php
                        }
                      }
                  ?>
                  <option value="0" select>Seleccione un uso de CFDI:</option>               
                  <?php
                    $usoCdfi = mysqli_query($conn,"SELECT * FROM uso_cfdi_sat"); 
                    if (mysqli_num_rows($usoCdfi) == 0) {
                      echo '<script>M.toast({html:"No se encontraron datos.", classes: "rounded"})</script>';
                    } else {
                      while($cfdi = mysqli_fetch_array($usoCdfi)) {
                      ?>
                        <option value="<?php echo $cfdi['clave'];?>"><?php echo '('.$cfdi['clave'].') '.$cfdi['nombre'];?></option>
                      <?php
                      }
                    }
                        ?>
                </select>
              </div> 
            </div>
            <a onclick="editar_receptor(<?php echo $datosReceptor['id'];?>);" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">edit</i>Editar emisor</a><br><br>
          </div>   
    </body>
  </html>
<?php 
}
?>
