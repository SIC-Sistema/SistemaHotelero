<?php
//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL CLIENTE
if (isset($_POST['id']) == false) {
  ?>
  <script>    
    M.toast({html: "Regresando a clientes.", classes: "rounded"});
    setTimeout("location.href='clientes.php'", 800);
  </script>
  <?php
}else{
?>
  <html>
  <head>
  	<title>San Roman | Editar Clientes</title>
    <?php 
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    $id = $_POST['id'];// POR EL METODO POST RECIBIMOS EL ID DEL CLIENTE
    //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL CLIENTE Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
    $datos = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id=$id"));
    $id_empresa = $datos['empresa'];
    if ($id_empresa == 0) {
      $emp['razon_social'] = 'N/A';
    }else{
      $emp = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `empresas` WHERE id=$id_empresa"));
    }
    ?>
    <script>
      //FUNCION QUE AL USAR VALIDA LA VARIABLE QUE LLEVE UN FORMATO DE CORREO 
      function validar_email( email )   {
        var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email) ? true : false;
      };

      //FUNCION QUE HACE LA ACTUALIZACION DEL CLIENTE (SE ACTIVA AL PRECIONAR UN BOTON)
      function update_cliente(id) {

        //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO LA INFORMCION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
        var textoNombre = $("input#nombre").val();//ej:LA VARIABLE "textoNombre" GUARDAREMOS LA INFORMACION QUE ESTE EN EL INPUT QUE TENGA EL id = "nombre"
        var textoTelefono = $("input#telefono").val();// ej: TRAE LE INFORMACION DEL INPUT FILA 95 (id="telefono")
        var textoEmail = $("input#email").val();
        var textoRFC = $("input#rfc").val();
        var textoDireccion = $("input#direccion").val();
        var textoColonia = $("input#colonia").val();
        var textoLocalidad = $("input#localidad").val();
        var textoCP = $("input#cp").val();
        var textoLimpieza = $("input#limpieza").val();
        var textoEmpresa = $("select#empresa").val();

        // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
        //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
        if (textoNombre == "") {
          M.toast({html: 'El campo Nombre Completo se encuentra vacío.', classes: 'rounded'});
        }else if(textoTelefono.length < 10){
          M.toast({html: 'El Telefono tiene que tener al menos 10 dijitos.', classes: 'rounded'});
        }else if(textoEmail == ""){
          M.toast({html:"Por favor ingrese un Email.", classes: "rounded"});
        }else if (!validar_email(textoEmail)) {
          M.toast({html:"Por favor ingrese un Email correcto.", classes: "rounded"});
        }else{
          //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_clientes.php"
          $.post("../php/control_clientes.php", {
            //Cada valor se separa por una ,
              accion: 2,
              valorNombre: textoNombre,
              valorTelefono: textoTelefono,
              valorEmail: textoEmail,
              valorRFC: textoRFC,
              valorDireccion: textoDireccion,
              valorColonia: textoColonia,
              valorLocalidad: textoLocalidad,
              valorCP: textoCP,
              valorLimpieza: textoLimpieza,
              valorEmpresa: textoEmpresa,
              id: id,
            }, function(mensaje) {
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_clientes.php"
                $("#resultado_update").html(mensaje);
            }); 
        }//FIN else CONDICIONES
      };//FIN function 
    </script>
  </head>
  <main>
  <body>
    <!-- DENTRO DE ESTE DIV VA TODO EL CONTENIDO Y HACE QUE SE VEA AL CENTRO DE LA PANTALLA.-->
    <div class="container"><br><br>
      <!--    //////    TITULO    ///////   -->
      <div class="row" >
        <h3 class="hide-on-med-and-down">Editar Cliente N°<?php echo $id; ?></h3>
        <h5 class="hide-on-large-only">Editar Cliente N°<?php echo $id; ?></h5>
      </div>
      <div class="row" >
       <!-- CREAMOS UN DIV EL CUAL TENGA id = "resultado_update"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
       <div id="resultado_update"></div>
       <div class="row">
        <!-- FORMULARIO EL CUAL SE MUETRA EN PANTALLA .-->
        <form class="row col s12">
          <!-- DIV QUE SEPARA A DOBLE COLUMNA PARTE IZQ.-->
          <div class="col s12 m6 l6">
            <br>
            <div class="input-field">
              <i class="material-icons prefix">people</i>
              <input id="nombre" type="text" class="validate" data-length="100" required value="<?php echo $datos['nombre']; ?>">
              <label for="nombre">Nombre Completo:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">phone</i>
              <input id="numero_exterior" type="text" class="validate" data-length="10" required value="<?php echo $datos['numero_exterior']; ?>">
              <label for="numeroExterior">Número Exterior:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">phone</i>
              <input id="colonia" type="text" class="validate" data-length="30" required value="<?php echo $datos['colonia']; ?>">
              <label for="colonia">Colonia:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">phone</i>
              <input id="municipio" type="text" class="validate" data-length="50" required value="<?php echo $datos['municipio']; ?>">
              <label for="municipio">Municipio:</label>
            </div>    
            <div class="input-field">
              <i class="material-icons prefix">location_on</i>
              <input id="cp" type="number" class="validate" data-length="6" required value="<?php echo $datos['cp']; ?>">
              <label for="cp">Codigo Postal:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">mail</i>
              <input id="email" type="text" class="validate" data-length="30" required value="<?php echo $datos['email']; ?>">
              <label for="email">E-mail:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">person</i>
              <input id="rfc" type="text" class="validate" data-length="15" required value="<?php echo $datos['rfc']; ?>">
              <label for="rfc">RFC:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">directions_walk</i>
              <input id="limpieza" type="text" class="validate" data-length="15" required value="<?php echo $datos['limpieza']; ?>">
              <label for="limpieza">Limpieza (Instrucciones del cliente):</label>
            </div>         
          </div>
          <!-- DIV DOBLE COLUMNA EN ESCRITORIO PARTE DERECHA -->
          <div class="col s12 m6 l6">
            <br>
            <div class="input-field">
              <i class="material-icons prefix">location_on</i>
              <input id="calle" type="text"  class="validate" data-length="50" required value="<?php echo $datos['calle']; ?>">
              <label for="calle">Calle:</label>
            </div>
            <div class="input-field">
              <i class="material-icons prefix">location_city</i>
              <input id="numeroInterior" type="text" class="validate" data-length="10" required value="<?php echo $datos['numero_interior']; ?>">
              <label for="numeroInterior">Número Interior:</label>
            </div> 
            <div class="input-field">
              <i class="material-icons prefix">location_city</i>
              <input id="localidad" type="text" class="validate" data-length="30" required value="<?php echo $datos['localidad']; ?>">
              <label for="localidad">Localidad:</label>
            </div> 
            <select id="estado" name="estado" class="browser-default">
											<!--OPTION PARA QUE LA SELECCION QUEDE POR DEFECTO VACIA-->
											
											<?php 
											$idEstado = $datos['estado_mex'];
											$consultaEstado = mysqli_query($conn, "SELECT * FROM estados_mex WHERE id=$idEstado");
											if (mysqli_num_rows($consultaEstado) == 0){
											  echo '<script>M.toast({html:"Hubo un error al obtener el estado", classes: "rounded"})</script>';
											  echo '<option value="100" select> Seleccione un estado de la república</option>';
											}else{
											  while($seleccionEstado =mysqli_fetch_array($consultaEstado)){
											   ?>
												<option value="<?php echo $seleccionEstado['id'];?>"><?php echo $seleccionEstado['nombre'];?></option>
											  <?php
											  }
								  }
												// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
											$consulta = mysqli_query($conn,"SELECT * FROM estados_mex");
												//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
											if (mysqli_num_rows($consulta) == 0) {
												echo '<script>M.toast({html:"No se encontraron estados.", classes: "rounded"})</script>';
											} else {
													//RECORREMOS UNO A UNO LOS ARTICULOS CON EL WHILE
												while($estados = mysqli_fetch_array($consulta)) {
														//Output
													?>                      
													<option value="<?php echo $estados['id'];?>"><?php echo $estados['nombre'];// MOSTRAMOS LA INFORMACION HTML?></option>
													<?php
												}//FIN while
											}//FIN else
											?>
										</select>
            <div class="input-field">
              <i class="material-icons prefix">phone</i>
              <input id="telefono" type="text" class="validate" data-length="13" required value="<?php echo $datos['telefono']; ?>">
              <label for="telefono">Teléfono:</label>
            </div> 
            <div class="input-field">
              <select id="empresa" name="empresa" class="browser-default">              
                <!--OPTION PARA QUE LA SELECCION QUEDE POR DEFECTO-->
                <option value="<?php echo $id_empresa; ?>" select>N° <?php echo $id_empresa.' - '.$emp['razon_social'];?>:</option>
                <option value="0">N/A</option>
                <?php
                  $consulta = mysqli_query($conn,"SELECT * FROM empresas"); 
                    //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
                    if (mysqli_num_rows($consulta) == 0) {
                      echo '<script>M.toast({html:"No se encontraron empresas.", classes: "rounded"})</script>';
                    } else {
                       //RECORREMOS UNO A UNO LOS ARTICULOS CON EL WHILE
                       while($empresa = mysqli_fetch_array($consulta)) {
                        //Output
                        ?>
                          <option value="<?php echo $empresa['id'];?>">N° <?php echo $empresa['id'].' - '.$empresa['razon_social'];?></option>
                        <?php
                     }//FIN while
                  }//FIN else
                ?>
              </select>
            </div>
          </div>
        </form>
        <!-- BOTON QUE MANDA LLAMAR EL SCRIPT PARA QUE EL SCRIPR HAGA LO QUE LA FUNCION CONTENGA -->
        <a onclick="update_cliente(<?php echo $id; ?>);" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">save</i>Guardar</a>
      </div> 
    </div><br>
  </body>
  </main>
  </html>
<?php
}// FIN else POST
?>