<?php
//VERIFICAMOS QUE SI NOS ENVIE POR POST EL ID DEL ARTICULO
if (isset($_POST['id_usuario']) == false) {
  ?>
  <script>    
    M.toast({html: "Regresando a lista de saldos.", classes: "rounded"});
    setTimeout("location.href='cajas.php'", 800);
  </script>
  <?php
}else{
?>
  <html>
  <head>
    <title>San Roman | Detalles de Caja</title>
    <?php 
    //INCLUIMOS EL ARCHIVO QUE CONTIENE LA BARRA DE NAVEGACION TAMBIEN TIENE (scripts, conexion, is_logged, modals)
    include('fredyNav.php');
    //REALIZAMOS LA CONSULTA PARA SACAR LA INFORMACION DEL USUARIO Y ASIGNAMOS EL ARRAY A UNA VARIABLE $datos
    $user_id = $_POST['id_usuario'];
    $datos = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id=$user_id"));

    $sql_efectivo = mysqli_query($conn,"SELECT * FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Efectivo'  AND id_user = $user_id");
    $Efectivo_w = mysqli_num_rows($sql_efectivo);
    $sql_banco = mysqli_query($conn,"SELECT * FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Banco'  AND id_user = $user_id");
    $Banco_w = mysqli_num_rows($sql_banco);
    $sql_credito = mysqli_query($conn,"SELECT * FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Credito'  AND id_user = $user_id");
    $Credito_w = mysqli_num_rows($sql_credito);
    $sql_salidas = mysqli_query($conn,"SELECT * FROM `salidas` WHERE corte = 0 AND usuario = $user_id");
    $salidas_w = mysqli_num_rows($sql_salidas);
    $entradads = mysqli_fetch_array(mysqli_query($conn,"SELECT sum(cantidad) as total FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Efectivo' AND id_user = $user_id"));
    $salidas = mysqli_fetch_array(mysqli_query($conn,"SELECT sum(cantidad) as total FROM `salidas` WHERE corte = 0 AND usuario = $user_id"));
    $banco = mysqli_fetch_array(mysqli_query($conn,"SELECT sum(cantidad) as total FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Banco' AND id_user = $user_id"));
    $credito = mysqli_fetch_array(mysqli_query($conn,"SELECT sum(cantidad) as total FROM `pagos` WHERE corte = 0 AND tipo_cambio = 'Credito' AND id_user = $user_id"));
    ?>  
    <script>
      //FUNCION QUE ENVIA LOS DATOS PARA VALIDAR DESPUES DE LLENADO DEL MODAL
      function recargar_corte() {
        var textoClave = $("input#clave").val(); 
       
        if (textoClave == "") {
            M.toast({html:"El campo clave no puede ir vacío.", classes: "rounded"});
        }else{
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_dinero.php" PARA MOSTRAR EL MODAL
          $.post("../php/control_dinero.php", {
            //Cada valor se separa por una ,
              valorClave: textoClave,
              valorEntradas: <?php echo $entradads['total']; ?>,
              valorSalidas: <?php echo $salidas['total']; ?>,
              valorBanco: <?php echo $banco['total']; ?>,
              valorCredito: <?php echo $credito['total']; ?>,
              valorUsuario: <?php echo $user_id; ?>,
              accion: 1,
            }, function(mensaje) {
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_dinero.php"
               $("#resultado_corte").html(mensaje);
          });
        } //FIN ELSE  
      } // FIN function
    </script>  
  </head>
  <main>
  <body>
    <div class="container">
      <div class="row"><br>
        <div id="resultado_corte"></div>
        <ul class="collection">
            <li class="collection-item avatar">
              <div class="hide-on-large-only"><br><br></div>
              <img src="../img/cliente.png" alt="" class="circle">
              <span class="title"><b>DETALLES DEL USUARIO</b></span><br><br>
              <p class="row col s12"><b>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">N° USUARIO: </b><?php echo $user_id;?></div>               
                </div>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">NOMBRE: </b><?php echo $datos['firstname'].' '.$datos['lastname'];?></div>    
                </div>
                <div class="col s12 m4">       
                  <div class="col s12"><b class="indigo-text">USUARIO: </b><?php echo $datos['user_name'];?></div>               
                </div>
              </b></p><br><br>
            </li>
        </ul>
        <ul class="collection">
            <li class="collection-item avatar">
              <div class="hide-on-large-only"><br><br></div>
              <span class="title"><b>RESUMEN: </b></span><br><br>
              <div class="col s6 m3">
                <b class="right">Entradas: </b><br>               
                <b class="right">Salidas: </b><br>
              </div>
              <div class="col s6 m9">
                $<?php echo sprintf('%.2f',$entradads['total']);?><br>
                $<?php echo sprintf('%.2f',$salidas['total']);?><br>
              </div>                
              <br><br><hr>   
              <div class="col s6 m3">
                <b class="indigo-text right">TOTAL EFECTIVO: </b><br>               
                <b class="indigo-text right">TOTAL A BANCO: </b><br>               
                <b class="indigo-text right">TOTAL CREDITO: </b><br>   
              </div>  
              <div class="col s6 m9">
                $<?php echo sprintf('%.2f',$entradads['total']-$salidas['total']);?><br>               
                $<?php echo sprintf('%.2f',$banco['total']);?><br>               
                $<?php echo sprintf('%.2f',$credito['total']);?><br>   
              </div>        
            </li>
        </ul>
        <!--    //////    BOTON QUE RELIZARA EL CORTE DEL USUARIO    ///////   -->
        <a href="#corte" class="waves-effect modal-trigger waves-light btn grey darken-3 left right">Relizar corte<i class="material-icons prefix left">content_cut</i></a>
        <!--    //////    TITULO    ///////   -->
        <div class="row" >
          <h3 class="hide-on-med-and-down">Desglose:</h3>
          <h5 class="hide-on-large-only">Desglose:</h5>
        </div>
        <div class="row">
          <div class="row">
            <ul class="collection">
              <li class="collection-item grey"><h6><b> >>> ENTRADAS: <span class="new badge green" data-badge-caption="entrada(s)"><?php echo $Efectivo_w+$Credito_w+$Banco_w; ?></span></b></h6></li>
            </ul>
            <ul class="collapsible">
              <li>
                <div class="collapsible-header">
                  <i class="material-icons">local_atm</i>
                  EFECTIVO
                  <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $Efectivo_w; ?></span>
                </div>
                <div class="collapsible-body">
                  <?php 
                  if ($Efectivo_w > 0) {
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
                  <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $Banco_w; ?></span>
                </div>
                <div class="collapsible-body">
                  <?php 
                  if ($Banco_w > 0) {
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
                  <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $Credito_w; ?></span>
                </div>
                <div class="collapsible-body">
                  <?php 
                  if ($Credito_w > 0) {
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
              <li class="collection-item grey"><h6><b> >>> SALIDAS: <span class="new badge red" data-badge-caption="salida(s)"><?php echo $salidas_w; ?></span></b></h6></li>
            </ul>
            <?php 
              if ($salidas_w > 0) {
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
        </div>
        <!--    //////    BOTON QUE RELIZARA EL CORTE DEL USUARIO    ///////   -->
        <a href="#corte" class="waves-effect modal-trigger waves-light btn grey darken-3 left right">Relizar corte<i class="material-icons prefix left">content_cut</i></a>
      </div>      
    </div>
  </body>
  </main>
  </html>
<?php
}// FIN else POST
?>