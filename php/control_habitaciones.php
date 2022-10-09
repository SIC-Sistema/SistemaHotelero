<?php
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');
//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
include('is_logged.php');
//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 3 PARA VER QUE ACCION HACER (Para Insertar = 0, Actualizar = 1, Borrar = 2)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (Para Insertar = 0, Consultar = 1, Actualizar = 2, Borar = 3)
//echo "hola aqui estoy";
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:

        //CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO QUE NESECITAMOS PARA INSERTAR
        $No = $conn->real_escape_string($_POST['valorNo']);     
        $Descripcion = $conn->real_escape_string($_POST['valorDescripcion']);     
        $Precio = $conn->real_escape_string($_POST['valorPrecio']);     
        $Piso = $conn->real_escape_string($_POST['valorPiso']); 
        $Tipo = $conn->real_escape_string($_POST['valorTipo']); 
        //VERIFICAMOS QUE NO HALLA UN ARTICULO CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id='$No' "))>0){
            echo '<script >M.toast({html:"Ya se encuentra una habitacion con el mismo numero.", classes: "rounded"})</script>';
        }else{
            // SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
            $sql_h = "INSERT INTO `habitaciones` (id, piso, descripcion, precio, tipo, estatus, usuario, fecha) 
               VALUES($No, '$Piso', '$Descripcion', '$Precio', '$Tipo', 0, $id_user, '$Fecha_hoy')";
            //VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql_h)){
				echo '<script >M.toast({html:"La habitació se registró exitosamente.", classes: "rounded"})</script>';	
                echo '<script>recargar_habitaciones()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
                echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
            }//FIN else DE ERROR
            
        }// FIN else DE BUSCAR CATEGORIA IGUAL

        break;
    case 1:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 1 realiza:

        //Obtenemos la informacion del Usuario
        $User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
        //SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR HABITACIONES
        if ($User['habitaciones'] == 1) {

            //CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO QUE NESECITAMOS PARA ACTUALIZAR
            $id = $conn->real_escape_string($_POST['id']);     
            $No = $conn->real_escape_string($_POST['valorNo']);     
            $Descripcion = $conn->real_escape_string($_POST['valorDescripcion']);     
            $Precio = $conn->real_escape_string($_POST['valorPrecio']);     
            $Piso = $conn->real_escape_string($_POST['valorPiso']); 
            $Tipo = $conn->real_escape_string($_POST['valorTipo']);

            $sql = "UPDATE habitaciones SET id = $No, descripcion = '$Descripcion', precio = '$Precio', piso = '$Piso', tipo = '$Tipo' WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                ?>
                <script>
                    id = <?php echo $No; ?>;
                    M.toast({html:"Habitacion se actualizó correctamente", classes: "rounded"});
                    setTimeout("location.href='detalles_habitacion.php?id='+id", 800);
                </script>
                <?php
            }else{
                echo '<script >M.toast({html:"Ha ocurrido un error...", classes: "rounded"})</script>'; 
            }
        }else{
            echo '<script >M.toast({html:"Permiso denegado.", classes: "rounded"});
            M.toast({html:"Comunicate con un administrador.", classes: "rounded"});</script>';
        } 
        break;
    case 2:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 2 realiza:

        //Obtenemos la informacion del Usuario
        $User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
        //SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR HABITACIONES
        if ($User['habitaciones'] == 1) {
            //CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "detalles_habitacion.php" QUE NESECITAMOS PARA BORRAR
            $id = $conn->real_escape_string($_POST['id']);

            //SI DE CREA LA INSERCION PROCEDEMOS A BORRRAR DE LA TABLA `habitaciones`
            #VERIFICAMOS QUE SE BORRE CORRECTAMENTE EL ALMACEN DE `habitaciones`
            if(mysqli_query($conn, "DELETE FROM `habitaciones` WHERE `habitaciones`.`id` = $id")){
                #SI ES ELIMINADO MANDAR MSJ CON ALERTA
                echo '<script >M.toast({html:"Habitacion borrada con exito.", classes: "rounded"})</script>';
                echo '<script>recargar_habitaciones()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
            }else{
                #SI NO ES BORRADO MANDAR UN MSJ CON ALERTA
                echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
            }
        }else{
            echo '<script >M.toast({html:"Permiso denegado.", classes: "rounded"});
            M.toast({html:"Comunicate con un administrador.", classes: "rounded"});</script>';
        }  
        break;
    case 3:
        // $Accion es igual a 3 realiza:
        //CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "almacenes_punto_venta.php" QUE NESECITAMOS PARA BORRAR
        $id = $conn->real_escape_string($_POST['id']);
    	//Obtenemos la informacion del Usuario
        $User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));
        //SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE BORRAR ALMACENES
        if ($User['b_almacenes'] == 1) {
            #SELECCIONAMOS LA INFORMACION A BORRAR
            $almacen = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `punto_venta_almacenes` WHERE id = $id"));
            #CREAMOS EL SQL DE LA INSERCION A LA TABLA  `pv_borrar_almacenes` PARA NO PERDER INFORMACION
            $sql = "INSERT INTO `pv_borrar_almacenes` (id_almacen, nombre, registro, borro, fecha_borro) 
                    VALUES($id, '".$almacen['nombre']."', '".$almacen['usuario']."', '$id_user','$Fecha_hoy')";
            //VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
            if(mysqli_query($conn, $sql)){
                //SI DE CREA LA INSERCION PROCEDEMOS A BORRRAR DE LA TABLA `punto_venta_almacenes`
                #VERIFICAMOS QUE SE BORRE CORRECTAMENTE EL ALMACEN DE `punto_venta_almacenes`
                if(mysqli_query($conn, "DELETE FROM `punto_venta_almacenes` WHERE `punto_venta_almacenes`.`id` = $id")){
                #SI ES ELIMINADO MANDAR MSJ CON ALERTA
                    echo '<script >M.toast({html:"Almacen borrado con exito.", classes: "rounded"})</script>';
                    echo '<script>recargar_almacen_lista()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
                }else{
                #SI NO ES BORRADO MANDAR UN MSJ CON ALERTA
                    echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
                }
            } 
        }else{
            echo '<script >M.toast({html:"Permiso denegado.", classes: "rounded"});
            M.toast({html:"Comunicate con un administrador.", classes: "rounded"});</script>';
        }   
        break;
    case 4:
        // $Accion es igual a 4 realiza:

        //CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO de "almacen_punto_venta.php"
        $Texto = $conn->real_escape_string($_POST['texto']);
        $id = $conn->real_escape_string($_POST['id']);
        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
        if ($Texto != "") {
            //MOSTRARA LOS ARTICULOS QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...... Codigo, Nombre, Descripcion
            $sql = "SELECT id_articulo, cantidad FROM `punto_venta_almacen_general` INNER JOIN `punto_venta_articulos` ON `punto_venta_almacen_general`.id_articulo = `punto_venta_articulos`.id WHERE id_almacen = $id AND (codigo LIKE '%$Texto%' OR nombre LIKE '%$Texto%' OR descripcion LIKE '%$Texto%' OR modelo LIKE '%$Texto%')";   
        }else{//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
            $sql = "SELECT id_articulo, cantidad FROM `punto_venta_almacen_general` WHERE id_almacen = $id LIMIT 50";
        }//FIN else $Texto VACIO O NO

        // REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
        $consulta = mysqli_query($conn, $sql);      
        $contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

        //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
        if (mysqli_num_rows($consulta) == 0) {
                echo '<script>M.toast({html:"No se encontraron articulos en el almacen N°'.$id.'.", classes: "rounded"})</script>';
        } else {
            //SI NO ESTA EN == 0 SI TIENE INFORMACION
            //La variable $contenido contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
            //RECORREMOS UNO A UNO LOS ARTICULOS CON EL WHILE
            while($almacen = mysqli_fetch_array($consulta)) {
                $id_articulo = $almacen['id_articulo'];
                $articulo = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `punto_venta_articulos` WHERE id=$id_articulo"));
                //Output
                $contenido .= '         
                  <tr>
                    <td>'.$articulo['codigo'].'</td>
                    <td>'.$articulo['nombre'].'</td>
                    <td>'.$articulo['descripcion'].'</td>
                    <td>$'.sprintf('%.2f', $articulo['precio']).'</td>
                    <td>'.$almacen['cantidad'].' '.$articulo['unidad'].'</td>
                    <td><form method="post" action="../views/editar_almacen_pv.php"><input id="id" name="id" type="hidden" value="'.$id.'"><button class="btn-floating btn-tiny waves-effect waves-light pink"><i class="material-icons">edit</i></button></form></td>
                  </tr>';

            }//FIN while
        }//FIN else

        echo $contenido;// MOSTRAMOS LA INFORMACION HTML
        // code...
        break;
}// FIN switch
mysqli_close($conn);
    
?>