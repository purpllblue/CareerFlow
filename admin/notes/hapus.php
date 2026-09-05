<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";


if (
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
) {

    header("Location: ../dashboard.php");

    exit;
}


$id =
    (int) $_GET['id'];


$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM notes
     WHERE id = ?"
);


if ($stmt) {

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}


header(
    "Location: ../dashboard.php?success=note_deleted"
);

exit;