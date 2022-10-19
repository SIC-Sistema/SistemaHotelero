<?php
include('../php/conexion.php');
$id = $conn->real_escape_string($_GET['id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title> San Roman | Calendario</title>
<link rel="shortcut icon" href="../img/logo.png" type="image/png" />
<link rel="stylesheet" href="../fullcalendar/fullcalendar.min.css" />
<script src="../fullcalendar/lib/jquery.min.js"></script>
<script src="../fullcalendar/lib/moment.min.js"></script>
<script src="../fullcalendar/fullcalendar.min.js"></script>
<script src='../fullcalendar/lib/locale/es.js'></script>

<script >
$(document).ready(function () {
    var calendar = $('#calendar').fullCalendar({
        editable: true,
        header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek', 
            },
        events: "../php/fetch-event.php?id="+<?php echo $id; ?>,
        displayEventTime: true,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,        
    });
});
</script>
<style>
body {
    margin-top: 50px;
    text-align: center;
    font-size: 12px;
    font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
}
#calendar {
    width: 700px;
    margin: 0 auto;
}
</style>
</head>
<body>
    <h1>DISPONIBLILIDAD DE LA HABITACION N° <?php echo $id; ?></h1><br><br><br>    
    <div id='calendar'></div>
    <h3>Color: <b style="color:#FF0000">ROJO</b> reservaciones 'Pendientes'.</h3>
    <h3>Color: <b style="color:blue">AZUL</b> reservaciones 'Ocupadas Ahora'.</h3>
    <h3>Color: <b>BLANCO</b> dias 'Disponibles'.</h3>
</body>
</html>