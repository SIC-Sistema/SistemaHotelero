<?php
//INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
include('fredyNav.php');
$idEmisor = 1;
$datosEmisor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `emisores_sat` WHERE id=$idEmisor"));
//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL CLIENTE
if ($_SESSION['user_id'] == false) {
  ?>
  <script>    
    M.toast({html: "Regresando a inicio.", classes: "rounded"})
    setTimeout("location.href='home.php'", 8000);
  </script>
    <?php
}else{
?>
  <!DOCTYPE html>
  <html>
    <head>
    	<title>San Roman | Perfil Usuario</title>
      <?php
      //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL USUARIO Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
      $user_id = $_SESSION['user_id'];
      $datos = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id=$user_id"));
      ?>
      <script>
        //FUNCION QUE AL USAR VALIDA LA VARIABLE QUE LLEVE UN FORMATO DE CORREO 
        function validar_email( email )   {
            var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email) ? true : false;
        };
        //FUNCION QUE HACE LA ACTUALIZACION DEL USUARIO (SE ACTIVA AL PRECIONAR UN BOTON)
        function editar_perfil(id){
          var textoNombres = $("input#nombres").val();
          var textoApellidos = $("input#apellidos").val();
          var textoUsuario = $("input#usuario").val();
          var textoEmail = $("input#email").val();

          if (textoNombres == "") {
            M.toast({html:"El campo Nombres se encuentra vacío", classes: "rounded"});
          }else if (textoApellidos == "") {
            M.toast({html:"El campo Apellidos se encuentra vacío", classes: "rounded"});
          }else if (textoUsuario == "") {
            M.toast({html:"El campo Usuario se encuentra vacío", classes: "rounded"});
          }else if (!validar_email(textoEmail)) {
              M.toast({html:"Por favor ingrese un E-mail correcto.", classes: "rounded"});
          }else if (textoEmail == "") {
            M.toast({html:"El campo E-mail se encuentra vacío", classes: "rounded"});
          }else {
            $.post("../php/control_users.php", { 
                accion: 1,
                valorId: id,
                valorNombres: textoNombres,
                valorApellidos: textoApellidos,
                valorUsuario: textoUsuario,
                valorEmail: textoEmail,
            }, function(mensaje) {
                $("#update_perfil").html(mensaje);   
            });
          }
        }
        //FUNCION QUE HACE LA ACTUALIZACION DEL USUARIO (SE ACTIVA AL PRECIONAR UN BOTON)
        function update_contra(id){
          var textoContraAnterior = $("input#contra_anterior").val();
          var textoContra = $("input#contra").val();
          var textoRepiteContra = $("input#repite_contra").val();

          // CREAMOS CONDICIONES QUE SI SE CUMPLEN MANDARA MENSAJES DE ALERTA EN FORMA DE TOAST
          //SI SE CUMPLEN LOS IF QUIERE DECIR QUE NO PASA LOS REQUISITOS MINIMOS DE LLENADO...
          if(textoContraAnterior == ""){
            M.toast({html:"Por favor ingrese una contraseña anterior.", classes: "rounded"});
          }else if(textoContra == ""){
            M.toast({html:"Por favor ingrese una nueva contraseña.", classes: "rounded"});
          }else if ((textoContra.length) < 6) {
            M.toast({html:"Ingrese una nueva contraseña mas larga.", classes: "rounded"});
          }else if(textoContra != textoRepiteContra){
            M.toast({html:"Las contraseñas (nueva) no coinciden.", classes: "rounded"});
          }else{
            //SI LOS IF NO SE CUMPLEN QUIERE DECIR QUE LA INFORMACION CUENTA CON TODO LO REQUERIDO
            //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_users.php"
            $.post("../php/control_users.php", { 
              //Cada valor se separa por una ,
                accion: 5,
                valorId: id,
                valorContra: textoContra,
                valorContraAnterior: textoContraAnterior,
            }, function(mensaje) {
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_users.php"
                $("#modal").html(mensaje);   
            });
          }// FIN else
        }//FIN function 

        //FUNCION QUE MUESTRA EL MODAL PARA CAMBIAR LA CONTRASEÑA
        function editar_pass(id){
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "modal_contrasena.php" PARA MOSTRAR EL MODAL
          $.post("modal_contrasena.php", {
            //Cada valor se separa por una ,
              id:id,
            }, function(mensaje){
                //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "modal_contrasena.php"
                $("#modal").html(mensaje);
          });//FIN post
        }//FIN function
      </script>
    </head>
    <body>
      <!-- CREAMOS UN DIV EL CUAL TENGA id = "modal"  PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
      <div id="modal"></div>
      <div class="container" id="update_perfil">
        <br>
          <div class="row">
            <div class="row">
              <h4 class="hide-on-med-and-down center">Perfil de acceso al sistema:</h2>
              <h4 class="hide-on-large-only center">Perfil de acceso al sistema:</h4>
            </div>
            <hr>
            <div class="row">
              <b class="col s4 m2 l2">N° Usuario: </b>
              <div class="col s8 m4 l4"><?php echo $datos['user_id'];?></div>
              <b class="col s4 m2 l2">Usuario: </b>
              <div class="col s8 m4 l4">
                <div class="input-field">
                  <i class="material-icons prefix">person_pin</i>
                  <input type="text" id="usuario" name="usuario" value="<?php echo $datos['user_name'];?>">
                  <label for="usuario">Nombre de usuario</label>
                </div>            
              </div>
              <b class="col s4 m2 l2">Nombre(s): </b>
              <div class="col s8 m4 l4">
                <div class="input-field">
                  <i class="material-icons prefix">people</i>
                  <input type="text" id="nombres" name="nombres" value="<?php echo $datos['firstname']; ?>">
                  <label for="nombre">Nombre</label>
                </div>            
              </div>
              <b class="col s4 m2 l2">Apellidos: </b>
              <div class="col s8 m4 l4">
                <div class="input-field">
                  <i class="material-icons prefix">people</i>
                  <input type="text" id="apellidos" name="apellidos" value="<?php echo $datos['lastname'];?>">
                  <label for="apellidos">Apellidos</label>
                </div>
              </div>
              <b class="col s4 m2 l2">E-mail: </b>
              <div class="col s8 m4 l4">
                <div class="input-field">
                  <i class="material-icons prefix">mail</i>
                  <input type="text" id="email" name="email" value="<?php echo $datos['user_email'];?>">
                  <label for="email">E-mail</label>
                </div>            
              </div>
              <b class="col s4 m2 l2"><br>Area: </b>
              <div class="col s8 m4 l4"><br><?php echo $datos['area'];?></div>
            </div>
            <hr>
            <a onclick="editar_pass(<?php echo $datos['user_id'];?>);" class="waves-effect waves-light btn grey darken-3 right"><i class="material-icons right">security</i>Editar contraseña</a>
            <a onclick="editar_perfil(<?php echo $datos['user_id'];?>);" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">edit</i>Editar perfil</a><br><br>
          </div>
    </body>
  </html>
<?php 
}
?>
