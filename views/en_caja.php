<html>
<head>
  <title>San Roman | En Caja</title>
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
  <div class="container">
    <!--    //////    TITULO    ///////   -->
    <div class="row" >
      <h3 class="hide-on-med-and-down">Historial</h3>
      <h5 class="hide-on-large-only">Historial</h5>
    </div>
    <div class="row">
     <div class="row">
        <ul class="collection">
          <li class="collection-item grey"><h6><b> >>> ENTRADAS: <span class="new badge green" data-badge-caption="entrada(s)"><?php echo $Efectivo+$Credito+$Banco; ?></span></b></h6></li>
        </ul>
        <ul class="collapsible">
          <li>
            <div class="collapsible-header">
              <i class="material-icons">local_atm</i>
              EFECTIVO
              <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $Efectivo; ?></span>
            </div>
            <div class="collapsible-body">
              <?php 
              if ($Efectivo > 0) {
              ?>
                <table>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>N°</th>
                      <th>Cliente</th>
                      <th>Tipo</th>
                      <th>Descripción</th>
                      <th>Fecha y Hora</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $aux = 0;
                    $Total = 0;
                    while ($pago = mysqli_fetch_array($sql_efectivo)) {
                      $aux ++;
                      $id_cliente = $pago['id_cliente'];
                      $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id = $id_cliente"));
                      ?>
                      <tr>
                        <td><?php echo $aux; ?></td>
                        <td><?php echo $pago['id_pago']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $pago['tipo']; ?></td>
                        <td><?php echo $pago['descripcion']; ?></td>
                        <td><?php echo $pago['fecha'].' '.$pago['hora']; ?></td>
                        <td>$<?php echo sprintf('%.2f', $pago['cantidad']); ?></td>
                      </tr>
                      <?php 
                      $Total += $pago['cantidad'];
                    }
                    ?>
                  </tbody>
                </table>
                <h6 class="right"><b>TOTAL EFECTIVO . $<?php echo sprintf('%.2f', $Total); ?> </b></h6><br>
              <?php 
              }
              ?>
            </div>
          </li> 
          <li>
            <div class="collapsible-header">
              <i class="material-icons">credit_card</i>
              A BANCO
              <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $Banco; ?></span>
            </div>
            <div class="collapsible-body">
              <?php 
              if ($Banco > 0) {
              ?>
                <table>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>N°</th>
                      <th>Cliente</th>
                      <th>Tipo</th>
                      <th>Descripción</th>
                      <th>Fecha y Hora</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $aux = 0;
                    $Total = 0;
                    while ($pago = mysqli_fetch_array($sql_banco)) {
                      $aux ++;
                      $id_cliente = $pago['id_cliente'];
                      $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id = $id_cliente"));
                      ?>
                      <tr>
                        <td><?php echo $aux; ?></td>
                        <td><?php echo $pago['id_pago']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $pago['tipo']; ?></td>
                        <td><?php echo $pago['descripcion']; ?></td>
                        <td><?php echo $pago['fecha'].' '.$pago['hora']; ?></td>
                        <td>$<?php echo sprintf('%.2f', $pago['cantidad']); ?></td>
                      </tr>
                      <?php 
                      $Total += $pago['cantidad'];
                    }
                    ?>
                  </tbody>
                </table>
                <h6 class="right"><b>TOTAL A BANCO . $<?php echo sprintf('%.2f', $Total); ?> </b></h6><br>
              <?php 
              }
              ?>
            </div>
          </li> 
          <li>
            <div class="collapsible-header">
              <i class="material-icons">featured_play_list</i>
              A CREDITO
              <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $Credito; ?></span>
            </div>
            <div class="collapsible-body">
              <?php 
              if ($Credito > 0) {
              ?>
                <table>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>N°</th>
                      <th>Cliente</th>
                      <th>Tipo</th>
                      <th>Descripción</th>
                      <th>Fecha y Hora</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $aux = 0;
                    $Total = 0;
                    while ($pago = mysqli_fetch_array($sql_credito)) {
                      $aux ++;
                      $id_cliente = $pago['id_cliente'];
                      $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id = $id_cliente"));
                      ?>
                      <tr>
                        <td><?php echo $aux; ?></td>
                        <td><?php echo $pago['id_pago']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $pago['tipo']; ?></td>
                        <td><?php echo $pago['descripcion']; ?></td>
                        <td><?php echo $pago['fecha'].' '.$pago['hora']; ?></td>
                        <td>$<?php echo sprintf('%.2f', $pago['cantidad']); ?></td>
                      </tr>
                      <?php 
                      $Total += $pago['cantidad'];
                    }
                    ?>
                  </tbody>
                </table>
                <h6 class="right"><b>TOTAL A CREDITO . $<?php echo sprintf('%.2f', $Total); ?> </b></h6><br>
              <?php 
              }
              ?>
            </div>
          </li>          
        </ul>
        <ul class="collection">
          <li class="collection-item grey"><h6><b> >>> SALIDAS: <span class="new badge red" data-badge-caption="salida(s)"><?php echo $salidas; ?></span></b></h6></li>
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
