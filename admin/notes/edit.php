<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";


/* =====================================================
   METHOD CHECK
===================================================== */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../dashboard.php");

    exit;
}


/* =====================================================
   GET DATA
===================================================== */

$id =
    isset($_POST['id'])
        ? (int) $_POST['id']
        : 0;

$title =
    trim($_POST['title'] ?? '');

$content =
    trim($_POST['content'] ?? '');

$note_color =
    $_POST['note_color'] ?? '#FEF3C7';


/* =====================================================
   VALIDATION
===================================================== */

if (
    $id <= 0 ||
    $title === '' ||
    $content === ''
) {

    header(
        "Location: ../dashboard.php?error=note_required"
    );

    exit;
}


/* =====================================================
   ALLOWED HTML
===================================================== */

$allowedTags =
    '<p><br><strong><b><em><i><u><ul><ol><li><div><span>';

$content =
    strip_tags(
        $content,
        $allowedTags
    );


/* =====================================================
   CHECK CONTENT
===================================================== */

if (
    trim(
        strip_tags($content)
    ) === ''
) {

    header(
        "Location: ../dashboard.php?error=note_required"
    );

    exit;
}


/* =====================================================
   ALLOWED COLORS
===================================================== */

$allowedColors = [

    '#DBEAFE',
    '#DDD6FE',
    '#faf4dd',
    '#efdbfa',
    '#FFEDD5',
    '#FCE7F3',
    '#DCFCE7'

];


$normalizedColors =
    array_map(
        'strtoupper',
        $allowedColors
    );


if (
    !in_array(
        strtoupper($note_color),
        $normalizedColors,
        true
    )
) {

    $note_color =
        '#FEF3C7';
}


/* =====================================================
   CHECK NOTE EXISTS
===================================================== */

$check =
    mysqli_prepare(
        $koneksi,
        "SELECT id
         FROM notes
         WHERE id = ?
         LIMIT 1"
    );


if (!$check) {

    header(
        "Location: ../dashboard.php?error=note_failed"
    );

    exit;
}


mysqli_stmt_bind_param(
    $check,
    "i",
    $id
);


mysqli_stmt_execute(
    $check
);


$result =
    mysqli_stmt_get_result(
        $check
    );


if (
    mysqli_num_rows($result) === 0
) {

    mysqli_stmt_close($check);

    header(
        "Location: ../dashboard.php?error=note_not_found"
    );

    exit;
}


mysqli_stmt_close($check);


/* =====================================================
   UPDATE
===================================================== */

$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE notes
     SET
        title = ?,
        content = ?,
        note_color = ?
     WHERE id = ?"
);


if (!$stmt) {

    header(
        "Location: ../dashboard.php?error=note_failed"
    );

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "sssi",
    $title,
    $content,
    $note_color,
    $id
);


if (
    mysqli_stmt_execute($stmt)
) {

    mysqli_stmt_close($stmt);

    header(
        "Location: ../dashboard.php?success=note_updated"
    );

    exit;
}


mysqli_stmt_close($stmt);


header(
    "Location: ../dashboard.php?error=note_failed"
);

exit;