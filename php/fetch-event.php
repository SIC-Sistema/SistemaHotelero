<?php
    require_once "conexion.php";
    $habitacion = $_GET['id'];//TOMAMOS POR METODO GET EL ID DE LA HABITACION
    $eventArray = array();
    
    // SACAMOS LAS RESERVACIONES PENDIENTES DE LA HABITACION EN TURNO
    $sqlQueryP = "SELECT nombre,fecha_entrada, fecha_salida FROM reservaciones WHERE id_habitacion  = $habitacion AND estatus = 0  ORDER BY id";

    $result = mysqli_query($conn, $sqlQueryP);
    while ($row = mysqli_fetch_assoc($result)) {
        $row["start"]=$row['fecha_entrada'];
        $row["end"]=$row['fecha_salida'];
        $row["rendering"]='background';
        $row['color']='#F90B0B';
        unset($row['nombre']);
        unset($row['fecha_entrada']);
        unset($row['fecha_salida']);        

        array_push($eventArray, $row);
    }
    $result = mysqli_query($conn, $sqlQueryP);
    while ($row = mysqli_fetch_assoc($result)) {
        $row["title"]='A nombre: '.$row['nombre'];
        $row["start"]=$row['fecha_entrada'];
        $row["end"]=$row['fecha_salida'];
        unset($row['nombre']);
        unset($row['fecha_entrada']);
        unset($row['fecha_salida']); 
        $row['color']='#616161';

        array_push($eventArray, $row);
    }
    mysqli_close($conn);
    echo json_encode($eventArray);
   # var_dump($eventArray);
?>