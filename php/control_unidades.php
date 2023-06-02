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

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 2 PARA VER QUE ACCION HACER (Para Insertar = 0, Consultar REEGISTROS = 1, Actualizar = 2)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (Para Insertar = 0, Consultar registros = 1, Actualizar = 2, Borrar = 3)
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:
    	//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "add_articulo.php" QUE NESECITAMOS PARA INSERTAR
    	$tipoUnidad 	= $conn->real_escape_string($_POST['valorTipUnidad']);
		$claveUnidad 	= $conn->real_escape_string($_POST['valorClaUnidad']);
		$nombreUnidad 	= $conn->real_escape_string($_POST['valorNomUnidad']);

		//VERIFICAMOS QUE NO HALLA UN ARTICULO CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `unidades` WHERE clave='$claveUnidad'"))>0){
	 		echo '<script >M.toast({html:"Ya se encuentra una unidad con los mismos datos registrados.", classes: "rounded"})</script>';
	 	}else{
	 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
	 		$sql = "INSERT INTO `unidades` (tipo, clave, nombre) 
				VALUES('$tipoUnidad', '$claveUnidad', '$nombreUnidad')";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				echo '<script >M.toast({html:"La unidad se dió de alta satisfactoriamente.", classes: "rounded"})</script>';	
				echo '<script>recargar_unidades()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
				echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
			}//FIN else DE ERROR
	 	}// FIN else DE BUSCAR ARTICULO IGUAL

        break;
    case 1:///////////////           IMPORTANTE               ///////////////
		$sql = "SELECT * FROM unidades";

		// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		$consulta = mysqli_query($conn, $sql);		
		$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

		//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		if (mysqli_num_rows($consulta) == 0) {
	 		echo '<script >M.toast({html:"No se encontraron Unidades.", classes: "rounded"})</script>';
		} else {
			
			//SI NO ESTA EN == 0 SI TIENE INFORMACION
			//RECORREMOS UNO A UNO LOS REGISTROS CON EL WHILE	
			while($unidad = mysqli_fetch_array($consulta)) {

				$contenido .= '			
		          <tr>
		            <td>'.$unidad['idunidad'].'</td>
		            <td>'.$unidad['tipo'].'</td>
		            <td>'.$unidad['clave'].'</td>
		            <td>'.$unidad['nombre'].'</td>
		            <td>

                        <form style="display:inline;" method="post" action="../views/editar_unidad.php">
                            <input id="idunidad" name="idunidad" type="hidden" value="'.$unidad['idunidad'].'">
                            <button class="btn-small waves-effect waves-light grey darken-3">
                                <i class="material-icons">edit</i>
                            </button>
                        </form>

                    </td>
		          </tr>';
			}//FIN while
		}//FIN else
		echo $contenido;// MOSTRAMOS LA INFORMACION HTML
        break;
    case 2:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 2 realiza:

    	$id 	= $conn->real_escape_string($_POST['id']);
    	$tipo 	= $conn->real_escape_string($_POST['valorTipo']);
		$clave 	= $conn->real_escape_string($_POST['valorClave']);
		$nombre = $conn->real_escape_string($_POST['valorNombre']);

		$sql = "UPDATE `unidades` SET tipo = '$tipo', clave = '$clave', nombre = '$nombre' WHERE idunidad = '$id'";
		//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
		if(mysqli_query($conn, $sql)){
			echo '<script >M.toast({html:"La unidad se actualizo con exito.", classes: "rounded"})</script>';	
			echo '<script>recargar_unidades()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
		}else{
			echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
		}//FIN else DE ERROR
        break;
}// FIN switch
mysqli_close($conn);