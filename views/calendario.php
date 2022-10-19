<!DOCTYPE html>
<html lang="es">
<head>
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
        events: "../php/fetch-event.php?id=2",
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
    <h2>CALENDARIO</h2>
    <div id='calendar'></div>
</body>
</html>