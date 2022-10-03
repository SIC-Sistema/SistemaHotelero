<?php
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');
//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
include('is_logged.php');
//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 3 PARA VER QUE ACCION HACER (Para Insertar = 0, Consultar compras = 1, InfoProveedor = 2, Borrar compra = 3, Buscar e Insertar Articulos TMP = 4, Actualizar Cant. o Costo = 5)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (Para Insertar = 0, Consultar = 1, Actualizar = 2, Borar = 3)
//echo "hola aqui estoy";
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:

        //CON POST RECIBIMOS TODAS LAS VARIABLES DEL FORMULARIO QUE NESECITAMOS PARA INSERTAR
        $Nombre = $conn->real_escape_string($_POST['valorNombre']);     
        //VERIFICAMOS QUE NO HALLA UN ARTICULO CON LOS MISMOS DATOS
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `punto_venta_almacenes` WHERE nombre='$Nombre' "))>0){
            echo '<script >M.toast({html:"Ya se encuentra una categoria con el mismo Nombre.", classes: "rounded"})</script>';
        }else{
            // SI NO HAY NUNGUNO IGUAL CREAMOS LA SENTECIA SQL  CON LA INFORMACION REQUERIDA Y LA ASIGNAMOS A UNA VARIABLE
            $sql = "INSERT INTO `punto_venta_almacenes` (nombre, usuario, fecha) 
               VALUES('$Nombre','$id_user','$Fecha_hoy')";
            //VERIFICAMOS QUE LA SENTECIA FUE EJECUTADA CON EXITO!
			if(mysqli_query($conn, $sql)){
				echo '<script >M.toast({html:"El almacen  se registró exitosamente.", classes: "rounded"})</script>';	
                echo '<script>recargar_almacen_lista()</script>';// REDIRECCIONAMOS (FUNCION ESTA EN ARCHIVO modals.php)
			}else{
                echo '<script >M.toast({html:"Ocurrio un error...", classes: "rounded"})</script>';	
            }//FIN else DE ERROR
            
        }// FIN else DE BUSCAR CATEGORIA IGUAL

        break;
    case 1:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 1 realiza:

        //CON POST RECIBIMOS UN TEXTO DEL BUSCADOR VACIO O NO de "almacenes_punto_venta.php"
        $Texto = $conn->real_escape_string($_POST['texto']);
        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
		if ($Texto != "") {
			//MOSTRARA LOS ALMACENES QUE SE ESTAN BUSCANDO Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql......
			$sql = "SELECT * FROM `punto_venta_almacenes` WHERE  nombre LIKE '%$Texto%' ORDER BY id";	
		}else{//ESTA CONSULTA SE HARA SIEMPRE QUE NO ALLA NADA EN EL BUSCADOR Y GUARDAMOS LA CONSULTA SQL EN UNA VARIABLE $sql...
			$sql = "SELECT * FROM `punto_venta_almacenes`";
		}//FIN else $Texto VACIO O NO

        // REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
		$consulta = mysqli_query($conn, $sql);		
		$contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO

		//VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
		if (mysqli_num_rows($consulta) == 0) {
				echo '<script>M.toast({html:"No se encontraron almacenes.", classes: "rounded"})</script>';
        } else {
            //SI NO ESTA EN == 0 SI TIENE INFORMACION
            //La variable $contenido contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
            //RECORREMOS UNO A UNO LOS ALMACENES CON EL WHILE
            while($almacen = mysqli_fetch_array($consulta)) {
                $id_user = $almacen['usuario'];
				$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id=$id_user"));
				//Output
                $contenido .= '			
		          <tr>
		            <td>'.$almacen['id'].'</td>
                    <td>'.$almacen['nombre'].'</td>
		            <td>'.$user['firstname'].'</td>
		            <td>'.$almacen['fecha'].'</td>
		            <td><form method="post" action="../views/editar_almacen_pv.php"><input id="id" name="id" type="hidden" value="'.$almacen['id'].'"><button class="btn-floating btn-tiny waves-effect waves-light pink"><i class="material-icons">edit</i></button></form></td>
		            <td><a onclick="borrar_almacen('.$almacen['id'].')" class="btn btn-floating red darken-1 waves-effect waves-light"><i class="material-icons">delete</i></a></td>
		          </tr>';

			}//FIN while
        }//FIN else
        echo $contenido;// MOSTRAMOS LA INFORMACION HTML
        break;
    case 2:///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 2 realiza:

        //CON POST RECIBIMOS EL ID DEL PROVEEDOR DEL FORMULARIO POR EL SCRIPT "add_compra.php" QUE NESECITAMOS PARA BUSCAR
    	$id = $conn->real_escape_string($_POST['proveedor']);    
        $contenido = '';//CREAMOS UNA VARIABLE VACIA PARA IR LLENANDO CON LA INFORMACION EN FORMATO
        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
        if ($id != 0) {
            //HACEMOS LA CONSULTA DEL PROVEEDOR Y MOSTRAMOS LA INFOR EN FORMATO HTML
            $proveedor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `punto_venta_proveedores` WHERE id=$id"));
            $contenido .= '<h6 class = "col s12 m4 l4"><b>RFC: </b>'.$proveedor['rfc'].'</h6>  <h6 class = "col s12 m4 l4"><b>Telefono: </b>'.$proveedor['telefono'].' </h6> <h6 class = "col s12 m4 l4"><b>Dias Credito:</b>'.$proveedor['dias_c'].' </h6>';
        }
        echo $contenido;// IMPRIMIMOS EL CONTENDIO QUE PUEDE IR VACIO SI ES $id = 0
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

        //CON POST RECIBIMOS UN ID DEL MODAL O AL INICIAR EL DOCUMENTO "add_punto_venta.php"
        $user_id = $conn->real_escape_string($_POST['id']);
        $insert = $conn->real_escape_string($_POST['insert']);
        //VERIFICAMOS SI CONTIENE ALGO DE TEXTO LA VARIABLE
        if ($insert) {
            //SE HACE LA ISNERCION A TMP
               
        }
        // REALIZAMOS LA CONSULTA A LA BASE DE DATOS MYSQL Y GUARDAMOS EN FORMARTO ARRAY EN UNA VARIABLE $consulta
        $consulta = mysqli_query($conn, "SELECT * FROM tmp_pv_detalle_compra WHERE usuario = $user_id");      
        ?>
        <div class="row" id="articulosCompra">
            <div class="hide-on-small-only col s1"><br></div>
            <table class="col s12 m10 l10">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Cantidad</th>
                  <th>Artículo</th>
                  <th>Costo U.</th>
                  <th>Importe</th>
                </tr>
              </thead>
              <tbody>
               <?php
               $aux = mysqli_num_rows($consulta);
               $total = 0;
               //VERIFICAMOS SI HA ARRTICULOS EN LA TABLA
               if(mysqli_num_rows($consulta)>0){

               }else{
                  echo '<tr><td></td><td></td><td><h6> Sin Artículos </h6></td></tr>';
               }
               ?>                
              </tbody>
            </table>
        </div>
        <div class="row">
            <div class="hide-on-small-only col s1"><br></div>
            <div class="col s12 m10 l10">
                <div class="col s6 m7 l7 ">
                    <h5 class="right"><b>Número de Artículos <?php echo $aux;?></b></h5><br><br><br><br>
                    <a href="#" class="waves-effect waves-light btn-small red right">Cancelar<i class="material-icons left">close</i></a>
                    <a href="#" class="waves-effect waves-light btn-small indigo right">Registrar<i class="material-icons left">done</i></a>
                </div>
                <div class="col s6 m5 l5">
                    <h6 class="right"><b>SubTotal $<?php echo sprintf('%.2f', $total-($total*0.16)); ?></b></h6><br><br>
                    <h6 class="right"><b>Impuesto $<?php echo sprintf('%.2f', $total*0.16); ?></b></h6><br><br>
                    <hr>
                    <h5 class="right"><b>Total $<?php echo sprintf('%.2f', $total); ?></b></h5>
                </div>
            </div>            
        </div>
        <hr><br>
        <?php
        break;
}// FIN switch
mysqli_close($conn);
    
?>