<?php 

include('../php/conexion.php');
include('is_logged.php');
date_default_timezone_set('America/Mexico_City');
$id_user = $_SESSION['user_id'];
$Fecha_hoy = date('Y-m-d');

$Accion = $conn->real_escape_string($_POST['accion']);
switch ($Accion) {
    case 1:
    	$Texto = $conn->real_escape_string($_POST['texto']);
		if ($Texto != "") {
			$consulta ="SELECT *, facturas_generadas.id AS id_factura FROM 
			facturas_generadas INNER JOIN receptores_sat ON facturas_generadas.id_receptor = 
			receptores_sat.id WHERE receptores_sat.rfc LIKE '%$Texto%'";
			$consultaJoin = mysqli_query($conn, $consulta);		
			$contenido = '';
			if (mysqli_num_rows($consultaJoin) == 0) {
				echo '<script>M.toast({html:"No se encontraron resultados.", classes: "rounded"})</script>';
			} else {
				while($factura = mysqli_fetch_array($consultaJoin)) {
					$pdf64 = $factura['pdf64'];
					$pdfJsonEncode = json_encode($pdf64);
					$contenido .= '			
					  <tr>
						<td>'.$factura['rfc'].'</td>
						<td>'.$factura['razon_social'].'</td>
						<td>'.$factura['id_reservacion'].'</td>
						<td>'.$factura['fecha']. " ".$factura['hora'].'</td>
						<td>'."$". $factura['total'].'</td>
						<td><a onclick="reimprimirCfdi('.$factura['id_factura'].')" class="btn-small blue darken-2 waves-effect waves-light"><i class="material-icons">print</i></a></td>
					  </tr>';
				}
			}	
			echo $contenido;	
		}else{
			echo '<script>M.toast({html: "Escriba un RFC.", classes: "rounded"})</script>';
		}
        break;
		case 2:
			$idFactura = $conn->real_escape_string($_POST['valorIdFactura']);
			$factura = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `facturas_generadas` 
			WHERE id = $idFactura"));
			$jsonEncodePdf = json_encode($factura['pdf64']);		
			echo '<script>
				imprimirCfdi('.$jsonEncodePdf.')
			</script>';
		break;
}