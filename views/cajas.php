<html>
<head>
  <title>San Roman | Cajas</title>
  <?php 
  //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
  include('fredyNav.php');
  $id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO

  $sql_efectivo = mysqli_query($conn,"SELECT * FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Efectivo'  AND id_user = $id_user");
  $Efectivo = mysqli_num_rows($sql_efectivo);
  $sql_banco = mysqli_query($conn,"SELECT * FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Banco'  AND id_user = $id_user");
  $Banco = mysqli_num_rows($sql_banco);
  $sql_credito = mysqli_query($conn,"SELECT * FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Credito'  AND id_user = $id_user");
  $Credito = mysqli_num_rows($sql_credito);
  $sql_salidas = mysqli_query($conn,"SELECT * FROM `salidas` WHERE corte = 0 AND usuario = $id_user");
  $salidas = mysqli_num_rows($sql_salidas);
  ?>
</head>
<main>
<body>
  <!-- DENTRO DE ESTE DIV VA TODO EL CONTENIDO Y HACE QUE SE VEA AL CENTRO DE LA PANTALLA.-->
  <div class="container"><br>
    <div class="row">
     <div class="row">        
        <ul class="collection">
          <li class="collection-item grey"><h6><b> >>> SALDOS EN CAJAS (SIN CORTE):</b></h6></li>
        </ul>
        <?php 
          if ($salidas > 0) {
            ?>
            <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>N°</th>
                    <th>Motivo</th>
                    <th>Fecha y Hora</th>
                    <th>Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $aux = 0;
                  $Total = 0;
                  while ($salida = mysqli_fetch_array($sql_salidas)) {  
                    $aux ++;
                    ?>
                    <tr>
                      <td><?php echo $aux; ?></td>
                      <td><?php echo $salida['id']; ?></td>
                      <td><?php echo $salida['motivo']; ?></td>
                      <td><?php echo $salida['fecha'].' '.$salida['hora']; ?></td>
                      <td>$<?php echo sprintf('%.2f', $salida['cantidad']); ?></td>
                    </tr>
                    <?php 
                    $Total += $salida['cantidad'];
                  }?>
                </tbody>
            </table>
            <h6 class="right"><b>TOTAL SALIDAS . $<?php echo sprintf('%.2f', $Total); ?> </b></h6><br>
            <?php 
          }
        ?>
    </div> 
  </div><br>
</body>
</main>
</html>
