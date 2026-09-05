<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../calendar.php");
    exit;
}

$id = (int) $_GET['id'];

mysqli_query(
    $koneksi,
    "DELETE FROM calendar_events
     WHERE id = $id"
);

header(
    "Location: ../calendar.php?success=deleted"
);

exit;