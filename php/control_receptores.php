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
					echo '<script>M.toast({html:"Receptor registrado correctamente.", classes: "rounded"})</script>';
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
					while($receptorSat = mysqli_fetch_array($consulta)) {
						//Output
						$contenido .= '			
						  <tr>
							<td>'.$receptorSat['rfc'].'</td>
							<td>'.$receptorSat['razon_social'].'</td>
							<td>'.$receptorSat['codigo_postal'].'</td>
							<td>'.$receptorSat['regimen'].'</td>
							<td>'.$receptorSat['uso_cfdi'].'</td>
							<td><form method="post" action="../views/detalles_receptores.php"><input id="id" name="id" type="hidden" value="'.$receptorSat['id'].'"><button class="btn-small waves-effect waves-light blue"><i class="material-icons">list</i></button></form></td>
							<td><form method="post" action="../views/editar_receptor_sat.php"><input id="id" name="id" type="hidden" value="'.$receptorSat['id'].'"><button class="btn-small waves-effect waves-light green darken-3"><i class="material-icons">edit</i></button></form></td>
						  </tr>';
					}//FIN while
				}//FIN else
				echo $contenido;// MOSTRAMOS LA INFORMACION HTML
				break;
				case 4:
					$texto = $conn->real_escape_string($_POST['texto']);
					if ($texto != "") {
						$sql = "SELECT * FROM `receptores_sat` WHERE  rfc LIKE '%$texto%' OR razon_social LIKE '%$texto%' OR nombre LIKE '%$texto%' LIMIT 5 "; 
					}else{
						$sql = "SELECT * FROM `receptores_sat` LIMIT 5";
					}
						$consulta = mysqli_query($conn, $sql);      
						?>
						<div class="row">
							<div class="hide-on-small-only col s1"><br></div>
							<table class="col s12 m10 l10">
							<thead>
								<tr>
								<th>RFC</th>
								<th>Razón social</th>
								<th>Nombre</th>
								</tr>
							</thead>
							<tbody>
							<?php
							if(mysqli_num_rows($consulta)>0){
									while($receptores = mysqli_fetch_array($consulta)){
									?>
										<tr>
											<td><?php echo $receptores['rfc'] ?></td>
											<td><?php echo $receptores['razon_social'] ?></td>
											<td><?php echo $receptores['nombre'] ?></td>
											<td><a onclick="muestraReceptor(<?php echo $receptores['id']?>);" class="waves-effect waves-light btn-small indigo right">Agregar</a></td>
										</tr>
									<?php
									}//FIN WHILE
							}else{
									echo '<tr><td></td><td></td><td><h6> No se encontraron receptores. </h6></td></tr>';
							}//FIN ELSE
							?>                
							</tbody>
							</table>
						</div>
					<?php
					break;
					case 5:
						$id = $conn->real_escape_string($_POST['receptor']);    
						if ($id != 0) {
							$receptor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `receptores_sat` WHERE id=$id"));
							?>
							<input id="idReceptor" name="idReceptor" type="hidden" value="<?php echo $receptor['id']; ?>">
							<div class = "col s12 m3 l3">
								<div class = "input-field">
									<i class="material-icons prefix">people</i>
									<input type="text" id ="rfc" data-length="13" value="<?php echo $receptor['rfc']; ?>">
									<label class ="active" for="rfc">RFC:</label>
								</div>	
							</div>
							<div class = "col s12 m9 l9">
								<div class = "input-field">
									<i class="material-icons prefix">people</i>
									<input type="text" id ="razonSocial" data-length="50" value="<?php echo $receptor['razon_social']; ?>">
									<label class ="active" for="razonSocial">Razón social:</label>
								</div>	
							</div>
							<div class = "col s12 m3 l3">
								<div class = "input-field">
									<i class="material-icons prefix">people</i>
									<input type="text" id ="codigoPostal" data-length="5" value="<?php echo $receptor['codigo_postal']; ?>">
									<label class ="active" for="codigoPostal">Código Postal:</label>
								</div>	
							</div>
							<div class = "col s12 m9 l9">
								<div class="input-field">
								<label>Selecciona un regimen fiscal</label>
								<br><br>
									<select id="regimen" name="regimen" class="browser-default">
									<?php
										$idRegimen = $receptor['regimen'];
										$consultaRegimen = mysqli_query($conn, "SELECT * FROM regimen_fiscal_sat WHERE clave=$idRegimen");
										if (mysqli_num_rows($consultaRegimen) == 0){
											echo '<script>M.toast({html:"Hubo un error al obtener la información del regimen.", classes: "rounded"})</script>';
										}else{
											while($seleccionRegimen =mysqli_fetch_array($consultaRegimen)){
											?>
											<option value="<?php echo $seleccionRegimen['clave'];?>"><?php echo '('.$seleccionRegimen['clave'].') '.$seleccionRegimen['nombre'];?></option>
											<?php
											}
										}
										?>                
									<option value="0" select>Seleccione un regimen:</option>
									<?php
										$regimenesFiscales = mysqli_query($conn,"SELECT * FROM regimen_fiscal_sat"); 
										if (mysqli_num_rows($regimenesFiscales) == 0) {
										echo '<script>M.toast({html:"No se encontraron datos.", classes: "rounded"})</script>';
										} else {
										while($regimen = mysqli_fetch_array($regimenesFiscales)) {
									?>
										<option value="<?php echo $regimen['clave'];?>"><?php echo '('.$regimen['clave'].') '.$regimen['nombre'];?></option>
											<?php
										}
										}
											?>
									</select>
								</div> 
								<a onclick="actualizaReceptor(<?php echo $receptor['id']?>);" class="waves-effect waves-light btn-small indigo right">Editar</a></td>
							</div>	
							<?php
							
						}
						break;
						case 6:///////////////           IMPORTANTE               ///////////////
							// $Accion es igual a 1 realiza:
								//CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO POR EL SCRIPT "perfil_user.php" QUE NESECITAMOS PARA ACTUALIZAR
								
								$idReceptor = $conn->real_escape_string($_POST['valorId']);
								$rfc = $conn->real_escape_string($_POST['valorRfc']);
								$razonSocial = $conn->real_escape_string($_POST['valorRazonSocial']);
								$regimen = $conn->real_escape_string($_POST['valorRegimen']);
								$codigoPostal = $conn->real_escape_string($_POST['valorCodigoPostal']);
								
								//CREAMOS LA SENTENCIA SQL PARA HACER LA ACTUALIZACION DE LA INFORMACION DEL USUARIO Y LA GUARDAMOS EN UNA VARIABLE
								$sql = "UPDATE `receptores_sat` SET  rfc = '$rfc', razon_social = '$razonSocial',
								regimen = '$regimen', codigo_postal = '$codigoPostal' WHERE id = '$idReceptor'";
								//VERIFICAMOS QUE SE EJECUTE LA SENTENCIA EN MYSQL 
								if(mysqli_query($conn, $sql)){
									echo '<script>M.toast({html:"Emisor actualizado correctamente.", classes: "rounded"})</script>';
									echo '<script>
										recargarReceptor('.$idReceptor.')
									</script>';
								}else{
									echo '<script>M.toast({html:"Ha ocurrido un error.", classes: "rounded"})</script>';	
								}
						break;
						case 7:
							$id = $conn->real_escape_string($_POST['receptor']);    
							if ($id != 0) {
								$receptor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `receptores_sat` WHERE id=$id"));
								?>
								<input id="idReceptor" name="idReceptor" type="hidden" value="<?php echo $receptor['id']; ?>">
								<div class = "col s12 m3 l3">
									<div class = "input-field">
										<i class="material-icons prefix">people</i>
										<input type="text" id ="rfc" data-length="13" value="<?php echo $receptor['rfc']; ?>">
										<label class ="active" for="rfc">RFC:</label>
									</div>	
								</div>
								<div class = "col s12 m9 l9">
									<div class = "input-field">
										<i class="material-icons prefix">people</i>
										<input type="text" id ="razonSocial" data-length="50" value="<?php echo $receptor['razon_social']; ?>">
										<label class ="active" for="razonSocial">Razón social:</label>
									</div>	
								</div>
								<div class = "col s12 m3 l3">
									<div class = "input-field">
										<i class="material-icons prefix">people</i>
										<input type="text" id ="codigoPostal" data-length="5" value="<?php echo $receptor['codigo_postal']; ?>">
										<label class ="active" for="codigoPostal">Código Postal:</label>
									</div>	
								</div>
								<div class = "col s12 m9 l9">
									<div class="input-field">
									<label>Selecciona un regimen fiscal</label>
									<br><br>
										<select id="regimen" name="regimen" class="browser-default">
										<?php
											$idRegimen = $receptor['regimen'];
											$consultaRegimen = mysqli_query($conn, "SELECT * FROM regimen_fiscal_sat WHERE clave=$idRegimen");
											if (mysqli_num_rows($consultaRegimen) == 0){
												echo '<script>M.toast({html:"Hubo un error al obtener la información del regimen.", classes: "rounded"})</script>';
											}else{
												while($seleccionRegimen =mysqli_fetch_array($consultaRegimen)){
												?>
												<option value="<?php echo $seleccionRegimen['clave'];?>"><?php echo '('.$seleccionRegimen['clave'].') '.$seleccionRegimen['nombre'];?></option>
												<?php
												}
											}
											?>                
										<option value="0" select>Seleccione un regimen:</option>
										<?php
											$regimenesFiscales = mysqli_query($conn,"SELECT * FROM regimen_fiscal_sat"); 
											if (mysqli_num_rows($regimenesFiscales) == 0) {
											echo '<script>M.toast({html:"No se encontraron datos.", classes: "rounded"})</script>';
											} else {
											while($regimen = mysqli_fetch_array($regimenesFiscales)) {
										?>
											<option value="<?php echo $regimen['clave'];?>"><?php echo '('.$regimen['clave'].') '.$regimen['nombre'];?></option>
												<?php
											}
											}
												?>
										</select>
									</div> 
									<a onclick="actualizaReceptor(<?php echo $receptor['id']?>);" class="waves-effect waves-light btn-small indigo right">Editar</a></td>
								</div>	
								<?php
							}
							break;
}
mysqli_close($conn);