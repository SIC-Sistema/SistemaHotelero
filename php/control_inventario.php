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

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 3 PARA VER QUE ACCION HACER (Para Insertar = 0, Consultar articulos = 1, Actualizar = 2, Borrar = 3, consultar inventario = 4, insert o consulta tabla compra = 5, busqueda modal = 6, borrar tabla = 7, cancelar ingreso = 8, cambiar cantidad de articulos = 9, insertar compra = 10)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (Para Insertar = 0, Consultar articulos = 1, Actualizar = 2, Borrar = 3, consultar inventario = 4, insert o consulta tabla compra = 5, busqueda modal = 6, borrar tabla = 7, cancelar ingreso = 8, cambiar cantidad de articulos = 9, insertar compra = 10)
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:
    	//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "add_articulo.php" QUE NESECITAMOS PARA INSERTAR
    	$Nombre = $conn->real_escape_string($_POST['valorNombre']);
		$Codigo = $conn->real_escape_string($_POST['valorCodigo']);
		$Unidad = $conn->real_escape_string($_POST['valorUnidad']);
		$Minimo = $conn->real_escape_string($_POST['valorMinimo']);

		//VERIFICAMOS QUE NO HALLA UN ARTICULO CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `articulos` WHERE codigo='$Codigo' AND nombre='$Nombre'"))>0){
	 		echo '<script >M.toast({html:"Ya se encuentra un articulo con los mismos datos registrados.", classes: "rounded"})</script>';
	 	}else{
	 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
	 		$sql = "INSERT INTO `articulos` (codigo, nombre, unidad, stock_minimo, usuario, fecha) 
				VALUES('$Codigo', '$Nombre', '$Unidad', '$Minimo', '$id_user','$Fecha_hoy')";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				echo '<script >M.toast({html:"El articulo se dió de alta satisfactoriamente.", classes: "rounded"})</script>';	
				echo '<script>recargar_articulo()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
				echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
			}//FIN else DE ERROR
	 	}// FIN else DE BUSCAR ARTICULO IGUAL

        break;
    case 1:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 1 realiza:

    	//CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO DE "articulos.php"
    	$Texto = $conn->real_escape_string($_POST['texto']);

    	//VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {			
			//MOSTRARA LOS ARTICULOS QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = "SELECT * FROM `articulos` WHERE  nombre LIKE '%$Texto%' OR id = '$Texto' OR codigo LIKE '%$Texto%' ORDER BY id";				
		}else{
			//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
			$sql = "SELECT * FROM `articulos` limit 200";
		}//FIN else $Texto VACIO O NO

		// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		$consulta = mysqli_query($conn, $sql);		
		$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

		//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		if (mysqli_num_rows($consulta) == 0) {
	 		echo '<script >M.toast({html:"No se encontraron articulos.", classes: "rounded"})</script>';
		} else {
			//SI NO ESTA EN == 0 SI TIENE INFORMACION
			//RECORREMOS UNO A UNO LOS ARTICULOS CON EL WHILE	
			while($articulo = mysqli_fetch_array($consulta)) {
				$id_user = $articulo['usuario'];
				$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
				//Output
				$contenido .= '			
		          <tr>
		            <td>'.$articulo['id'].'</td>
		            <td>'.$articulo['codigo'].'</td>
		            <td>'.$articulo['nombre'].'</td>
		            <td>'.$articulo['unidad'].'</td>
		            <td>'.$articulo['stock_minimo'].'</td>
		            <td>'.$user['firstname'].'</td>
		            <td>'.$articulo['fecha'].'</td>
		            <td><form method="post" action="../views/editar_articulo.php"><input id="id" name="id" type="hidden" value="'.$articulo['id'].'"><button class="btn-small waves-effect waves-light grey darken-3"><i class="material-icons">edit</i></button></form></td>
		            <td><a onclick="borrar_articulo('.$articulo['id'].')" class="btn-small red waves-effect waves-light"><i class="material-icons">delete</i></a></td>
		          </tr>';
			}//FIN while
		}//FIN else
		echo $contenido;// MOSTRAMOS LA INFORMACION HTML
        break;
    case 2:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 2 realiza:

	    //CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "editar_articulo.php" QUE NESECITAMOS PARA ACTUALIZAR
    	$id = $conn->real_escape_string($_POST['id']);
    	$Nombre = $conn->real_escape_string($_POST['valorNombre']);
		$Codigo = $conn->real_escape_string($_POST['valorCodigo']);
		$Unidad = $conn->real_escape_string($_POST['valorUnidad']);
		$Minimo = $conn->real_escape_string($_POST['valorMinimo']);

		//CREAMO LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL CLIENTE Y LA GUARDAMOS EN UNA VARIABLE
		$sql = "UPDATE `articulos` SET codigo = '$Codigo', nombre = '$Nombre', unidad = '$Unidad', stock_minimo = '$Minimo' WHERE id = '$id'";
		//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
		if(mysqli_query($conn, $sql)){
			echo '<script >M.toast({html:"El articulo se actualizo con exito.", classes: "rounded"})</script>';	
			echo '<script>recargar_articulo()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
		}else{
			echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
		}//FIN else DE ERROR
        break;
    case 3:
        // $Accion es igual a 3 realiza:
    	//CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "articulos.php" QUE NESECITAMOS PARA BORRAR
    	$id = $conn->real_escape_string($_POST['id']);
    	
		//SI DE CREA LA INSERCION PROCEDEMOS A BORRRAR DE LA TABLA `articulos`
	    #VERIFICAMOS QUE SE BORRE CORRECTAMENTE EL CLIENTE DE `articulos`
		if(mysqli_query($conn, "DELETE FROM `articulos` WHERE `articulos`.`id` = $id")){
			#SI ES ELIMINADO MANDAR MSJ CON ALERTA
			echo '<script >M.toast({html:"Articulo borrado con exito.", classes: "rounded"})</script>';
			echo '<script>recargar_articulo()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
		}else{
			#SI NO ES BORRADO MANDAR UN MSJ CON ALERTA
			echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
		}		 
    	break;
    case 4:
        // $Accion es igual a 4 realiza:
    	//CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO DE "articulos.php"
    	$Texto = $conn->real_escape_string($_POST['texto']);

    	//VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {			
			//MOSTRARA LOS ARTICULOS QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = "SELECT * FROM `inventario` WHERE  id_articulo LIKE '%$Texto%' ORDER BY id";				
		}else{
			//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
			$sql = "SELECT * FROM `inventario`";
		}//FIN else $Texto VACIO O NO

		// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		$consulta = mysqli_query($conn, $sql);		
		$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

		//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		if (mysqli_num_rows($consulta) == 0) {
	 		echo '<script >M.toast({html:"No se encontraron articulos.", classes: "rounded"})</script>';
		} else {
			//SI NO ESTA EN == 0 SI TIENE INFORMACION
			//RECORREMOS UNO A UNO LOS ARTICULOS CON EL WHILE	
			while($articulo = mysqli_fetch_array($consulta)) {
				$id_user = $articulo['modifico'];
				$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
				$id_articulo = $articulo['id_articulo'];
				$info_art = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `articulos` WHERE id=$id_articulo"));
				if ($info_art['stock_minimo'] >= $articulo['cantidad']) {
					$estatus = '<span class="new badge red" data-badge-caption="">Faltante</span>';
					$color = 'red-text';
				}else{
					$estatus = '<span class="new badge green" data-badge-caption="">Correcto</span>'; 
					$color = '';
				}
				//Output
				$contenido .= '			
		          <tr>
		            <td>'.$estatus.'</td>
		            <td>'.$id_articulo.'</td>
		            <td>'.$info_art['codigo'].'</td>
		            <td>'.$info_art['nombre'].'</td>
		            <td class = "'.$color.'">'.$articulo['cantidad'].' '.$info_art['unidad'].'</td>
		            <td>'.$user['firstname'].'</td>
		            <td>'.$articulo['fecha_modifico'].'</td>
		          </tr>';
			}//FIN while
		}//FIN else
		echo $contenido;// MOSTRAMOS LA INFORMACION HTML
        break;
    case 5:
    	// $Accion es igual a 5 realiza:
        //CON POST RECIBIMOS UN ID DEL MODAL O AL INICIAR EL DOCUMENTO "ingreso_articulos.php"
        $insert = $conn->real_escape_string($_POST['insert']);

        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE insid_user
        if ($insert) {
            //SE HACE LA INSERCION A TMP
            $id_art = $conn->real_escape_string($_POST['id_art']);
            if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `tmp_detalle_compra` WHERE id_articulo = $id_art"))>0) {
                echo '<script >M.toast({html:"No se pueden repetir los articulos en la lista.", classes: "rounded"})</script>';   
            }else{
                #SELECCIONAMOS LA INFORMACION DEL ARTICULO
                $articulo = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `articulos` WHERE id = $id_art"));
                //CREAMOS EL SQL PARA INSERTAR
                $sql = "INSERT INTO `tmp_detalle_compra` (id_articulo, cantidad, usuario) 
                   VALUES($id_art,'1','$id_user')";
                if(mysqli_query($conn, $sql)){
                    echo '<script >M.toast({html:"El articulo se registró exitosamente.", classes: "rounded"})</script>';   
                }else{
                    echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>'; 
                }//FIN else DE ERROR
            }
        }
        // REALIZAMOS LA CONSULTA A LA BASE DE DATOS Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
        $consulta = mysqli_query($conn, "SELECT * FROM tmp_detalle_compra WHERE usuario = $id_user");      
        ?>
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <table class="col s12 m10 l10">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Cantidad</th>
                  <th>Artículo</th>
                  <th>Accion</th>
                </tr>
              </thead>
              <tbody>
               <?php
               $aux = mysqli_num_rows($consulta);
               //VERIFICAMOS SI HA ARRTICULOS EN LA TABLA
               if(mysqli_num_rows($consulta)>0){
                    while($detalle_articulo = mysqli_fetch_array($consulta)){
                        $id_art = $detalle_articulo['id_articulo'];
                        $articulo = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `articulos` WHERE id = $id_art"));
                        ?>
                        <tr>
                            <td><?php echo $articulo['codigo'] ?></td>
                            <td><input id="cantidadA<?php echo $id_art; ?>" type="number" class="validate col s6 m4 l4" value="<?php echo $detalle_articulo['cantidad'];?>" onchange= 'totales(<?php echo $id_art.', '.$id_user;?>);'><br><?php echo $articulo['unidad'] ?></td>
                            <td><?php echo $articulo['nombre'] ?></td>
                            <td><a onclick="borrar_lista_articulo(<?php echo $id_art; ?>);" class="waves-effect waves-light btn-small red right"><i class="material-icons">delete</i></a></td>
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
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <div class="col s12 m10 l10">
                <div class="col s6 m6 l6 ">
                    <h5 class="right"><b>Número de Artículos <?php echo $aux;?></b></h5><br><br><br><br>
                    <a onclick="borrar_lista_all(<?php echo $id_user; ?>)" class="waves-effect waves-light btn-small red right">Cancelar<i class="material-icons left">close</i></a>
                    <a onclick="insert_compra()" class="waves-effect waves-light btn-small indigo right">Registrar<i class="material-icons left">done</i></a>
                </div>
                <div class="hide-on-small-only col s2"><br></div>
            </div>            
        </div>
        <hr><br>
        <?php
        break;
    case 6:
    	// $Accion es igual a 6 realiza:
        
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
    case 7:
    	# code...
    	$id_art = $conn->real_escape_string($_POST['id']);
    	if (mysqli_query($conn, "DELETE FROM tmp_detalle_compra WHERE id_articulo = $id_art AND usuario  = $id_user")) {
            echo '<script >M.toast({html:"Artículo borrado con exito", classes: "rounded"})</script>'; 
    	}else{
            echo '<script >M.toast({html:"Ocurrio un error al borrar", classes: "rounded"})</script>'; 
    	}
    	// REALIZAMOS LA CONSULTA A LA BASE DE DATOS Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
        $consulta = mysqli_query($conn, "SELECT * FROM tmp_detalle_compra WHERE usuario = $id_user");      
        ?>
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <table class="col s12 m10 l10">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Cantidad</th>
                  <th>Artículo</th>
                  <th>Accion</th>
                </tr>
              </thead>
              <tbody>
               <?php
               $aux = mysqli_num_rows($consulta);
               //VERIFICAMOS SI HA ARRTICULOS EN LA TABLA
               if(mysqli_num_rows($consulta)>0){
                    while($detalle_articulo = mysqli_fetch_array($consulta)){
                        $id_art = $detalle_articulo['id_articulo'];
                        $articulo = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `articulos` WHERE id = $id_art"));
                        ?>
                        <tr>
                            <td><?php echo $articulo['codigo'] ?></td>
                            <td><input id="cantidadA<?php echo $id_art; ?>" type="number" class="validate col s6 m4 l4" value="<?php echo $detalle_articulo['cantidad'];?>" onchange= 'totales(<?php echo $id_art.', '.$id_user;?>);'><br><?php echo $articulo['unidad'] ?></td>
                            <td><?php echo $articulo['nombre'] ?></td>
                            <td><a onclick="borrar_lista_articulo(<?php echo $id_art; ?>);" class="waves-effect waves-light btn-small red right"><i class="material-icons">delete</i></a></td>
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
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <div class="col s12 m10 l10">
                <div class="col s6 m6 l6 ">
                    <h5 class="right"><b>Número de Artículos <?php echo $aux;?></b></h5><br><br><br><br>
                    <a onclick="borrar_lista_all(<?php echo $id_user; ?>)" class="waves-effect waves-light btn-small red right">Cancelar<i class="material-icons left">close</i></a>
                    <a onclick="insert_compra()" class="waves-effect waves-light btn-small indigo right">Registrar<i class="material-icons left">done</i></a>
                </div>
                <div class="hide-on-small-only col s2"><br></div>
            </div>            
        </div>
        <hr><br>
        <?php
    	break;
    case 8:///////////////           IMPORTANTE               //////////////

        $id_usuario = $conn->real_escape_string($_POST['usuario']);
        #VERIFICAMOS QUE SE BORRE CORRECTAMENTE TODOS LAS ARTICULOS QUE REGITRO EL USUARIO EN `tmp_pv_detalle_compra`
        if(mysqli_query($conn, "DELETE FROM `tmp_detalle_compra` WHERE `usuario` = $id_usuario")){
            #SI ES ELIMINADO MANDAR MSJ CON ALERTA
            echo '<script >M.toast({html:"Si hay articulos en la lsita fueron borrados con exito.", classes: "rounded"})</script>';
            echo '<script>recargar_inventario()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
        }else{
            #SI NO ES BORRADO MANDAR UN MSJ CON ALERTA
            echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
        }
        break;
    case 9:
    	sleep(1);// HACE UNA PAUSA DE 1 segundo para hace el cambio
        //CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO QUE NESECITAMOS PARA CAMBIAR LAS CANTIDADES
        $id_articulo = $conn->real_escape_string($_POST['valorIdArt']);
        $CantidadA = $conn->real_escape_string($_POST['valorCantidadA']);
        //CREAMOS LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DE LOS ARTICULOS Y LA GUARDAMOS EN UNA VARIABLE
        $sql = "UPDATE `tmp_detalle_compra` SET cantidad = '$CantidadA' WHERE id_articulo = $id_articulo AND usuario = $id_user";
        //VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
        if(mysqli_query($conn, $sql)){
            #echo '<script >M.toast({html:"Las cantidades se actualizaron con exito.", classes: "rounded"})</script>';    
        }else{
            #echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>'; 
        }//FIN else DE ERROR
        break;
    case 10:
     	//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO QUE NESECITAMOS PARA INSERTAR
        
        $sql = "INSERT INTO `compras` (usuario, fecha, hora) VALUES('$id_user', '$Fecha_hoy', '$Hora')";
         //VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
		if(mysqli_query($conn, $sql)){
			echo '<script >M.toast({html:"La compra(ingreso) se registró exitosamente.", classes: "rounded"})</script>';
            #SELECCIONAMOS LA ULTIMA COMPRA CREADO
            $ultimo =  mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id) AS id FROM compras WHERE usuario=$id_user"));           
            $compra = $ultimo['id'];//TOMAMOS EL ID DEL ULTIMO CORTE

            //REGISTRAMOS LOS ARTICULOS EN tmp_detalle_compra
            $consulta = mysqli_query($conn, "SELECT * FROM tmp_detalle_compra WHERE usuario = $id_user"); 
            //VERIFICAMOS SI HAY ARTICULOS POR AGREGAR
            if(mysqli_num_rows($consulta)>0){
            	//RECORREMOS CON UN WHILE UNO POR UNO LOS ARTICULOS
                while($detalle_articulo = mysqli_fetch_array($consulta)){
                    $id_articulo = $detalle_articulo['id_articulo'];
                    $cantidad = $detalle_articulo['cantidad'];
                    // CREAMOS EL SQL INSERT DEL ARTICULO EN TURNO EN detalle_compra
                    $sql = "INSERT INTO `detalle_compra` (id_compra, id_articulo, cantidad) VALUES($compra, $id_articulo, '$cantidad')";

                    // VERIFICAMOS SI SE HIZO LA INSERCION
                    if (mysqli_query($conn, $sql)) {
                        // VERIFICAMOS SI EL ARTICULO YA ESTA EN ALMACEN Y SOLO MODIFICAMOS LA CANTIDAD +
                        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `inventario` WHERE id_articulo = '$id_articulo'"))>0) {
                            mysqli_query($conn, "UPDATE `inventario` SET cantidad = cantidad+$cantidad, modifico = $id_user, fecha_modifico = '$Fecha_hoy' WHERE id_articulo = '$id_articulo'");
                        }else{
                            // SI NO REGISTRAMOS EL ARTICULO Y CANTIDAD
                            mysqli_query($conn, "INSERT INTO `inventario` (id_articulo, cantidad, modifico, fecha_modifico) VALUES ($id_articulo, $cantidad, $id_user, '$Fecha_hoy')");
                        } 
                        // SI SE INSERTO BORRAMOS EL ARTICULO DE tmp_pv_detalle_compra
                        mysqli_query($conn, "DELETE FROM `tmp_detalle_compra` WHERE id_articulo = $id_articulo");
                    }
                }//FIN WHILE
            }else{
                echo '<script >M.toast({html:"No se encontraron articulos por agregar.", classes: "rounded"})</script>';  
            }//FIN ELSE
            echo '<script>recargar_inventario()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
		}else{
            echo '<script >M.toast({html:"Ha ocurrio un error.", classes: "rounded"})</script>';   
        }//FIN else DE ERROR            
     	break;
}// FIN switch
mysqli_close($conn);