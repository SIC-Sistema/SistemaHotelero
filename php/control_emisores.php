<?php 
// Si estamos usando una versión de PHP superior entonces usamos la API para encriptar la contrasela con el archivo: password_api_compatibility_library.php
include_once("password_compatibility_library.php");
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');

//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL

//CON POST TOMAMOS UN VALOR DEL 0 AL 4 PARA VER QUE ACCION HACER (Insertar = 0, Actualizar Info = 1, Actualizar Est = 2, Borrar = 3, Permisos = 4, Contraseña = 5)
$Accion = $conn->real_escape_string($_POST['accion']);
if ($Accion != 0) {
	//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
	include('is_logged.php');
	$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
}
//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (Insertar = 0, Actualizar Info = 1, Actualizar Est = 2, Borrar = 3, Permisos = 4)
switch ($Accion) {
   
    case 1:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 1 realiza:

		$emisorId = $conn->real_escape_string($_POST['valorId']);//VALOR DEL USUARIO A EDITAR POR POST "perfil_user.php"

		//REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL USUARIO Y ASIGNAMOS EL ARRAY A UNA VARIABLE $area
		$area = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$id_user"));

		if($area['area'] == "Administrador"){
			//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "perfil_user.php" QUE NESECITAMOS PARA ACTUALIZAR
			$rfc = $conn->real_escape_string($_POST['valorRfc']);
			$razonSocial = $conn->real_escape_string($_POST['valorRazonSocial']);
			$regimen = $conn->real_escape_string($_POST['valorRegimen']);
			$estadoMx = $conn->real_escape_string($_POST['valorEstadoMx']);
			$municipio = $conn->real_escape_string($_POST['valorMunicipio']);
			$localidad = $conn->real_escape_string($_POST['valorLocalidad']);
			$colonia = $conn->real_escape_string($_POST['valorColonia']);
			$calle = $conn->real_escape_string($_POST['valorCalle']);
			$numeroExterior = $conn->real_escape_string($_POST['valorNumeroExterior']);
			$numeroInterior = $conn->real_escape_string($_POST['valorNumeroInterior']);
			$codigoPostal = $conn->real_escape_string($_POST['valorCodigoPostal']);
			$nombre = $conn->real_escape_string($_POST['valorNombreCompleto']);
			$email = $conn->real_escape_string($_POST['valorEmail']);
			$asuntoCorreo = $conn->real_escape_string($_POST['valorAsuntoCorreo']);
			
			//CREAMOS LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL USUARIO Y LA GUARDAMOS EN UNA VARIABLE
			$sql = "UPDATE emisores_sat SET rfc='$rfc', razon_social='$razonSocial', regimen='$regimen', 
			estado_Mx = '$estadoMx', municipio='$municipio', localidad='$localidad', colonia='$colonia', 
			calle='$calle', numero_exterior='$numeroExterior', numero_interior='$numeroInterior', 
			codigo_postal='$codigoPostal', nombre='$nombre', email='$email', 
			asunto_email='$asuntoCorreo'WHERE id='$emisorId'";
			//VERIFICAMOS QUE SE EJECUTE LA SENTENCIA EN MYSQL 
			if(mysqli_query($conn, $sql)){
				echo '<script>M.toast({html:"Emisor actualizado correctamente.", classes: "rounded"})</script>';
				echo '<script>recargar_emisores()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
				echo '<script>M.toast({html:"Ha ocurrido un error.", classes: "rounded"})</script>';	
			}
		}else{
		  echo "<script >M.toast({html: 'Sólo un administrador puede modificar emisores fiscales.', classes: 'rounded'});</script>";
		}
        break;
    
}// FIN switch
mysqli_close($conn);