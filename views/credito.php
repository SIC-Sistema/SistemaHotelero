<html>
<head>
  <title>San Roman | Creditos</title>
  <?php 
  //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
  include('fredyNav.php');
  $id_user = $_SESSION['user_id'];// ID DEL USUARIO LOGEADO

  $sql_creditos = mysqli_query($conn,"SELECT * FROM `deudas` WHERE liquidada = 0");
  $deudas = mysqli_num_rows($sql_creditos);
  ?>
</head>
<main>
<body>
  <!-- DENTRO DE ESTE DIV VA TODO EL CONTENIDO Y HACE QUE SE VEA AL CENTRO DE LA PANTALLA.-->
  <div class="container">
    <div class="row"><br><br><br>
     <div class="row">
        <ul class="collection">
          <li class="collection-item grey"><h6><b>  CREDITOS PENDIENTES: </b></h6></li>
        </ul><br>
          <table>
              <thead>
                  <tr>
                    <th>#</th>
                    <th>N°</th>
                    <th>Cliente</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Cantidad</th>
                    <th>Acción</th>
                  </tr>
              </thead>
              <tbody>
                <?php 
                $aux = 0;
                if ($deudas > 0) {
                  while ($deuda = mysqli_fetch_array($sql_creditos)) { 
                    $id_cliente = $deuda['id_cliente'];
                    $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id = $id_cliente"));
                    $id = $deuda['usuario'];
                    $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id")); 
                    $aux ++;
                    ?>
                    <tr>
                      <td><?php echo $aux; ?></td>
                      <td><?php echo $deuda['id_deuda']; ?></td>
                      <td><?php echo $cliente['nombre']; ?></td>
                      <td><?php echo $deuda['descripcion']; ?></td>
                      <td><?php echo $deuda['fecha_deuda']; ?></td>
                      <td><?php echo $user['firstname']; ?></td>
                      <td>$<?php echo sprintf('%.2f', $deuda['cantidad']); ?></td>
                      <td><a href =  "../views/detalles_credito.php?id_cte=<?php echo $deuda['id_cliente']; ?>" class="btn-small waves-effect waves-light grey darken-4 tooltipped" data-position="bottom" data-tooltip="Detalles"><i class="material-icons">list</i></a></td>

                    </tr>
                    <?php 
                  }
                }?>
              </tbody>
          </table>
    </div> 
  </div><br>
</body>
</main>
</html>
