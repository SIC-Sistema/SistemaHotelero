<?php
#INCLUIMOS EL ARCHIVO CON LA CONEXION A LA BASE DE DATPS
    include('../php/conexion.php');
    #INCLUIMOS EL ARCHIVO CON LAS LIBRERIAS DE FPDF PARA PODER CREAR ARCHIVOS CON FORMATO PDF
    include("../fpdf/fpdf.php");
    #INCLUIMOS EL PHP DONDE VIENE LA INFORMACION DEL INICIO DE SESSION
    include('is_logged.php');

    $id = $_GET['id'];;//TOMAMOS EL ID DEl SALIDA
    $salida = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM salidas WHERE id = $id"));

    #DEFINIMOS UNA ZONA HORARIA
    date_default_timezone_set('America/Mexico_City');
    $Fecha_hoy = date('Y-m-d');//CREAMOS UNA FECHA DEL DIA EN CURSO SEGUN LA ZONA HORARIA
   
class PDF extends FPDF{

    }

    $pdf = new PDF('P', 'mm', array(80,297));
    $pdf->setTitle(utf8_decode('San Roman | TICKET SALIDA'));// TITULO BARRA NAVEGACION
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
    $folio = substr(str_repeat(0, 5).$id, - 6);
    $pdf->MultiCell(70,4,utf8_decode(date_format(new \DateTime($salida['fecha'].' '.$salida['hora']), "d/m/Y H:i" ).'             FOLIO: '.$folio),0,'C',0);

    $pdf->SetY($pdf->GetY()+1);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 11);
    $pdf->MultiCell(70,4,utf8_decode('TICKET SALIDA'),0,'C',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);       
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 9);    
    $pdf->MultiCell(70,4,utf8_decode('  DESCRIPCION                                     TOTAL'),0,'L',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
    
    
    $pdf->SetY($pdf->GetY()+1);
    $pdf->SetX(6);
    $pdf->SetFont('Helvetica','', 9);
    $pdf->MultiCell(44,3,utf8_decode($salida['motivo']),0,'L',0); 
    $pdf->SetY($pdf->GetY()-3);
    $pdf->SetX(50);
    $pdf->MultiCell(24,3,utf8_decode('$'.sprintf('%.2f',$salida['cantidad'])),0,'R',0);

    $id_user = $salida['usuario'];// ID DEL USUARIO AL QUE SE LE APLICO EL CORTE
    
    $pdf->SetY($pdf->GetY()+1);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
    $pdf->SetFont('Helvetica','B', 9);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->MultiCell(30,4,utf8_decode('TOTAL:'),0,'R',0);    
    $pdf->SetY($pdf->GetY()-3);
    $pdf->SetX(35);
    $pdf->MultiCell(39,4,utf8_decode('$'.sprintf('%.2f',$salida['cantidad'])),0,'R',0);
   
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

    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->MultiCell(70,5,utf8_decode("\n"."\n"."\n"."\n".'__________________________________'."\n".'Nombre y Firma'),1,'C',0);

    $pdf->Output('PAGO','I');
?>