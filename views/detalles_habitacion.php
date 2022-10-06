
<!DOCTYPE html>
<html>
<head>
    <title>San Roman | Habitaciones</title>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
	$id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO

    //Obtenemos la informacion del Usuario
    $User = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id_user"));

	//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL ARTICULO
	if (isset($_GET['id']) == false OR $User['habitaciones'] == 0) {
	  ?>
	  <script>    
	    M.toast({html: "Regresando a habitaciones.", classes: "rounded"});
	    setTimeout("location.href='habitaciones.php'", 500);
	  </script>
	  <?php
	}else{
		$id = $_GET['id'];// POR EL METODO POST RECIBIMOS EL ID DEL HABITACION
	    //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL ARTICULO Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
	    $habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id"));
	}
	?>
</head>
<body>

</body>
</html>