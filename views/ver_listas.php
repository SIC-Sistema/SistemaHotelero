<?php
function getRangeDate($date_ini, $date_end, $format = 'Y-m-d') {

    $dt_ini = DateTime::createFromFormat($format, $date_ini);
    $dt_end = DateTime::createFromFormat($format, $date_end);
    $period = new DatePeriod(
        $dt_ini,
        new DateInterval('P1D'),
        $dt_end,
    );
    $range = [];
    foreach ($period as $date) {
        $range[] = $date->format($format);
    }
    $range[] = $date_end;
    return $range;
}

$Lista_ocupadas = getRangeDate('2022-10-14', '2022-10-20');
$Lista_buscar = getRangeDate('2022-10-09', '2022-10-15');
echo($Lista_buscar);
foreach ($Lista_buscar as $busca) {
    if (in_array($busca, $Lista_ocupadas)) {
        echo 'FECHA NO DISPONIBLE: '.$busca;
        break;
    }
}
?>