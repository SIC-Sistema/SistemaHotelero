<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    //DEFINIMOS LA ZONA  HORARIA
    date_default_timezone_set('America/Mexico_City');
    $Fecha_hoy = date('Y-m-d');// FECHA ACTUAL
    ?>
    <title>San Roman | Habitaciones</title>
  </head>
  <main>
  <body>
    <div class="container"><br><br>
      <!--    //////    BOTON QUE REDIRECCIONA AL FORMULARIO DE AGREGAR HABITACION    ///////   -->
      <a href="add_habitacion.php" class="waves-effect waves-light btn grey darken-3 left right">Agregar Habitación<i class="material-icons prefix left">add</i></a>
      <div class="row">
        <h2 class="hide-on-med-and-down">Habitaciones</h2>
        <h4 class="hide-on-large-only">Habitaciones</h4>
      </div>
      <div class="row">
      <?php 
      $habitaciones = mysqli_query($conn,"SELECT * FROM habitaciones"); 
      //VERIFICAMOS QUE LA VARIABLE SI CONTENGA INFORMACION
      if (mysqli_num_rows($habitaciones) == 0) {
        echo '<h4>No se encontraron habitaciones.</h4>';
      } else {
        while ($Habitacion = mysqli_fetch_array($habitaciones)) {
          $id_habitacion = $Habitacion['id'];
          $habitacion = (mysqli_query($conn, "SELECT * FROM `reservaciones` WHERE id_habitacion=$id_habitacion AND fecha_entrada = '$Fecha_hoy' AND estatus = 0"));            
          if ($Habitacion['estatus'] == 0 AND mysqli_num_rows($habitacion) > 0) {
            $color = 'amber';
            $icono = 'directions_walk';
            $estatus = 'Reservacion Hoy';
          }else if ($Habitacion['estatus'] == 1) {
            $color = 'red';
            $icono = 'hotel';
            $estatus = 'Ocupada';
          }else if ($Habitacion['estatus'] == 2) {
            $color = 'blue';
            $icono = 'pan_tool';
            $estatus = 'Limpieza';
          }else if ($Habitacion['estatus'] == 0) {
            $color = 'green';
            $icono = 'weekend';
            $estatus = 'Disponible';
          }
          ?>
          <div class="col s6 m4 l3">
            <a href="detalles_habitacion.php?id=<?php echo $Habitacion['id']; ?>">
            <div class="card">
              <div class="card-content">
                <span class="card-title black-text"><b>N° <?php echo $Habitacion['id']; ?> <b><i class="medium <?php echo $color; ?>-text material-icons right"><?php echo $icono; ?></i></span>
              </div>
              <div class="<?php echo $color; ?> accent-4">
                <h6 class="white-text">|<b> - <?php echo $estatus; ?><i class="material-icons right">chevron_right</i><b></h6>
              </div>
            </div>
            </a>
          </div>
          <?php
        }//FIN WHILE
      }//FIN else
      ?>        
      </div>
  </body>
  </main>
</html>