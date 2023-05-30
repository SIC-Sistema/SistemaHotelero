<?php
require_once('vendor/autoload.php');
include ('conexion.php');
$idEmisor = 1;
$idReceptor = 4;

$queryClientes = mysqli_query($conn, "SELECT * FROM facturar_compras WHERE id_compra=1");
$datosEmisor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `emisores_sat` WHERE id=$idEmisor"));
$datosReceptor = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `receptores_sat` WHERE id=$idReceptor"));

while ($clientes = mysqli_fetch_array($queryClientes)) {
   
    $arreglodinamico[] = 
        Array (
            "Cantidad" => $clientes["cantidad"],
            "CodigoUnidad" => $clientes["codigo_unidad"],
            "Unidad" => $clientes["unidad"],
            "CodigoProducto" => $clientes["codigo_producto"],
            "Producto" => $clientes["producto"],
            "PrecioUnitario" => $clientes["precio_unitario"],
            "Importe" => $clientes["importe"],
            "ObjetoDeImpuesto" => $clientes["objeto_impuesto"],
                "Impuestos" => Array ( Array (
                    "TipoImpuesto" => $clientes["tipo_impuesto"],
                    "Impuesto" => $clientes["impuesto"],
                    "Factor" => $clientes["factor"],
                    "Base" => $clientes["base"],
                    "Tasa" => $clientes["tasa"],
                    "ImpuestoImporte" => $clientes["impuesto_importe"]
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
$opcionesDecimales = "1";
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
$rfcEmisor = $datosEmisor['rfc'];
$nombreRazonSocialEmisor = $datosEmisor['razon_social'];
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
$uscoCfdiReceptor = $datosReceptor['uso_cfdi'];;
$rfcReceptor = $datosReceptor['rfc'];;
$nombreRazonSocialReceptor = $datosReceptor['razon_social'];
$regimenFiscalReceptor = $datosReceptor['razon_social'];
$calleReceptor = $datosReceptor['calle'];
$numeroExteriorReceptor = $datosReceptor['numero_exterior'];
$numeroInteriorReceptor = $datosReceptor['numero_interior'];
$coloniaReceptor = $datosReceptor['colonia'];
$localidadReceptor = $datosReceptor['localidad'];
$municipioReceptor = $datosReceptor['municipio'];
$estadoReceptor = $datosReceptor['estado_Mx'];
$paisReceptor = $datosReceptor['pais'];
$codigoPostalReceptor = $datosReceptor['codigo_postal'];
$fecha = "2023-05-07T08:03:16";
$serie = "AB";
$folio = "102";
$metodoPago = "PUE";
$formaPago = "01";
$moneda = "MXN";
$lugarExpedicion = "26015";
$subTotal = "150";
$total = "174";

/* Declaración del tercer sub-array y de sus variables usando el estándar variableName*/
$tercerSubArreglo = "Conceptos";

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

   "$tercerSubArreglo" => $arreglodinamico
);

$json = json_encode($arregloFactura);
echo $json;
/*
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
$estadoRecibido = $jsonRespuesta['estatus']['codigo'];
$mensajeRecibido = $jsonRespuesta['estatus']['descripcion'];
$errorRecibido = $jsonRespuesta['estatus']['informacionTecnica'];
$pdfBase64 = $jsonRespuesta['cfdiTimbrado']['respuesta']['pdf'];
$pdfEnconde = base64_decode($pdfBase64,true);
if (strpos($pdfEnconde, '%PDF') !== 0) {
    throw new Exception('PDF no válido');
}
if ($estadoRecibido == '000'){
    echo $mensajeRecibido;
    echo $errorRecibido;
    file_put_contents("factura$rfcReceptor.pdf", $pdfEnconde);
    header('Content-Type: application/pdf');
    echo $pdfEnconde;
}else {
    echo $mensajeRecibido;
    echo $errorRecibido;
}
*/
?>