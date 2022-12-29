<?php 
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');
//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
include('is_logged.php');
//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 3 PARA VER QUE ACCION HACER (Para Insertar = 0, Consultar articulos = 1, Actualizar = 2, Borrar = 3, consultar inventario = 4)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (Para Insertar = 0, Consultar articulos = 1, Actualizar = 2, Borrar = 3, consultar inventario = 4)
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:
    	//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "add_articulo.php" QUE NESECITAMOS PARA INSERTAR
    	$Nombre = $conn->real_escape_string($_POST['valorNombre']);
		$Codigo = $conn->real_escape_string($_POST['valorCodigo']);
		$Unidad = $conn->real_escape_string($_POST['valorUnidad']);

		//VERIFICAMOS QUE NO HALLA UN ARTICULO CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `articulos` WHERE codigo='$Codigo' AND nombre='$Nombre'"))>0){
	 		echo '<script >M.toast({html:"Ya se encuentra un articulo con los mismos datos registrados.", classes: "rounded"})</script>';
	 	}else{
	 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
	 		$sql = "INSERT INTO `articulos` (codigo, nombre, unidad,  usuario, fecha) 
				VALUES('$Codigo', '$Nombre', '$Unidad', '$id_user','$Fecha_hoy')";
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

		//CREAMO LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL CLIENTE Y LA GUARDAMOS EN UNA VARIABLE
		$sql = "UPDATE `articulos` SET codigo = '$Codigo', nombre = '$Nombre', unidad = '$Unidad' WHERE id = '$id'";
		//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
		if(mysqli_query($conn, $sql)){
			echo '<script >M.toast({html:"La empresa se actualizo con exito.", classes: "rounded"})</script>';	
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
				//Output
				$contenido .= '			
		          <tr>
		            <td>'.$id_articulo.'</td>
		            <td>'.$info_art['codigo'].'</td>
		            <td>'.$info_art['nombre'].'</td>
		            <td>'.$info_art['unidad'].'</td>
		            <td>'.$articulo['cantidad'].'</td>
		            <td>'.$user['firstname'].'</td>
		            <td>'.$articulo['fecha_modifico'].'</td>
		            <td><form method="post" action=""><input id="id" name="id" type="hidden" value="'.$articulo['id'].'"><button class="btn-small waves-effect waves-light grey darken-3"><i class="material-icons">edit</i></button></form></td>
		          </tr>';
			}//FIN while
		}//FIN else
		echo $contenido;// MOSTRAMOS LA INFORMACION HTML
        break;
}// FIN switch
mysqli_close($conn);