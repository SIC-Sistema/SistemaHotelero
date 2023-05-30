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
			//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "perfil_user.php" QUE NESECITAMOS PARA ACTUALIZAR
			$pais = "MEXICO";
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
			$usoCfdi = $conn->real_escape_string($_POST['valorUsoCfdi']);
			
			if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `receptores_sat` WHERE rfc='$rfc'"))>0){
					echo '<script >M.toast({html:"Ya se encuentra un receptor con los mismos datos registrados.", classes: "rounded"})</script>';
					echo '<script>recargar_receptores()</script>';
			}else{
				//CREAMOS LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL USUARIO Y LA GUARDAMOS EN UNA VARIABLE
				$sql = "INSERT INTO `receptores_sat` (nombre, rfc, razon_social, regimen, estado_Mx, municipio, 
				pais, localidad, colonia, calle, numero_exterior, numero_interior, codigo_postal, email, uso_cfdi, registro ) 
				VALUES('$nombre', '$rfc', '$razonSocial', '$regimen', '$estadoMx', '$municipio', '$pais', 
				'$localidad', '$colonia', '$calle','$numeroExterior','$numeroInterior','$codigoPostal',
				'$email','$usoCfdi', '$id_user')";
				//VERIFICAMOS QUE SE EJECUTE LA SENTENCIA EN MYSQL 
				if(mysqli_query($conn, $sql)){
					echo '<script>M.toast({html:"Emisor registrado correctamente.", classes: "rounded"})</script>';
					echo '<script>recargar_receptores()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
				}else{
					echo '<script>M.toast({html:"Ha ocurrido un error.", classes: "rounded"})</script>';	
				}
			}
        break;
		case 2:///////////////           IMPORTANTE               ///////////////
			// $Accion es igual a 1 realiza:
				//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "perfil_user.php" QUE NESECITAMOS PARA ACTUALIZAR
				$pais = "MEXICO";
				$idReceptor = $conn->real_escape_string($_POST['valorId']);
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
				$usoCfdi = $conn->real_escape_string($_POST['valorUsoCfdi']);
				
				
				//CREAMOS LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL USUARIO Y LA GUARDAMOS EN UNA VARIABLE
				$sql = "UPDATE `receptores_sat` SET nombre = '$nombre', rfc = '$rfc', razon_social = '$razonSocial',
				regimen = '$regimen', estado_Mx = '$estadoMx', municipio = '$municipio', pais = '$pais', 
				localidad = '$localidad', colonia = '$colonia', calle = '$calle', 
				numero_exterior = '$numeroExterior', numero_interior = '$numeroInterior', 
				codigo_postal = '$codigoPostal', email = '$email', uso_cfdi = '$usoCfdi', registro = '$id_user'
				WHERE id = '$idReceptor'";
				//VERIFICAMOS QUE SE EJECUTE LA SENTENCIA EN MYSQL 
				if(mysqli_query($conn, $sql)){
					echo '<script>M.toast({html:"Emisor actualizado correctamente.", classes: "rounded"})</script>';
					echo '<script>recargar_receptores_sat()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
				}else{
					echo '<script>M.toast({html:"Ha ocurrido un error.", classes: "rounded"})</script>';	
				}
			break;
			case 3:///////////////           IMPORTANTE               ///////////////
				// $Accion es igual a 1 realiza:
		
				//CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO DE "clientes_punto_venta.php"
				$Texto = $conn->real_escape_string($_POST['texto']);
		
				//VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
				if ($Texto != "") {
					//MOSTRARA LOS CLIENTES QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
					$sql = "SELECT * FROM `receptores_sat` WHERE  rfc LIKE '%$Texto%' OR razon_social LIKE '%$Texto%' ORDER BY id";			
				}else{
					//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
					$sql = "SELECT * FROM `receptores_sat` limit 20";
				}//FIN else $Texto VACIO O NO
		
				// REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
				$consulta = mysqli_query($conn, $sql);		
				$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO
		
				//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
				if (mysqli_num_rows($consulta) == 0) {
					echo '<script>M.toast({html:"No se encontraron receptores.", classes: "rounded"})</script>';
				} else {
					//SI NO ESTA EN == 0 SI TIENE INFORMACION
					//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
					//RECORREMOS UNO A UNO LOS CLIENTES CON EL WHILE	
					while($receptor = mysqli_fetch_array($consulta)) {
						//Output
						$contenido .= '			
						  <tr>
							<td>'.$receptor['rfc'].'</td>
							<td>'.$receptor['razon_social'].'</td>
							<td>'.$receptor['codigo_postal'].'</td>
							<td>'.$receptor['regimen'].'</td>
							<td>'.$receptor['uso_cfdi'].'</td>
							<td><form method="post" action="../views/detalles_receptores.php"><input id="id" name="id" type="hidden" value="'.$receptor['id'].'"><button class="btn-small waves-effect waves-light blue"><i class="material-icons">list</i></button></form></td>
							<td><form method="post" action="../views/editar_receptor_sat.php"><input id="id" name="id" type="hidden" value="'.$receptor['id'].'"><button class="btn-small waves-effect waves-light green darken-3"><i class="material-icons">edit</i></button></form></td>
						  </tr>';
					}//FIN while
				}//FIN else
				echo $contenido;// MOSTRAMOS LA INFORMACION HTML
				break;
}// FIN switch
mysqli_close($conn);