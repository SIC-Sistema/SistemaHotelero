<?php
require_once('vendor/autoload.php');
include ('conexion.php');
$valorDvisionTotal = 0.8403361344537815;
$valorIva = 0.1600;
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
    $precioUnitario = bcdiv($precioUnitarioSinFormato, 1, 2);;
    $importe = $cantidad * $precioUnitario;
    $ivaImporte = round_up($importe * $valorIva, 2);
    $ishImporte = round_up($importe * $valorIsh, 2);
    $arreglodinamico[] = 
        Array  (
            "Cantidad" => $cantidad,
            "CodigoUnidad" => $datosReservacion["clave"],
            "Unidad" => $datosReservacion["nombre"],
            "CodigoProducto" => $datosReservacion["codigo_producto"],
            "Producto" => $datosReservacion["producto"],
            "PrecioUnitario" => $precioUnitario,
            "Importe" => $importe,
            "ObjetoDeImpuesto" => "02",
                "Impuestos" => Array ( Array (
                    "TipoImpuesto" => "1",
                    "Impuesto" => "2",
                    "Factor" => "1",
                    "Base" => $importe,
                    "Tasa" => $valorIvaF,
                    "ImpuestoImporte" => $ivaImporte
                )
                )
        );
}

/*Declaración del cliente de Guzzle*/ 
$client = new \GuzzleHttp\Client();
/* Declaración del primer sub-array y de sus variables usando el estándar variableName*/
$primerSubArreglo = "DatosGenerales";
$version = "4.0";
$csd = $datosEmisor['csd'];
$llavePrivada = $datosEmisor['llave_privada'];
$csdPassword = $datosEmisor['csd_password'];
$generarPDF = true;
$logotipo = $datosEmisor['logotipo'];
$cfdi = "Factura";
$opcionesDecimales = "2";
$numeroDecimales = "2";
$tipoCFDI = "Ingreso";
$enviaEmail = false;
$receptorEmail = $datosReceptor['email'];
$receptorEmailCC = $datosEmisor['email']; 
$receptorEmailCCO = "";
$emailMensaje = $datosEmisor['asunto_email'];

/* Declaración del segundo sub-array y de sus variables usando el estándar variableName*/
$segundoSubArreglo = "Encabezado";
$cfdisRelacionados = "";
$tipoRelacion = "04";
$rfcEmisor = "EKU9003173C9";
$nombreRazonSocialEmisor = "ESCUELA KEMPER URGATE";
$regimenFiscalEmisor = $datosEmisor['regimen'];
$calleEmisor = $datosEmisor['calle'];
$numeroExteriorEmisor = $datosEmisor['numero_exterior'];
$numeroInteriorEmisor = $datosEmisor['numero_interior'];
$coloniaEmisor = $datosEmisor['colonia'];
$localidadEmisor = $datosEmisor['localidad'];
$municipioEmisor = $datosEmisor['municipio'];
$estadoEmisor = $datosEmisor['estado_Mx'];
$paisEmisor = $datosEmisor['pais'];
$codigoPostalEmisor = $datosEmisor['codigo_postal'];
$uscoCfdiReceptor = "G03";
$rfcReceptor = "SSF1103037F1";
$nombreRazonSocialReceptor = "SCAFANDRA SOFTWARE FACTORY, ";
$regimenFiscalReceptor = "601";
$calleReceptor = $datosReceptor['calle'];
$numeroExteriorReceptor = $datosReceptor['numero_exterior'];
$numeroInteriorReceptor = $datosReceptor['numero_interior'];
$coloniaReceptor = $datosReceptor['colonia'];
$localidadReceptor = $datosReceptor['localidad'];
$municipioReceptor = $datosReceptor['municipio'];
$estadoReceptor = $datosReceptor['estado_Mx'];
$paisReceptor = $datosReceptor['pais'];
$codigoPostalReceptor = "06470";
$fecha = "2023-06-29T09:03:16";
$serie = "";
$folio = "";
$metodoPago = $datosMetodoPago['clave'];
$formaPago = $datosFormaPago['clave'];
$moneda = "MXN";
$lugarExpedicion = "26015";
$subTotal = $importe;
$total = $importe + $ivaImporte + $ishImporte;

/* Declaración del tercer sub-array y de sus variables usando el estándar variableName*/
$tercerSubArreglo = "Conceptos";
$cuartoSubArreglo = "Complemento";

$arregloFactura = Array (
  $primerSubArreglo => Array (
      "Version" => $version,
      "CSD" => $csd,
      "LlavePrivada" => $llavePrivada,
      "CSDPassword" => $csdPassword,
      "GeneraPDF" =>  $generarPDF,
      "Logotipo" => $logotipo,
      "CFDI" => $cfdi,
      "OpcionDecimales" => $opcionesDecimales,
      "NumeroDecimales" => $numeroDecimales,
      "TipoCFDI" => $tipoCFDI,
      "EnviaEmail" => $enviaEmail,
      "ReceptorEmail" => $receptorEmail,
      "ReceptorEmailCC" => $receptorEmailCC,
      "ReceptorEmailCCO" => $receptorEmailCCO,
      "EmailMensaje" => $emailMensaje
  ),
  "$segundoSubArreglo" => Array (
      "CFDIsRelacionados" => $cfdisRelacionados,
      "TipoRelacion" => $tipoRelacion,
      "Emisor" => Array (
           "RFC" => $rfcEmisor,
           "NombreRazonSocial" => $nombreRazonSocialEmisor,
           "RegimenFiscal" => $regimenFiscalEmisor,
           "Direccion" => Array ( Array (
               "Calle" => $calleEmisor,
               "NumeroExterior" => $numeroExteriorEmisor,
               "NumeroInterior" => $numeroInteriorEmisor,
               "Colonia" => $coloniaEmisor,
               "Localidad" => $localidadEmisor,
               "Municipio" => $municipioEmisor,
               "Estado" => $estadoEmisor,
               "Pais" => $paisEmisor,
               "CodigoPostal" => $codigoPostalEmisor
            )) ,
       ),
       "Receptor" => Array (
           "RFC" => $rfcReceptor,
           "NombreRazonSocial" => $nombreRazonSocialReceptor,
           "UsoCFDI" => $uscoCfdiReceptor,
           "RegimenFiscal" => $regimenFiscalReceptor,
           "Direccion" => Array (
               "Calle" => $calleReceptor,
               "NumeroExterior" => $numeroExteriorReceptor,
               "NumeroInterior" => $numeroInteriorReceptor,
               "Colonia" => $coloniaReceptor,
               "Localidad" => $localidadReceptor,
               "Municipio" => $municipioReceptor,
               "Estado" => $estadoReceptor,
               "Pais" => $paisReceptor,
               "CodigoPostal" => $codigoPostalReceptor
           )
       ),

       "Fecha" => $fecha,
       "Serie" => $serie,
       "Folio" => $folio,
       "MetodoPago" => $metodoPago,
       "FormaPago" => $formaPago,
       "Moneda" => $moneda,
       "LugarExpedicion" => $lugarExpedicion,
       "Subtotal" => $subTotal,
       "Total" => $total    
   ),

   "$tercerSubArreglo" => $arreglodinamico,
   
   "$cuartoSubArreglo" => Array (
    "ImpuestosLocales" => 
    Array (
        Array (
            "Tipo" => "1",
            "Nombre" => "Impuesto sobre hospedaje",
            "Tasa" => $tasaIsh,
            "Importe" => "$ishImporte",
        )
    ),
     "TipoComplemento" => "5"
)
  
);

$json = json_encode($arregloFactura);
//echo $json;
$response = $client->request('POST', 'https://testapi.facturoporti.com.mx/servicios/timbrar/json', [
    'body' => $json,
    'headers' => [
        'accept' => 'application/json',
        'authorization' => 'Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1lIjoialYrdVVUYmtWNmUxRmNZb2cvNWtGQT09IiwibmJmIjoxNjU4MzMxNjU2LCJleHAiOjE2NjA5MjM2NTYsImlzcyI6IlNjYWZhbmRyYVNlcnZpY2lvcyIsImF1ZCI6IlNjYWZhbmRyYSBTZXJ2aWNpb3MiLCJJZEVtcHJlc2EiOiJqVit1VVRia1Y2ZTFGY1lvZy81a0ZBPT0iLCJJZFVzdWFyaW8iOiJidXlaYzFMWUl5VURaSGhGR3NqaGdRPT0ifQ.5vDG7CZmLCU2wC0W6ri1mazNjfEgxVd7udxiFkhgqFw',
        'content-type' => 'application/*+json',
  ],
], 
);

$respuesta =  $response->getBody();
$jsonRespuesta = json_decode($respuesta, true);
//echo $respuesta;
$estadoRecibido = $jsonRespuesta['estatus']['codigo'];
$mensajeRecibido = $jsonRespuesta['estatus']['descripcion'];
$errorRecibido = $jsonRespuesta['estatus']['informacionTecnica'];
$pdfBase64 = $jsonRespuesta['cfdiTimbrado']['respuesta']['pdf'];
$uuid = $jsonRespuesta['cfdiTimbrado']['respuesta']['uuid'];
$pdfEnconde = base64_decode($pdfBase64,true);
if (strpos($pdfEnconde, '%PDF') !== 0) {
    echo '<script>
        errorTimbrado();
    </script>';	
}
//echo $estadoRecibido;
if ($estadoRecibido != "000"){
    echo '<script>
        errorTimbrado();
    </script>';	   
}else{
   
//echo $mensajeRecibido;
//echo $errorRecibido;
//file_put_contents("factura$rfcReceptor.pdf", $pdfEnconde);
 //header('Content-Type: application/pdf');
    $insertFactura = "INSERT INTO `facturas_generadas` (id_receptor, id_reservacion, id_cliente,  pdf64, fecha, hora, uuid, total) 
    VALUES('$idReceptor', '$idReservacion', '$idCliente', '$pdfBase64', '$fecha1', '$hora', '$uuid', '$total')";
    if(mysqli_query($conn, $insertFactura)){
        $updateReservacion = "UPDATE `reservaciones` SET  facturado = 1 WHERE id = '$idReservacion'";
        if(mysqli_query($conn, $updateReservacion)){
            $jsonEncodePdf = json_encode($pdfBase64);
            echo '<script>
                imprimirCfdi('.$jsonEncodePdf.')
            </script>';
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

?>