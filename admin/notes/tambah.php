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
    '#EDE9FE',
    '#FEF3C7',
    '#DCFCE7',
    '#FFEDD5',
    '#FCE7F3'

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
   INSERT
===================================================== */

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO notes
    (
        title,
        content,
        note_color
    )
    VALUES (?, ?, ?)"
);


if (!$stmt) {

    header(
        "Location: ../dashboard.php?error=note_failed"
    );

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $title,
    $content,
    $note_color
);


if (
    mysqli_stmt_execute($stmt)
) {

    mysqli_stmt_close($stmt);

    header(
        "Location: ../dashboard.php?success=note_added"
    );

    exit;
}


mysqli_stmt_close($stmt);


header(
    "Location: ../dashboard.php?error=note_failed"
);

exit;