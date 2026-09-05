<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data company
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM companies WHERE id = $id"
);

$company = mysqli_fetch_assoc($query);

if (!$company) {
    header("Location: index.php?error=notfound");
    exit;
}

// Cek apakah masih digunakan oleh lamaran
$check = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM lamaran
     WHERE company_id = $id"
);

$check_data = mysqli_fetch_assoc($check);

// Jika masih digunakan
if ($check_data['total'] > 0) {
    header(
        "Location: index.php?error=linked&company="
        . urlencode($company['nama_perusahaan'])
    );
    exit;
}

// Jika tidak digunakan, hapus
$delete = mysqli_query(
    $koneksi,
    "DELETE FROM companies WHERE id = $id"
);

if ($delete) {
    header("Location: index.php?success=deleted");
    exit;
}

// Jika gagal karena constraint database
header(
    "Location: index.php?error=linked&company="
    . urlencode($company['nama_perusahaan'])
);
exit;