<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}


/* =========================================================
   AMBIL DATA PERUSAHAAN
========================================================= */

$query = mysqli_query(
    $koneksi,
    "SELECT *
     FROM companies
     WHERE id = $id
     LIMIT 1"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit;
}


/* =========================================================
   PROSES UPDATE
========================================================= */

if (isset($_POST['update'])) {

    $nama_perusahaan = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['nama_perusahaan'])
    );

    $industri = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['industri'])
    );

    $lokasi = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['lokasi'])
    );

    $website = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['website'])
    );

    $catatan = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['catatan'])
    );

    if ($nama_perusahaan != '') {

        mysqli_query(
            $koneksi,
            "UPDATE companies SET
                nama_perusahaan = '$nama_perusahaan',
                industri = '$industri',
                lokasi = '$lokasi',
                website = '$website',
                catatan = '$catatan'
             WHERE id = $id"
        );

        header("Location: index.php?success=updated");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Company - CareerFlow</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >


    <!-- Poppins -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- Theme -->

    <link
        href="../../assets/css/theme.css?v=3"
        rel="stylesheet"
    >


    <!-- Theme Loader -->

    <script>
    (function () {

        const theme =
            localStorage.getItem('careerFlowTheme') || 'blue';

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
    font-family: 'Poppins', sans-serif;
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
   THEME SIDEBAR
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

html[data-theme="pink"] .sidebar {
    background:
        linear-gradient(
            180deg,
            #9D174D 0%,
            #BE185D 50%,
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

html[data-theme="black"] .sidebar {
    background:
        linear-gradient(
            180deg,
            #111827 0%,
            #1F2937 50%,
            #374151 100%
        ) !important;
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


/* BLUE */

html[data-theme="blue"] .logo span {
    color: #60A5FA !important;
}


/* PINK */

html[data-theme="pink"] .logo span {
    color: #F9A8D4 !important;
}


/* PURPLE */

html[data-theme="purple"] .logo span {
    color: #C4B5FD !important;
}


/* BLACK */

html[data-theme="black"] .logo span {
    color: #D1D5DB !important;
}


/* =====================================================
   MENU TITLE
===================================================== */

.menu-title {
    color:
        rgba(255,255,255,.7) !important;

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
   NAV LINK
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
    transition: transform .2s ease;
}

.theme-toggle.open .theme-arrow {
    transform: rotate(180deg);
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
    padding: 9px 14px 9px 48px;
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
    background: rgba(255,255,255,.10);
    transform: translateX(4px);
}

.theme-option.active {
    color: #FFFFFF;
    font-weight: 600;
    background: rgba(255,255,255,.14);
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

body.sidebar-collapsed .theme-toggle .theme-arrow {
    display: none;
}

body.sidebar-collapsed .theme-options {
    display: none !important;
}


/* =====================================================
   ACTIVE
===================================================== */

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
    color: #FFFFFF !important;

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
   LOGOUT
===================================================== */

.nav-link.logout {
    font-weight: 700 !important;

    border-radius: 10px;

    transition:
        all .2s ease !important;
}


/* BLUE */

html[data-theme="blue"] .nav-link.logout {
    color: #1E40AF !important;
    background: #DBEAFE !important;
}

html[data-theme="blue"] .nav-link.logout:hover {
    color: #1E3A8A !important;
    background: #BFDBFE !important;
}


/* PINK */

html[data-theme="pink"] .nav-link.logout {
    color: #9D174D !important;
    background: #FCE7F3 !important;
}

html[data-theme="pink"] .nav-link.logout:hover {
    color: #831843 !important;
    background: #FBCFE8 !important;
}


/* PURPLE */

html[data-theme="purple"] .nav-link.logout {
    color: #5B21B6 !important;
    background: #EDE9FE !important;
}

html[data-theme="purple"] .nav-link.logout:hover {
    color: #4C1D95 !important;
    background: #DDD6FE !important;
}


/* BLACK */

html[data-theme="black"] .nav-link.logout {
    color: #374151 !important;
    background: #E5E7EB !important;
}

html[data-theme="black"] .nav-link.logout:hover {
    color: #1F2937 !important;
    background: #D1D5DB !important;
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

    transition: all .2s ease;

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


/* BLUE */

html[data-theme="blue"] .sidebar-toggle {
    border-color: #93C5FD;

    box-shadow:
        0 3px 10px rgba(37,99,235,.30),
        0 2px 4px rgba(37,99,235,.15);
}

html[data-theme="blue"] .sidebar-toggle:hover {
    box-shadow:
        0 5px 14px rgba(37,99,235,.40),
        0 2px 5px rgba(37,99,235,.20);
}


/* PINK */

html[data-theme="pink"] .sidebar-toggle {
    border-color: #F9A8D4;

    box-shadow:
        0 3px 10px rgba(236,72,153,.30),
        0 2px 4px rgba(236,72,153,.15);
}

html[data-theme="pink"] .sidebar-toggle:hover {
    box-shadow:
        0 5px 14px rgba(236,72,153,.40),
        0 2px 5px rgba(236,72,153,.20);
}


/* PURPLE */

html[data-theme="purple"] .sidebar-toggle {
    border-color: #C4B5FD;

    box-shadow:
        0 3px 10px rgba(128,102,216,.30),
        0 2px 4px rgba(128,102,216,.15);
}

html[data-theme="purple"] .sidebar-toggle:hover {
    box-shadow:
        0 5px 14px rgba(128,102,216,.40),
        0 2px 5px rgba(128,102,216,.20);
}


/* BLACK */

html[data-theme="black"] .sidebar-toggle {
    border-color: #9CA3AF;

    box-shadow:
        0 3px 10px rgba(107,114,128,.35),
        0 2px 4px rgba(75,85,99,.20);
}

html[data-theme="black"] .sidebar-toggle:hover {
    box-shadow:
        0 5px 14px rgba(107,114,128,.40),
        0 2px 5px rgba(75,85,99,.20);
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
   FORM CARD
===================================================== */

.main-card {
    background: white;

    border:
        1px solid #E2E8F0;

    border-radius: 12px;

    overflow: hidden;

    box-shadow:
        0 4px 15px rgba(15,23,42,.03);
}

.card-header-custom {
    padding: 23px 26px;

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
    margin: 6px 0 0;

    color: #94A3B8;

    font-size: 14px;
}


/* =====================================================
   FORM
===================================================== */

.form-body {
    padding: 26px;
}

.form-label {
    font-weight: 600;

    color: #334155;

    margin-bottom: 8px;

    font-size: 14px;
}

.form-control {
    min-height: 46px;

    border-radius: 8px;

    border:
        1px solid #CBD5E1;

    font-size: 14px;

    color: #0F172A;

    transition:
        all .2s ease;
}

.form-control:focus {
    border-color:
        var(--accent);

    box-shadow:
        0 0 0 3px var(--accent-light);
}

textarea.form-control {
    min-height: 120px;

    resize: vertical;
}


/* =====================================================
   SAVE CHANGES
===================================================== */

.btn-save {
    color: #FFFFFF !important;

    border: none;

    border-radius: 8px;

    padding:
        10px 16px;

    font-size: 14px;

    font-weight: 600;

    transition:
        all .2s ease;
}


/* BLUE */

html[data-theme="blue"] .btn-save {
    background: #2563EB !important;
}

html[data-theme="blue"] .btn-save:hover {
    background: #1D4ED8 !important;
}


/* PINK */

html[data-theme="pink"] .btn-save {
    background: #EC4899 !important;
}

html[data-theme="pink"] .btn-save:hover {
    background: #DB2777 !important;
}


/* PURPLE */

html[data-theme="purple"] .btn-save {
    background: #8066D8 !important;
}

html[data-theme="purple"] .btn-save:hover {
    background: #6D52C7 !important;
}


/* BLACK */

html[data-theme="black"] .btn-save {
    background: #6B7280 !important;
}

html[data-theme="black"] .btn-save:hover {
    background: #4B5563 !important;
}

.btn-save:hover {
    color: #FFFFFF !important;

    transform:
        translateY(-1px);

    box-shadow:
        0 5px 12px var(--shadow-color);
}


/* =====================================================
   CANCEL
===================================================== */

.btn-cancel {
    background: #F1F5F9;

    color: #334155;

    border: none;

    border-radius: 8px;

    padding:
        10px 16px;

    font-size: 14px;

    font-weight: 500;

    transition:
        all .2s ease;
}

.btn-cancel:hover {
    background: #E2E8F0;

    color: #0F172A;

    transform:
        translateY(-1px);
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
        padding: 20px;
    }

    .card-header-custom h5 {
        font-size: 17px;
    }

    .card-header-custom p {
        font-size: 13px;
    }

    .form-body {
        padding: 20px;
    }

    .form-label {
        font-size: 13px;
    }

    .form-control {
        font-size: 13px;
    }

    .btn-save,
    .btn-cancel {
        font-size: 13px;

        padding:
            9px 14px;
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


<!-- =========================================================
     SIDEBAR
========================================================= -->

<div class="sidebar">


    <!-- TOGGLE -->

    <button
        type="button"
        class="sidebar-toggle"
        id="sidebarToggle"
        title="Collapse sidebar"
    >

        <i class="bi bi-chevron-left"></i>

    </button>


    <!-- LOGO -->

    <div class="logo">

        Career<span>Flow</span>

    </div>


    <!-- MAIN -->

    <div class="menu-title">

        Main

    </div>


    <!-- DASHBOARD -->

    <a
        href="../dashboard.php"
        class="nav-link"
    >

        <i class="bi bi-grid-1x2"></i>

        <span>
            Dashboard
        </span>

    </a>


    <!-- APPLICATIONS -->

    <a
        href="../lamaran/index.php"
        class="nav-link"
    >

        <i class="bi bi-briefcase"></i>

        <span>
            Applications
        </span>

    </a>


    <!-- COMPANIES -->

    <a
        href="index.php"
        class="nav-link active"
    >

        <i class="bi bi-buildings"></i>

        <span>
            Companies
        </span>

    </a>


    <!-- CALENDAR -->

    <a
        href="../calendar.php"
        class="nav-link"
    >

        <i class="bi bi-calendar3"></i>

        <span>
            Calendar
        </span>

    </a>


    <!-- ACCOUNT -->

    <div class="menu-title account-title">

        Account

    </div>


    <!-- PROFILE -->

    <a
        href="../profile.php"
        class="nav-link"
    >

        <i class="bi bi-person"></i>

        <span>
            Profile
        </span>

    </a>


    <!-- SPACER -->

    <div class="sidebar-spacer"></div>


    <!-- THEME -->
<button
    type="button"
    class="nav-link theme-toggle"
    id="themeToggle"
>
    <i class="bi bi-palette"></i>
    <span>Theme</span>
    <i class="bi bi-chevron-down theme-arrow"></i>
</button>

<div class="theme-options" id="themeOptions">

    <div
        class="theme-option"
        data-theme-value="blue"
    >
        <span class="theme-dot blue"></span>
        <span>Blue</span>
    </div>

    <div
        class="theme-option"
        data-theme-value="pink"
    >
        <span class="theme-dot pink"></span>
        <span>Pink</span>
    </div>

    <div
        class="theme-option"
        data-theme-value="purple"
    >
        <span class="theme-dot purple"></span>
        <span>Purple</span>
    </div>

    <div
        class="theme-option"
        data-theme-value="black"
    >
        <span class="theme-dot black"></span>
        <span>Black</span>
    </div>

</div>


    <!-- LOGOUT -->
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


<!-- =========================================================
     MAIN
========================================================= -->

<div class="main">


    <!-- PAGE HEADER -->

    <div class="page-header">

        <div class="page-title">

            <h1>
                Edit Company
            </h1>

            <p>
                Update company information.
            </p>

        </div>

    </div>


    <!-- =====================================================
         FORM CARD
    ====================================================== -->

    <div class="main-card">


        <!-- CARD HEADER -->

        <div class="card-header-custom">

            <div>

                <h5>
                    Company Information
                </h5>

                <p>
                    Update the details of this company.
                </p>

            </div>

        </div>


        <!-- FORM BODY -->

        <div class="form-body">

            <form method="POST">


                <!-- COMPANY NAME -->

                <div class="mb-4">

                    <label class="form-label">
                        Company Name
                    </label>

                    <input
                        type="text"
                        name="nama_perusahaan"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $data['nama_perusahaan']
                        ); ?>"
                        required
                    >

                </div>


                <!-- INDUSTRY + LOCATION -->

                <div class="row">


                    <!-- INDUSTRY -->

                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Industry
                        </label>

                        <input
                            type="text"
                            name="industri"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $data['industri'] ?? ''
                            ); ?>"
                        >

                    </div>


                    <!-- LOCATION -->

                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Location
                        </label>

                        <input
                            type="text"
                            name="lokasi"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $data['lokasi'] ?? ''
                            ); ?>"
                        >

                    </div>

                </div>


                <!-- WEBSITE -->

                <div class="mb-4">

                    <label class="form-label">
                        Website
                    </label>

                    <input
                        type="url"
                        name="website"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $data['website'] ?? ''
                        ); ?>"
                        placeholder="https://example.com"
                    >

                </div>


                <!-- NOTES -->

                <div class="mb-4">

                    <label class="form-label">
                        Notes
                    </label>

                    <textarea
                        name="catatan"
                        class="form-control"
                        placeholder="Additional notes about this company..."
                    ><?= htmlspecialchars(
                        $data['catatan'] ?? ''
                    ); ?></textarea>

                </div>


                <!-- BUTTON -->

                <div class="d-flex gap-2">

                    <a
                        href="index.php"
                        class="btn btn-cancel"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        name="update"
                        class="btn btn-save"
                    >
                        Save Changes
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

<!-- =====================================================
     LOGOUT CONFIRMATION MODAL
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


<!-- =========================================================
     SIDEBAR TOGGLE
========================================================= -->

<script>
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

        /* AMBIL STATUS SIDEBAR */
        const sidebarState =
            localStorage.getItem(
                'careerFlowSidebar'
            );

        /* TERAPKAN STATUS TERAKHIR */
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

        /* TOGGLE SIDEBAR */
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

                /* SIMPAN STATUS */
                localStorage.setItem(
                    'careerFlowSidebar',
                    collapsed
                );

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
</script>

<!-- =====================================================
     THEME MENU
===================================================== -->

<script>

const themeToggle =
    document.getElementById('themeToggle');

const themeOptions =
    document.getElementById('themeOptions');

const themeOptionElements =
    document.querySelectorAll('.theme-option');

const currentTheme =
    localStorage.getItem('careerFlowTheme') || 'blue';

document.documentElement.setAttribute(
    'data-theme',
    currentTheme
);

themeOptionElements.forEach(function (option) {

    if (option.dataset.themeValue === currentTheme) {
        option.classList.add('active');
    }

});

if (themeToggle) {

    themeToggle.addEventListener(
        'click',
        function () {

            themeOptions.classList.toggle('show');
            themeToggle.classList.toggle('open');

        }
    );

}

themeOptionElements.forEach(function (option) {

    option.addEventListener(
        'click',
        function () {

            const theme =
                this.dataset.themeValue;

            document.documentElement.setAttribute(
                'data-theme',
                theme
            );

            localStorage.setItem(
                'careerFlowTheme',
                theme
            );

            themeOptionElements.forEach(
                function (item) {

                    item.classList.remove('active');

                }
            );

            this.classList.add('active');

        }
    );

});

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

    if (
        !logoutLink ||
        !logoutConfirmModal ||
        !logoutConfirmCancel
    ) {
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