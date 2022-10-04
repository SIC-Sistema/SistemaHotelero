<!DOCTYPE html>
<html lang="en">
<head>
<?php
  include('fredyNav.php');
?>
<title>SIC | Inicio</title>
</head>
<main>
<script>
	$(document).ready(function(){
	    $('#bienvenida').modal();
	    $('#bienvenida').modal('open'); 
	 });
</script>
<body>
	<div class="row ">
		 <img class="materialboxed" width="100%" src="../img/bannerSR1.jpg">
	</div>
 	<!--Modal cortes BIENVENIDA-->
	<div id="bienvenida" class="modal">
	  <div class="modal-content">
	    <h3 class="red-text center">¡ Hola !</h3><br>
	    <h4 class="grey-text center"><b><?php echo $_SESSION['user_name'] ?></b></h4><br>
	    <h5 class="center">¡Hotel San Roman, Te da la bienvenida!</h5>	     
	  </div>
	  <div class="modal-footer">
	      <a href="#" class="modal-action modal-close waves-effect waves-red btn-flat">Cerrar<i class="material-icons right">close</i></a>
	  </div>
	</div>
 	<!--Modal cortes BIENVENIDA-->
</body>
</main>
</html>