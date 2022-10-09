<?php
if (isset($_POST['cliente'])) {
	echo "Mostrar informacion del cliente";
}else{
	echo "Mostrar buscador selector de cliente";
}
if (isset($_POST['habitacion'])){
	echo "Mostrar informacion de habitacion";
}else{
	echo "Seleccionar habitacion y mostrar informacion";
}

?>