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

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (Para Insertar = 0, Actualizar = 1, Borrar = 2)
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
        //SE VERIFICA SI EL USUARIO LOGEADO TIENE PERMISO DE EDITAR HABITACIONES
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
            #VERIFICAMOS QUE SE BORRE CORRECTAMENTE LA HABITACION DE `habitaciones`
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
}// FIN switch
mysqli_close($conn);
    
?>