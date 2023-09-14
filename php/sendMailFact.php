<?php
	require '../vendor/autoload.php';

$facturama = new Facturama\Client('SANROMANPB2', 'wALA25hN');
	$body = [];
	$params = [
	'cfdiType' => 'issued',
	'cfdiId' => 'Afo4IFJqAirYoXw0LOB8vQ2',
	'email' => 'domin',
	];

	$result = $facturama->post('Cfdi', $body, $params);
	printf('<pre>%s<pre>', var_export($result, true));
?>