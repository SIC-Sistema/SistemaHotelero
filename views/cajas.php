<html>
<head>
  <title>San Roman | Cajas</title>
  <?php 
  //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
  include('fredyNav.php');

  $id = $_SESSION['user_id'];
  #TOMAMOS LA INFORMACION DEL USUARIO (PARA SABER A QUE AREA PERTENECE)
  $area = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$id"));
  #COMPARAMOS SI SU AREA ES DIFERENTE A UN ADMINISTRADOR
  if($area['area'] != "Administrador" ){
    #SI NO ES DIFERENTE A UN ADMINISTRADOR LE MUESTRA MENSAJE DE NEGACION Y REDIRECCIONA A LA PAGINA PRINCIPAL
    echo '<script>M.toast({html:"Permiso denegado. Direccionando a la página principal.", classes: "rounded"})</script>';
      #LLAMAR LA FUNCION admin() DEFINIDA EN EL ARCHIVO MODALS PARA REDIRECCIONAR
      echo '<script>admin();</script>';
      #CERRAR LA CONEXION A LA BASE DE DATOS
    mysqli_close($conn);
    exit;
  }
  ?>
</head>
<main>
<body>
  <!-- DENTRO DE ESTE DIV VA TODO EL CONTENIDO Y HACE QUE SE VEA AL CENTRO DE LA PANTALLA.-->
  <div class="container"><br>
    <div class="row">
     <div class="row"><br>        
        <ul class="collection">
          <li class="collection-item grey"><h6><b> >>> SALDOS EN CAJAS (SIN CORTE):</b></h6></li>
        </ul>
        <table class="centered">
          <thead>
            <tr>
              <th>N°</th>
              <th>Nombre</th>
              <th>Apellidos</th>
              <th>Entradas</th>
              <th>Salidas</th>
              <th>Efectivo</th>
              <th>Banco</th>
              <th>Credito</th>
              <th>Detalles</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $sql_tmp = mysqli_query($conn,"SELECT * FROM users WHERE estatus = 1");
              $columnas = mysqli_num_rows($sql_tmp);
              if($columnas == 0){
                echo '<h5 class="center">No se encontraron Usuarios</h5>';
              }else{
                $AllEfectivo = 0;  $AllBanco = 0;  $AllCredito = 0;

                while($tmp = mysqli_fetch_array($sql_tmp)){
                  $id_user = $tmp['user_id'];
                  $entradas = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(cantidad) AS suma FROM pagos WHERE id_user=$id_user AND corte = 0 AND tipo_cambio='Efectivo'"));         
                  $banco = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(cantidad) AS suma FROM pagos WHERE id_user=$id_user AND corte = 0 AND tipo_cambio='Banco'"));
                  $credito = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(cantidad) AS suma FROM pagos WHERE id_user=$id_user AND corte = 0 AND tipo_cambio='Credito'"));
                  $salidas = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(cantidad) AS suma FROM salidas WHERE corte = 0 AND usuario = $id_user"));
                  $cortes = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(entradas) AS entradas, SUM(salidas) AS salidas, SUM(banco) AS banco, SUM(credito) AS credito FROM cortes WHERE corte = 0 AND realizo = $id_user"));
                  if ($salidas['suma'] == '') {
                    $salidas['suma'] = 0;
                  }
                  ?>
                  <tr>
                    <td><?php echo $tmp['user_id']; ?></td>
                    <td><?php echo $tmp['firstname']; ?></td>
                    <td><?php echo $tmp['lastname']; ?></td>
                    <td>$<?php echo sprintf('%.2f', $entradas['suma']+$cortes['entradas']); ?></td>
                    <td>-$<?php echo sprintf('%.2f', $salidas['suma']+$cortes['salidas']); ?></td>
                    <td>$<?php echo sprintf('%.2f', ($entradas['suma']+$cortes['entradas'])-($salidas['suma']+$cortes['salidas'])); ?></td>
                    <td>$<?php echo sprintf('%.2f', $banco['suma']+$cortes['banco']); ?></td>
                    <td>$<?php echo sprintf('%.2f', $credito['suma']+$cortes['credito']); ?></td>
                    <td><form method="post" action="../views/detalles_caja.php"><input id="id_usuario" name="id_usuario" type="hidden" value="<?php echo $tmp['user_id']; ?>"><button class="btn-small waves-effect waves-light grey darken-4"><i class="material-icons">list</i></button></form></td>
                  </tr>
                  <?php
                  $AllEfectivo += $entradas['suma']+$cortes['entradas'];
                  $AllEfectivo -= $salidas['suma']+$cortes['salidas'];
                  $AllBanco += $banco['suma']+$cortes['banco'];
                  $AllCredito += $credito['suma']+$cortes['credito'];
                }//FIN WHILE
              }//FIN ELSE
            ?>   
            <tr>
            
              <td colspan="5"><h6><b>TOTALES</b></h6></td>
              <td><h6><b>$<?php echo sprintf('%.2f', $AllEfectivo); ?></b></h6></td>
              <td><h6><b>$<?php echo sprintf('%.2f', $AllBanco); ?></b></h6></td>
              <td><h6><b>$<?php echo sprintf('%.2f', $AllCredito) ?></b></h6></td>
            </tr>         
          </tbody>
    </div> 
  </div><br>
</body>
</main>
</html>
