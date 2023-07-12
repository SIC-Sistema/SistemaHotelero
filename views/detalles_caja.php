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
    $sql_cortes = mysqli_query($conn,"SELECT * FROM `cortes` WHERE corte = 0 AND realizo = $user_id");
    $cortes_w = mysqli_num_rows($sql_cortes);
    $cortes = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(entradas) AS entradas, SUM(salidas) AS salidas, SUM(banco) AS banco, SUM(credito) AS credito FROM cortes WHERE corte = 0 AND realizo = $user_id"));
    $entradads['total'] = $entradads['total']+$cortes['entradas'];
    $salidas['total'] = $salidas['total']+$cortes['salidas'];
    $banco['total'] = $banco['total']+$cortes['banco'];
    $credito['total'] = $credito['total']+$cortes['credito'];
    $joinTransferencia = mysqli_query($conn, "SELECT * FROM pagos INNER JOIN referencias ON pagos.id_pago = referencias.id_pago WHERE pagos.corte = 0 AND pagos.tipo_cambio = 'Banco'
    AND referencias.transferencia = 1 AND pagos.id_user = $user_id;");
    $pagosTransferencia = mysqli_num_rows($joinTransferencia);
    $joinTarjetaDebito = mysqli_query($conn, "SELECT * FROM pagos INNER JOIN referencias ON pagos.id_pago = referencias.id_pago WHERE pagos.corte = 0 AND pagos.tipo_cambio = 'Banco'
    AND referencias.tarjeta = 1 AND referencias.debito = 1 AND pagos.id_user = $user_id;");
    $pagosTarjetaDebito = mysqli_num_rows($joinTarjetaDebito);
    $joinTarjetaCredito = mysqli_query($conn, "SELECT * FROM pagos INNER JOIN referencias ON pagos.id_pago = referencias.id_pago WHERE pagos.corte = 0 AND pagos.tipo_cambio = 'Banco'
    AND referencias.tarjeta = 1 AND referencias.credito = 1 AND pagos.id_user = $user_id;");
    $pagosTarjetaCredito = mysqli_num_rows($joinTarjetaCredito);
    $ultimoCorte 	= mysqli_fetch_array(mysqli_query($conn,"SELECT  MAX(id_corte) AS id  FROM cortes"));
    $ultimoIdCorte 	= $ultimoCorte['id'];
    $enCajaInicio	= mysqli_fetch_array(mysqli_query($conn, "SELECT  en_caja FROM cortes WHERE id_corte ='$ultimoIdCorte'"));
    ?>  
    <script>
      //FUNCION QUE ENVIA LOS DATOS PARA VALIDAR DESPUES DE LLENADO DEL MODAL
      function recargar_corte() {
        var textoClave = $("input#clave").val(); 
        var tipo = $("input#corteTipo").val();
        var textoMontoEnCaja = $("input#montoEnCaja").val(); 
        var textoMontoTransferencia = $("input#transferenciaBancaria").val(); 
        var textoMontoTarjetaDebito = $("input#tarjetaDeDebito").val();
        var textoMontoTarjetaCredito = $("input#tarjetaDeCredito").val();
       
        if (textoClave == "") {
            M.toast({html:"El campo clave no puede ir vacío.", classes: "rounded"});
        }else if(textoMontoEnCaja == ""){
          M.toast({html:"El campo monto no puede ir vacío.", classes: "rounded"});
        }
        else{
          //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "control_dinero.php" PARA MOSTRAR EL MODAL
          $.post("../php/control_dinero.php", {
            //Cada valor se separa por una ,
              valorClave: textoClave,
              valorEntradas: <?php echo ($entradads['total'] != '')? $entradads['total']:0; ?>,
              valorSalidas: <?php echo ($salidas['total'] != '')? $salidas['total']:0; ?>,
              valorBanco: <?php echo ($banco['total'] != '')? $banco['total']:0; ?>,
              valorCredito: <?php echo ($credito['total'] != '')? $credito['total']:0; ?>,
              valorUsuario: <?php echo $user_id; ?>,
              valorTransferencia: textoMontoTransferencia,
              valorTarjetaDebito: textoMontoTarjetaDebito,
              valorTarjetaCredito: textoMontoTarjetaCredito,
              accion: 1,
              tipo: tipo,
              valorMontoEnCaja: textoMontoEnCaja,
            }, function(mensaje) {
              //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_dinero.php"
               $("#resultado_corte").html(mensaje);
          });
        } //FIN ELSE  
      } // FIN function
      //FUNCION Calcula el Cambio
        function corteTipo(n){
          //RECIBIMOS LOS VALORES DE LOS INPUTS AFECTADOS
          document.getElementById("corteTipo").value = n;
        }// FIN function
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
                <b class="right">En caja al inicio:</b><br>
                <b class="right">Entradas: </b><br>               
                <b class="right">Salidas: </b><br>
              </div>
              <div class="col s6 m9">
                $<?php echo sprintf('%.2f',$enCajaInicio['en_caja']);?><br>
                $<?php echo sprintf('%.2f',$entradads['total']);?><br>
                $<?php echo sprintf('%.2f',$salidas['total']);?><br>
              </div>
              <div class="col s12 m12 l12">          
              <hr>
              </div>
              <div class="col s6 m3">
                <b class="indigo-text right">TOTAL EFECTIVO: </b><br>               
                <b class="indigo-text right">TOTAL A BANCO: </b><br>               
                <b class="indigo-text right">TOTAL CREDITO: </b><br>   
              </div>  
              <div class="col s6 m9">
                $<?php echo sprintf('%.2f',$enCajaInicio['en_caja']+$entradads['total']-$salidas['total']);?><br>               
                $<?php echo sprintf('%.2f',$banco['total']);?><br>               
                $<?php echo sprintf('%.2f',$credito['total']);?><br>   
              </div>        
            </li>
        </ul>
        <!--    //////    BOTON QUE RELIZARA EL CORTE DEL USUARIO    ///////   -->
        <input type="hidden" id="corteTipo" value="0">
        <?php
        $us = $_SESSION['user_id'];
        $user = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id=$us"));

        if ($user['cortes'] == 1) {
        ?>
          <a href="#corte" onclick="corteTipo(2);" class="waves-effect modal-trigger waves-light btn grey darken-3 left right">Relizar corte total<i class="material-icons prefix left">content_cut</i></a>
        <?php
        }
        ?>
        <a href="#corte" onclick="corteTipo(1);" class="waves-effect modal-trigger waves-light btn grey darken-3 left right">Relizar corte pagos<i class="material-icons prefix left">content_cut</i></a>   
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
              TRANSFERENCIA BANCARIA
              <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $pagosTransferencia; ?></span>
            </div>
            <div class="collapsible-body">
              <?php 
              if ($pagosTransferencia > 0) {
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
                    $TotalTransferencia = 0;
                    while ($pagosTransferenciaT = mysqli_fetch_array($joinTransferencia)) {
                      $aux ++;
                      $id_cliente = $pagosTransferenciaT['id_cliente'];
                      $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id = $id_cliente"));
                      ?>
                      <tr>
                        <td><?php echo $aux; ?></td>
                        <td><?php echo $pagosTransferenciaT['id_pago']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $pagosTransferenciaT['tipo']; ?></td>
                        <td><?php echo $pagosTransferenciaT['descripcion']; ?></td>
                        <td><?php echo $pagosTransferenciaT['fecha'].' '.$pagosTransferenciaT['hora']; ?></td>
                        <td>$<?php echo sprintf('%.2f', $pagosTransferenciaT['cantidad']); ?></td>
                      </tr>
                      <?php 
                      $TotalTransferencia += $pagosTransferenciaT['cantidad'];
                    }
                    ?>
                  </tbody>
                </table>
                <input type="hidden" id="transferenciaBancaria" name="transferenciaBancaria" value="<?php echo $TotalTransferencia; ?>" />
                <h6 class="right"><b>TOTAL TRANSFERENCIA BANCARIA . $<?php echo sprintf('%.2f', $TotalTransferencia); ?> </b></h6><br>
              <?php 
              }
              ?>
            </div>
          </li> 
          <li>
            <div class="collapsible-header">
              <i class="material-icons">credit_card</i>
              TARJETA DE DÉBITO
              <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $pagosTarjetaDebito; ?></span>
            </div>
            <div class="collapsible-body">
              <?php 
              if ($pagosTarjetaDebito > 0) {
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
                    $TotalTarjetaDebito = 0;
                    while ($pagosTarjetaDebitoT = mysqli_fetch_array($joinTarjetaDebito)) {
                      $aux ++;
                      $id_cliente = $pagosTarjetaDebitoT['id_cliente'];
                      $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id = $id_cliente"));
                      ?>
                      <tr>
                        <td><?php echo $aux; ?></td>
                        <td><?php echo $pagosTarjetaDebitoT['id_pago']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $pagosTarjetaDebitoT['tipo']; ?></td>
                        <td><?php echo $pagosTarjetaDebitoT['descripcion']; ?></td>
                        <td><?php echo $pagosTarjetaDebitoT['fecha'].' '.$pagosTarjetaDebitoT['hora']; ?></td>
                        <td>$<?php echo sprintf('%.2f', $pagosTarjetaDebitoT['cantidad']); ?></td>
                      </tr>
                      <?php 
                      $TotalTarjetaDebito += $pagosTarjetaDebitoT['cantidad'];
                    }
                    ?>
                  </tbody>
                </table>
                <input type="hidden" id="tarjetaDeDebito" name="tarjetaDeDebito" value="<?php echo $TotalTarjetaDebito; ?>" />
                <h6 class="right"><b>TOTAL TARJETA DE DÉBITO . $<?php echo sprintf('%.2f', $TotalTarjetaDebito); ?> </b></h6><br>
              <?php 
              }
              ?>
            </div>
          </li> 
          <li>
          <li>
            <div class="collapsible-header">
              <i class="material-icons">credit_card</i>
              TARJETA DE CRÉDITO
              <span class="new badge pink" data-badge-caption="pago(s)"><?php echo $pagosTarjetaCredito; ?></span>
            </div>
            <div class="collapsible-body">
              <?php 
              if ($pagosTarjetaCredito > 0) {
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
                    $TotalTarjetaCredito = 0;
                    while ($pagosTarjetaCreditoT = mysqli_fetch_array($joinTarjetaCredito)) {
                      $aux ++;
                      $id_cliente = $pagosTarjetaCreditoT['id_cliente'];
                      $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `clientes` WHERE id = $id_cliente"));
                      ?>
                      <tr>
                        <td><?php echo $aux; ?></td>
                        <td><?php echo $pagosTarjetaCreditoT['id_pago']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $pagosTarjetaCreditoT['tipo']; ?></td>
                        <td><?php echo $pagosTarjetaCreditoT['descripcion']; ?></td>
                        <td><?php echo $pagosTarjetaCreditoT['fecha'].' '.$pagosTarjetaCreditoT['hora']; ?></td>
                        <td>$<?php echo sprintf('%.2f', $pagosTarjetaCreditoT['cantidad']); ?></td>
                      </tr>
                      <?php 
                      $TotalTarjetaCredito += $pagosTarjetaCreditoT['cantidad'];
                    }
                    ?>
                  </tbody>
                </table>
                <input type="hidden" id="tarjetaDeCredito" name="tarjetaDeCredito" value="<?php echo $TotalTarjetaCredito; ?>" />
                <h6 class="right"><b>TOTAL TARJETA DE CRÉDITO . $<?php echo sprintf('%.2f', $TotalTarjetaCredito); ?> </b></h6><br>
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
            if ($cortes_w > 0) {
            ?>
              <ul class="collection">
                <li class="collection-item grey"><h6><b> >>> CORTES: <span class="new badge red" data-badge-caption="corte(s)"><?php echo $cortes_w; ?></span></b></h6></li>
              </ul>
                <table>
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>N°</th>
                        <th>Usuario</th>
                        <th>Fecha y Hora</th>
                        <th>Entradas</th>
                        <th>Salidas</th>
                        <th>Banco</th>
                        <th>Credito</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $aux = 0;
                      while ($corte = mysqli_fetch_array($sql_cortes)) {  
                        $id = $corte['usuario'];
                        $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = $id"));
                        $aux ++;
                        ?>
                        <tr>
                          <td><?php echo $aux; ?></td>
                          <td><?php echo $corte['id_corte']; ?></td>
                          <td><?php echo $user['firstname']; ?></td>
                          <td><?php echo $corte['fecha'].' '.$corte['hora']; ?></td>
                          <td>$<?php echo sprintf('%.2f', $corte['entradas']); ?></td>
                          <td>$<?php echo sprintf('%.2f', $corte['salidas']); ?></td>
                          <td>$<?php echo sprintf('%.2f', $corte['banco']); ?></td>
                          <td>$<?php echo sprintf('%.2f', $corte['credito']); ?></td>
                        </tr>
                        <?php 
                      }?>
                    </tbody>
                </table>
                <?php 
              }
            ?>
          </div> 
        </div>
        <!--    //////    BOTON QUE RELIZARA EL CORTE DEL USUARIO    ///////   -->
        <?php
        if ($user['cortes'] == 1) {
        ?>
          <a href="#corte" onclick="corteTipo(2);" class="waves-effect modal-trigger waves-light btn grey darken-3 left right">Relizar corte total<i class="material-icons prefix left">content_cut</i></a>
        <?php
        }
        ?>
        <a href="#corte" onclick="corteTipo(1);" class="waves-effect modal-trigger waves-light btn grey darken-3 left right">Relizar corte pagos<i class="material-icons prefix left">content_cut</i></a>   
      </div>      
    </div>
  </body>
  </main>
  </html>
<?php
}// FIN else POST
?>