<?php 
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');
//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
include('is_logged.php');
//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL
$Hora = date('H:i:s');

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 3 PARA VER QUE ACCION HACER (insert o consulta tabla salida = 0, busqueda modal = 6, borrar tabla = 2, cancelar ingreso = 3, cambiar cantidad de articulos = 4, insertar salida = 5)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (insert o consulta tabla salida = 0, busqueda modal = 6, borrar tabla = 2, cancelar ingreso = 3, cambiar cantidad de articulos = 4, insertar salida = 5)
switch ($Accion) {
    case 0:
        // $Accion es igual a 0 realiza:
        //CON POST RECIBIMOS UN ID DEL MODAL O AL INICIAR EL DOCUMENTO "salida_productos.php"
        $insert = $conn->real_escape_string($_POST['insert']);

        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE insid_user
        if ($insert) {
            //SE HACE LA INSERCION A TMP
            $id_art = $conn->real_escape_string($_POST['id_art']);
            if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `tmp_detalle_salida` WHERE id_articulo = $id_art"))>0) {
                echo '<script >M.toast({html:"No se pueden repetir los articulos en la lista.", classes: "rounded"})</script>';   
            }else{
                #SELECCIONAMOS LA INFORMACION DEL ARTICULO
                $articulo = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `articulos` WHERE id = $id_art"));
                //CREAMOS EL SQL PARA INSERTAR
                $sql = "INSERT INTO `tmp_detalle_salida` (id_articulo, cantidad, usuario) 
                   VALUES($id_art,'1','$id_user')";
                if(mysqli_query($conn, $sql)){
                    echo '<script >M.toast({html:"El articulo se registró exitosamente.", classes: "rounded"})</script>';   
                }else{
                    echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>'; 
                }//FIN else DE ERROR
            }
        }
        // REALIZAMOS LA CONSULTA A LA BASE DE DATOS Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
        $consulta = mysqli_query($conn, "SELECT * FROM tmp_detalle_salida WHERE usuario = $id_user");      
        ?>
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <table class="col s12 m10 l10">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Cantidad</th>
                  <th>Artículo</th>
                  <th>Exist.</th>
                  <th>Accion</th>
                </tr>
              </thead>
              <tbody>
               <?php
               $aux = mysqli_num_rows($consulta);
               //VERIFICAMOS SI HA ARRTICULOS EN LA TABLA
               if(mysqli_num_rows($consulta)>0){
               		$mayor = false;
                    while($detalle_articulo = mysqli_fetch_array($consulta)){
                        $id_art = $detalle_articulo['id_articulo'];
                        $articulo = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `articulos` WHERE id = $id_art"));
                        $existe = mysqli_fetch_array(mysqli_query($conn, "SELECT cantidad FROM `inventario` WHERE  id_articulo = $id_art"));
                        if (!$existe) {  $existe['cantidad'] = 0;  }
                        if ($detalle_articulo['cantidad']>$existe['cantidad']){        $mayor = true;           }
                        ?>
                        <tr>
                            <td><?php echo $articulo['codigo'] ?></td>
                            <td><input id="cantidadA<?php echo $id_art; ?>" type="number" class="validate col s6 m4 l4" value="<?php echo $detalle_articulo['cantidad'];?>" onchange= 'totales(<?php echo $id_art.', '.$id_user;?>);'><br><?php echo $articulo['unidad'] ?></td>
                            <td><?php echo $articulo['nombre'] ?></td>
                            <td><?php echo $existe['cantidad'].' '.$articulo['unidad'] ?></td>
                            <td><a onclick="borrar_lista_articulo(<?php echo $id_art; ?>);" class="waves-effect waves-light btn-small red right"><i class="material-icons">delete</i></a></td>
                        </tr>
                    <?php
                    }//FIN WHILE
                    echo '<input type="hidden" id="mayor_exist" value="'.$mayor.'">';
               }else{
                  echo '<tr><td></td><td></td><td><h6> Sin Artículos </h6></td></tr>';
               }//FIN ELSE
               ?>                
              </tbody>
            </table>
        </div>
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <div class="col s12 m10 l10">
                <div class="col s6 m6 l6 ">
                    <h5 class="right"><b>Número de Artículos <?php echo $aux;?></b></h5><br><br><br><br>
                    <a onclick="borrar_lista_all(<?php echo $id_user; ?>)" class="waves-effect waves-light btn-small red right">Cancelar<i class="material-icons left">close</i></a>
                    <a onclick="insert_salida()" class="waves-effect waves-light btn-small indigo right">Registrar<i class="material-icons left">done</i></a>
                </div>
                <div class="hide-on-small-only col s2"><br></div>
            </div>            
        </div>
        <hr><br>
        <?php
        break;
    case 1:
    	// $Accion es igual a 1 realiza:
        
        //CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO de "ingreso_articulos.php" MODAL
        $Texto = $conn->real_escape_string($_POST['texto']);
        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
        if ($Texto != "") {
            //MOSTRARA LOS ARTICULOS QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
            $sql = "SELECT * FROM `articulos` WHERE  codigo LIKE '%$Texto%' OR nombre LIKE '%$Texto%'  LIMIT 5 "; 
        }else{//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
            $sql = "SELECT * FROM `articulos` LIMIT 5";
        }//FIN else $Texto VACIO O NO

         // REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
        $consulta = mysqli_query($conn, $sql);      
        ?>
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <table class="col s12 m10 l10">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Nombre</th>
                </tr>
              </thead>
              <tbody>
               <?php
               //VERIFICAMOS SI HA ARRTICULOS EN LA TABLA
               if(mysqli_num_rows($consulta)>0){
                    while($articulo = mysqli_fetch_array($consulta)){
                    ?>
                        <tr>
                            <td><?php echo $articulo['codigo'] ?></td>
                            <td><?php echo $articulo['nombre'] ?></td>
                            <td><a onclick="tmp_articulos(1,<?php echo $articulo['id'] ?>);" class="waves-effect waves-light btn-small indigo right">Agregar</a></td>
                        </tr>
                    <?php
                    }//FIN WHILE
               }else{
                    echo '<tr><td></td><td></td><td><h6> Sin Artículos </h6></td></tr>';
               }//FIN ELSE
               ?>                
              </tbody>
            </table>
        </div>
        <?php
    	break;
    case 2:
    	# code...
    	$id_art = $conn->real_escape_string($_POST['id']);
    	if (mysqli_query($conn, "DELETE FROM tmp_detalle_salida WHERE id_articulo = $id_art AND usuario  = $id_user")) {
            echo '<script >M.toast({html:"Artículo borrado con exito", classes: "rounded"})</script>'; 
    	}else{
            echo '<script >M.toast({html:"Ocurrio un error al borrar", classes: "rounded"})</script>'; 
    	}
    	// REALIZAMOS LA CONSULTA A LA BASE DE DATOS Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
        $consulta = mysqli_query($conn, "SELECT * FROM tmp_detalle_salida WHERE usuario = $id_user");      
        ?>
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <table class="col s12 m10 l10">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Cantidad</th>
                  <th>Artículo</th>
                  <th>Exist.</th>
                  <th>Accion</th>
                </tr>
              </thead>
              <tbody>
               <?php
               $aux = mysqli_num_rows($consulta);
               //VERIFICAMOS SI HA ARRTICULOS EN LA TABLA
               if(mysqli_num_rows($consulta)>0){
               		$mayor = false;
                    while($detalle_articulo = mysqli_fetch_array($consulta)){
                        $id_art = $detalle_articulo['id_articulo'];
                        $articulo = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `articulos` WHERE id = $id_art"));
                        $existe = mysqli_fetch_array(mysqli_query($conn, "SELECT cantidad FROM `inventario` WHERE  id_articulo = $id_art"));
                        if (!$existe) {  $existe['cantidad'] = 0;  }
                        if ($detalle_articulo['cantidad']>$existe['cantidad']){        $mayor = true;           }
                        ?>
                        ?>
                        <tr>
                            <td><?php echo $articulo['codigo'] ?></td>
                            <td><input id="cantidadA<?php echo $id_art; ?>" type="number" class="validate col s6 m4 l4" value="<?php echo $detalle_articulo['cantidad'];?>" onchange= 'totales(<?php echo $id_art.', '.$id_user;?>);'><br><?php echo $articulo['unidad'] ?></td>
                            <td><?php echo $articulo['nombre'] ?></td>
                            <td><?php echo $existe['cantidad'].' '.$articulo['unidad'] ?></td>
                            <td><a onclick="borrar_lista_articulo(<?php echo $id_art; ?>);" class="waves-effect waves-light btn-small red right"><i class="material-icons">delete</i></a></td>
                        </tr>
                    <?php
                    }//FIN WHILE
                    echo '<input type="hidden" id="mayor_exist" value="'.$mayor.'">';
               }else{
                  echo '<tr><td></td><td></td><td><h6> Sin Artículos </h6></td></tr>';
               }//FIN ELSE
               ?>                
              </tbody>
            </table>
        </div>
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <div class="col s12 m10 l10">
                <div class="col s6 m6 l6 ">
                    <h5 class="right"><b>Número de Artículos <?php echo $aux;?></b></h5><br><br><br><br>
                    <a onclick="borrar_lista_all(<?php echo $id_user; ?>)" class="waves-effect waves-light btn-small red right">Cancelar<i class="material-icons left">close</i></a>
                    <a onclick="insert_salida()" class="waves-effect waves-light btn-small indigo right">Registrar<i class="material-icons left">done</i></a>
                </div>
                <div class="hide-on-small-only col s2"><br></div>
            </div>            
        </div>
        <hr><br>
        <?php
    	break;
    case 3:///////////////           IMPORTANTE               //////////////

        $id_usuario = $conn->real_escape_string($_POST['usuario']);
        #VERIFICAMOS QUE SE BORRE CORRECTAMENTE TODOS LAS ARTICULOS QUE REGITRO EL USUARIO EN `tmp_pv_detalle_salida`
        if(mysqli_query($conn, "DELETE FROM `tmp_detalle_salida` WHERE `usuario` = $id_usuario")){
            #SI ES ELIMINADO MANDAR MSJ CON ALERTA
            echo '<script >M.toast({html:"Si hay articulos en la lsita fueron borrados con exito.", classes: "rounded"})</script>';
            echo '<script>recargar_inventario()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
        }else{
            #SI NO ES BORRADO MANDAR UN MSJ CON ALERTA
            echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
        }
        break;
    case 4:
    	sleep(1);// HACE UNA PAUSA DE 1 segundo para hace el cambio
        //CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO QUE NESECITAMOS PARA CAMBIAR LAS CANTIDADES
        $id_articulo = $conn->real_escape_string($_POST['valorIdArt']);
        $CantidadA = $conn->real_escape_string($_POST['valorCantidadA']);
        //CREAMOS LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DE LOS ARTICULOS Y LA GUARDAMOS EN UNA VARIABLE
        $sql = "UPDATE `tmp_detalle_salida` SET cantidad = '$CantidadA' WHERE id_articulo = $id_articulo AND usuario = $id_user";
        //VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
        if(mysqli_query($conn, $sql)){
            #echo '<script >M.toast({html:"Las cantidades se actualizaron con exito.", classes: "rounded"})</script>';    
        }else{
            #echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>'; 
        }//FIN else DE ERROR
        break;
    case 5:
     	//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO QUE NESECITAMOS PARA INSERTAR
        
        $sql = "INSERT INTO `salidas_productos` (usuario, fecha, hora) VALUES('$id_user', '$Fecha_hoy', '$Hora')";
         //VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
		if(mysqli_query($conn, $sql)){
			echo '<script >M.toast({html:"La salida (productos) se registró exitosamente.", classes: "rounded"})</script>';
            #SELECCIONAMOS LA ULTIMA salida  CREADO
            $ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id) AS id FROM salidas_productos WHERE usuario=$id_user"));           
            $salida = $ultimo['id'];//TOMAMOS EL ID DEL ULTIMO CORTE

            //REGISTRAMOS LOS ARTICULOS EN tmp_detalle_salida
            $consulta = mysqli_query($conn, "SELECT * FROM tmp_detalle_salida WHERE usuario = $id_user"); 
            //VERIFICAMOS SI HAY ARTICULOS POR AGREGAR
            if(mysqli_num_rows($consulta)>0){
            	//RECORREMOS CON UN WHILE UNO POR UNO LOS ARTICULOS
                while($detalle_articulo = mysqli_fetch_array($consulta)){
                    $id_articulo = $detalle_articulo['id_articulo'];
                    $cantidad = $detalle_articulo['cantidad'];
                    // CREAMOS EL SQL INSERT DEL ARTICULO EN TURNO EN detalle_salida
                    $sql = "INSERT INTO `detalle_salida` (id_salida, id_articulo, cantidad) VALUES($salida, $id_articulo, '$cantidad')";

                    // VERIFICAMOS SI SE HIZO LA INSERCION
                    if (mysqli_query($conn, $sql)) {
                        // MODIFICAMOS LA CANTIDAD - EN EL INVENTARIO
                        mysqli_query($conn, "UPDATE `inventario` SET cantidad = cantidad-$cantidad, modifico = $id_user, fecha_modifico = '$Fecha_hoy' WHERE id_articulo = '$id_articulo'");
                       
                        // SI SE INSERTO BORRAMOS EL ARTICULO DE tmp_pv_detalle_salida
                        mysqli_query($conn, "DELETE FROM `tmp_detalle_salida` WHERE id_articulo = $id_articulo");
                    }
                }//FIN WHILE
            }else{
                echo '<script >M.toast({html:"No se encontraron articulos por agregar.", classes: "rounded"})</script>';  
            }//FIN ELSE
            echo '<script>recargar_inventario()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
            ?>
            <script>
                var a = document.createElement("a");
                    a.href = "../php/ticket_productos.php?id="+<?php echo $salida; ?>;
                    a.target = "blank";
                    a.click();
            </script>
            <?php
		}else{
            echo '<script >M.toast({html:"Ha ocurrio un error.", classes: "rounded"})</script>';   
        }//FIN else DE ERROR            
     	break;
}// FIN switch
mysqli_close($conn);