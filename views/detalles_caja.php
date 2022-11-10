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
    ?>    
  </head>
  <main>
  <body>
    <div class="container">
      <div class="row"><br>
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
              <b>Entradas: </b><?php echo $user_id;?><br>               
              <b>Salidas: </b><?php echo $user_id;?><br>  
              <hr>             
              <b class="indigo-text">TOTAL EFECTIVO: </b><?php echo $user_id;?><br>               
              <b class="indigo-text">TOTAL A BANCO: </b><?php echo $user_id;?><br>               
              <b class="indigo-text">TOTAL A CREDITO: </b><?php echo $user_id;?><br>               
              
            </li>
        </ul>
      </div>
    </div>
  </body>
  </main>
  </html>
<?php
}// FIN else POST
?>