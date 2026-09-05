<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Application
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,
    "DELETE FROM lamaran WHERE id = $id"
);

if ($query) {
    header("Location: index.php?success=deleted");
    exit;
}

header("Location: index.php");
exit;