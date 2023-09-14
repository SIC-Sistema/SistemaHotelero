<?php

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require '../vendor/autoload.php';

$facturama = new Facturama\Client('SANROMANPB2', 'wALA25hN');

$format = 'pdf';  //Formato del archivo a obtener(pdf,Xml,html)
$type = 'issued'; // Tipo de comprobante a obtener (payroll | received | issued)
$id = 'Afo4IFJqAirYoXw0LOB8vQ2'; // Identificador unico de la factura
$params = [];
$result = $facturama->get('cfdi/'.$format.'/'.$type.'/'.$id, $params);
$myfile = fopen('factura'.$id.'.'.$format, 'a+');
$pdf64 = base64_decode(end($result));
fwrite($myfile, base64_decode(end($result)));
fclose($myfile);
header('Content-type: application/pdf');
echo $pdf64;
printf('<pre>%s<pre>', var_export(true));
?>
