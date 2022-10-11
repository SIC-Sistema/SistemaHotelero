					
<?php
//ARCHIVO QUE CONTIENE LA VARIABLE CON LA CONEXION A LA BASE DE DATOS
include('../php/conexion.php');
//ARCHIVO QUE CONDICIONA QUE TENGAMOS ACCESO A ESTE ARCHIVO SOLO SI HAY SESSION INICIADA Y NOS PREMITE TIMAR LA INFORMACION DE ESTA
include('is_logged.php');
//DEFINIMOS LA ZONA  HORARIA
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO
$Fecha_hoy = date('Y-m-d');// FECHA ACTUAL

//CON METODO POST TOMAMOS UN VALOR DEL 0 AL 6 PARA VER QUE ACCION HACER (busca Habit = 0)
$Accion = $conn->real_escape_string($_POST['accion']);

//UN SWITCH EL CUAL DECIDIRA QUE ACCION REALIZA DEL CRUD (busca Habit = 0)
//echo "hola aqui estoy";
switch ($Accion) {
    case 0:  ///////////////           IMPORTANTE               ///////////////
        // $Accion es igual a 0 realiza:

    	//CON POST RECIBIMOS EL ID DE LA HABITACION DEL FORMULARIO POR EL SCRIPT "reservacion.php" QUE NESECITAMOS PARA BUSCAR
    	$id = $conn->real_escape_string($_POST['habitacion']);  
    	if ($id != 0) {
            //HACEMOS LA CONSULTA DE LA HABITACION Y MOSTRAMOS LA INFOR EN FORMATO HTML
            $habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id"));
            
            ?>
            	<div class="row col s12" id="infoHabitacion"><b>
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">ESTADO: </b>
		              		<span class="new badge <?php echo ($habitacion['estatus'] == 0)?'green':'red';?> prefix" data-badge-caption=""><?php echo ($habitacion['estatus'] == 0)?'Disponible':'Ocupada';?></span>
		              	</div>      
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">NIVEL / PISO: </b>
		              		<?php echo $habitacion['piso'];?>
		              	</div> 
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">HASTA: </b>
		              		<?php echo 'Por Definir';?>
		              	</div>           		
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">DESCRIPCION: </b>
		              		<?php echo $habitacion['descripcion'];?>
		              	</div>
		              	<div class="col s12"><br>
		              		<b class="indigo-text col s12 m5">TIPO DE HABITACION: </b>
		              		<br><?php echo $habitacion['tipo'];?>
		              	</div> 
		              	<div class="col s12"><br><br>
		              		<b class="indigo-text col s12 m5">PRECIO POR DIA: </b>
		              		<div class="col s10 m6">
				              <input id="precioXDia" type="text" class="validate" data-length="100" value="<?php echo '$'.sprintf('%.2f', $habitacion['precio']);?>" required>	
				            </div>   		
		              	</div>
		        </b></div>
            <?php
        }
        break;


}// FIN switch
mysqli_close($conn);
    
?>