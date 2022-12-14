<?php 
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');
//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
include('is_logged.php');
//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 3 PARA VER QUE ACCION HACER (Para Insertar = 0, Consultar = 1, Actualizar = 2, Borrar = 3)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (Para Insertar = 0, Consultar = 1, Actualizar = 2, Borrar = 3)
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:
    	//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "add_empresa.php" QUE NESECITAMOS PARA INSERTAR
    	$RazonSocial = $conn->real_escape_string($_POST['valorRazonSocial']);
		$RFC = $conn->real_escape_string($_POST['valorRFC']);
		$Direccion = $conn->real_escape_string($_POST['valorDireccion']);
		$CP = $conn->real_escape_string($_POST['valorCP']);

		//VERIFICAMOS QUE NO HALLA UN EMPRESA CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `empresas` WHERE razon_social='$RazonSocial' AND rfc='$RFC'"))>0){
	 		echo '<script >M.toast({html:"Ya se encuentra una empresa con los mismos datos registrados.", classes: "rounded"})</script>';
	 	}else{
	 		// SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
	 		$sql = "INSERT INTO `empresas` (razon_social, direccion, cp, rfc, usuario, fecha) 
				VALUES('$RazonSocial', '$Direccion', '$CP', '$RFC', '$id_user','$Fecha_hoy')";
			//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				echo '<script >M.toast({html:"La empresa se dió de alta satisfactoriamente.", classes: "rounded"})</script>';	
				echo '<script>recargar_empresas()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
				echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
			}//FIN else DE ERROR
	 	}// FIN else DE BUSCAR EMPRESA IGUAL

        break;
    case 1:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 1 realiza:

    	//CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO DE "empresas.php"
    	$Texto = $conn->real_escape_string($_POST['texto']);

    	//VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {			
			//MOSTRARA LAS EMPRESAS QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = "SELECT * FROM `empresas` WHERE  razon_social LIKE '%$Texto%' OR id = '$Texto' OR rfc LIKE '%$Texto%' ORDER BY id";				
		}else{
			//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
			$sql = "SELECT * FROM `empresas` limit 150";
		}//FIN else $Texto VACIO O NO

		// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		$consulta = mysqli_query($conn, $sql);		
		$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

		//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		if (mysqli_num_rows($consulta) == 0) {
				echo '<script>M.toast({html:"No se encontraron empresas.", classes: "rounded"})</script>';
			
		} else {
			//SI NO ESTA EN == 0 SI TIENE INFORMACION
			//RECORREMOS UNO A UNO LOS CLIENTES CON EL WHILE	
			while($empresa = mysqli_fetch_array($consulta)) {
				$id_user = $empresa['usuario'];
				$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
				//Output
				$contenido .= '			
		          <tr>
		            <td>'.$empresa['id'].'</td>
		            <td>'.$empresa['razon_social'].'</td>
		            <td>'.$empresa['rfc'].'</td>
		            <td>'.$empresa['direccion'].'</td>
		            <td>'.$empresa['cp'].'</td>
		            <td>'.$user['firstname'].'</td>
		            <td>'.$empresa['fecha'].'</td>
		            <td><form method="post" action="../views/editar_empresa.php"><input id="id" name="id" type="hidden" value="'.$empresa['id'].'"><button class="btn-small waves-effect waves-light grey darken-3"><i class="material-icons">edit</i></button></form></td>
		            <td><a onclick="borrar_empresa('.$empresa['id'].')" class="btn-small red waves-effect waves-light"><i class="material-icons">delete</i></a></td>

		          </tr>';
			}//FIN while
		}//FIN else
		echo $contenido;// MOSTRAMOS LA INFORMACION HTML
        break;
    case 2:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 2 realiza:

 
	    //CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "add_empresa.php" QUE NESECITAMOS PARA ACTUALIZAR
    	$id = $conn->real_escape_string($_POST['id']);
    	$RazonSocial = $conn->real_escape_string($_POST['valorRazonSocial']);
		$RFC = $conn->real_escape_string($_POST['valorRFC']);
		$Direccion = $conn->real_escape_string($_POST['valorDireccion']);
		$CP = $conn->real_escape_string($_POST['valorCP']);

		//CREAMO LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DE LA EMPRESA Y LA GUARDAMOS EN UNA VARIABLE
		$sql = "UPDATE `empresas` SET razon_social = '$RazonSocial', rfc = '$RFC', direccion = '$Direccion', cp = '$CP' WHERE id = '$id'";
		//VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
		if(mysqli_query($conn, $sql)){
			echo '<script >M.toast({html:"La empresa se actualizo con exito.", classes: "rounded"})</script>';	
			echo '<script>recargar_empresas()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
		}else{
			echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
		}//FIN else DE ERROR
        break;
    case 3:
        // $Accion es igual a 3 realiza:
    	//CON POST RECIBIMOS LA VARIABLE DEL BOTON POR EL SCRIPT DE "empresas.php" QUE NESECITAMOS PARA BORRAR
    	$id = $conn->real_escape_string($_POST['id']);
    	
		//SI DE CREA LA INSERCION PROCEDEMOS A BORRRAR DE LA TABLA `empresas`
	    #VERIFICAMOS QUE SE BORRE CORRECTAMENTE EL CLIENTE DE `empresas`
		if(mysqli_query($conn, "DELETE FROM `empresas` WHERE `empresas`.`id` = $id")){
			#SI ES ELIMINADO MANDAR MSJ CON ALERTA
			echo '<script >M.toast({html:"Empresa borrada con exito.", classes: "rounded"})</script>';
			echo '<script>recargar_empresas()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
		}else{
			#SI NO ES BORRADO MANDAR UN MSJ CON ALERTA
			echo "<script >M.toast({html: 'Ha ocurrido un error.', classes: 'rounded'});/script>";
		}		 
    	break;
}// FIN switch
mysqli_close($conn);