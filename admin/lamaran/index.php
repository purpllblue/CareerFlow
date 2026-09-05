<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";


/* =========================================================
   DATA APPLICATIONS
========================================================= */

$data = mysqli_query(
    $koneksi,
    "SELECT
        lamaran.*,
        companies.nama_perusahaan
     FROM lamaran
     LEFT JOIN companies
        ON lamaran.company_id = companies.id
     ORDER BY lamaran.id DESC"
);

if (!$data) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$success = $_GET['success'] ?? '';


/* =========================================================
   DATE
========================================================= */

$today = new DateTime(
    date('Y-m-d')
);


/* =========================================================
   DATE FORMAT
========================================================= */

function formatDateId($date)
{
    if (!$date) {
        return '-';
    }

    $months = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'May',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Aug',
        9 => 'Sep',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dec'
    ];

    $timestamp = strtotime($date);

    if (!$timestamp) {
        return '-';
    }

    return date('d', $timestamp)
        . ' '
        . $months[(int) date('n', $timestamp)]
        . ' '
        . date('Y', $timestamp);
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

<title>
    Applications - CareerFlow
</title>


<!-- =====================================================
     BOOTSTRAP
===================================================== -->

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
    rel="stylesheet"
>


<!-- =====================================================
     BOOTSTRAP ICONS
===================================================== -->

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
    rel="stylesheet"
>


<!-- =====================================================
     GOOGLE FONT
===================================================== -->

<link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet"
>


<!-- =====================================================
     GLOBAL THEME
===================================================== -->

<link
    href="../assets/css/theme.css?v=3"
    rel="stylesheet"
>


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
        1px solid rgba(255, 255, 255, .08);

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
        0 2px 4px rgba(15, 23, 42, .20);
}

.logo span {
    color: var(--flow-color);
}


/* =====================================================
   MENU TITLE
===================================================== */

.menu-title {
    color: #94A3B8;

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
    color: #94A3B8;

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
    color: white;

    background:
        rgba(255, 255, 255, .12);

    box-shadow:
        0 8px 16px rgba(0, 0, 0, .20),
        0 3px 6px rgba(0, 0, 0, .12);

    transform:
        translateX(6px)
        translateY(-3px);

    border-right:
        2px solid rgba(255, 255, 255, .25);
}

.nav-link.active {
    color: white;

    background:
        linear-gradient(
            135deg,
            var(--accent) 0%,
            var(--accent-hover) 100%
        );

    box-shadow:
        0 4px 12px var(--shadow-color),
        inset 0 1px 0 rgba(255, 255, 255, .15);
}

.nav-link.active:hover {
    transform:
        translateX(6px)
        translateY(-3px);

    box-shadow:
        0 9px 18px var(--shadow-color),
        inset 0 1px 0 rgba(255, 255, 255, .15);
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


/* LOGOUT ICON */

.nav-link.logout i,
.nav-link.logout:hover i {
    color: inherit !important;
}


/* LOGOUT HOVER */

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

    color: var(--accent);

    display: flex;

    align-items: center;

    justify-content: center;

    cursor: pointer;

    transition:
        all .2s ease;

    z-index: 1001;
}


/* BLUE */

html[data-theme="blue"] .sidebar-toggle {
    border-color: #93C5FD;

    box-shadow:
        0 3px 10px rgba(37, 99, 235, 0.30),
        0 2px 4px rgba(37, 99, 235, 0.15);
}


/* PINK */

html[data-theme="pink"] .sidebar-toggle {
    border-color: #F9A8D4;

    box-shadow:
        0 3px 10px rgba(236, 72, 153, 0.30),
        0 2px 4px rgba(236, 72, 153, 0.15);
}


/* PURPLE */

html[data-theme="purple"] .sidebar-toggle {
    border-color: #C4B5FD;

    box-shadow:
        0 3px 10px rgba(128, 102, 216, 0.30),
        0 2px 4px rgba(128, 102, 216, 0.15);
}


/* BLACK */

html[data-theme="black"] .sidebar-toggle {
    border-color: #9CA3AF;

    box-shadow:
        0 3px 10px rgba(107, 114, 128, 0.35),
        0 2px 4px rgba(75, 85, 99, 0.20);
}


/* TOGGLE HOVER */

.sidebar-toggle:hover {
    color: var(--accent);

    border-color: var(--accent);

    transform:
        scale(1.05);
}

html[data-theme="blue"] .sidebar-toggle:hover {
    box-shadow:
        0 5px 14px rgba(37, 99, 235, 0.40),
        0 2px 5px rgba(37, 99, 235, 0.20);
}

html[data-theme="pink"] .sidebar-toggle:hover {
    box-shadow:
        0 5px 14px rgba(236, 72, 153, 0.40),
        0 2px 5px rgba(236, 72, 153, 0.20);
}

html[data-theme="purple"] .sidebar-toggle:hover {
    box-shadow:
        0 5px 14px rgba(128, 102, 216, 0.40),
        0 2px 5px rgba(128, 102, 216, 0.20);
}

html[data-theme="black"] .sidebar-toggle:hover {
    box-shadow:
        0 5px 14px rgba(107, 114, 128, 0.40),
        0 2px 5px rgba(107, 114, 128, 0.20);
}


/* =====================================================
   COLLAPSED SIDEBAR
===================================================== */

body.sidebar-collapsed .sidebar {
    width: 72px;

    box-shadow:
        6px 0 20px rgba(15, 23, 42, .16);
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

    justify-content: space-between;

    align-items: flex-start;

    margin-bottom: 24px;
}

.page-title h1 {
    font-size: 36px;

    font-weight: 700;

    margin-bottom: 9px;

    color: #0F172A;
}

.page-title p {
    color: #64748B;

    font-size: 17px;

    margin: 0;
}


/* =====================================================
   APPLICATIONS TITLE — THEME
===================================================== */

html[data-theme="blue"] .page-title h1 {
    color: #2563EB;
}

html[data-theme="pink"] .page-title h1 {
    color: #EC4899;
}

html[data-theme="purple"] .page-title h1 {
    color: #8066D8;
}

html[data-theme="black"] .page-title h1 {
    color: #6B7280;
}


/* =====================================================
   ADD APPLICATION
===================================================== */

.btn-add {
    font-size: 14px;

    padding:
        10px 16px;

    border-radius: 8px;

    font-weight: 600 !important;

    transition:
        all .2s ease;
}


/* BLUE */

html[data-theme="blue"] .btn-add {
    background: #2563EB !important;

    border-color: #2563EB !important;

    color: #FFFFFF !important;
}

html[data-theme="blue"] .btn-add:hover {
    background: #1D4ED8 !important;

    border-color: #1D4ED8 !important;

    color: #FFFFFF !important;
}


/* PINK */

html[data-theme="pink"] .btn-add {
    background: #EC4899 !important;

    border-color: #EC4899 !important;

    color: #FFFFFF !important;
}

html[data-theme="pink"] .btn-add:hover {
    background: #DB2777 !important;

    border-color: #DB2777 !important;

    color: #FFFFFF !important;
}


/* PURPLE */

html[data-theme="purple"] .btn-add {
    background: #8B5CF6 !important;

    border-color: #8B5CF6 !important;

    color: #FFFFFF !important;
}

html[data-theme="purple"] .btn-add:hover {
    background: #7C3AED !important;

    border-color: #7C3AED !important;

    color: #FFFFFF !important;
}


/* BLACK */

html[data-theme="black"] .btn-add {
    background: #6B7280 !important;

    border-color: #6B7280 !important;

    color: #FFFFFF !important;
}

html[data-theme="black"] .btn-add:hover {
    background: #4B5563 !important;

    border-color: #4B5563 !important;

    color: #FFFFFF !important;
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
   TABLE
===================================================== */

.table {
    margin: 0;
}

.table th {
    font-size: 14px;

    font-weight: 600;

    color: #64748B;

    background: #F8FAFC;

    border-bottom:
        1px solid #E2E8F0;

    padding:
        18px 20px;

    white-space: nowrap;
}

.table td {
    font-size: 15px;

    color: #334155;

    padding:
        19px 20px;

    border-bottom:
        1px solid #F1F5F9;

    vertical-align: middle;
}

.table tbody tr:last-child td {
    border-bottom: 0;
}

.table tbody tr:hover {
    background: #F8FAFC;
}


/* =====================================================
   COMPANY
===================================================== */

.table td strong {
    font-weight: 700;

    color: #0F172A;
}


/* =====================================================
   STATUS
===================================================== */

.status-badge {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding:
        6px 11px;

    border-radius:
        6px;

    font-size:
        12px;

    font-weight:
        600;

    white-space: nowrap;
}

.status-applied {
    background: #DBEAFE;

    color: #2563EB;
}

.status-review {
    background: #FEF3C7;

    color: #D97706;
}

.status-interview {
    background: #EDE9FE;

    color: #7C3AED;
}

.status-assessment {
    background: #E0F2FE;

    color: #0284C7;
}

.status-offer {
    background: #DCFCE7;

    color: #16A34A;
}

.status-rejected {
    background: #FEE2E2;

    color: #DC2626;
}

.status-default {
    background: #F1F5F9;

    color: #475569;
}


/* =====================================================
   DEADLINE
===================================================== */

.deadline-warning {
    color: #EA580C !important;

    font-weight: 600;
}

.deadline-urgent {
    color: #DC2626 !important;

    font-weight: 600;
}

.deadline-overdue {
    color: #DC2626 !important;

    font-weight: 700;
}


/* =====================================================
   MORE ACTIONS
===================================================== */

.action-menu-btn {
    width: 35px;
    height: 35px;
    border: 1px solid #E2E8F0;
    border-radius: 7px;
    background: white;
    color: #64748B;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    cursor: pointer;
    transition: all .2s ease;
}

.action-menu-btn:hover {
    background: #F8FAFC;
    color: var(--accent);
    border-color: var(--accent-border);
}

.dropdown-menu {
    min-width: 140px;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 6px;
    box-shadow:
        0 10px 25px rgba(15, 23, 42, .12);
}

.dropdown-item {
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 14px;
    color: #334155;
}

.dropdown-item:hover {
    background: #EFF6FF;
    color: #2563EB;
}

.dropdown-item:hover {
    background: #EFF6FF;
    color: #2563EB;
}

.dropdown-item.edit {
    color: #2563EB;
}

.dropdown-item.edit:hover {
    background: #EFF6FF;
    color: #2563EB;
}

.dropdown-item.delete {
    color: #DC2626;
}

.dropdown-item.delete {
    color: #DC2626;
}

.dropdown-item.delete:hover {
    background: #FEF2F2;
    color: #DC2626;
}

/* =====================================================
   EMPTY STATE
===================================================== */

.empty-state {
    padding:
        50px 20px;

    text-align: center;

    color: #94A3B8;
}

.empty-state i {
    font-size: 36px;

    display: block;

    margin-bottom: 10px;

    color: #CBD5E1;
}

.empty-state-title {
    font-size: 16px;

    font-weight: 600;

    color: #475569;

    margin-bottom: 4px;
}

.empty-state-text {
    font-size: 13px;

    color: #94A3B8;
}


/* =====================================================
   DELETE MODAL
===================================================== */

.action-modal-icon {
    width: 48px;
    height: 48px;

    margin:
        0 auto 14px;

    border-radius: 50%;

    background: #FEF2F2;

    color: #DC2626;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 22px;
}

.action-modal-title {
    font-size: 20px;

    font-weight: 700;

    color: #0F172A;

    text-align: center;

    margin-bottom: 7px;
}

.action-modal-text {
    font-size: 15px;

    color: #64748B;

    text-align: center;

    margin-bottom: 20px;
}


/* =====================================================
   SUCCESS TOAST
===================================================== */

.success-toast {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;

    min-width: 320px;

    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;

    box-shadow:
        0 10px 30px rgba(15, 23, 42, .12);

    padding: 14px 16px;

    display: flex;
    align-items: flex-start;
    gap: 12px;

    animation:
        toastSlideIn .3s ease;

    position: fixed;
    overflow: hidden;
}

/* =====================================================
   STRIP KIRI
===================================================== */

.success-toast::before {
    content: "";
    position: absolute;

    left: 0;
    top: 0;
    bottom: 0;

    width: 4px;

    background: var(--accent);

    border-radius:
        10px 0 0 10px;
}

/* =====================================================
   SUCCESS ICON
===================================================== */

.success-toast-icon {
    width: 34px;
    height: 34px;

    flex-shrink: 0;

    border-radius: 50%;

    background: var(--accent-light);
    color: var(--accent);

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 17px;
}

/* =====================================================
   SUCCESS CONTENT
===================================================== */

.success-toast-content {
    flex: 1;
}

.success-toast-title {
    font-size: 15px;
    font-weight: 700;

    color: #0F172A;

    margin-bottom: 2px;
}

.success-toast-text {
    font-size: 13px;

    color: #64748B;

    line-height: 1.5;
}

/* =====================================================
   SUCCESS CLOSE
===================================================== */

.success-toast-close {
    border: 0;
    background: transparent;

    color: #94A3B8;

    padding: 0;

    font-size: 17px;

    cursor: pointer;

    transition:
        color .2s ease;
}

.success-toast-close:hover {
    color: var(--accent);
}

/* =====================================================
   SUCCESS TOAST — BLUE
===================================================== */

html[data-theme="blue"] .success-toast::before {
    background: #2563EB;
}

html[data-theme="blue"] .success-toast {
    border-color: #BFDBFE;
}

html[data-theme="blue"] .success-toast-icon {
    background: #DBEAFE;
    color: #2563EB;
}

html[data-theme="blue"] .success-toast-title {
    color: #1E40AF;
}

/* =====================================================
   SUCCESS TOAST — PINK
===================================================== */

html[data-theme="pink"] .success-toast::before {
    background: #EC4899;
}

html[data-theme="pink"] .success-toast {
    border-color: #F9A8D4;
}

html[data-theme="pink"] .success-toast-icon {
    background: #FCE7F3;
    color: #EC4899;
}

html[data-theme="pink"] .success-toast-title {
    color: #9D174D;
}

/* =====================================================
   SUCCESS TOAST — PURPLE
===================================================== */

html[data-theme="purple"] .success-toast::before {
    background: #8066D8;
}

html[data-theme="purple"] .success-toast {
    border-color: #C4B5FD;
}

html[data-theme="purple"] .success-toast-icon {
    background: #EDE9FE;
    color: #8066D8;
}

html[data-theme="purple"] .success-toast-title {
    color: #5B21B6;
}

/* =====================================================
   SUCCESS TOAST — BLACK
===================================================== */

html[data-theme="black"] .success-toast::before {
    background: #6B7280;
}

html[data-theme="black"] .success-toast {
    border-color: #9CA3AF;
}

html[data-theme="black"] .success-toast-icon {
    background: #E5E7EB;
    color: #6B7280;
}

html[data-theme="black"] .success-toast-title {
    color: #374151;
}


/* =====================================================
   TOAST ANIMATION
===================================================== */

@keyframes toastSlideIn {

    from {
        opacity: 0;

        transform:
            translateX(30px);
    }

    to {
        opacity: 1;

        transform:
            translateX(0);
    }

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

    .table th {
        font-size: 12px;

        padding:
            14px 16px;
    }

    .table td {
        font-size: 13px;

        padding:
            15px 16px;
    }

    .status-badge {
        font-size: 11px;

        padding:
            5px 9px;
    }

    .success-toast {
        left: 20px;

        right: 20px;

        min-width: auto;
    }

}


/* =====================================================
   SIDEBAR THEME COLORS
===================================================== */


/* BLUE */

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


/* PINK */

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


/* PURPLE */

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


/* BLACK */

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
   LOGO FLOW
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
        rgba(255, 255, 255, 0.7) !important;
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
     SUCCESS TOAST
===================================================== -->

<?php if (
    in_array(
        $success,
        [
            'added',
            'updated',
            'deleted'
        ]
    )
): ?>

<div
    class="success-toast"
    id="successToast"
>

    <div class="success-toast-icon">

        <i class="bi bi-check-lg"></i>

    </div>


    <div class="success-toast-content">

        <div class="success-toast-title">

            <?php if (
                $success === 'added'
            ): ?>

                APPLICATION ADDED!

            <?php elseif (
                $success === 'updated'
            ): ?>

                APPLICATION UPDATED!

            <?php elseif (
                $success === 'deleted'
            ): ?>

                APPLICATION DELETED!

            <?php endif; ?>

        </div>


        <div class="success-toast-text">

            <?php if (
                $success === 'added'
            ): ?>

                Your application has been added successfully.

            <?php elseif (
                $success === 'updated'
            ): ?>

                Your application has been updated successfully.

            <?php elseif (
                $success === 'deleted'
            ): ?>

                Your application has been deleted successfully.

            <?php endif; ?>

        </div>

    </div>


    <button
        type="button"
        class="success-toast-close"
        onclick="
            document
                .getElementById('successToast')
                .remove();
        "
    >

        <i class="bi bi-x"></i>

    </button>

</div>

<?php endif; ?>


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


    <!-- =================================================
         PAGE HEADER
    ================================================== -->

    <div class="page-header">


        <div class="page-title">

            <h1>
                Applications
            </h1>

            <p>
                Keep track of all your job applications.
            </p>

        </div>


        <a
            href="tambah.php"
            class="btn btn-primary btn-add"
        >

            <i class="bi bi-plus-lg"></i>

            Add Application

        </a>

    </div>


    <!-- =================================================
         APPLICATIONS CARD
    ================================================== -->

    <div class="main-card">


        <div class="card-header-custom">

            <div>

                <h5>
                    All Applications
                </h5>

                <p>
                    Manage and monitor your job applications
                </p>

            </div>

        </div>


        <?php if (
            mysqli_num_rows($data) > 0
        ): ?>


            <div class="table-responsive">

                <table class="table align-middle">


                    <thead>

                        <tr>

                            <th>
                                Company
                            </th>

                            <th>
                                Position
                            </th>

                            <th>
                                Date Applied
                            </th>

                            <th>
                                Closing Date
                            </th>

                            <th>
    Status
</th>

<th class="text-end"></th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php while (
                            $row =
                                mysqli_fetch_assoc($data)
                        ): ?>


                            <?php

                            $company =
                                $row['nama_perusahaan']
                                ?: ($row['perusahaan'] ?? '-');


                            /* =================================================
                               DEADLINE
                            ================================================= */

                            $deadlineClass = '';

                            if (
                                !empty(
                                    $row['deadline']
                                )
                            ) {

                                $deadlineDate =
                                    new DateTime(
                                        $row['deadline']
                                    );

                                $deadlineDiff =
                                    (int) $today
                                        ->diff(
                                            $deadlineDate
                                        )
                                        ->format(
                                            '%r%a'
                                        );


                                if (
                                    $deadlineDiff < 0
                                ) {

                                    $deadlineClass =
                                        'deadline-overdue';

                                } elseif (
                                    $deadlineDiff <= 7
                                ) {

                                    $deadlineClass =
                                        'deadline-warning';

                                }


                                if (
                                    $deadlineDiff <= 1 &&
                                    $deadlineDiff >= 0
                                ) {

                                    $deadlineClass =
                                        'deadline-urgent';

                                }

                            }


                            /* =================================================
                               STATUS
                            ================================================= */

                            $status =
                                $row['status']
                                ?: 'Applied';

                            $statusClass =
                                'status-default';


                            if (
                                $status === 'Applied'
                            ) {

                                $statusClass =
                                    'status-applied';

                            } elseif (
                                $status === 'Under Review'
                            ) {

                                $statusClass =
                                    'status-review';

                            } elseif (
                                $status === 'Interview'
                            ) {

                                $statusClass =
                                    'status-interview';

                            } elseif (
                                $status === 'Assessment'
                            ) {

                                $statusClass =
                                    'status-assessment';

                            } elseif (
                                $status === 'Offer'
                            ) {

                                $statusClass =
                                    'status-offer';

                            } elseif (
                                $status === 'Rejected'
                            ) {

                                $statusClass =
                                    'status-rejected';

                            }

                            ?>


                            <tr>


                                <!-- COMPANY -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $company
                                        ) ?>

                                    </strong>

                                </td>


                                <!-- POSITION -->

                                <td>

                                    <?= htmlspecialchars(
                                        $row['posisi']
                                    ) ?>

                                </td>


                                <!-- DATE APPLIED -->

                                <td>

                                    <?= formatDateId(
                                        $row['tanggal_lamar']
                                    ) ?>

                                </td>


                                <!-- CLOSING DATE -->

                                <td
                                    class="<?= $deadlineClass ?>"
                                >

                                    <?= !empty(
                                        $row['deadline']
                                    )
                                        ? formatDateId(
                                            $row['deadline']
                                        )
                                        : '-' ?>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="
                                            status-badge
                                            <?= $statusClass ?>
                                        "
                                    >

                                        <?= htmlspecialchars(
                                            $status
                                        ) ?>

                                    </span>

                                </td>


                                <!-- MORE ACTIONS -->
<td class="text-end">
    <div class="dropdown">

        <button
            type="button"
            class="action-menu-btn"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            title="More actions"
        >
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            <li>
                <a
    class="dropdown-item edit"
    href="edit.php?id=<?= (int) $row['id'] ?>"
>
    <i class="bi bi-pencil me-2"></i>
    Edit
</a>
            </li>

            <li>
                <button
                    type="button"
                    class="dropdown-item delete"
                    data-id="<?= (int) $row['id'] ?>"
                    data-name="<?= htmlspecialchars(
                        $company,
                        ENT_QUOTES
                    ) ?>"
                >
                    <i class="bi bi-trash3 me-2"></i>
                    Delete
                </button>
            </li>

        </ul>

    </div>
</td>


                            </tr>


                        <?php endwhile; ?>


                    </tbody>

                </table>

            </div>


        <?php else: ?>


            <div class="empty-state">

                <i class="bi bi-briefcase"></i>

                <div class="empty-state-title">

                    No applications yet.

                </div>

                <div class="empty-state-text">

                    Start tracking your job applications by adding one.

                </div>

            </div>


        <?php endif; ?>


    </div>


</div>


<!-- =====================================================
     DELETE MODAL
===================================================== -->

<div
    class="modal fade"
    id="deleteModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div
        class="modal-dialog modal-dialog-centered"
    >

        <div
            class="modal-content border-0 shadow"
        >

            <div class="modal-body p-4">


                <div class="action-modal-icon">

                    <i class="bi bi-trash3"></i>

                </div>


                <div class="action-modal-title">

                    Delete this application?

                </div>


                <div class="action-modal-text">

                    <span id="deleteApplicationName"></span>

                    <br>

                    This action cannot be undone.

                </div>


                <div
                    class="
                        d-flex
                        justify-content-center
                        gap-2
                    "
                >

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <a
                        href="#"
                        id="confirmDeleteButton"
                        class="btn btn-danger"
                    >

                        <i class="bi bi-trash3"></i>

                        Delete

                    </a>

                </div>


            </div>

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
   DELETE MODAL
===================================================== */

const deleteButtons =
    document.querySelectorAll(
        '.dropdown-item.delete'
    );

deleteButtons.forEach(
    function (button) {

        button.addEventListener(
            'click',
            function () {

                const id =
                    this.dataset.id;

                const name =
                    this.dataset.name;

                document.getElementById(
                    'deleteApplicationName'
                ).textContent =
                    '"' + name + '"';

                document.getElementById(
                    'confirmDeleteButton'
                ).href =
                    'hapus.php?id=' + id;

                const modalElement =
                    document.getElementById(
                        'deleteModal'
                    );

                const modal =
                    bootstrap.Modal.getOrCreateInstance(
                        modalElement
                    );

                modal.show();
            }
        );
    }
);


/* =====================================================
   AUTO HIDE SUCCESS TOAST
===================================================== */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const toast =
            document.getElementById(
                'successToast'
            );

        if (toast) {

            setTimeout(
                function () {

                    if (
                        toast &&
                        toast.parentNode
                    ) {
                        toast.remove();
                    }

                },
                3000
            );
        }
    }
);

/* =====================================================
   LOGOUT CONFIRMATION
===================================================== */

const logoutLink =
    document.getElementById('logoutLink');

const logoutConfirmModal =
    document.getElementById('logoutConfirmModal');

const logoutConfirmCancel =
    document.getElementById('logoutConfirmCancel');

if (
    logoutLink &&
    logoutConfirmModal &&
    logoutConfirmCancel
) {

    logoutLink.addEventListener(
        'click',
        function (event) {

            event.preventDefault();

            logoutConfirmModal.classList.add(
                'show'
            );

        }
    );

    logoutConfirmCancel.addEventListener(
        'click',
        function () {

            logoutConfirmModal.classList.remove(
                'show'
            );

        }
    );

    logoutConfirmModal.addEventListener(
        'click',
        function (event) {

            if (
                event.target ===
                logoutConfirmModal
            ) {

                logoutConfirmModal.classList.remove(
                    'show'
                );

            }

        }
    );

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Escape') {

                logoutConfirmModal.classList.remove(
                    'show'
                );

            }

        }
    );

}
</script>


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

/* =====================================================
   THEME MENU
===================================================== */

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

    if (
        option.dataset.themeValue ===
        currentTheme
    ) {
        option.classList.add('active');
    }

});

if (themeToggle) {

    themeToggle.addEventListener(
        'click',
        function () {

            themeOptions.classList.toggle(
                'show'
            );

            themeToggle.classList.toggle(
                'open'
            );

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

                    item.classList.remove(
                        'active'
                    );

                }
            );

            this.classList.add('active');

        }
    );

});

</script>


</body>

</html>