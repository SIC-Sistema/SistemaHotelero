<?php
//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL USUARIO
if (isset($_POST['id']) == false) {
  ?>
  <script>    
    M.toast({html: "Regresando a usuarios.", classes: "rounded"})
    setTimeout("location.href='usuarios.php'", 800);
  </script>
  <?php
}else{
?>
  <!DOCTYPE html>
  <html>
    <head>
    	<title>San Roman | Permisos Usuarios</title>
      <?php
      //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
      include('fredyNav.php');
      $id = $_POST['id'];// POR EL METODO POST RECIBIMOS EL ID DEL USUARIO
      //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL USUARIO Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
      $datos = mysqli_fetch_array( mysqli_query($conn,"SELECT * FROM users WHERE user_id=$id"));
      ?>
      <script>
        //FUNCION QUE ENVIA LA INFORMACION PARA CAMBIR LOS PERMISOIS DEL CLIENTE SELECCIONADO
        function cambiar_permisos(id){  
          //PRIMERO VAMOS Y BUSCAMOS EN ESTE MISMO ARCHIVO LA INFORMCION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
          var textoArea = $("select#area").val();//ej:LA VARIABLE "textoArea" GUARDAREMOS LA INFORMACION QUE ESTE EN EL SELECT QUE TENGA EL id = "area"  
          //SE VERIFICA SI EL SELECT DEL PERMISO ESTA SELECCIONADO O NO Y SE DA UN VALOR
          if(document.getElementById('usuarios').checked==true){
            Usuario = 1;
          }else { Usuario = 0; }
          if(document.getElementById('clientes').checked==true){
            Clientes = 1;
          }else { Clientes = 0; }
          if(document.getElementById('habitaciones').checked==true){
            Habitaciones = 1;
          }else { Habitaciones = 0; }
          if(document.getElementById('borrar').checked==true){
            Borrar = 1;
          }else { Borrar = 0; }
          if(document.getElementById('facturar').checked==true){
            Facturar = 1;
          }else { Facturar = 0; }
          if(document.getElementById('reportes').checked==true){
            Reportes = 1;
          }else { Reportes = 0; }
          if(document.getElementById('cancelar').checked==true){
            Cancelar = 1;
          }else { Cancelar = 0; }
          if(document.getElementById('cortes').checked==true){
            Cortes = 1;
          }else { Cortes = 0; }

            //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO NE LA DIRECCION "../php/control_users.php"
            $.post("../php/control_users.php", { 
              //Cada valor se separa por ,
                accion: 4,
                id: id,
                Usuario: Usuario,               
                Clientes: Clientes,               
                Habitaciones: Habitaciones,               
                Borrar: Borrar,               
                Facturar: Facturar,               
                Reportes: Reportes,               
                Cancelar: Cancelar,               
                Cortes: Cortes,               
                Area: textoArea,               
            }, function(mensaje) {
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_users.php"
                $("#cambio_permisos").html(mensaje);   
            });//FIN post
        }//FIN function
      </script>
    </head>
    <body>
      <!-- DENTRO DE ESTE DIV VA TODO EL CONTENIDO Y HACE QUE SE VEA AL CENTRO DE LA PANTALLA.-->
    	<div class="container">
        <!--    //////    TITULO    ///////   -->
    		<div class="row">
    			<h2 class="hide-on-med-and-down">Permisos del Usuario:</h2>
     			<h4 class="hide-on-large-only">Permisos del Usuario:</h4>
    		</div>
        <!--   //// INFORMACION DEL USUARIO  //// --->
    		<div class="row">
    			<ul class="collection">
                <li class="collection-item avatar">
                  <img src="../img/cliente.png" alt="" class="circle">
                  <span class="title"><b>N°: </b><?php echo $id; ?></span>
                  <p><b>Nombre(s): </b><?php echo $datos['firstname'].' '.$datos['lastname']; ?><br>
                     <b>Usuario: </b><?php echo $datos['user_name']; ?><br>
                     <b>Email: </b><?php echo $datos['user_email']; ?><br>
                     <b>Area: </b><?php echo $datos['area']; ?><br>
                  </p>
                </li>
            </ul>		
    		</div>
        <div class="row">
          <h3 class="hide-on-med-and-down">Permisos:</h3>
          <h5 class="hide-on-large-only">Permisos:</h5>
        </div>
        <!-- ///// FORMULARIO QUE MUESTRA LOS CHECK DE PERMISOS ////-->

        <!-- CAJA DE SELECCION DE ROL -->
        <div class="row col s12">
          <h4 class="hide-on-med-and-down col s12 m5 l4">Rol de Usuario:</h4>
          <h6 class="hide-on-large-only col s12 m6 l5">Rol de Usuario:</h6>
          <div class="input-field col s12 m6 l5">
            <i class="material-icons prefix">autorenew</i>
            <select id="area" name="area" class="validate">              
              <!--OPTION PARA QUE LA SELECCION QUEDE POR DEFECTO-->
              <option value="<?php echo $datos['area']; ?>" select><?php echo $datos['area']; ?></option>
              <option value="Recepcionista">Recepcionista</option>
              <option value="Oficina">Oficina</option>
              <option value="Administrador">Administrador</option>
            </select>
          </div>
        </div>
        <div class="row">
            <div class="col s6 m3 l3">
              <p>
                <br>
                <label>
                  <input type="checkbox" <?php echo ($datos['usuarios'] == 1)?"checked":"";?> id="usuarios"/>
                  <span for="usuarios">Usuarios</span>
                </label>
              </p>
            </div>
            <div class="col s6 m3 l3">
              <p>
                <br>
                <label>
                  <input type="checkbox" <?php echo ($datos['clientes']  == 1)?"checked":"";?> id="clientes"/>
                  <span for="clientes">Clientes</span>
                </label>
              </p>
            </div>
            <div class="col s6 m3 l3">
              <p>
                <br>
                <label>
                  <input type="checkbox" <?php echo ($datos['habitaciones']  == 1)?"checked":"";?> id="habitaciones"/>
                  <span for="habitaciones">Habitaciones</span>
                </label>
              </p>
            </div>
            <div class="col s6 m3 l3">
              <p>
                <br>
                <label>
                  <input type="checkbox" <?php echo ($datos['borrar']  == 1)?"checked":"";?> id="borrar"/>
                  <span for="borrar">Borrar Ingreso/Egreso</span>
                </label>
              </p>
            </div>
            <div class="col s6 m3 l3">
              <p>
                <br>
                <label>
                  <input  type="checkbox" <?php echo ($datos['cancelar']  == 1)?"checked":"";?> id="cancelar"/>
                  <span for="cancelar">Cancelar Reservación</span>
                </label>
              </p>
            </div>
            <div class="col s6 m3 l3">
              <p>
                <br>
                <label>
                  <input  type="checkbox" <?php echo ($datos['facturar']  == 1)?"checked":"";?> id="facturar"/>
                  <span for="facturar">Facturar</span>
                </label>
              </p>
            </div>            
            <div class="col s6 m3 l3">
              <p>
                <br>
                <label>
                  <input  type="checkbox" <?php echo ($datos['reportes']  == 1)?"checked":"";?> id="reportes"/>
                  <span for="reportes">Reportes</span>
                </label>
              </p>
            </div>  
            <div class="col s6 m3 l3">
              <p>
                <br>
                <label>
                  <input  type="checkbox" <?php echo ($datos['cortes']  == 1)?"checked":"";?> id="cortes"/>
                  <span for="cortes">Cortes</span>
                </label>
              </p>
            </div> 
        </div>
          
        <!--BOTON DE GURARDAR PERMISOS-->
        <div class="row s12">
          <a onclick="cambiar_permisos(<?php echo $id; ?>);" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">save</i>Guardar Todo</a>
        </div><br><br>
        <!-- CREAMOS UN DIV EL CUAL TENGA id="cambio_permisos" PARA QUE EN ESTA PARTE NOS MUESTRE LOS RESULTADOS EN TEXTO HTML DEL SCRIPT EN FUNCION  -->
        <div id="cambio_permisos"></div>
    	</div><!--DIV DEL CONTAINER-->
    </body>
  </html>
<?php 
}
?>