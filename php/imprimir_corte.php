<?php
    #INCLUIMOS TODAS LAS LIBRERIAS  DE MAILER PARA PODER ENVIAR CORREOS DE ESTE ARCHIVO
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/vendor/autoload.php';
    #INCLUIMOS EL ARCHIVO CON LA CONEXION A LA BASE DE DATPS
    include('../php/conexion.php');
    #INCLUIMOS EL ARCHIVO CON LAS LIBRERIAS DE FPDF PARA PODER CREAR ARCHIVOS CON FORMATO PDF
    include("../fpdf/fpdf.php");
    #INCLUIMOS EL PHP DONDE VIENE LA INFORMACION DEL INICIO DE SESSION
    include('is_logged.php');

    $corte = $_GET['id'];//TOMAMOS EL ID DEL CORTE PREVIAMENTE CREADO PARA¨PODERLE ASIGNAR LOS PAGOS EN EL DETALLE
    #DEFINIMOS UNA ZONA HORARIA
    date_default_timezone_set('America/Mexico_City');
    $Fecha_hoy = date('Y-m-d');//CREAMOS UNA FECHA DEL DIA EN CURSO SEGUN LA ZONA HORARIA
    #TOMAMOS LA INFORMACION DEL CORTE CON EL ID GUARDADO EN LA VARIABLE $corte QUE RECIBIMOS CON EL GET
    $Info_Corte =  mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM cortes WHERE id_corte = $corte")); 
    $user_id = $Info_Corte['usuario'];
    $datos = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id=$user_id"));
    $realizo_id = $Info_Corte['realizo'];
    $realizo = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE user_id=$realizo_id"));

    //CONTAMOS LOS PAGOS SEGUN SU TIPO
    $Liquidacion = mysqli_num_rows(mysqli_query($conn,"SELECT *  FROM detalles_corte INNER JOIN pagos ON detalles_corte.id_pago = pagos.id_pago WHERE detalles_corte.id_corte = $corte AND pagos.tipo = 'Liquidacion'" ));
    $Anticipo = mysqli_num_rows(mysqli_query($conn,"SELECT *  FROM detalles_corte INNER JOIN pagos ON detalles_corte.id_pago = pagos.id_pago WHERE detalles_corte.id_corte = $corte AND pagos.tipo = 'Anticipo'" ));
    $Abono = mysqli_num_rows(mysqli_query($conn,"SELECT *  FROM detalles_corte INNER JOIN pagos ON detalles_corte.id_pago = pagos.id_pago WHERE detalles_corte.id_corte = $corte AND pagos.tipo = 'Abono'" ));
    $AbonoCredito = mysqli_num_rows(mysqli_query($conn,"SELECT *  FROM detalles_corte INNER JOIN pagos ON detalles_corte.id_pago = pagos.id_pago WHERE detalles_corte.id_corte = $corte AND pagos.tipo = 'Abono Credito'" ));

    
    $idAnterior = $corte-1;
    if (mysqli_fetch_array(mysqli_query($conn, "SELECT  en_caja FROM cortes WHERE id_corte ='$idAnterior'"))==null) {
        while (mysqli_fetch_array(mysqli_query($conn, "SELECT  en_caja FROM cortes WHERE id_corte ='$idAnterior'"))==null) {
            $idAnterior -=1;
        }
    }

    $enCajaInicio=mysqli_fetch_array(mysqli_query($conn, "SELECT  en_caja FROM cortes WHERE id_corte ='$idAnterior'"));
    


    class PDF extends FPDF{

    }

    $pdf = new PDF('P', 'mm', array(80,297));
    $pdf->setTitle(utf8_decode('San Roman | CORTE PAGOS '));// TITULO BARRA NAVEGACION
    $pdf->AddPage();

    $pdf->Image('../img/logo.png', 30, 2, 20, 21, 'png'); /// LOGO San Roman

    /// INFORMACION DE LA EMPRESA ////
    $pdf->SetFont('Courier','B', 8);
    $pdf->SetY($pdf->GetY()+15);
    $pdf->SetX(5);
    $pdf->MultiCell(70,3,utf8_decode('HOTEL SAN ROMAN'."\n".'RUBY ROMAN'."\n".'RFC: RORU97102759A'."\n".'LAZARO CARDENAS 13 COL CENTRO C.P. 99100 SOMBRERETE, ZACATECAS '."\n".'TEL. 4331033890'),0,'C',0);
    /// INFORMACION DEL CORTE
    $pdf->SetY($pdf->GetY()+3);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 10);
    $folio = substr(str_repeat(0, 5).$corte, - 6);
    $pdf->MultiCell(70,4,utf8_decode(date_format(new \DateTime($Info_Corte['fecha'].' '.$Info_Corte['hora']), "d/m/Y H:i" ).'              FOLIO:'.$folio),0,'C',0);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 10);
    $pdf->MultiCell(70,4,utf8_decode('CORTE DE CAJA'."\n".'USUARIO: '.$datos['firstname']."\n".'REALIZO: '.$realizo['firstname']),0,'C',0);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    //////////////         TOTALES DE CANTIDADES       ////////////////
    $TotalPagos = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `detalles_corte` WHERE id_corte = $corte" ));
    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->MultiCell(70,4,utf8_decode($TotalPagos.' PAGOS TOTALES'),0,'C',0);
    $pdf->SetY($pdf->GetY()+1);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 12);

    $pdf->MultiCell(70,4,utf8_decode('======== RESUMEN ========'),0,'C',0);
    //====================================== A CAJA ==========================================================

    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->SetY($pdf->GetY()+0);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 12);
    $pdf->MultiCell(70,0,utf8_decode('----------   CAJA  ----------'),0,'C',0);

    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->MultiCell(35,4,utf8_decode('EN CAJA INICIO'."\n".'EN EFECTIVO'."\n".'SALIDAS'),0,'L',0);    
    $pdf->SetY($pdf->GetY()-12);
    $pdf->SetX(41);
    $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $enCajaInicio['en_caja'])."\n".'$'.sprintf('%.2f', $Info_Corte['entradas'])."\n".'-$'.sprintf('%.2f', $Info_Corte['salidas']).')'),0,'R',0);


    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);



    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->MultiCell(35,4,utf8_decode('DEJO EN CAJA'),0,'L',0); 
    $pdf->SetY($pdf->GetY()-4);
    $pdf->SetX(41);
    $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $Info_Corte['en_caja'])),0,'R',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 10);
    $pdf->MultiCell(35,4,utf8_decode('RETIRO'),0,'L',0); 
    $pdf->SetY($pdf->GetY()-4);
    $pdf->SetX(41);
    $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $Info_Corte['entradas']-$Info_Corte['salidas']+$Info_Corte['en_caja']-$Info_Corte['en_caja'])),0,'R',0);

    //====================================== A BANCO =============================================================

    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->SetY($pdf->GetY()+0);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 12);
    $pdf->MultiCell(70,0,utf8_decode('----------   BANCO  ----------'),0,'C',0);
    
    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->MultiCell(35,4,utf8_decode('TRANSFERENCIA'."\n".'TARJETA DEBITO'."\n".'TARJETA CREDITO'),0,'L',0);    
    $pdf->SetY($pdf->GetY()-12);
    $pdf->SetX(41);
    $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $Info_Corte['transferencia'])."\n".'$'.sprintf('%.2f', $Info_Corte['tarjeta_debito'])."\n".'$'.sprintf('%.2f', $Info_Corte['tarjeta_credito']).')'),0,'R',0);
    
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 10);
    $pdf->MultiCell(35,4,utf8_decode('TOTAL EN BANCO'),0,'L',0); 
    $pdf->SetY($pdf->GetY()-4);
    $pdf->SetX(41);
    $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $Info_Corte['transferencia']+$Info_Corte['tarjeta_debito']+$Info_Corte['tarjeta_credito'])),0,'R',0);

    //====================================== A CREDITO =========================================================

    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->SetY($pdf->GetY()+0);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 12);
    $pdf->MultiCell(70,0,utf8_decode('----------   A CRÉDITO  ----------'),0,'C',0);

    $pdf->SetY($pdf->GetY()+4);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 10);
    $pdf->MultiCell(35,4,utf8_decode('A CREDITO'),0,'L',0);    
    $pdf->SetY($pdf->GetY()-5);
    $pdf->SetX(41);
    $pdf->MultiCell(34,4,utf8_decode('($'.sprintf('%.2f', $Info_Corte['credito']).')'),0,'R',0);


    ///////      DESGOSE DE PAGOS         //////////
    $pdf->SetY($pdf->GetY()+6);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 12);
    $pdf->MultiCell(70,4,utf8_decode('==== DESGLOSE PAGOS ===='),0,'C',0);
    $CONTENIDO = '';
    $CONTENIDO .= ($Liquidacion>0) ?'LIQUIDACION                                    '.$Liquidacion."\n":'';
    $CONTENIDO .= ($Anticipo>0)?'ANTICIPO                                           '.$Anticipo."\n":'';
    $CONTENIDO .= ($Abono>0)?'ABONO                                               '.$Abono."\n":'';
    $CONTENIDO .= ($AbonoCredito>0)?'ABONO CREDITO                              '.$AbonoCredito."\n":'';
    $pdf->SetY($pdf->GetY()+2);
    $pdf->SetX(10);
    $pdf->SetFont('Helvetica','', 9);
    $pdf->MultiCell(65,4,utf8_decode($CONTENIDO),0,'L',0);
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','', 8);
    $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);

    $pdf->SetY($pdf->GetY()+5);
    $pdf->SetX(5);
    $pdf->SetFont('Helvetica','B', 12);
    $pdf->MultiCell(70,4,utf8_decode('== MOVIMIENTOS RELIZADOS =='),0,'C',0);

    //////////      DESGOSE DE MOVIMIENTOS      /////////////
    $sql_efectivo_int = mysqli_query($conn, "SELECT * FROM detalles INNER JOIN pagos ON detalles.id_pago = pagos.id_pago WHERE detalles.id_corte = $corte AND pagos.tipo_cambio = 'Efectivo' AND pagos.tipo NOT IN ('Mes-Tel', 'Min-Extra', 'Orden Servicio', 'Corte SAN', 'Dispositivo')");
    $sql_banco_int = mysqli_query($conn, "SELECT * FROM detalles INNER JOIN pagos ON detalles.id_pago = pagos.id_pago WHERE detalles.id_corte = $corte AND pagos.tipo_cambio = 'Banco' AND pagos.tipo NOT IN ('Mes-Tel', 'Min-Extra', 'Orden Servicio', 'Corte SAN', 'Dispositivo')");
    $sql_credito_int = mysqli_query($conn, "SELECT * FROM detalles INNER JOIN pagos ON detalles.id_pago = pagos.id_pago WHERE detalles.id_corte = $corte AND pagos.tipo_cambio = 'Credito' AND pagos.tipo NOT IN ('Mes-Tel', 'Min-Extra', 'Orden Servicio', 'Corte SAN', 'Dispositivo')");

    $sql_efectivo = mysqli_query($conn,"SELECT * FROM `detalles_corte` INNER JOIN `pagos` ON `detalles_corte`.`id_pago`=`pagos`.`id_pago` WHERE id_corte = $corte AND tipo_cambio = 'Efectivo' AND `pagos`.corte = 1");
    $sql_banco = mysqli_query($conn,"SELECT * FROM `detalles_corte` INNER JOIN `pagos` ON `detalles_corte`.`id_pago`=`pagos`.`id_pago` WHERE id_corte = $corte AND tipo_cambio = 'Banco'  AND `pagos`.corte = 1");
    $sql_credito = mysqli_query($conn,"SELECT * FROM `detalles_corte` INNER JOIN `pagos` ON `detalles_corte`.`id_pago`=`pagos`.`id_pago` WHERE id_corte = $corte AND tipo_cambio = 'Credito'  AND `pagos`.corte = 1");
    $sql_salidas = mysqli_query($conn,"SELECT * FROM `detalles_corte` INNER JOIN `salidas` ON `detalles_corte`.`id_salida`=`salidas`.`id` WHERE id_corte = $corte AND id_salida > 0  AND `salidas`.corte = 1");
    $sql_cortes = mysqli_query($conn,"SELECT * FROM `detalles_corte` INNER JOIN `cortes` ON `detalles_corte`.`corte`=`cortes`.`id_corte` WHERE `detalles_corte`.id_corte = $corte AND `detalles_corte`.corte > 0  AND `cortes`.corte = 1");
    $joinTransferencia = mysqli_query($conn, "SELECT * FROM detalles_corte INNER JOIN pagos ON 
        detalles_corte.id_pago = pagos.id_pago INNER JOIN referencias ON pagos.id_pago = referencias.id_pago 
        WHERE detalles_corte.id_corte = $corte AND pagos.tipo_cambio = 'Banco' AND referencias.transferencia = 1 
        AND pagos.corte = 1;");
    $joinTarjetaDebito = mysqli_query($conn, "SELECT * FROM detalles_corte INNER JOIN pagos ON 
        detalles_corte.id_pago = pagos.id_pago INNER JOIN referencias ON pagos.id_pago = referencias.id_pago 
        WHERE detalles_corte.id_corte = $corte AND pagos.tipo_cambio = 'Banco' AND referencias.tarjeta = 1
        AND referencias.debito = 1 AND pagos.corte = 1;");
    $joinTarjetaCredito = mysqli_query($conn, "SELECT * FROM detalles_corte INNER JOIN pagos ON 
        detalles_corte.id_pago = pagos.id_pago INNER JOIN referencias ON pagos.id_pago = referencias.id_pago 
        WHERE detalles_corte.id_corte = $corte AND pagos.tipo_cambio = 'Banco' AND referencias.tarjeta = 1
        AND referencias.credito = 1 AND pagos.corte = 1;");
    if (mysqli_num_rows($sql_efectivo) > 0 OR mysqli_num_rows($sql_banco) > 0 OR mysqli_num_rows($sql_credito) > 0 OR mysqli_num_rows($sql_salidas) OR mysqli_num_rows($sql_cortes)) {
        $pdf->SetY($pdf->GetY()+5);
        $pdf->SetX(5);
        $pdf->SetFont('Helvetica','B', 10);
        $pdf->MultiCell(70,4,utf8_decode('<<MOVIMIENTOS>>'),0,'C',0);///   >>>>>>>>>>>>>>>>>>>>>>>>
        if (mysqli_num_rows($sql_efectivo) > 0) {
            $pdf->SetY($pdf->GetY()+2);
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 9);
            $pdf->MultiCell(70,4,utf8_decode('::: EN EFECTIVO :::'),0,'L',0);// ***********************
            $pdf->SetY($pdf->GetY()+1);
            $Total_Efectivo_int = 0;
            while($pagoE = mysqli_fetch_array($sql_efectivo)){
                $pdf->SetX(4);
                $pdf->MultiCell(50,4,utf8_decode(' -- N°Clte: '.$pagoE['id_cliente'].'; '.$pagoE['tipo']."\n".$pagoE['descripcion']),0,'L',0);
                $pdf->SetY($pdf->GetY()-4);
                $pdf->SetX(54);
                $pdf->MultiCell(20,4,utf8_decode('$'.sprintf('%.2f', $pagoE['cantidad'])),0,'R',0);
                $Total_Efectivo_int += $pagoE['cantidad'];
            }//FIN WHILE
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','B', 9);
            $pdf->MultiCell(35,4,utf8_decode('TOTAL EFECTIVO'),0,'L',0);    
            $pdf->SetY($pdf->GetY()-4);
            $pdf->SetX(41);
            $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $Total_Efectivo_int)),0,'R',0);
        }// FIN IF EFECTIVO
        if (mysqli_num_rows($joinTransferencia) > 0) {
            $pdf->SetY($pdf->GetY()+2);
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 9);
            $pdf->MultiCell(70,4,utf8_decode('::: TRANSFERENCIA :::'),0,'L',0);//   **********************
            $pdf->SetY($pdf->GetY()+1);
            $TotalTransferencia = 0;
            while($pagoTransferencia = mysqli_fetch_array($joinTransferencia)){
                $pdf->SetX(4);
                $pdf->MultiCell(50,4,utf8_decode(' -- N°Clte: '.$pagoTransferencia['id_cliente'].'; '.$pagoTransferencia['tipo']."\n".$pagoTransferencia['descripcion']),0,'L',0);
                $pdf->SetY($pdf->GetY()-4);
                $pdf->SetX(54);
                $pdf->MultiCell(20,4,utf8_decode('$'.sprintf('%.2f', $pagoTransferencia['cantidad'])),0,'R',0);
                $TotalTransferencia += $pagoTransferencia['cantidad'];
            }//FIN WHILE
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','B', 7);
            $pdf->MultiCell(35,4,utf8_decode('TOTAL TRANSFERENCIA'),0,'L',0);    
            $pdf->SetY($pdf->GetY()-4);
            $pdf->SetX(41);
            $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $TotalTransferencia)),0,'R',0);
        }/// FIN IF BANCO
        if (mysqli_num_rows($joinTarjetaDebito) > 0) {
            $pdf->SetY($pdf->GetY()+2);
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 9);
            $pdf->MultiCell(70,4,utf8_decode('::: TARJETA DEBITO :::'),0,'L',0);//   **********************
            $pdf->SetY($pdf->GetY()+1);
            $TotalTarjetaDebito = 0;
            while($pagoTarjetaDebito = mysqli_fetch_array($joinTarjetaDebito)){
                $pdf->SetX(4);
                $pdf->MultiCell(50,4,utf8_decode(' -- N°Clte: '.$pagoTarjetaDebito['id_cliente'].'; '.$pagoTarjetaDebito['tipo']."\n".$pagoTarjetaDebito['descripcion']),0,'L',0);
                $pdf->SetY($pdf->GetY()-4);
                $pdf->SetX(54);
                $pdf->MultiCell(20,4,utf8_decode('$'.sprintf('%.2f', $pagoTarjetaDebito['cantidad'])),0,'R',0);
                $TotalTarjetaDebito += $pagoTarjetaDebito['cantidad'];
            }//FIN WHILE
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','B', 9);
            $pdf->MultiCell(35,4,utf8_decode('TOTAL T. DEBITO'),0,'L',0);    
            $pdf->SetY($pdf->GetY()-4);
            $pdf->SetX(41);
            $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $TotalTarjetaDebito)),0,'R',0);
        }/// FIN IF TARJETA DEBITO
        if (mysqli_num_rows($joinTarjetaCredito) > 0) {
            $pdf->SetY($pdf->GetY()+2);
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 9);
            $pdf->MultiCell(70,4,utf8_decode('::: TARJETA CREDITO :::'),0,'L',0);//   **********************
            $pdf->SetY($pdf->GetY()+1);
            $TotalTarjetaCredito = 0;
            while($pagoTarjetaCredito = mysqli_fetch_array($joinTarjetaCredito)){
                $pdf->SetX(4);
                $pdf->MultiCell(50,4,utf8_decode(' -- N°Clte: '.$pagoTarjetaCredito['id_cliente'].'; '.$pagoTarjetaCredito['tipo']."\n".$pagoTarjetaCredito['descripcion']),0,'L',0);
                $pdf->SetY($pdf->GetY()-4);
                $pdf->SetX(54);
                $pdf->MultiCell(20,4,utf8_decode('$'.sprintf('%.2f', $pagoTarjetaCredito['cantidad'])),0,'R',0);
                $TotalTarjetaCredito += $pagoTarjetaCredito['cantidad'];
            }//FIN WHILE
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','B', 9);
            $pdf->MultiCell(35,4,utf8_decode('TOTAL T. CREDITO'),0,'L',0);    
            $pdf->SetY($pdf->GetY()-4);
            $pdf->SetX(41);
            $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $TotalTarjetaCredito)),0,'R',0);
        }/// FIN IF TARJETA CREDITO
        if (mysqli_num_rows($sql_credito) > 0) {
            $pdf->SetY($pdf->GetY()+2);
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 9);
            $pdf->MultiCell(70,4,utf8_decode('::: A CREDITO :::'),0,'L',0);//    *******************
            $pdf->SetY($pdf->GetY()+1);
            $Total_credito_int = 0;
            while($pagoC = mysqli_fetch_array($sql_credito)){
                $pdf->SetX(4);
                $pdf->MultiCell(50,4,utf8_decode(' -- N°Clte: '.$pagoC['id_cliente'].'; '.$pagoC['tipo']."\n".$pagoC['descripcion']),0,'L',0);
                $pdf->SetY($pdf->GetY()-4);
                $pdf->SetX(54);
                $pdf->MultiCell(20,4,utf8_decode('$'.sprintf('%.2f', $pagoC['cantidad'])),0,'R',0);
                $Total_credito_int += $pagoC['cantidad'];
            }//FIN WHILE
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','B', 9);
            $pdf->MultiCell(35,4,utf8_decode('TOTAL A CREDITO'),0,'L',0);    
            $pdf->SetY($pdf->GetY()-4);
            $pdf->SetX(41);
            $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $Total_credito_int)),0,'R',0);
        }// FIN IF CREDITO
        if (mysqli_num_rows($sql_salidas) > 0) {
            $pdf->SetY($pdf->GetY()+2);
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 9);
            $pdf->MultiCell(70,4,utf8_decode('::: SALIDAS :::'),0,'L',0);//    *******************
            $pdf->SetY($pdf->GetY()+1);
            $Total_salidas = 0;
            while($Salida = mysqli_fetch_array($sql_salidas)){
                $pdf->SetX(4);
                $pdf->MultiCell(50,4,utf8_decode(' -- N°: '.$Salida['id'].'; '.$Salida['motivo']),0,'L',0);
                $pdf->SetY($pdf->GetY()-4);
                $pdf->SetX(54);
                $pdf->MultiCell(20,4,utf8_decode('$'.sprintf('%.2f', $Salida['cantidad'])),0,'R',0);
                $Total_salidas += $Salida['cantidad'];
            }//FIN WHILE
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','B', 9);
            $pdf->MultiCell(35,4,utf8_decode('TOTAL SALIDAS'),0,'L',0);    
            $pdf->SetY($pdf->GetY()-4);
            $pdf->SetX(41);
            $pdf->MultiCell(34,4,utf8_decode('$'.sprintf('%.2f', $Total_salidas)),0,'R',0);
        }// FIN IF SALIDAS
        if (mysqli_num_rows($sql_cortes) > 0) {
            $pdf->SetY($pdf->GetY()+2);
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 9);
            $pdf->MultiCell(70,4,utf8_decode('::: CORTES :::'),0,'L',0);//    *******************

            $Total_salidas = 0;

            $pdf->SetY($pdf->GetY()+1);
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
            $pdf->SetFont('Helvetica','B', 9);
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(6);
            $pdf->MultiCell(70,4,utf8_decode('N°          ENT.       SALID.    BANCO   CREDIT.'),0,'L',0);
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
            while($Cort = mysqli_fetch_array($sql_cortes)){
                $pdf->SetY($pdf->GetY()+1);
                $pdf->SetX(5);
                $pdf->MultiCell(10,3,utf8_decode($Cort['id_corte']),0,'L',0);
                $pdf->SetY($pdf->GetY()-3);
                $pdf->SetX(15);
                $pdf->MultiCell(15,3,utf8_decode('$'.sprintf('%.1f',$Cort['entradas'])),0,'R',0);                  
                $pdf->SetY($pdf->GetY()-3);
                $pdf->SetX(30);
                $pdf->MultiCell(15,3,utf8_decode('$'.sprintf('%.1f',$Cort['salidas'])),0,'R',0);                  
                $pdf->SetY($pdf->GetY()-3);
                $pdf->SetX(45);
                $pdf->MultiCell(15,3,utf8_decode('$'.sprintf('%.1f',$Cort['banco'])),0,'R',0);
                $pdf->SetY($pdf->GetY()-3);
                $pdf->SetX(60);
                $pdf->MultiCell(14,3,utf8_decode('$'.sprintf('%.1f',$Cort['credito'])),0,'R',0);
            }//FIN WHILE
            $pdf->SetY($pdf->GetY());
            $pdf->SetX(5);
            $pdf->SetFont('Helvetica','', 8);
            $pdf->MultiCell(70,3,utf8_decode('------------------------------------------------------------------------'),0,'L',0);
        }// FIN IF CORTES
        
       
    }//FIN IF MOVIMIENTOS 

    $pdf->Output('CORTE','I');
    $doc = $pdf->Output('CORTE','S');

    $Aviso = 'Buen dia, le adjuntamos automaticamente el comprobarte del corte, Saludos!';
      #AVISO
      if ($Aviso != '') {
          $correo = new PHPMailer(true);
          try{
              #$correo->SMTPDebug = SMTP::DEBUG_SERVER;
              $correo->isSMTP();
              $correo->Host = 'sicsom.com';
              $correo->SMTPAuth = true;
              $correo->Username = 'cortes@sicsom.com';
              $correo->Password = '3.NiOYNE(Txj';
              $correo->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
              $correo->Port = 465;
              #COLOCAMOS UN TITULO AL CORREO  COMO REMITENTE
              $correo->setFrom('no_replay2023@hotelsanroman.net', 'CORTES SAN ROMAN');
              #DEFINIMOS A QUE CORREOS SERAN LOS DESTINATARIOS
              $correo->addAddress('ruby.roman@hotelsanroman.net', 'RUBY ROMAN');   //Cnfkv9mSJr
              $correo->Subject = 'CORTES SAN ROMAN';// SE CREA EL ASUNTO DEL CORREO
              $correo->Body = $Aviso;
              $correo->AddStringAttachment($doc, 'Corte_'.$corte.'.pdf', 'base64', 'application/pdf');
              $correo->send();
              echo "CORREO ENVIADO CON EXITO !!!";
          }catch(Exception $e){
              echo 'ERROR: '.$correo->ErrorInfo;
          }
    }
?>