<?php
//ARCHIVO QUE DETECTA QUE PODAMOS USAR ESTE ARCHIVO SOLO SI HAY ALGUNA SESSION ACTIVA O INICIADA
include("is_logged.php");
// INCLUIMOS EL ARCHIVO CON LA CONEXION A LA BD PARA HACER CONSULTAS
include('../php/conexion.php');
//SE INCLUYE EL ARCHIVO QUE CONTIENEN LAS LIBRERIAS FPDF PARA CREAR ARCHIVOS PDF
include("../fpdf/fpdf.php");

class PDF extends FPDF{
   //Cabecera de página
   function Header(){ 
	   
   }

   //Pie de pagina 
   function footer(){
		$this->SetFont('Helvetica','', 10);
		$this->SetFillColor(35, 35, 35);
		$this->SetDrawColor(35, 35, 35);
		$this->SetTextColor(255, 255, 255);
		$this->SetY(-30);
		$this->SetX(0);
	 	$this->SetFont('Helvetica', 'B', 13);
		$this->MultiCell(216,10,utf8_decode('    Siguenos en:                                                                                              Estamos ubicados en:'),0,'C',1);
		$this->SetX(0);
	   $this->MultiCell(18,15,utf8_decode(' '."\n".' '),1,'C',1);
	   $this->SetY(-20);
		$this->SetX(18);
	   $this->SetFont('Helvetica', '', 10);
		$this->Image('../img/icon-facebook.png', 5, 259, 10, 10, 'png'); /// LOGO FACEBOOK
	   $this->MultiCell(145,8,utf8_decode('HOTEL SAN ROMAN'."\n".' '."\n".' '),1,'L',1);
	   $this->SetY(-20);
	   $this->SetX(160);
	   $this->MultiCell(56,6,utf8_decode('Lazaro Cardenas #13, Sombrerete, Mexico'."\n".' '),1,'L',1);
	   $this->SetY(-10);
	   $this->SetX(160);
	   $this->AliasNbPages('tpagina');
	   $this->Cell(56,10,utf8_decode($this->PageNo().'/tpagina'),1,0,'R',1);
   }
}

//Creación del objeto de la clase heredada
$pdf=new PDF('P','mm','letter', true);
$pdf->SetAutoPageBreak(true, 35);
$pdf->AliasNbPages();
$pdf->SetMargins(15, 35, 10);
$pdf->setTitle(utf8_decode('San Roman | Mantenimientos '));// TITULO BARRA NAVEGACION
$pdf->AddPage('portrait', 'letter');

$pdf->SetFont('Helvetica','B', 12);
$pdf->Image('../img/logoCompleto.png', 25, 7, 45, 45, 'png'); /// LOGO SIC
/////   RECUADRO DERECHO  FECHA  //////
$pdf->SetFillColor(35, 35, 35);
$pdf->SetDrawColor(35, 35, 35);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetY($pdf->GetY()-15);
$pdf->SetX(120);
$pdf->Cell(70,8,utf8_decode('Limpieza: '),1,0,'C',1);
$pdf->SetY($pdf->GetY()+8);
$pdf->SetX(120);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->Cell(35,10,utf8_decode('Fecha Impresión:'),1,0,'C');
$pdf->SetY($pdf->GetY());
$pdf->SetX(155);
$pdf->SetFont('Helvetica', '', 10);
$pdf->Cell(35,10,date('Y-m-d'),1,0,'C');
$pdf->Ln();
/////   RECAUADRO AZUL DEL CENTRO   ////////
$pdf->SetY($pdf->GetY()+10);
$pdf->SetFillColor(35, 35, 35);
$pdf->SetDrawColor(35, 35, 35);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Helvetica', 'B', 14);
$pdf->MultiCell(0,9,utf8_decode('HOTEL SAN ROMAN'."\n"),0,'C',1);
$pdf->SetFont('Helvetica', '', 10);
$pdf->SetY($pdf->GetY());
$pdf->MultiCell(0,6,utf8_decode('Telefono: 433 104 6366'),0,'C',1);
$pdf->SetY($pdf->GetY());

////   TITULO ANTES DE TABLA  ///////
$pdf->SetTextColor(35, 35, 35);
$pdf->SetY($pdf->GetY()+6);

////   TABLA A MOSTRAR    //////
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Helvetica', 'B', 13);
$pdf->MultiCell(0,11,utf8_decode('MANTENIMIENTOS:'),0,'C',1);
$pdf->SetY($pdf->GetY());
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(10,8,utf8_decode('N°'),1,0,'C');
$pdf->Cell(53,8,utf8_decode('Habitación'),1,0,'C');
$pdf->Cell(108,8,utf8_decode('Descripción'),1,0,'C');
$pdf->Cell(20,8,utf8_decode('Fecha'),1,0,'C');

////   CONTENIDO DE LA TABLA    /////
$pdf->SetFont('Helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0);
$pdf->Ln();
//VARIABLE $aux PARA EL CONTADOR DE ELEMENTOS
$aux = 1;

//AQUÍ SE MUESTRA EL CONTENIDO DE LA TABLA ------->

//VARIABLE $actividade PARA TRAER TODO EL CONTENIDO DE LA TABLA DEL ALMACEN DEL USUARIO
$Actividades = mysqli_query($conn,"SELECT * FROM `mantenimientos` WHERE estatus = 0");

// SOLO RECORRE LAS Actividades DE LIMPIEZA
while($actividad = mysqli_fetch_array($Actividades)){ 
    $id_habitacion = $actividad['id_habitacion'];
    $habitacion = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `habitaciones` WHERE id=$id_habitacion"));

    $pdf->SetX(15);
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->MultiCell(10,8,utf8_decode($actividad['id']),1,'C',0);
    $pdf->SetY($pdf->GetY()-8);
    $pdf->SetX(25);
    $pdf->MultiCell(53,8,utf8_decode($habitacion['id'].'. '.$habitacion['descripcion']),1,'C',0);
    $pdf->SetY($pdf->GetY()-8);
    $pdf->SetX(78);
    $pdf->MultiCell(108,8,utf8_decode($actividad['descripcion']),1,'C',0);   
    $pdf->SetY($pdf->GetY()-8);
    $pdf->SetX(186);
    $pdf->MultiCell(20,8,utf8_decode($actividad['fecha']),1,'C',0);
    $aux ++;
}//FIN WHILE CATALOGO

//Aquí escribimos lo que deseamos mostrar... (PRINT)
$pdf->Output();
?>