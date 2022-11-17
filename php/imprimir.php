<?php
#INCLUIMOS EL ARCHIVO CON LA CONEXION A LA BASE DE DATPS
    include('../php/conexion.php');
    #INCLUIMOS EL ARCHIVO CON LAS LIBRERIAS DE FPDF PARA PODER CREAR ARCHIVOS CON FORMATO PDF
    include("../fpdf/fpdf.php");
    #INCLUIMOS EL PHP DONDE VIENE LA INFORMACION DEL INICIO DE SESSION
    include('is_logged.php');

    $id_pago = $_GET['id'];;//TOMAMOS EL ID DEl PAGO
    $pago = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pagos WHERE id_pago = $id_pago"));

    #DEFINIMOS UNA ZONA HORARIA
    date_default_timezone_set('America/Mexico_City');
    $Fecha_hoy = date('Y-m-d');//CREAMOS UNA FECHA DEL DIA EN CURSO SEGUN LA ZONA HORARIA
   
class PDF extends FPDF{

    }

    $pdf = new PDF('P', 'mm', array(80,297));
    $pdf->setTitle(utf8_decode('San Roman | TICKET PAGO'));// TITULO BARRA NAVEGACION
    $pdf->AddPage();

    $pdf->Image('../img/logo.png', 30, 2, 20, 24, 'png'); /// LOGO San Roman

    /// INFORMACION DE LA EMPRESA ////
    $pdf->SetFont('Courier','B', 8);
    $pdf->SetY($pdf->GetY()+18);
    $pdf->SetX(5);
    $pdf->MultiCell(70,3,utf8_decode('HOTEL SAN ROMAN'."\n".'RUBY ROMAN'."\n".'RFC: RORU97102759A'."\n".'LAZARO CARDENAS 13 COL CENTRO C.P. 99100 SOMBRERETE, ZACATECAS '."\n".'TEL. 4331033890'),0,'C',0);

    /// INFORMACION DEL PAGO
    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 10);    
    $folio = substr(str_repeat(0, 5).$id_pago, - 6);
    $pdf->MultiCell(70,4,utf8_decode(date_format(new \DateTime($pago['fecha'].' '.$pago['hora']), "d/m/Y H:i" ).'             FOLIO: '.$folio),0,'C',0);

    $pdf->SetY($pdf->GetY()+1);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 11);
    $pdf->MultiCell(70,4,utf8_decode('TICKET PAGO'),0,'C',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0); 
    /// INFORMACION DEL CLIENTE
    $id_cliente = $pago['id_cliente'];
    $cliente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM clientes WHERE id = $id_cliente"));
     
    $pdf->SetY($pdf->GetY()+2);
    $pdf->SetX(5);
    $pdf->SetFont('Courier','B', 9);
    $pdf->MultiCell(70,4,utf8_decode('N° CLIENTE: '.$id_cliente."\n".'NOMBRE:  '.$cliente['nombre']."\n".'RFC:  '.$cliente['rfc']."\n".'TELEFONO:  '.$cliente['telefono']),0,'L',0);

    $pdf->SetY($pdf->GetY()+2);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
    // INFORMACION DEL PAGO
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 11);
    $pdf->MultiCell(70,4,utf8_decode('TIPO: '.$pago['tipo']),0,'C',0);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);       
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 9);    
    $pdf->MultiCell(70,4,utf8_decode(' DESCRIPCION             T.CAMBIO      TOTAL'),0,'L',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
    
    
    $pdf->SetY($pdf->GetY()+1);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 9);
    $pdf->MultiCell(35,3,utf8_decode($pago['descripcion']),0,'L',0);
    $pdf->SetY($pdf->GetY()-3);
    $pdf->SetX(40);
    $pdf->MultiCell(14,3,utf8_decode($pago['tipo_cambio']),0,'R',0);    
    $pdf->SetY($pdf->GetY()-3);
    $pdf->SetX(55);
    $pdf->MultiCell(20,3,utf8_decode('$'.sprintf('%.2f',$pago['cantidad'])),0,'R',0);

    $id_user = $pago['id_user'];// ID DEL USUARIO AL QUE SE LE APLICO EL CORTE
    
    $pdf->SetY($pdf->GetY()+2);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
   
    
    $pdf->SetFont('Helvetica','', 9);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->MultiCell(30,4,utf8_decode('IVA:'."\n".'SUBTOTAL:'."\n".'TOTAL:'),0,'R',0);    
    $pdf->SetY($pdf->GetY()-12);
    $pdf->SetX(35);
    $pdf->MultiCell(40,4,utf8_decode('$'.sprintf('%.2f',$pago['cantidad']*0.16)."\n".'$'.sprintf('%.2f',$pago['cantidad']-($pago['cantidad']*0.16))."\n".'$'.sprintf('%.2f',$pago['cantidad'])),0,'R',0);
    
    $pdf->SetY($pdf->GetY()+6);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 10);      
    #TOMAMOS LA INFORMACION DEL USUARIO QUE ESTA LOGEADO QUIEN HIZO LOS COBROS
    $usuario = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $id_user"));  
    $pdf->MultiCell(70,4,utf8_decode('LE ATENDIO: '.$usuario['firstname'].' '.$usuario['lastname']),0,'C',0);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    if ($pago['tipo_cambio'] == 'Credito') {
        $pdf->SetY($pdf->GetY()+3);
        $pdf->SetX(5);
        $pdf->SetFont('Helvetica','', 10);
        $pdf->MultiCell(70,5,utf8_decode("\n"."\n"."\n".'__________________________________'."\n".'Nombre y Firma (Cliente)'),1,'C',0);
    }

    $pdf->SetY($pdf->GetY()+3);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
    $pdf->SetY($pdf->GetY()+2);
    $pdf->SetX(5);    
    $pdf->SetFont('Helvetica','B', 10);      
    $pdf->MultiCell(70,4,utf8_decode('¡GRACIAS POR TU PAGO!'."\n"),0,'C',0);
    $pdf->SetY($pdf->GetY()+2);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->SetX(5);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    $pdf->Output('PAGO','I');
?>