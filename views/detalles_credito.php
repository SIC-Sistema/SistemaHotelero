<!DOCTYPE html>
<html>
<head>
	<title>San Roman | Credito de Cliente</title>
  <?php
  include('fredyNav.php');
  ?>
  <script>
     function showContent() {
          element2 = document.getElementById("content2");
          if (document.getElementById('bancoA').checked==true) {
              element2.style.display='block';
          } else {
              element2.style.display='none';
          }    
      };
    //FUNCION QUE ENVIA LA INFORMACION PARA INSERTAR EL ABONO
    function insert_abono(){    
      var textoCantidad = $("input#cantidad").val();
      var textoDescripcion = $("input#descripcion").val();
      var textoIdCliente = $("input#id_cliente").val();
      var referenciaB = $("input#referenciaB").val();

      if(document.getElementById('bancoA').checked==true){
        textoTipo_Campio = "Banco";
      }else{
        textoTipo_Campio = "Efectivo";
      }

      if (textoCantidad == "" || textoCantidad ==0) {
        M.toast({html:"El campo Cantidad se encuentra vacío o en 0.", classes: "rounded"});
      }else if (textoDescripcion == "") {
        M.toast({html:"El campo Descripción esta vacio.", classes: "rounded"});
      }else if ((document.getElementById('bancoA').checked==true) && referenciaB == "") {
        M.toast({html: 'Los pagos en banco deben de llevar una referencia.', classes: 'rounded'});
      }else{
        //MEDIANTE EL METODO POST ENVIAMOS UN ARRAY CON LA INFORMACION AL ARCHIVO EN LA DIRECCION "../php/control_dinero.php"
        $.post("../php/control_dinero.php", { 
          //Cada valor se separa por una ,
            valorTipo_Campio: textoTipo_Campio,
            referenciaB: referenciaB,
            valorCantidad: textoCantidad,
            valorDescripcion: textoDescripcion,
            valorIdCliente: textoIdCliente,
            accion: 2,
        }, function(mensaje) {
          //SE CREA UNA VARIABLE LA CUAL TRAERA EN TEXTO HTML LOS RESULTADOS QUE ARROJE EL ARCHIVO AL CUAL SE LE ENVIO LA INFORMACION "control_dinero.php"
            $("#mostrar_abonos").html(mensaje);   
        });
      }// FIN else
    }//FIN function
  </script>
</head>
<?php
if (isset($_GET['id_cte']) == false) {
  ?>
  <script>    
    M.toast({html: "Regresando a clientes.", classes: "rounded"})
    setTimeout("location.href='clientes.php'", 8000);
  </script>
  <?php
}else{
$id_cte = $_GET['id_cte'];
$user_id = $_SESSION['user_id'];
?>
<body>
	<div class="container" >
  <?php 
  $sql = mysqli_query($conn,"SELECT * FROM clientes WHERE id = $id_cte");
  $cliente = mysqli_fetch_array($sql);

  // SACAMOS LA SUMA DE TODAS LAS DEUDAS Y ABONOS ....
  $deuda = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(cantidad) AS suma FROM deudas WHERE id_cliente = $id_cte"));
  $abono = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(cantidad) AS suma FROM pagos WHERE id_cliente = $id_cte AND tipo = 'Abono Credito'"));
  //COMPARAMOS PARA VER SI LOS VALORES ESTAN VACOIOS::
  $deuda['suma'] = ($deuda['suma'] == "")? 0 : $deuda['suma'];
  $abono['suma'] = ($abono['suma'] == "")? 0 : $abono['suma'];
  //SE RESTAN DEUDAS DE ABONOS Y SI EL SALDO ES NEGATIVO SE CAMBIA DE COLOR
  $Saldo = $abono['suma']-$deuda['suma'];
  $color = 'green';
  if ($Saldo < 0) {
    $color = 'red darken-2';
  }
  ?>
    <div id="mostrar_abonos"></div>
		<div class="row">
			<h2 class="hide-on-med-and-down">Credito de Cliente:</h2>
 			<h4 class="hide-on-large-only">Credito de Cliente:</h4>
		</div>
		<div class="row">
			<ul class="collection">
            <li class="collection-item avatar">
              <img src="../img/cliente.png" alt="" class="circle">
              <span class="title"><b>No. Cliente: </b><?php echo $id_cte; ?></span>
              <p class="row col s12"><b>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">N° CLIENTE: </b><?php echo $id_cte;?></div>
                  <div class="col s12"><b class="indigo-text">NOMBRE: </b><?php echo $cliente['nombre'];?></div>              
                  <div class="col s12"><b class="indigo-text">RFC: </b><?php echo $cliente['rfc'];?></div>              
                  <div class="col s12"><b class="indigo-text">TELEFONO: </b><?php echo $cliente['telefono'];?></div>                
                </div>
                <div class="col s12 m4">
                  <div class="col s12"><b class="indigo-text">EMAIL: </b><?php echo $cliente['email'];?></div>              
                  <div class="col s12"><b class="indigo-text">LIMPIEZA: </b><?php echo $cliente['limpieza'];?></div>              
                  <div class="col s12"><b class="indigo-text">CODIGO POSTAL: </b><?php echo $cliente['cp'];?></div>               
                </div>
                <div class="col s12 m4">       
                  <div class="col s12"><b class="indigo-text">DIRECCION: </b><?php echo $cliente['direccion'];?></div>              
                  <div class="col s12"><b class="indigo-text">COLONIA: </b><?php echo $cliente['colonia'];?></div>              
                  <div class="col s12"><b class="indigo-text">LOCALIDAD: </b><?php echo $cliente['localidad'];?></div>              
                </div>
                <hr class="col s12">
                <div class="col s12">       
                  <b>SALDO: </b> <span class="new badge <?php echo $color ?>" id="mostrar_deuda" data-badge-caption="">$<?php echo $Saldo; ?></span><br>          
                </div>
              </b></p>
            </li>
        </ul>		
		</div>
    <div class="row">
      <h3 class="hide-on-med-and-down">Abonar:</h3>
      <h5 class="hide-on-large-only">Abonar:</h5>
    </div>
    <div class="row">
      <form class="col s12">        
        <div class="row col s12 m5 l3">
          <div class="input-field">
            <i class="material-icons prefix">payment</i>
            <input id="cantidad" type="number" class="validate" data-length="6" value="0" required>
            <label for="cantidad">Cantidad:</label>
          </div>
        </div>
        <div class="row col s12 m7 l5">
          <div class="input-field">
            <i class="material-icons prefix">description</i>
            <input id="descripcion" type="text" class="validate" data-length="100" required>
            <label for="descripcion">Descripción: </label>
          </div>
        </div>
        <div class="col s6 m2">
          <p>
            <br>
            <label>
              <input type="checkbox" id="bancoA" onchange="javascript:showContent()"/>
              <span for="bancoA">Banco</span>
            </label>
          </p>
        </div>
        <div class="col s6 m2 " id="content2" style="display: none;">
          <div class="input-field">
              <input id="referenciaB" type="text" class="validate" required>
              <label for="referenciaB">Referencia:</label>
          </div>
        </div>
        <input id="id_cliente" value="<?php echo htmlentities($id_cte);?>" type="hidden">
      </form>
      <a onclick="insert_abono();" class="waves-effect waves-light btn grey darken-4 right"><i class="material-icons right">send</i>Registrar Abono</a>
      <br>
    </div>
    <div class="row">
      <div class="col s12 m6 l6">
        <h4>Deudas: </h4>
        <table>
          <thead>
            <tr>
              <th>Id Deuda</th>
              <th>Cantidad</th>
              <th>Fecha</th>
              <th>Descripcion</th>
              <th>Usuario</th>
              <th>Liquid.</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $deudas = mysqli_query($conn, "SELECT * FROM deudas WHERE id_cliente = $id_cte");
            $aux = mysqli_num_rows($deudas);
            if ($aux > 0) {
              while ($resultados = mysqli_fetch_array($deudas)) {
                $id_user = $resultados['usuario'];
                $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id_user'"));
                ?>
                <tr>
                  <td><b><?php echo $resultados['id_deuda'];?></b></td>         
                  <td>$<?php echo $resultados['cantidad'];?></td>
                  <td><?php echo $resultados['fecha_deuda'];?></td>
                  <td><?php echo $resultados['descripcion'];?></td>
                  <td><?php echo $user['firstname'];?></td>
                  <td><?php echo ($resultados['liquidada'] == 1)?'<span class="new badge green" data-badge-caption=""></span>':'<span class="new badge red" data-badge-caption=""></span>';?></td>
                </tr>
                <?php 
              }//fin while
            }else{
              echo "<center><b><h4>Este cliente aún no ha registrado Deudas</h4></b></center>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="col s12 m6 l6">
        <h4>Abonos: </h4>
        <table >
          <thead>
            <tr>
              <th>Id Abono</th>
              <th>Cantidad</th>
              <th>Fecha</th>
              <th>Descripcion</th>
              <th>Usuario</th>
            </tr>
          </thead>
          <tbody>
           <?php
            $abonos = mysqli_query($conn, "SELECT * FROM pagos WHERE id_cliente = $id_cte AND tipo = 'Abono Credito'");
            $aux = mysqli_num_rows($abonos);
            if ($aux > 0) {
              while ($resultados = mysqli_fetch_array($abonos)) {
                $id_user = $resultados['id_user'];
                $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id_user'"));
                ?>
                <tr>
                  <td><b><?php echo $resultados['id_pago'];?></b></td>         
                  <td>$<?php echo $resultados['cantidad'];?></td>
                  <td><?php echo $resultados['fecha'];?></td>
                  <td><?php echo $resultados['descripcion'];?></td>
                  <td><?php echo $user['firstname'];?></td>
                </tr>
                <?php 
              }//fin while
            }else{
              echo "<center><b><h4>Este cliente aún no ha registrado Abonos</h4></b></center>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row"><br>
      <h3 class="hide-on-med-and-down">ESTADO DE CUENTA:</h3>
      <h5 class="hide-on-large-only">ESTADO DE CUENTA:</h5>
    </div>
    <div class="row">
        <div class="col s12 l4 m4">
            <label for="fecha_de_estado">De:</label>
            <input id="fecha_de_estado" type="date" >    
        </div>
        <div class="col s12 l4 m4">
            <label for="fecha_a_estado">A:</label>
            <input id="fecha_a_estado" type="date" >
        </div>  
        <div><br>
          <button class="btn waves-light waves-effect right grey darken-3" onclick="estado_X_fecha(<?php echo $no_cliente; ?>);">ESTADO CUENTA<i class="material-icons prefix right">print</i></button>
        </div>
    </div><br><br><br><br>
    <div id="mostrar_resultado"></div>
	</div>
</body>
<?php 
}
?>
</html>