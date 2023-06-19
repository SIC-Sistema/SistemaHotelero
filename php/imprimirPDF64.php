<?php
    include ('conexion.php');
    $datosFactura = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `facturar_compras` 
    WHERE id=2"));
    $estadoRecibido = "000";
    $rfcReceptor = "DOBU921217";
    $pdfBase64 = $datosFactura['base64Pdf'];
    $pdfEnconde = base64_decode($pdfBase64,true);
    if (strpos($pdfEnconde, '%PDF') !== 0) {
    throw new Exception('PDF no válido');
}
if ($estadoRecibido == '000'){
    //file_put_contents("factura$rfcReceptor.pdf", $pdfEnconde);
    header('Content-Type: application/pdf');
    echo $pdfEnconde;
}else {
   echo "error";
   
}
?>