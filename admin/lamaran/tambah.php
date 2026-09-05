<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";


/* =========================================================
   ADD COMPANY
========================================================= */

if (isset($_POST['add_company'])) {

    $nama_perusahaan = mysqli_real_escape_string(
        $koneksi,
        $_POST['nama_perusahaan']
    );

    $industri = mysqli_real_escape_string(
        $koneksi,
        $_POST['industri']
    );

    $lokasi = mysqli_real_escape_string(
        $koneksi,
        $_POST['lokasi']
    );

    $website = mysqli_real_escape_string(
        $koneksi,
        $_POST['website']
    );

    $catatan = mysqli_real_escape_string(
        $koneksi,
        $_POST['catatan']
    );

    if ($nama_perusahaan != '') {

        mysqli_query(
            $koneksi,
            "INSERT INTO companies
            (
                nama_perusahaan,
                industri,
                lokasi,
                website,
                catatan
            )
            VALUES
            (
                '$nama_perusahaan',
                '$industri',
                '$lokasi',
                '$website',
                '$catatan'
            )"
        );

        $new_company_id = mysqli_insert_id($koneksi);

        header(
            "Location: tambah.php?company=" . $new_company_id
        );

        exit;
    }
}


/* =========================================================
   SAVE APPLICATION
========================================================= */

if (isset($_POST['save'])) {

    $company_id = intval($_POST['company_id']);

    $posisi = mysqli_real_escape_string(
        $koneksi,
        $_POST['posisi']
    );

    $tanggal_lamar = mysqli_real_escape_string(
        $koneksi,
        $_POST['tanggal_lamar']
    );

    $sumber = mysqli_real_escape_string(
        $koneksi,
        $_POST['sumber']
    );

    $status = mysqli_real_escape_string(
        $koneksi,
        $_POST['status']
    );

    $tahap = mysqli_real_escape_string(
        $koneksi,
        $_POST['tahap']
    );

    $deadline = !empty($_POST['deadline'])
        ? "'" . mysqli_real_escape_string(
            $koneksi,
            $_POST['deadline']
        ) . "'"
        : "NULL";

    $link_lowongan = mysqli_real_escape_string(
        $koneksi,
        $_POST['link_lowongan']
    );

    $link_perusahaan = mysqli_real_escape_string(
        $koneksi,
        $_POST['link_perusahaan']
    );

    $catatan = mysqli_real_escape_string(
        $koneksi,
        $_POST['catatan']
    );


    /* GET COMPANY NAME */

    $company_query = mysqli_query(
        $koneksi,
        "SELECT nama_perusahaan
         FROM companies
         WHERE id = $company_id
         LIMIT 1"
    );

    $company_data = mysqli_fetch_assoc(
        $company_query
    );

    $perusahaan = mysqli_real_escape_string(
        $koneksi,
        $company_data['nama_perusahaan'] ?? ''
    );


    /* INSERT APPLICATION */

    $insert = mysqli_query(
        $koneksi,
        "INSERT INTO lamaran
        (
            company_id,
            perusahaan,
            posisi,
            tanggal_lamar,
            sumber,
            status,
            tahap,
            deadline,
            link_lowongan,
            link_perusahaan,
            catatan
        )
        VALUES
        (
            $company_id,
            '$perusahaan',
            '$posisi',
            '$tanggal_lamar',
            '$sumber',
            '$status',
            '$tahap',
            $deadline,
            '$link_lowongan',
            '$link_perusahaan',
            '$catatan'
        )"
    );

    if ($insert) {

        header(
            "Location: index.php?success=added"
        );

        exit;

    } else {

        die(
            "Gagal menyimpan application: "
            . mysqli_error($koneksi)
        );
    }
}


/* =========================================================
   COMPANIES
========================================================= */

$companies = mysqli_query(
    $koneksi,
    "SELECT *
     FROM companies
     ORDER BY nama_perusahaan ASC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Application - CareerFlow</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <!-- Poppins -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- CareerFlow Theme -->

    <link
        href="../../assets/css/theme.css?v=3"
        rel="stylesheet"
    >


    <!-- Apply saved theme immediately -->

    <script>

        (function () {

            const theme =
                localStorage.getItem(
                    'careerFlowTheme'
                ) || 'blue';

            document.documentElement.setAttribute(
                'data-theme',
                theme
            );

        })();

    </script>


<style>
    :root {
    --login-primary: #2563EB;
    --login-primary-dark: #1D4ED8;
    --login-soft: #EFF6FF;
    --login-soft-2: #DBEAFE;
    --login-text: #0F172A;
    --login-muted: #64748B;
}

html[data-theme="pink"] {
    --login-primary: #EC4899;
    --login-primary-dark: #DB2777;
    --login-soft: #FCE7F3;
    --login-soft-2: #FBCFE8;
    --login-text: #3F172B;
    --login-muted: #8B6475;
}

html[data-theme="purple"] {
    --login-primary: #8066D8;
    --login-primary-dark: #6D52C7;
    --login-soft: #EDE9FE;
    --login-soft-2: #DDD6FE;
    --login-text: #24163D;
    --login-muted: #75658F;
}

html[data-theme="black"] {
    --login-primary: #6B7280;
    --login-primary-dark: #4B5563;
    --login-soft: #E5E7EB;
    --login-soft-2: #D1D5DB;
    --login-text: #F4F4F5;
    --login-muted: #A1A1AA;
}

/* =====================================================
   GLOBAL
===================================================== */

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    font-family:
        'Poppins',
        sans-serif;

    background: #F8FAFC;

    color: #0F172A;

    font-size: 15px;
}


/* =====================================================
   SIDEBAR
===================================================== */

.sidebar {

    position: fixed;

    left: 0;

    top: 0;

    width: 240px;

    height: 100vh;

    background:
        linear-gradient(
            180deg,
            var(--sidebar-1) 0%,
            var(--sidebar-2) 50%,
            var(--sidebar-3) 100%
        );

    padding: 24px 14px;

    z-index: 1000;

    display: flex;

    flex-direction: column;

    border-right:
        1px solid rgba(255,255,255,.08);

    box-shadow:
        8px 0 25px var(--shadow-color),
        2px 0 6px var(--shadow-color);

    transition:
        width .25s ease,
        box-shadow .25s ease;
}


/* =====================================================
   LOGO
===================================================== */

.logo {

    color: white;

    font-size: 30px;

    font-weight: 700;

    padding: 0 14px;

    margin-bottom: 35px;

    white-space: nowrap;

    overflow: hidden;

    transition: all .25s ease;

    text-shadow:
        0 2px 4px rgba(15,23,42,.20);
}

.logo span {
    color: var(--flow-color);
}


/* =====================================================
   MENU TITLE
===================================================== */

.menu-title {

    color:
        rgba(255,255,255,.7);

    font-size: 13px;

    font-weight: 700;

    padding: 0 14px;

    margin-bottom: 10px;

    text-transform: uppercase;

    letter-spacing: .7px;
}

.account-title {
    margin-top: 25px;
}


/* =====================================================
   NAVIGATION
===================================================== */

.nav-link {

    color: #FFFFFF !important;

    padding: 13px 14px;

    border-radius: 8px;

    display: flex;

    align-items: center;

    gap: 11px;

    font-size: 15px;

    text-decoration: none;

    transition: all .2s ease;

    white-space: nowrap;

    position: relative;
}

.nav-link:hover {

    color: #FFFFFF !important;

    background:
        rgba(255,255,255,.12);

    box-shadow:
        0 8px 16px rgba(0,0,0,.20),
        0 3px 6px rgba(0,0,0,.12);

    transform:
        translateX(6px)
        translateY(-3px);

    border-right:
        2px solid rgba(255,255,255,.25);
}

.nav-link.active {

    color: #FFFFFF !important;

    background:
        linear-gradient(
            135deg,
            var(--accent) 0%,
            var(--accent-hover) 100%
        );

    box-shadow:
        0 4px 12px var(--shadow-color),
        inset 0 1px 0 rgba(255,255,255,.15);
}

.nav-link.active:hover {

    transform:
        translateX(6px)
        translateY(-3px);

    box-shadow:
        0 9px 18px var(--shadow-color),
        inset 0 1px 0 rgba(255,255,255,.15);
}

.nav-link i {

    font-size: 19px;

    min-width: 19px;

    text-align: center;

    transition:
        transform .2s ease;
}

.nav-link:hover i {

    transform:
        translateY(-1px);
}


/* =====================================================
   SIDEBAR THEME
===================================================== */

html[data-theme="blue"] .sidebar {

    background:
        linear-gradient(
            180deg,
            #1E3A6D 0%,
            #234A7A 50%,
            #1F5A8A 100%
        ) !important;
}

html[data-theme="blue"] .nav-link.active {

    background:
        linear-gradient(
            135deg,
            #2563EB 0%,
            #1D4ED8 100%
        ) !important;
}


html[data-theme="pink"] .sidebar {

    background:
        linear-gradient(
            180deg,
            #9D174D 0%,
            #BE185D 50%,
            #DB2777 100%
        ) !important;
}

html[data-theme="pink"] .nav-link.active {

    background:
        linear-gradient(
            135deg,
            #EC4899 0%,
            #DB2777 100%
        ) !important;
}


html[data-theme="purple"] .sidebar {

    background:
        linear-gradient(
            180deg,
            #6D4BC3 0%,
            #8066D8 50%,
            #9278E3 100%
        ) !important;
}

html[data-theme="purple"] .nav-link.active {

    background:
        linear-gradient(
            135deg,
            #8066D8 0%,
            #6D4BC3 100%
        ) !important;
}


html[data-theme="black"] .sidebar {

    background:
        linear-gradient(
            180deg,
            #111827 0%,
            #1F2937 50%,
            #374151 100%
        ) !important;
}

html[data-theme="black"] .nav-link.active {

    background:
        linear-gradient(
            135deg,
            #6B7280 0%,
            #4B5563 100%
        ) !important;
}


/* =====================================================
   LOGO THEME
===================================================== */

html[data-theme="blue"] .logo span {
    color: #60A5FA !important;
}

html[data-theme="pink"] .logo span {
    color: #F9A8D4 !important;
}

html[data-theme="purple"] .logo span {
    color: #C4B5FD !important;
}

html[data-theme="black"] .logo span {
    color: #D1D5DB !important;
}


/* =====================================================
   SIDEBAR TEXT
===================================================== */

.sidebar .nav-link {
    color: #FFFFFF !important;
}

.sidebar .nav-link:hover {
    color: #FFFFFF !important;
}

.sidebar .nav-link.active {
    color: #FFFFFF !important;
}

.sidebar .menu-title {
    color:
        rgba(255,255,255,.7) !important;
}


/* =====================================================
   LOGOUT
===================================================== */

.nav-link.logout {

    font-weight: 700 !important;

    border-radius: 10px;

    transition:
        all .2s ease !important;
}


html[data-theme="blue"] .nav-link.logout {

    color: #1E40AF !important;

    background:
        #DBEAFE !important;
}

html[data-theme="blue"] .nav-link.logout:hover {

    color: #1E3A8A !important;

    background:
        #BFDBFE !important;
}


html[data-theme="pink"] .nav-link.logout {

    color: #9D174D !important;

    background:
        #FCE7F3 !important;
}

html[data-theme="pink"] .nav-link.logout:hover {

    color: #831843 !important;

    background:
        #FBCFE8 !important;
}


html[data-theme="purple"] .nav-link.logout {

    color: #5B21B6 !important;

    background:
        #EDE9FE !important;
}

html[data-theme="purple"] .nav-link.logout:hover {

    color: #4C1D95 !important;

    background:
        #DDD6FE !important;
}


html[data-theme="black"] .nav-link.logout {

    color: #374151 !important;

    background:
        #E5E7EB !important;
}

html[data-theme="black"] .nav-link.logout:hover {

    color: #1F2937 !important;

    background:
        #D1D5DB !important;
}


.nav-link.logout i,
.nav-link.logout:hover i {

    color: inherit !important;
}

.nav-link.logout:hover {

    transform:
        translateX(6px)
        translateY(-3px) !important;
}


/* =====================================================
   SPACER
===================================================== */

.sidebar-spacer {
    flex: 1;
}


/* =====================================================
   THEME MENU
===================================================== */

.theme-toggle {

    border: none;

    background: transparent;

    width: 100%;

    text-align: left;

    cursor: pointer;
}

.theme-toggle .theme-arrow {

    margin-left: auto;

    font-size: 14px;

    min-width: auto;

    transition:
        transform .2s ease;
}

.theme-toggle.open .theme-arrow {

    transform:
        rotate(180deg);
}

.theme-options {

    display: none;

    margin-top: 2px;

    margin-bottom: 5px;
}

.theme-options.show {
    display: block;
}

/* =====================================================
   THEME MENU - LOGO ANIMATION
===================================================== */

.logo {
    transition:
        font-size .25s ease,
        transform .25s ease,
        margin-bottom .25s ease;
}

/* Saat Theme dibuka */
.sidebar:has(.theme-options.show) .logo {
    transform: scale(.82);
    transform-origin: left center;
    margin-bottom: 20px;
}

.theme-option {

    color: #FFFFFF;

    padding:
        9px 14px 9px 48px;

    border-radius: 7px;

    display: flex;

    align-items: center;

    gap: 9px;

    font-size: 14px;

    cursor: pointer;

    transition: all .2s ease;
}

.theme-option:hover {

    color: #FFFFFF;

    background:
        rgba(255,255,255,.10);

    transform:
        translateX(4px);
}

.theme-option.active {

    color: #FFFFFF;

    font-weight: 600;

    background:
        rgba(255,255,255,.14);
}

.theme-dot {

    width: 9px;

    height: 9px;

    border-radius: 50%;

    flex-shrink: 0;
}

.theme-dot.blue {
    background: #60A5FA;
}

.theme-dot.pink {
    background: #F9A8D4;
}

.theme-dot.purple {
    background: #C4B5FD;
}

.theme-dot.black {
    background: #D1D5DB;
}

body.sidebar-collapsed
.theme-toggle .theme-arrow {

    display: none;
}

body.sidebar-collapsed
.theme-options {

    display: none !important;
}


/* =====================================================
   SIDEBAR TOGGLE
===================================================== */

.sidebar-toggle {

    position: absolute;

    top: 22px;

    right: -16px;

    width: 32px;

    height: 32px;

    border:
        1px solid var(--accent-border);

    border-radius: 50%;

    background: white;

    color: #000000;

    display: flex;

    align-items: center;

    justify-content: center;

    cursor: pointer;

    transition:
        all .2s ease;

    z-index: 1001;
}

.sidebar-toggle i {

    color: #000000 !important;
}

.sidebar-toggle:hover {

    color: #000000;

    border-color:
        var(--accent);

    transform:
        scale(1.05);
}

.sidebar-toggle:hover i {

    color: #000000 !important;
}


/* =====================================================
   TOGGLE THEME SHADOW
===================================================== */

html[data-theme="blue"] .sidebar-toggle {

    border-color: #93C5FD;

    box-shadow:
        0 3px 10px rgba(37,99,235,.30),
        0 2px 4px rgba(37,99,235,.15);
}

html[data-theme="pink"] .sidebar-toggle {

    border-color: #F9A8D4;

    box-shadow:
        0 3px 10px rgba(236,72,153,.30),
        0 2px 4px rgba(236,72,153,.15);
}

html[data-theme="purple"] .sidebar-toggle {

    border-color: #C4B5FD;

    box-shadow:
        0 3px 10px rgba(128,102,216,.30),
        0 2px 4px rgba(128,102,216,.15);
}

html[data-theme="black"] .sidebar-toggle {

    border-color: #9CA3AF;

    box-shadow:
        0 3px 10px rgba(107,114,128,.35),
        0 2px 4px rgba(75,85,99,.20);
}


/* =====================================================
   TOGGLE HOVER SHADOW
===================================================== */

html[data-theme="blue"] .sidebar-toggle:hover {

    box-shadow:
        0 5px 14px rgba(37,99,235,.40),
        0 2px 5px rgba(37,99,235,.20);
}

html[data-theme="pink"] .sidebar-toggle:hover {

    box-shadow:
        0 5px 14px rgba(236,72,153,.40),
        0 2px 5px rgba(236,72,153,.20);
}

html[data-theme="purple"] .sidebar-toggle:hover {

    box-shadow:
        0 5px 14px rgba(128,102,216,.40),
        0 2px 5px rgba(128,102,216,.20);
}

html[data-theme="black"] .sidebar-toggle:hover {

    box-shadow:
        0 5px 14px rgba(107,114,128,.40),
        0 2px 5px rgba(107,114,128,.20);
}


/* =====================================================
   COLLAPSED SIDEBAR
===================================================== */

body.sidebar-collapsed .sidebar {

    width: 72px;

    box-shadow:
        6px 0 20px rgba(15,23,42,.16);
}

body.sidebar-collapsed .logo {

    font-size: 0;

    padding-left: 10px;

    padding-right: 10px;
}

body.sidebar-collapsed .logo::before {

    content: "CF";

    font-size: 18px;

    font-weight: 700;
}

body.sidebar-collapsed .sidebar:has(.theme-options.show) .logo {
    transform: scale(1);
    margin-bottom: 35px;
}

body.sidebar-collapsed .menu-title {

    opacity: 0;

    height: 0;

    padding-top: 0;

    padding-bottom: 0;

    margin-top: 0;

    margin-bottom: 0;

    overflow: hidden;
}

body.sidebar-collapsed .nav-link {

    justify-content: center;

    padding-left: 0;

    padding-right: 0;

    gap: 0;

    transform:
        translateX(0)
        translateY(0);
}

body.sidebar-collapsed .nav-link:hover {

    transform:
        translateX(3px)
        translateY(-2px);
}

body.sidebar-collapsed .nav-link span {

    display: none;
}

body.sidebar-collapsed .sidebar-toggle i {

    transform:
        rotate(180deg);
}


/* =====================================================
   MAIN
===================================================== */

.main {

    margin-left: 240px;

    padding: 30px;

    min-height: 100vh;

    transition:
        margin-left .25s ease;
}

body.sidebar-collapsed .main {

    margin-left: 72px;
}


/* =====================================================
   PAGE HEADER
===================================================== */

.page-header {

    display: flex;

    justify-content:
        space-between;

    align-items:
        flex-start;

    margin-bottom: 24px;
}

.page-title h1 {

    font-size: 36px;

    font-weight: 700;

    margin-bottom: 9px;

    color:
        var(--accent);
}

.page-title p {

    color: #64748B;

    font-size: 17px;

    margin: 0;
}


/* =====================================================
   MAIN CARD
===================================================== */

.main-card {

    background: white;

    border:
        1px solid #E2E8F0;

    border-radius: 12px;

    overflow: hidden;
}


/* =====================================================
   CARD HEADER
===================================================== */

.card-header-custom {

    padding:
        23px 26px;

    border-bottom:
        1px solid #E2E8F0;

    display: flex;

    justify-content:
        space-between;

    align-items:
        center;
}

.card-header-custom h5 {

    margin: 0;

    font-size: 19px;

    font-weight: 700;

    color: #0F172A;
}

.card-header-custom p {

    margin:
        6px 0 0;

    color: #94A3B8;

    font-size: 14px;
}


/* =====================================================
   FORM AREA
===================================================== */

.form-content {

    padding:
        26px;
}

.section-title {

    font-size: 19px;

    font-weight: 700;

    color: #0F172A;

    margin-bottom:
        20px;
}

.form-label {

    font-size: 14px;

    font-weight: 600;

    color: #334155;

    margin-bottom: 8px;
}

.form-control,
.form-select {

    font-size: 14px;

    color: #334155;

    border:
        1px solid #CBD5E1;

    border-radius: 8px;

    padding:
        11px 13px;

    min-height: 45px;
}

.form-control::placeholder {

    color: #94A3B8;
}

.form-control:focus,
.form-select:focus {

    border-color:
        var(--accent);

    box-shadow:
        0 0 0 3px var(--accent-soft);
}

textarea.form-control {

    min-height: 120px;

    resize: vertical;
}


/* =====================================================
   COMPANY
===================================================== */

.company-row {

    display: flex;

    gap: 8px;
}

.company-row .form-select {
    flex: 1;
}

.btn-add-company {

    width: 45px;

    min-width: 45px;

    border:
        1px solid #CBD5E1;

    border-radius: 8px;

    background: white;

    color:
        var(--accent);

    display: flex;

    align-items: center;

    justify-content: center;

    text-decoration: none;

    transition:
        all .2s ease;
}

.btn-add-company:hover {

    background:
        var(--accent-soft);

    border-color:
        var(--accent-border);

    color:
        var(--accent-hover);
}


/* =====================================================
   DIVIDER
===================================================== */

.divider {

    border-top:
        1px solid #E2E8F0;

    margin:
        28px 0;
}


/* =====================================================
   BUTTONS
===================================================== */

.form-actions {

    display: flex;

    justify-content:
        flex-end;

    gap: 10px;

    margin-top: 6px;
}

.btn-cancel {
    font-size: 14px;
    font-weight: 500;
    padding: 10px 16px;
    border-radius: 8px;
    border: 1px solid #CBD5E1;
    background: #F1F5F9;
    color: #475569;
    text-decoration: none;
    transition: all .2s ease;
}

.btn-cancel:hover {
    background: #E2E8F0;
    color: #334155;
    border-color: #CBD5E1;
}

.btn-save {

    font-size: 14px;

    font-weight: 500;

    padding:
        10px 16px;

    border-radius: 8px;

    border:
        1px solid var(--accent);

    background:
        var(--accent);

    color: white;

    transition:
        all .2s ease;
}

.btn-save:hover {

    background:
        var(--accent-hover);

    border-color:
        var(--accent-hover);

    color: white;

    transform:
        translateY(-1px);

    box-shadow:
        0 4px 10px var(--shadow-color);
}


/* =====================================================
   MODAL
===================================================== */

.modal-content {

    border: none;

    border-radius: 12px;

    box-shadow:
        0 20px 40px rgba(15,23,42,.15);
}

.modal-header {

    padding:
        20px 24px;

    border-bottom:
        1px solid #E2E8F0;
}

.modal-title {

    font-size: 20px;

    font-weight: 700;

    color: #0F172A;
}

.modal-body {

    padding: 24px;
}

.modal-footer {

    padding:
        16px 24px;

    border-top:
        1px solid #E2E8F0;
}

.btn-save-company {

    font-size: 14px;

    font-weight: 500;

    padding:
        10px 16px;

    border-radius: 8px;

    border:
        1px solid var(--accent);

    background:
        var(--accent);

    color: white;

    transition:
        all .2s ease;
}

.btn-save-company:hover {

    background:
        var(--accent-hover);

    border-color:
        var(--accent-hover);

    color: white;

    transform:
        translateY(-1px);

    box-shadow:
        0 4px 10px var(--shadow-color);
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 768px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;
    }

    .main {

        margin-left: 0;

        padding: 20px;
    }

    .page-header {

        flex-direction: column;

        gap: 15px;
    }

    .page-title h1 {

        font-size: 30px;
    }

    .page-title p {

        font-size: 15px;
    }

    .card-header-custom {

        padding:
            20px;
    }

    .card-header-custom h5 {

        font-size: 17px;
    }

    .card-header-custom p {

        font-size: 13px;
    }

    .form-content {

        padding:
            20px;
    }
}

/* =====================================================
   LOGIN MODAL COLOR VARIABLES
===================================================== */

:root {
    --login-primary: #2563EB;
    --login-soft: #EFF6FF;
    --login-soft-2: #DBEAFE;
    --login-text: #0F172A;
    --login-muted: #64748B;
}

html[data-theme="pink"] {
    --login-primary: #EC4899;
    --login-soft: #FCE7F3;
    --login-soft-2: #FBCFE8;
    --login-text: #3F172B;
    --login-muted: #8B6475;
}

html[data-theme="purple"] {
    --login-primary: #8066D8;
    --login-soft: #EDE9FE;
    --login-soft-2: #DDD6FE;
    --login-text: #24163D;
    --login-muted: #75658F;
}

html[data-theme="black"] {
    --login-primary: #6B7280;
    --login-soft: #E5E7EB;
    --login-soft-2: #D1D5DB;
    --login-text: #F4F4F5;
    --login-muted: #A1A1AA;
}

/* =====================================================
   LOGOUT CONFIRMATION MODAL
===================================================== */

.logout-confirm-modal {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
}

.logout-confirm-modal.show {
    display: flex;
}

.logout-confirm-box {
    width: 100%;
    max-width: 420px;
    background: #FFFFFF;
    border-radius: 18px;
    padding: 35px 30px 30px;
    text-align: center;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.20);
    animation: logoutPopup .2s ease;
}

@keyframes logoutPopup {
    from {
        opacity: 0;
        transform: scale(.95) translateY(8px);
    }

    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.logout-confirm-icon {
    width: 58px;
    height: 58px;
    margin: 0 auto 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
}

.logout-confirm-box h3 {
    margin: 0 0 8px;
    font-size: 20px;
    font-weight: 700;
    color: var(--login-text);
}

.logout-confirm-box p {
    margin: 0 0 25px;
    color: var(--login-muted);
    font-size: 14px;
    line-height: 1.6;
}

.logout-confirm-actions {
    display: flex;
    gap: 10px;
}

.logout-confirm-cancel,
.logout-confirm-yes {
    flex: 1;
    height: 44px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border: none;
}

/* CANCEL = SAMA DENGAN NO DI LOGIN AS LELI */
.logout-confirm-cancel {
    background: transparent;
    color: var(--login-primary);
    border: 1px solid var(--login-primary);
}

.logout-confirm-cancel:hover {
    background: transparent;
    color: var(--login-primary-dark);
    border-color: var(--login-primary-dark);
}

/* BLUE */
html[data-theme="blue"] .logout-confirm-icon {
    background: #DBEAFE;
    color: #1E40AF;
}

html[data-theme="blue"] .logout-confirm-yes {
    background: #2563EB;
    color: #FFFFFF;
}

html[data-theme="blue"] .logout-confirm-yes:hover {
    background: #1D4ED8;
}

/* PINK */
html[data-theme="pink"] .logout-confirm-icon {
    background: #FCE7F3;
    color: #9D174D;
}

html[data-theme="pink"] .logout-confirm-yes {
    background: #EC4899;
    color: #FFFFFF;
}

html[data-theme="pink"] .logout-confirm-yes:hover {
    background: #DB2777;
}

/* PURPLE */
html[data-theme="purple"] .logout-confirm-icon {
    background: #EDE9FE;
    color: #5B21B6;
}

html[data-theme="purple"] .logout-confirm-yes {
    background: #8066D8;
    color: #FFFFFF;
}

html[data-theme="purple"] .logout-confirm-yes:hover {
    background: #6D4BC3;
}

/* BLACK */
html[data-theme="black"] .logout-confirm-icon {
    background: #E5E7EB;
    color: #374151;
}

html[data-theme="black"] .logout-confirm-yes {
    background: #6B7280;
    color: #FFFFFF;
}

html[data-theme="black"] .logout-confirm-yes:hover {
    background: #4B5563;
}

</style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<div class="sidebar">

    <button
        type="button"
        class="sidebar-toggle"
        id="sidebarToggle"
        title="Collapse sidebar"
    >

        <i class="bi bi-chevron-left"></i>

    </button>


    <div class="logo">

        Career<span>Flow</span>

    </div>


    <div class="menu-title">
        Main
    </div>


    <a
        href="../dashboard.php"
        class="nav-link"
    >

        <i class="bi bi-grid-1x2"></i>

        <span>
            Dashboard
        </span>

    </a>


    <a
        href="index.php"
        class="nav-link active"
    >

        <i class="bi bi-briefcase"></i>

        <span>
            Applications
        </span>

    </a>


    <a
        href="../companies/index.php"
        class="nav-link"
    >

        <i class="bi bi-buildings"></i>

        <span>
            Companies
        </span>

    </a>


    <a
        href="../calendar.php"
        class="nav-link"
    >

        <i class="bi bi-calendar3"></i>

        <span>
            Calendar
        </span>

    </a>


    <div class="menu-title account-title">
        Account
    </div>


    <a
        href="../profile.php"
        class="nav-link"
    >

        <i class="bi bi-person"></i>

        <span>
            Profile
        </span>

    </a>


    <div class="sidebar-spacer"></div>


    <!-- THEME -->

    <button
        type="button"
        class="nav-link theme-toggle"
        id="themeToggle"
    >

        <i class="bi bi-palette"></i>

        <span>
            Theme
        </span>

        <i class="bi bi-chevron-down theme-arrow"></i>

    </button>


    <div
        class="theme-options"
        id="themeOptions"
    >

        <div
            class="theme-option"
            data-theme-value="blue"
        >

            <span class="theme-dot blue"></span>

            <span>
                Blue
            </span>

        </div>


        <div
            class="theme-option"
            data-theme-value="pink"
        >

            <span class="theme-dot pink"></span>

            <span>
                Pink
            </span>

        </div>


        <div
            class="theme-option"
            data-theme-value="purple"
        >

            <span class="theme-dot purple"></span>

            <span>
                Purple
            </span>

        </div>


        <div
            class="theme-option"
            data-theme-value="black"
        >

            <span class="theme-dot black"></span>

            <span>
                Black
            </span>

        </div>

    </div>


    <a
    href="../logout.php"
    class="nav-link logout"
    id="logoutLink"
>
    <i class="bi bi-box-arrow-right"></i>
    <span>
        Logout
    </span>
</a>

</div>

<!-- =====================================================
     LOGOUT CONFIRMATION
===================================================== -->

<div
    class="logout-confirm-modal"
    id="logoutConfirmModal"
>
    <div class="logout-confirm-box">

        <div class="logout-confirm-icon">
            <i class="bi bi-box-arrow-right"></i>
        </div>

        <h3>
            Are you sure you want to leave?
        </h3>

        <p>
            You will be logged out of your CareerFlow account.
        </p>

        <div class="logout-confirm-actions">

            <button
                type="button"
                class="logout-confirm-cancel"
                id="logoutConfirmCancel"
            >
                Cancel
            </button>

            <a
                href="../logout.php"
                class="logout-confirm-yes"
            >
                Logout
            </a>

        </div>

    </div>
</div>

<!-- =====================================================
     MAIN
===================================================== -->

<div class="main">


    <!-- PAGE HEADER -->

    <div class="page-header">

        <div class="page-title">

            <h1>
                Add Application
            </h1>

            <p>
                Record a new job application and keep track of your progress.
            </p>

        </div>

    </div>


    <!-- =================================================
         CARD
    ================================================== -->

    <div class="main-card">


        <!-- CARD HEADER -->

        <div class="card-header-custom">

            <div>

                <h5>
                    Add Application
                </h5>

                <p>
                    Add a new job application to your tracker
                </p>

            </div>

        </div>


        <!-- FORM CONTENT -->

        <div class="form-content">

            <form method="POST">


                <!-- =================================================
                     APPLICATION DETAILS
                ================================================== -->

                <div class="section-title">
                    Application Details
                </div>


                <!-- COMPANY -->

                <div class="mb-4">

                    <label
                        class="form-label"
                    >
                        Company
                    </label>


                    <div class="company-row">

                        <select
                            name="company_id"
                            id="company_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select a company
                            </option>


                            <?php while (
                                $company =
                                mysqli_fetch_assoc($companies)
                            ): ?>

                                <option
                                    value="<?= (int) $company['id']; ?>"
                                >

                                    <?= htmlspecialchars(
                                        $company['nama_perusahaan']
                                    ); ?>

                                </option>

                            <?php endwhile; ?>

                        </select>


                        <button
                            type="button"
                            class="btn-add-company"
                            data-bs-toggle="modal"
                            data-bs-target="#companyModal"
                            title="Add New Company"
                        >

                            <i class="bi bi-plus-lg"></i>

                        </button>

                    </div>

                </div>


                <!-- POSITION -->

                <div class="mb-4">

                    <label
                        class="form-label"
                    >
                        Position
                    </label>

                    <input
                        type="text"
                        name="posisi"
                        class="form-control"
                        placeholder="e.g. Data Entry Staff"
                        required
                    >

                </div>


                <!-- DATE + SOURCE -->

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label
                            class="form-label"
                        >
                            Date Applied
                        </label>

                        <input
                            type="date"
                            name="tanggal_lamar"
                            class="form-control"
                            value="<?= date('Y-m-d'); ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-4">

                        <label
                            class="form-label"
                        >
                            Application Source
                        </label>

                        <select
                            name="sumber"
                            class="form-select"
                        >

                            <option value="">
                                Select source
                            </option>

                            <option value="LinkedIn">
                                LinkedIn
                            </option>

                            <option value="Jobstreet">
                                Jobstreet
                            </option>

                            <option value="Glints">
                                Glints
                            </option>

                            <option value="Kalibrr">
                                Kalibrr
                            </option>

                            <option value="Company Website">
                                Company Website
                            </option>

                            <option value="Indeed">
                                Indeed
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

                    </div>

                </div>


                <!-- STATUS + STAGE -->

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label
                            class="form-label"
                        >
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option value="To Apply">
                                To Apply
                            </option>

                            <option value="Applied">
                                Applied
                            </option>

                            <option value="Under Review">
                                Under Review
                            </option>

                            <option value="Interview">
                                Interview
                            </option>

                            <option value="Assessment">
                                Assessment
                            </option>

                            <option value="Offer">
                                Offer
                            </option>

                            <option value="Rejected">
                                Rejected
                            </option>

                            <option value="Withdrawn">
                                Withdrawn
                            </option>

                        </select>

                    </div>


                    <div class="col-md-6 mb-4">

                        <label
                            class="form-label"
                        >
                            Current Stage
                        </label>

                        <select
                            name="tahap"
                            class="form-select"
                        >

                            <option value="">
                                Select stage
                            </option>

                            <option value="CV Screening">
                                CV Screening
                            </option>

                            <option value="HR Interview">
                                HR Interview
                            </option>

                            <option value="User Interview">
                                User Interview
                            </option>

                            <option value="Technical Test">
                                Technical Test
                            </option>

                            <option value="Psychological Test">
                                Psychological Test
                            </option>

                            <option value="Final Interview">
                                Final Interview
                            </option>

                            <option value="Offering">
                                Offering
                            </option>

                        </select>

                    </div>

                </div>


                <div class="divider"></div>


                <!-- =================================================
                     IMPORTANT DATES
                ================================================== -->

                <div class="section-title">
                    Important Dates
                </div>


                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label
                            class="form-label"
                        >
                            Closing Date
                        </label>

                        <input
                            type="date"
                            name="deadline"
                            class="form-control"
                        >

                    </div>

                </div>


                <div class="divider"></div>


                <!-- =================================================
                     LINKS
                ================================================== -->

                <div class="section-title">
                    Links
                </div>


                <div class="mb-4">

                    <label
                        class="form-label"
                    >
                        Job Posting URL
                    </label>

                    <input
                        type="url"
                        name="link_lowongan"
                        class="form-control"
                        placeholder="https://..."
                    >

                </div>


                <div class="mb-4">

                    <label
                        class="form-label"
                    >
                        Company Website
                    </label>

                    <input
                        type="url"
                        name="link_perusahaan"
                        class="form-control"
                        placeholder="https://..."
                    >

                </div>


                <div class="divider"></div>


                <!-- =================================================
                     NOTES
                ================================================== -->

                <div class="section-title">
                    Notes
                </div>


                <div class="mb-4">

                    <label
                        class="form-label"
                    >
                        Notes
                    </label>

                    <textarea
                        name="catatan"
                        class="form-control"
                        rows="5"
                        placeholder="Add any useful notes about this application..."
                    ></textarea>

                </div>


                <!-- =================================================
                     BUTTONS
                ================================================== -->

                <div class="form-actions">

                    <a
                        href="index.php"
                        class="btn-cancel"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        name="save"
                        class="btn-save"
                    >

                        Save Application

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<!-- =====================================================
     ADD COMPANY MODAL
===================================================== -->

<div
    class="modal fade"
    id="companyModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div
        class="modal-dialog modal-dialog-centered"
    >

        <div
            class="modal-content"
        >


            <!-- MODAL HEADER -->

            <div class="modal-header">

                <h5 class="modal-title">
                    Add New Company
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <!-- MODAL FORM -->

            <form method="POST">


                <div class="modal-body">


                    <!-- COMPANY NAME -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                        >
                            Company Name
                        </label>

                        <input
                            type="text"
                            name="nama_perusahaan"
                            class="form-control"
                            placeholder="e.g. PT Bank Central Asia Tbk"
                            required
                        >

                    </div>


                    <!-- INDUSTRY -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                        >
                            Industry
                        </label>

                        <input
                            type="text"
                            name="industri"
                            class="form-control"
                            placeholder="e.g. Banking"
                        >

                    </div>


                    <!-- LOCATION -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                        >
                            Location
                        </label>

                        <input
                            type="text"
                            name="lokasi"
                            class="form-control"
                            placeholder="e.g. Jakarta"
                        >

                    </div>


                    <!-- WEBSITE -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                        >
                            Website
                        </label>

                        <input
                            type="url"
                            name="website"
                            class="form-control"
                            placeholder="https://..."
                        >

                    </div>


                    <!-- NOTES -->

                    <div class="mb-0">

                        <label
                            class="form-label"
                        >
                            Notes
                        </label>

                        <textarea
                            name="catatan"
                            class="form-control"
                            rows="3"
                            placeholder="Add company notes..."
                        ></textarea>

                    </div>

                </div>


                <!-- MODAL FOOTER -->

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn-cancel"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        name="add_company"
                        class="btn-save-company"
                    >

                        Save Company

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- =====================================================
     BOOTSTRAP JS
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
></script>


<script>

/* =====================================================
   SIDEBAR TOGGLE
===================================================== */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const sidebarToggle =
            document.getElementById(
                'sidebarToggle'
            );

        if (!sidebarToggle) {
            return;
        }

        const icon =
            sidebarToggle.querySelector('i');


        /* RESTORE SIDEBAR STATE */

        const sidebarState =
            localStorage.getItem(
                'careerFlowSidebar'
            );


        if (sidebarState === 'true') {

            document.body.classList.add(
                'sidebar-collapsed'
            );

            icon.classList.remove(
                'bi-chevron-left'
            );

            icon.classList.add(
                'bi-chevron-right'
            );

            sidebarToggle.title =
                'Expand sidebar';

        } else {

            document.body.classList.remove(
                'sidebar-collapsed'
            );

            icon.classList.remove(
                'bi-chevron-right'
            );

            icon.classList.add(
                'bi-chevron-left'
            );

            sidebarToggle.title =
                'Collapse sidebar';
        }


        /* TOGGLE */

        sidebarToggle.addEventListener(
            'click',
            function () {

                document.body.classList.toggle(
                    'sidebar-collapsed'
                );


                const collapsed =
                    document.body.classList.contains(
                        'sidebar-collapsed'
                    );


                /* SAVE STATE */

                localStorage.setItem(
                    'careerFlowSidebar',
                    collapsed
                );


                /* CHANGE ICON */

                if (collapsed) {

                    icon.classList.remove(
                        'bi-chevron-left'
                    );

                    icon.classList.add(
                        'bi-chevron-right'
                    );

                    sidebarToggle.title =
                        'Expand sidebar';

                } else {

                    icon.classList.remove(
                        'bi-chevron-right'
                    );

                    icon.classList.add(
                        'bi-chevron-left'
                    );

                    sidebarToggle.title =
                        'Collapse sidebar';
                }

            }
        );

    }
);


/* =====================================================
   THEME MENU
===================================================== */

const themeToggle =
    document.getElementById(
        'themeToggle'
    );

const themeOptions =
    document.getElementById(
        'themeOptions'
    );

const themeOptionElements =
    document.querySelectorAll(
        '.theme-option'
    );


/* CURRENT THEME */

const currentTheme =
    localStorage.getItem(
        'careerFlowTheme'
    ) || 'blue';


document.documentElement.setAttribute(
    'data-theme',
    currentTheme
);


/* ACTIVE THEME */

themeOptionElements.forEach(
    function (option) {

        if (
            option.dataset.themeValue ===
            currentTheme
        ) {

            option.classList.add(
                'active'
            );

        }

    }
);


/* OPEN THEME */

if (
    themeToggle &&
    themeOptions
) {

    themeToggle.addEventListener(
        'click',
        function (event) {

            event.preventDefault();

            event.stopPropagation();

            themeOptions.classList.toggle(
                'show'
            );

            themeToggle.classList.toggle(
                'open'
            );

        }
    );

}


/* SELECT THEME */

themeOptionElements.forEach(
    function (option) {

        option.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();


                const theme =
                    this.dataset.themeValue;


                /* APPLY */

                document.documentElement.setAttribute(
                    'data-theme',
                    theme
                );


                /* SAVE */

                localStorage.setItem(
                    'careerFlowTheme',
                    theme
                );


                /* REMOVE ACTIVE */

                themeOptionElements.forEach(
                    function (item) {

                        item.classList.remove(
                            'active'
                        );

                    }
                );


                /* ACTIVE */

                this.classList.add(
                    'active'
                );


                /* KEEP THEME MENU OPEN */
themeOptions.classList.add('show');
themeToggle.classList.add('open');

            }
        );

    }
);


/* =====================================================
   AUTO SELECT NEW COMPANY
===================================================== */

const params =
    new URLSearchParams(
        window.location.search
    );

const companyId =
    params.get('company');


if (companyId) {

    const companySelect =
        document.getElementById(
            'company_id'
        );

    if (companySelect) {

        companySelect.value =
            companyId;
    }

}
/* =====================================================
   LOGOUT CONFIRMATION
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const logoutLink =
        document.getElementById('logoutLink');

    const logoutConfirmModal =
        document.getElementById('logoutConfirmModal');

    const logoutConfirmCancel =
        document.getElementById('logoutConfirmCancel');

    if (!logoutLink || !logoutConfirmModal || !logoutConfirmCancel) {
        return;
    }

    /* OPEN MODAL */
    logoutLink.addEventListener('click', function (event) {

        event.preventDefault();

        logoutConfirmModal.classList.add('show');

    });

    /* CANCEL */
    logoutConfirmCancel.addEventListener('click', function () {

        logoutConfirmModal.classList.remove('show');

    });

    /* CLICK OUTSIDE MODAL */
    logoutConfirmModal.addEventListener('click', function (event) {

        if (event.target === logoutConfirmModal) {

            logoutConfirmModal.classList.remove('show');

        }

    });

    /* ESCAPE */
    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            logoutConfirmModal.classList.remove('show');

        }

    });

});

</script>


</body>

</html>