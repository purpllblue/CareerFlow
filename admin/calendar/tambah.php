<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../calendar.php");
    exit;
}

/* =========================
   AMBIL DATA
========================= */

$title = trim(
    $_POST['title'] ?? ''
);

$event_type = trim(
    $_POST['event_type'] ?? ''
);

$event_date =
    $_POST['event_date'] ?? '';

$end_date =
    !empty($_POST['end_date'])
        ? $_POST['end_date']
        : null;

$start_time =
    !empty($_POST['start_time'])
        ? $_POST['start_time']
        : null;

$end_time =
    !empty($_POST['end_time'])
        ? $_POST['end_time']
        : null;

$company_id =
    !empty($_POST['company_id'])
        ? (int) $_POST['company_id']
        : null;

$event_color =
    $_POST['event_color'] ?? '#2563EB';

$notes =
    trim(
        $_POST['notes'] ?? ''
    );


/* =========================
   VALIDASI
========================= */

if (
    $title === '' ||
    $event_type === '' ||
    $event_date === ''
) {
    header(
        "Location: ../calendar.php?error=required"
    );
    exit;
}


/* =========================
   VALIDASI DATE RANGE
========================= */

if (
    !empty($end_date) &&
    $end_date < $event_date
) {
    header(
        "Location: ../calendar.php?error=date_range"
    );
    exit;
}


/* =========================
   WARNA YANG DIIZINKAN
========================= */

$allowedColors = [
    '#2563EB',
    '#7C3AED',
    '#EA580C',
    '#16A34A',
    '#DC2626',
    '#475569',
    '#0F766E'
];

$normalizedColors =
    array_map(
        'strtoupper',
        $allowedColors
    );

if (
    !in_array(
        strtoupper($event_color),
        $normalizedColors,
        true
    )
) {
    $event_color = '#2563EB';
}


/* =========================
   INSERT
========================= */

$stmt = mysqli_prepare(
    $koneksi,
    "
    INSERT INTO calendar_events
    (
        title,
        event_type,
        event_color,
        event_date,
        end_date,
        start_time,
        end_time,
        company_id,
        notes
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    "
);

if (!$stmt) {
    header(
        "Location: ../calendar.php?error=failed"
    );
    exit;
}


/* =========================
   BIND PARAMETER
========================= */

mysqli_stmt_bind_param(
    $stmt,
    "sssssssis",
    $title,
    $event_type,
    $event_color,
    $event_date,
    $end_date,
    $start_time,
    $end_time,
    $company_id,
    $notes
);


/* =========================
   SIMPAN
========================= */

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header(
        "Location: ../calendar.php?success=added"
    );

    exit;
}


/* =========================
   GAGAL
========================= */

mysqli_stmt_close($stmt);

header(
    "Location: ../calendar.php?error=failed"
);

exit;