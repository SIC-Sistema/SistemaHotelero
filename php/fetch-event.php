<?php
    require_once "conexion.php";

    $json = array();
    $sqlQuery = "SELECT * FROM tbl_events ORDER BY id";

    $result = mysqli_query($conn, $sqlQuery);
    $eventArray = array();
    while ($row = mysqli_fetch_assoc($result)) {
        unset($row['id']);
        unset($row['title']);
        $row["rendering"]='background';
        $row['color']='#F90B0B';

        array_push($eventArray, $row);
    }
    $result = mysqli_query($conn, $sqlQuery);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['color']='#616161';

        array_push($eventArray, $row);
    }
    mysqli_close($conn);
    echo json_encode($eventArray);
   # var_dump($eventArray);
?>