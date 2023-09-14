<?php

require '../vendor/autoload.php';
include ('conexion.php');
$valorDvisionTotal = 0.8403361344537815;
$valorIva = 0.16;
$valorIvaF = "0.160000";
$valorIsh = 0.03;
$tasaIsh = "3";
$idEmisor = 1;
$idReceptor = $conn->real_escape_string($_POST['valorReceptor']);
$idReservacion = $conn->real_escape_string($_POST['valorReservacion']);
$idFormaPago = $conn->real_escape_string($_POST['valorFormaPago']);
$idMetodoPago = $conn->real_escape_string($_POST['valorMetodoPago']);

date_default_timezone_set('America/Monterrey'); 
$fecha1 = date('Y/m/d');
$hora = date('H:i:s');
$queryImpuestos = mysqli_query ($conn, "SELECT * FROM reservaciones INNER JOIN habitaciones 
ON reservaciones.id_habitacion = habitaciones.id INNER JOIN unidades 
ON habitaciones.idunidad = unidades.idunidad WHERE reservaciones.id = $idReservacion");
$queryReservacion = mysqli_query($conn, "SELECT * FROM reservacion WHERE id_compra=$idReservacion");
$datosEmisor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `emisores_sat` WHERE id=$idEmisor"));
$datosReceptor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `receptores_sat` WHERE id=$idReceptor"));
$datosFormaPago = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `formas_pago_sat` WHERE id=$idFormaPago"));
$datosMetodoPago = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `metodos_pago_sat` WHERE id=$idMetodoPago"));
function round_up($number, $precision)
{
    $fig = pow(10, $precision);
    return (ceil($number * $fig) / $fig);
}

while ($datosReservacion = mysqli_fetch_array($queryImpuestos)) {
    $cantidad = 1;
    $idCliente = $datosReservacion['id_cliente'];
    $totalFactura = $datosReservacion['total'];
    $precioUnitarioSinFormato =  $datosReservacion['total'] * $valorDvisionTotal;
    $precioUnitario = number_format($precioUnitarioSinFormato,2,'.', '');
    $importe = $cantidad * $precioUnitario;
    $ivaImporte = number_format($importe * $valorIva,2,'.', '');
    $ishImporte = number_format($importe * $valorIsh,2,'.', '');
    $arreglodinamico[] = 
      Array  (
            "ProductCode" => $datosReservacion["codigo_producto"],
            "Description" => $datosReservacion["producto"],
            "Unit" => $datosReservacion["nombre"],
            "UnitCode" => $datosReservacion["clave"],
            "UnitPrice" => $precioUnitario,
            "Quantity" => $cantidad,
			"Subtotal" => $importe,
			"TaxObject" => "02",
			"Taxes" => [
                  [
                     "Total" => $ivaImporte, 
                     "Name" => "IVA", 
                     "Base" => $importe, 
                     "Rate" => 0.16, 
                     "IsRetention" => "false",
					 "IsFederalTax" => "true"					 
                  ],
				  [
                     "Total" => $ishImporte, 
                     "Name" => "ISH", 
                     "Base" => $importe, 
                     "Rate" => 0.03, 
                     "IsRetention" => "false",
					 "IsFederalTax" => "false"
                  ],
			
			],
            "Total" => number_format($importe + $ivaImporte +$ishImporte,2,'.', ''),
        );
}
$facturama = new Facturama\Client('SANROMANPB2', 'wALA25hN');

$params = [
   "NameId" => "1", 
   "Currency" => "MXN", 
   "Folio" => "18", 
   "Serie" => "Habitacion", 
   "CfdiType" => "I", 
   "PaymentForm" => $datosFormaPago['clave'], 
   "PaymentMethod" => $datosMetodoPago['clave'], 
   "OrderNumber" => $idReservacion, 
   "ExpeditionPlace" => "99100", 
   "Date" => "2023-08-16T12:56:02-06:00", 
   "PaymentConditions" => "", 
   "Observations" => "", 
   "Exportation" => "01", 
   "Receiver" => [
         "Rfc" => "URE180429TM6", 
         "CfdiUse" => "CP01", 
         "Name" => "UNIVERSIDAD ROBOTICA ESPAÑOLA", 
         "FiscalRegime" => "601", 
         "TaxZipCode" => "86991" 
      ], 
   "Items" => $arreglodinamico,
            
          
];

$jsonCompleto = json_encode($params);
echo $jsonCompleto;

$result = $facturama->post('3/cfdis', $params);
//$respuestaJSON = var_export($result, true);
$idFacturaRecibido =  get_object_vars($result)['Id'];

//echo $estadoRecibido;
if (!isset($idFacturaRecibido)){
    echo '<script>
        errorTimbrado();
    </script>';	   
}else{
   $uuid = "NA";
   $total = $importe + $ivaImporte + $ishImporte;
   $pdfBase64 = "NA";
    $insertFactura = "INSERT INTO `facturas_generadas` (id_receptor, id_reservacion, id_cliente,  pdf64, fecha, hora, uuid, total, IdFactura) 
    VALUES('$idReceptor', '$idReservacion', '$idCliente', '$pdfBase64', '$fecha1', '$hora', '$uuid', '$total', '$idFacturaRecibido')";
    if(mysqli_query($conn, $insertFactura)){
        $updateReservacion = "UPDATE `reservaciones` SET  facturado = 1 WHERE id = '$idReservacion'";
        if(mysqli_query($conn, $updateReservacion)){
           
			$format = 'pdf';  //Formato del archivo a obtener(pdf,Xml,html)
			$type = 'issued'; // Tipo de comprobante a obtener (payroll | received | issued)
			$id = $idFacturaRecibido; // Identificador unico de la factura
			$params2 = [];
			$result2 = $facturama->get('cfdi/'.$format.'/'.$type.'/'.$id, $params2);
			$pdf64 = end($result2);
			$updateFacturaGenerada = "UPDATE `facturas_generadas` SET  pdf64 ='$pdf64' WHERE IdFactura = '$idFacturaRecibido'";
			$jsonEncodePdf = json_encode(end($result2));
            echo '<script>
                imprimirCfdi('.$jsonEncodePdf.')
            </script>';
			if (mysqli_query($conn, $updateFacturaGenerada)){
				$body = [];
				$params3 = [
					'cfdiType' => 'issued',
					'cfdiId' => $idFacturaRecibido,
					'email' => 'ulises.dominguez@sicsom.com',
				];

				$result3 = $facturama->post('Cfdi', $body, $params3);
				$estadoCorreo =  get_object_vars($result3)['success'];
			}else{
				echo '<script>
					errorTimbrado();
				</script>';	
			}
        }else{
            echo '<script>
                errorTimbrado();
            </script>';	  
        }
    }else{
        echo '<script>
            errorTimbrado();
        </script>';	
    }
}

//printf('<pre>%s<pre>', var_export($result, true));
?>
