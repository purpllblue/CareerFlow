<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "../config/koneksi.php";


/* =========================================================
   AMBIL DATA EVENT
========================================================= */

$queryEvents = mysqli_query(
    $koneksi,
    "SELECT
        calendar_events.*,
        companies.nama_perusahaan
     FROM calendar_events
     LEFT JOIN companies
        ON calendar_events.company_id = companies.id
     ORDER BY event_date ASC, start_time ASC"
);

$events = [];

while ($row = mysqli_fetch_assoc($queryEvents)) {

    $start = $row['event_date'];

    if (!empty($row['start_time'])) {
        $start .= 'T' . $row['start_time'];
    }

    $end = null;

    if (!empty($row['end_date'])) {

        $end = $row['end_date'];

        if (empty($row['end_time'])) {

            $endDateObj = new DateTime($row['end_date']);
            $endDateObj->modify('+1 day');

            $end = $endDateObj->format('Y-m-d');

        } else {

            $end .= 'T' . $row['end_time'];

        }

    } elseif (!empty($row['end_time'])) {

        $end =
            $row['event_date'] .
            'T' .
            $row['end_time'];
    }

    $events[] = [

        'id' =>
            $row['id'],

        'title' =>
            $row['title'],

        'start' =>
            $start,

        'end' =>
            $end,

        'event_type' =>
            $row['event_type'],

        'event_color' =>
            $row['event_color']
            ?? '#2563EB',

        'company' =>
            $row['nama_perusahaan']
            ?? '',

        'notes' =>
            $row['notes']
            ?? '',

        'event_date' =>
            $row['event_date'],

        'end_date' =>
            $row['end_date']
            ?? '',

        'start_time' =>
            $row['start_time']
            ?? '',

        'end_time' =>
            $row['end_time']
            ?? ''

    ];
}


/* =========================================================
   AMBIL DATA COMPANY
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

    <title>Calendar - CareerFlow</title>


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


    <!-- FullCalendar -->

    <link
        href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css"
        rel="stylesheet"
    >


    <!-- CareerFlow Theme -->

    <link
        href="assets/css/theme.css?v=3"
        rel="stylesheet"
    >


    <!-- Load Saved Theme Before Page Renders -->

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

        /* =========================================================
           THEME VARIABLES
        ========================================================= */

        :root {

            --accent:
                #2563EB;

            --accent-hover:
                #1D4ED8;

            --accent-light:
                #DBEAFE;

            --accent-pastel:
                #EFF6FF;

            --accent-border:
                #BFDBFE;

            --accent-light-border:
                #93C5FD;

            --accent-title:
                #1E40AF;

            --accent-soft:
                #60A5FA;

            --accent-rgb:
                37, 99, 235;


            --sidebar-1:
                #1E3A6D;

            --sidebar-2:
                #234A7A;

            --sidebar-3:
                #1F5A8A;


            --flow-color:
                #60A5FA;

            --shadow-color:
                rgba(15,23,42,.18);


            --body-bg:
                #F8FAFC;

            --text-dark:
                #0F172A;

            --text:
                #334155;

            --text-muted:
                #64748B;

            --text-light:
                #94A3B8;

            --border:
                #E2E8F0;

            --border-dark:
                #CBD5E1;

            --surface:
                #FFFFFF;

            --soft-bg:
                #F8FAFC;

        }


        /* =========================================================
           BLUE
        ========================================================= */

        html[data-theme="blue"] {

            --accent:
                #2563EB;

            --accent-hover:
                #1D4ED8;

            --accent-light:
                #DBEAFE;

            --accent-pastel:
                #EFF6FF;

            --accent-border:
                #BFDBFE;

            --accent-light-border:
                #93C5FD;

            --accent-title:
                #1E40AF;

            --accent-soft:
                #60A5FA;

            --accent-rgb:
                37, 99, 235;


            --sidebar-1:
                #1E3A6D;

            --sidebar-2:
                #234A7A;

            --sidebar-3:
                #1F5A8A;

            --flow-color:
                #60A5FA;

        }


        /* =========================================================
           PINK
        ========================================================= */

        html[data-theme="pink"] {

            --accent:
                #EC4899;

            --accent-hover:
                #DB2777;

            --accent-light:
                #FCE7F3;

            --accent-pastel:
                #FDF2F8;

            --accent-border:
                #F9A8D4;

            --accent-light-border:
                #F472B6;

            --accent-title:
                #9D174D;

            --accent-soft:
                #F9A8D4;

            --accent-rgb:
                236, 72, 153;


            --sidebar-1:
                #5A1F45;

            --sidebar-2:
                #6F2855;

            --sidebar-3:
                #7D2F62;

            --flow-color:
                #F9A8D4;

        }


        /* =========================================================
           PURPLE
        ========================================================= */

        html[data-theme="purple"] {

            --accent:
                #8066D8;

            --accent-hover:
                #6D52C7;

            --accent-light:
                #EDE9FE;

            --accent-pastel:
                #F5F3FF;

            --accent-border:
                #C4B5FD;

            --accent-light-border:
                #A78BFA;

            --accent-title:
                #5B21B6;

            --accent-soft:
                #C4B5FD;

            --accent-rgb:
                128, 102, 216;


            --sidebar-1:
                #3D2D68;

            --sidebar-2:
                #4C3780;

            --sidebar-3:
                #5A4398;

            --flow-color:
                #C4B5FD;

        }


        /* =========================================================
           BLACK
        ========================================================= */

        html[data-theme="black"] {

            --accent:
                #6B7280;

            --accent-hover:
                #4B5563;

            --accent-light:
                #E5E7EB;

            --accent-pastel:
                #F3F4F6;

            --accent-border:
                #D1D5DB;

            --accent-light-border:
                #9CA3AF;

            --accent-title:
                #374151;

            --accent-soft:
                #D1D5DB;

            --accent-rgb:
                107, 114, 128;


            --sidebar-1:
                #111827;

            --sidebar-2:
                #1F2937;

            --sidebar-3:
                #374151;

            --flow-color:
                #D1D5DB;

        }


        /* =========================================================
           GLOBAL
        ========================================================= */

        * {
            box-sizing:
                border-box;
        }


        body {

            margin:
                0;

            font-family:
                'Poppins',
                sans-serif;

            background:
                var(--body-bg);

            color:
                var(--text-dark);

            font-size:
                15px;
        }


        /* =========================================================
           SIDEBAR
        ========================================================= */

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


        /* =========================================================
           LOGO
        ========================================================= */

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


        /* =========================================================
           MENU TITLE
        ========================================================= */

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


        /* =========================================================
           NAVIGATION
        ========================================================= */

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
            background: rgba(255,255,255,.12);
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
            color: white;
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
            transition: transform .2s ease;
        }

        .nav-link:hover i {
            transform: translateY(-1px);
        }


        /* =========================================================
           LOGOUT
        ========================================================= */

        .nav-link.logout {
            font-weight: 700 !important;
            border-radius: 10px;
            transition: all .2s ease !important;
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


        /* =========================================================
           SIDEBAR SPACER
        ========================================================= */

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

        /* =========================================================
           SIDEBAR TOGGLE
        ========================================================= */

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
            border-color: var(--accent);
            transform: scale(1.05);
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

        /* =========================================================
           COLLAPSED
        ========================================================= */

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
            transform: rotate(180deg);
        }


        /* =========================================================
           THEME SIDEBAR
        ========================================================= */

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

        html[data-theme="blue"] .logo span {
            color: #60A5FA !important;
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

        html[data-theme="pink"] .logo span {
            color: #F9A8D4 !important;
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

        html[data-theme="purple"] .logo span {
            color: #C4B5FD !important;
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

        html[data-theme="black"] .logo span {
            color: #D1D5DB !important;
        }


        /* =========================================================
           FORCE SIDEBAR TEXT WHITE
        ========================================================= */

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


        /* =========================================================
           MAIN
        ========================================================= */

        .main {

            margin-left:
                240px;

            padding:
                30px;

            min-height:
                100vh;

            transition:
                margin-left .25s ease;
        }


        body.sidebar-collapsed
        .main {

            margin-left:
                72px;
        }


        /* =========================================================
           PAGE HEADER
        ========================================================= */

        .page-header {

            display:
                flex;

            justify-content:
                space-between;

            align-items:
                flex-start;

            margin-bottom:
                24px;
        }

        .page-title h1 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 9px;
    color: var(--accent);
}

.page-title p {
    color: #64748B;
    font-size: 17px;
    margin: 0;
}
        /* =========================================================
           VIEW TOGGLE
        ========================================================= */

        .view-toggle {

            display:
                inline-flex;

            background:
                var(--accent-light);

            padding:
                4px;

            border-radius:
                8px;

            border:
                1px solid
                var(--accent-border);
        }


        .view-btn {

            border:
                none;

            background:
                transparent;

            padding:
                8px 15px;

            border-radius:
                6px;

            font-size:
                13px;

            font-weight:
                600;

            color:
                var(--text-muted);

            transition:
                .2s ease;
        }


        .view-btn:hover {

            color:
                var(--accent-title);

            background:
                rgba(
                    255,
                    255,
                    255,
                    .55
                );
        }


        .view-btn.active {

            background:
                white;

            color:
                var(--accent);

            box-shadow:
                0 2px 6px
                rgba(
                    var(--accent-rgb),
                    .16
                );
        }


        /* =========================================================
           CALENDAR CARD
        ========================================================= */

        .calendar-card {

            background:
                white;

            border:
                1px solid
                var(--border);

            border-radius:
                12px;

            overflow:
                hidden;

            box-shadow:
                0 2px 8px
                rgba(
                    15,
                    23,
                    42,
                    .03
                );
        }


        #calendarView {

            padding:
                23px 26px;
        }


        #listView {

            padding:
                23px 26px;
        }


        /* =========================================================
           FULLCALENDAR
        ========================================================= */

        .fc {

            font-size:
                13px;
        }


        .fc .fc-toolbar-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--accent);
}


        .fc .fc-button {

            background:
                white;

            border:
                1px solid
                var(--border-dark);

            color:
                var(--text);

            box-shadow:
                none;

            font-size:
                12px;

            font-weight:
                600;

            transition:
                .2s ease;
        }


        .fc .fc-button:hover {

            background:
                var(--accent-pastel);

            border-color:
                var(--accent-border);

            color:
                var(--accent-title);
        }


        .fc
        .fc-button-primary:not(:disabled)
        .fc-button-active {

            background:
                var(--accent);

            border-color:
                var(--accent);

            color:
                white;
        }


        /* =========================================================
           CALENDAR HEADER
        ========================================================= */

        .fc .fc-col-header-cell {

            background:
                var(--accent) !important;

            border-color:
                var(--accent) !important;
        }


        .fc .fc-col-header-cell-cushion {

            color:
                white !important;

            font-size:
                16px;

            font-weight:
                600;

            text-decoration:
                none;

            padding:
                10px 4px;
        }


        /* =========================================================
           DAY NUMBER
        ========================================================= */

        .fc .fc-day-sun .fc-daygrid-day-number {

            color:
                #EF4444 !important;
        }


        .fc .fc-daygrid-day-number {

            color:
                var(--text);

            font-size:
                12px;

            text-decoration:
                none;

            padding:
                8px;
        }


        .fc .fc-daygrid-day {

            cursor:
                pointer;
        }


        /* =====================================================
   TODAY
===================================================== */

.fc .fc-day-today {
    background: var(--accent-pastel) !important;
}

.fc .fc-day-today .fc-daygrid-day-number {
    color: var(--accent);
    font-weight: 700;
}

.fc .fc-day-sun.fc-day-today .fc-daygrid-day-number {
    color: #EF4444 !important;
}

        /* =========================================================
           CALENDAR EVENTS
        ========================================================= */

        .fc .fc-daygrid-event {

            cursor:
                pointer;

            border:
                none !important;

            padding:
                0 !important;

            margin-top:
                4px;

            min-height:
                24px;

            box-shadow:
                none !important;

            position:
                relative;

            overflow:
                hidden;
        }


        /* =========================================================
           SINGLE DATE
        ========================================================= */

        .fc
        .fc-daygrid-event.single-date-event {

            background:
                #F8FAFC !important;

            border-radius:
                6px !important;

            z-index:
                4;
        }


        .fc
        .fc-daygrid-event.single-date-event::before {

            content:
                "";

            position:
                absolute;

            left:
                0;

            top:
                0;

            bottom:
                0;

            width:
                5px;

            background:
                var(
                    --event-color,
                    var(--accent)
                );

            border-radius:
                6px 0 0 6px;

            z-index:
                20;

            pointer-events:
                none;
        }


        /* =========================================================
           RANGE EVENT
        ========================================================= */

        .fc
        .fc-daygrid-event.range-event {

            background:
                color-mix(
                    in srgb,
                    var(
                        --event-color,
                        var(--accent)
                    ) 14%,
                    white
                ) !important;

            border-radius:
                0 !important;

            z-index:
                1;
        }


        .fc
        .fc-daygrid-event.range-start {

            border-radius:
                6px 0 0 6px !important;
        }


        .fc
        .fc-daygrid-event.range-start::before {

            content:
                "";

            position:
                absolute;

            left:
                0;

            top:
                0;

            bottom:
                0;

            width:
                5px;

            background:
                var(
                    --event-color,
                    var(--accent)
                );

            border-radius:
                6px 0 0 6px;

            z-index:
                20;

            pointer-events:
                none;
        }


        .fc
        .fc-daygrid-event.range-end {

            border-radius:
                0 6px 6px 0 !important;
        }


        .fc
        .fc-daygrid-event.range-start.range-end {

            border-radius:
                6px !important;
        }


        /* =========================================================
           EVENT CONTENT
        ========================================================= */

        .custom-calendar-event {

            display:
                flex;

            align-items:
                center;

            gap:
                6px;

            width:
                100%;

            min-width:
                0;

            padding:
                4px 7px;

            position:
                relative;

            z-index:
                5;
        }


        .fc
        .fc-daygrid-event.single-date-event
        .custom-calendar-event {

            padding-left:
                11px;
        }


        .range-event-content {

            padding-left:
                11px;
        }


        /* =========================================================
           BULLET
        ========================================================= */

        .custom-event-dot {

            width:
                7px;

            height:
                7px;

            min-width:
                7px;

            border-radius:
                50%;

            background:
                var(
                    --event-color,
                    var(--accent)
                );

            display:
                inline-block;

            flex-shrink:
                0;
        }


        /* =========================================================
           TITLE
        ========================================================= */

        .custom-event-title {

            color:
                var(--text);

            font-size:
                12px;

            font-weight:
                600;

            white-space:
                nowrap;

            overflow:
                hidden;

            text-overflow:
                ellipsis;
        }


        /* =========================================================
           RANGE MIDDLE / END
        ========================================================= */

        .fc
        .fc-daygrid-event.range-middle
        .custom-calendar-event,

        .fc
        .fc-daygrid-event.range-end
        .custom-calendar-event {

            visibility:
                hidden;
        }


        /* =========================================================
           EVENT MAIN
        ========================================================= */

        .fc .fc-event-main {

            color:
                var(--text) !important;

            font-size:
                12px;

            font-weight:
                600;

            line-height:
                1.3;

            min-width:
                0;
        }


        /* =========================================================
           HIDE DEFAULT TIME
        ========================================================= */

        .fc .fc-event-time {

            display:
                none !important;
        }


        .fc .fc-daygrid-event-dot {

            display:
                none !important;
        }


        .fc .fc-daygrid-event:hover {

            filter:
                brightness(.97);
        }


        /* =========================================================
           LIST VIEW
        ========================================================= */

        #listView {

            display:
                none;
        }


        .list-date {

            color:
                var(--accent-title);

            font-size:
                14px;

            font-weight:
                700;

            margin-bottom:
                10px;

            margin-top:
                20px;
        }


        .list-date:first-child {

            margin-top:
                0;
        }


        .list-event {

            display:
                flex;

            align-items:
                flex-start;

            gap:
                14px;

            padding:
                14px 12px;

            border-bottom:
                1px solid
                var(--border);

            cursor:
                pointer;

            transition:
                .2s ease;
        }


        .list-event:hover {

            background:
                var(--accent-pastel);
        }


        .event-line {

            width:
                5px;

            min-width:
                5px;

            height:
                48px;

            border-radius:
                5px;

            background:
                var(
                    --event-color,
                    var(--accent)
                );
        }


        .list-event-content {

            flex:
                1;
        }


        .list-event-title {

            color:
                var(--text-dark);

            font-size:
                14px;

            font-weight:
                600;

            margin-bottom:
                3px;
        }


        .list-event-company {

            color:
                var(--text-muted);

            font-size:
                12px;

            margin-bottom:
                4px;
        }


        .list-event-time {

            color:
                var(--text-light);

            font-size:
                12px;
        }


        .list-event-date-range {

            color:
                var(--text-muted);

            font-size:
                12px;

            margin-bottom:
                4px;
        }


        /* =========================================================
           ADD BUTTON
        ========================================================= */

        .btn-add {

            display:
                inline-flex;

            align-items:
                center;

            gap:
                8px;

            background:
                var(--accent);

            color:
                white;

            border:
                none;

            border-radius:
                8px;

            padding:
                10px 16px;

            font-size:
                14px;

            font-weight:
                500;

            transition:
                .2s ease;

            box-shadow:
                0 4px 10px
                rgba(
                    var(--accent-rgb),
                    .18
                );
        }


        .btn-add:hover {

            background:
                var(--accent-hover);

            color:
                white;

            transform:
                translateY(-1px);

            box-shadow:
                0 6px 14px
                rgba(
                    var(--accent-rgb),
                    .25
                );
        }


        /* =========================================================
           MODAL
        ========================================================= */

        .modal-content {

            border:
                none;

            border-radius:
                14px;

            box-shadow:
                0 20px 50px
                rgba(
                    15,
                    23,
                    42,
                    .15
                );
        }


        .modal-header {

            padding:
                20px 22px;

            border-bottom:
                1px solid
                var(--border);
        }


        .modal-title {

            font-size:
                18px;

            font-weight:
                700;

            color:
                var(--accent-title);
        }


        .modal-body {

            padding:
                22px;
        }


        .form-label {

            font-size:
                13px;

            font-weight:
                600;

            color:
                var(--text);

            margin-bottom:
                7px;
        }


        .form-control,
        .form-select {

            border:
                1px solid
                var(--border-dark);

            border-radius:
                8px;

            padding:
                10px 12px;

            font-size:
                13px;
        }


        .form-control:focus,
        .form-select:focus {

            border-color:
                var(--accent);

            box-shadow:
                0 0 0 3px
                rgba(
                    var(--accent-rgb),
                    .10
                );
        }


        .modal-footer {

            padding:
                15px 22px;

            border-top:
                1px solid
                var(--border);
        }


        .btn-secondary-custom {

            border:
                1px solid
                var(--border-dark);

            background:
                white;

            color:
                var(--text);

            border-radius:
                8px;

            padding:
                9px 15px;

            font-size:
                13px;

            font-weight:
                600;

            text-decoration:
                none;

            transition:
                .2s ease;
        }


        .btn-secondary-custom:hover {

            background:
                var(--accent-pastel);

            border-color:
                var(--accent-border);

            color:
                var(--accent-title);
        }


        .btn-primary-custom {

            border:
                none;

            background:
                var(--accent);

            color:
                white;

            border-radius:
                8px;

            padding:
                9px 15px;

            font-size:
                13px;

            font-weight:
                600;

            transition:
                .2s ease;
        }


        .btn-primary-custom:hover {

            background:
                var(--accent-hover);

            color:
                white;
        }


        .btn-danger-custom {

            border:
                none;

            background:
                #DC2626;

            color:
                white;

            border-radius:
                8px;

            padding:
                9px 15px;

            font-size:
                13px;

            font-weight:
                600;
        }


        .btn-danger-custom:hover {

            background:
                #B91C1C;

            color:
                white;
        }


        /* =========================================================
           COLOR PICKER
        ========================================================= */

        .color-options {

            display:
                flex;

            gap:
                10px;

            flex-wrap:
                wrap;
        }


        .color-option {

            position:
                relative;

            width:
                32px;

            height:
                32px;

            border-radius:
                8px;

            cursor:
                pointer;

            border:
                2px solid
                transparent;

            transition:
                .2s ease;
        }


        .color-option:hover {

            transform:
                scale(1.08);
        }


        .color-option input {

            position:
                absolute;

            opacity:
                0;

            pointer-events:
                none;
        }


        .color-option.selected {

            border-color:
                var(--accent);

            box-shadow:
                0 0 0 2px
                white,
                0 0 0 4px
                var(--accent);
        }


        /* =========================================================
           DETAIL
        ========================================================= */

        .detail-row {

            display:
                flex;

            padding:
                11px 0;

            border-bottom:
                1px solid
                var(--border);
        }


        .detail-row:last-child {

            border-bottom:
                none;
        }


        .detail-label {

            width:
                120px;

            color:
                var(--text-muted);

            font-size:
                12px;

            font-weight:
                600;
        }


        .detail-value {

            flex:
                1;

            color:
                var(--text);

            font-size:
                13px;

            font-weight:
                500;
        }


        /* =========================================================
           SUCCESS TOAST
        ========================================================= */

        .success-toast {

            position:
                fixed;

            top:
                24px;

            right:
                24px;

            z-index:
                9999;

            min-width:
                320px;

            background:
                #FFFFFF;

            border:
                1px solid
                var(--accent-border);

            border-left:
                4px solid
                var(--accent);

            border-radius:
                10px;

            box-shadow:
                0 10px 30px
                rgba(
                    15,
                    23,
                    42,
                    .12
                );

            padding:
                14px 16px;

            display:
                flex;

            align-items:
                flex-start;

            gap:
                12px;

            animation:
                toastSlideIn .3s ease;
        }


        .success-icon {

            width:
                34px;

            height:
                34px;

            flex-shrink:
                0;

            border-radius:
                50%;

            background:
                var(--accent-light);

            color:
                var(--accent);

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            font-size:
                17px;
        }


        .success-content {

            flex:
                1;
        }


        .success-title {

            font-size:
                14px;

            font-weight:
                700;

            color:
                var(--accent-title);

            margin-bottom:
                2px;
        }


        .success-message {

            font-size:
                12px;

            color:
                var(--text-muted);

            line-height:
                1.5;
        }


        @keyframes toastSlideIn {

            from {

                opacity:
                    0;

                transform:
                    translateX(30px);
            }

            to {

                opacity:
                    1;

                transform:
                    translateX(0);
            }

        }


        /* =========================================================
           DELETE CONFIRMATION
        ========================================================= */

        .confirm-icon {

            width:
                48px;

            height:
                48px;

            margin:
                0 auto 15px;

            border-radius:
                50%;

            background:
                #FEE2E2;

            color:
                #DC2626;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            font-size:
                22px;
        }


        .confirm-title {

            color:
                var(--accent-title);

            font-size:
                18px;

            font-weight:
                700;

            text-align:
                center;

            margin-bottom:
                7px;
        }


        .confirm-text {

            color:
                var(--text-muted);

            font-size:
                13px;

            text-align:
                center;

            margin-bottom:
                0;
        }


        /* =========================================================
           NO EVENT
        ========================================================= */

        .no-event-icon {

            width:
                48px;

            height:
                48px;

            margin:
                0 auto 15px;

            border-radius:
                50%;

            background:
                var(--accent-light);

            color:
                var(--accent);

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            font-size:
                22px;
        }


        .no-event-title {

            color:
                var(--accent-title);

            font-size:
                18px;

            font-weight:
                700;

            text-align:
                center;

            margin-bottom:
                7px;
        }


        .no-event-text {

            color:
                var(--text-muted);

            font-size:
                13px;

            text-align:
                center;

            margin-bottom:
                0;
        }


        .btn-delete-confirm {

            border:
                none;

            background:
                #DC2626;

            color:
                white;

            border-radius:
                8px;

            padding:
                9px 15px;

            font-size:
                13px;

            font-weight:
                600;

            text-decoration:
                none;
        }


        .btn-delete-confirm:hover {

            background:
                #B91C1C;

            color:
                white;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 768px) {

    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        padding: 20px 14px;
    }

    .sidebar-toggle {
        display: none;
    }

    .logo {
        padding: 0 14px;
        margin-bottom: 25px;
    }

    .main {
        margin-left: 0;
        padding: 20px;
    }

    body.sidebar-collapsed .sidebar {
        width: 100%;
    }

    body.sidebar-collapsed .main {
        margin-left: 0;
    }
            }


        @media (max-width: 768px) {

            .sidebar {

                position:
                    relative;

                width:
                    100%;

                height:
                    auto;

                padding:
                    20px 14px;
            }


            .sidebar-toggle {

                display:
                    none;
            }


            .logo {

                padding:
                    0 14px;

                margin-bottom:
                    25px;
            }


            .main {

                margin-left:
                    0;

                padding:
                    20px;
            }


            body.sidebar-collapsed
            .sidebar {

                width:
                    100%;
            }


            body.sidebar-collapsed
            .main {

                margin-left:
                    0;
            }


            .page-header {

                flex-direction:
                    column;

                gap:
                    15px;
            }


            .page-title h1 {

                font-size:
                    30px;
            }


            .page-title p {

                font-size:
                    15px;
            }


            .calendar-card {

                border-radius:
                    12px;
            }


            #calendarView,
            #listView {

                padding:
                    20px;
            }


            .fc .fc-toolbar {

                flex-wrap:
                    wrap;

                gap:
                    8px;
            }


            .success-toast {

                left:
                    20px;

                right:
                    20px;

                min-width:
                    auto;
            }

        }


        /* =========================================================
   TODAY BUTTON
========================================================= */

.fc .fc-today-button {
    background: var(--accent-light) !important;
    border-color: var(--accent-border) !important;
    color: var(--accent) !important;
    font-weight: 600;
    box-shadow: none !important;
    opacity: 1 !important;
}

.fc .fc-today-button:hover {
    background: var(--accent-light) !important;
    border-color: var(--accent-border) !important;
    color: var(--accent) !important;
}

.fc .fc-today-button:disabled {
    background: var(--accent-light) !important;
    border-color: var(--accent-border) !important;
    color: var(--accent) !important;
    opacity: 1 !important;
}

/* =========================================================
   CALENDAR NAVIGATION BUTTONS
========================================================= */

/* SEMUA BUTTON CALENDAR */
.fc .fc-button {
    background: var(--accent-light) !important;
    border-color: var(--accent-border) !important;
    color: var(--accent) !important;
    box-shadow: none !important;
    text-shadow: none !important;
}

/* TODAY - NORMAL */
.fc .fc-today-button {
    background: var(--accent-light) !important;
    border-color: var(--accent-border) !important;
    color: var(--accent) !important;
    font-weight: 600;
    box-shadow: none !important;
    opacity: 1 !important;
}

/* TODAY - HOVER */
.fc .fc-today-button:hover {
    background: var(--accent) !important;
    border-color: var(--accent) !important;
    color: #FFFFFF !important;
}

/* TODAY - DISABLED */
.fc .fc-today-button:disabled {
    background: var(--accent-light) !important;
    border-color: var(--accent-border) !important;
    color: var(--accent) !important;
    opacity: 1 !important;
}

/* =========================================================
   CALENDAR NAVIGATION BUTTONS
========================================================= */

/* NORMAL */
.fc .fc-prev-button,
.fc .fc-next-button {
    background: var(--accent-light) !important;
    border: 1px solid var(--accent-border) !important;
    color: var(--accent) !important;
    box-shadow: none !important;
}

/* HOVER */
.fc .fc-prev-button:hover,
.fc .fc-next-button:hover {
    background: var(--accent) !important;
    border-color: var(--accent) !important;
    color: #FFFFFF !important;
}

/* SAAT DITEKAN */
.fc .fc-prev-button:active,
.fc .fc-next-button:active {
    background: var(--accent) !important;
    border-color: var(--accent) !important;
    color: #FFFFFF !important;
    box-shadow: none !important;
}

/* SETELAH KLIK / FOCUS */
.fc .fc-prev-button:focus,
.fc .fc-next-button:focus {
    background: var(--accent) !important;
    border-color: var(--accent) !important;
    color: #FFFFFF !important;
    box-shadow: none !important;
}

        /* =========================================================
           CALENDAR GRID BORDER
        ========================================================= */

        .fc-theme-standard td,
        .fc-theme-standard th {

            border-color:
                var(--border);
        }


        .fc-theme-standard .fc-scrollgrid {

            border-color:
                var(--border);
        }


        /* =========================================================
           SELECT ARROW / INPUT DATE
        ========================================================= */

        .form-control::placeholder {

            color:
                #94A3B8;
        }


        /* =========================================================
           FOCUS RING
        ========================================================= */

        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {

            outline:
                2px solid
                var(--accent-soft);

            outline-offset:
                2px;
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
        href="dashboard.php"
        class="nav-link"
    >

        <i class="bi bi-grid-1x2"></i>

        <span>
            Dashboard
        </span>

    </a>


    <a
        href="lamaran/index.php"
        class="nav-link"
    >

        <i class="bi bi-briefcase"></i>

        <span>
            Applications
        </span>

    </a>


    <a
        href="companies/index.php"
        class="nav-link"
    >

        <i class="bi bi-buildings"></i>

        <span>
            Companies
        </span>

    </a>


    <a
        href="calendar.php"
        class="nav-link active"
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
        href="profile.php"
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

<div
    class="theme-options"
    id="themeOptions"
>

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
    href="logout.php"
    class="nav-link logout"
    id="logoutLink"
>
    <i class="bi bi-box-arrow-right"></i>
    <span>Logout</span>
</a>

</div>

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
                href="logout.php"
                class="logout-confirm-yes"
            >
                Logout
            </a>
        </div>
    </div>
</div>


<!-- =========================================================
     MAIN
========================================================= -->

<div class="main">


    <div class="page-header">


        <div class="page-title">

            <h1>
                Calendar
            </h1>

            <p>
                Manage interviews, assessments, deadlines, and other career events.
            </p>

        </div>


        <div class="d-flex align-items-center gap-3">


            <div class="view-toggle">


                <button
                    type="button"
                    class="view-btn active"
                    id="calendarBtn"
                >

                    <i class="bi bi-calendar3 me-1"></i>

                    Calendar

                </button>


                <button
                    type="button"
                    class="view-btn"
                    id="listBtn"
                >

                    <i class="bi bi-list-ul me-1"></i>

                    List

                </button>


            </div>


            <button
                type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addEventModal"
            >

                <i class="bi bi-plus-lg"></i>

                Add Event

            </button>


        </div>

    </div>


    <!-- =========================================================
         CALENDAR
    ========================================================= -->

    <div
        class="calendar-card"
        id="calendarView"
    >

        <div id="calendar"></div>

    </div>


    <!-- =========================================================
         LIST
    ========================================================= -->

    <div
        class="calendar-card"
        id="listView"
    >

        <div id="eventList"></div>

    </div>


</div>


<!-- =========================================================
     SUCCESS TOAST
========================================================= -->

<?php if (
    isset($_GET['success']) &&
    in_array(
        $_GET['success'],
        ['added', 'updated', 'deleted'],
        true
    )
): ?>

    <div
        class="success-toast"
        id="successToast"
    >

        <div class="success-icon">

            <i class="bi bi-check-lg"></i>

        </div>


        <div class="success-content">

            <div class="success-title">
    <?php
    if ($_GET['success'] === 'added') {
        echo 'EVENT ADDED!';
    } elseif ($_GET['success'] === 'updated') {
        echo 'EVENT UPDATED!';
    } elseif ($_GET['success'] === 'deleted') {
        echo 'EVENT DELETED!';
    }
    ?>
</div>


            <div class="success-message">

                <?php

                if (
                    $_GET['success']
                    === 'added'
                ) {

                    echo
                        'Your event has been added successfully.';

                } elseif (
                    $_GET['success']
                    === 'updated'
                ) {

                    echo
                        'Your event has been updated successfully.';

                } elseif (
                    $_GET['success']
                    === 'deleted'
                ) {

                    echo
                        'Your event has been deleted successfully.';

                }

                ?>

            </div>

        </div>

    </div>

<?php endif; ?>


<!-- =========================================================
     ADD EVENT MODAL
========================================================= -->

<div
    class="modal fade"
    id="addEventModal"
    tabindex="-1"
>

    <div
        class="modal-dialog modal-lg modal-dialog-centered"
    >

        <div class="modal-content">


            <form
                action="calendar/tambah.php"
                method="POST"
            >


                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Event
                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">


                    <div class="row g-3">


                        <div class="col-md-8">

                            <label class="form-label">
                                Event Title
                            </label>


                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                placeholder="e.g. HR Interview - Frontend Developer"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Event Type
                            </label>


                            <select
                                name="event_type"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Type
                                </option>

                                <option value="Interview">
                                    Interview
                                </option>

                                <option value="Assessment">
                                    Assessment
                                </option>

                                <option value="Technical Test">
                                    Technical Test
                                </option>

                                <option value="Deadline">
                                    Deadline
                                </option>

                                <option value="Follow Up">
                                    Follow Up
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Date Type
                            </label>


                            <select
                                id="dateType"
                                class="form-select"
                            >

                                <option value="single">
                                    Single Date
                                </option>

                                <option value="range">
                                    Date Range
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Start Date
                            </label>


                            <input
                                type="date"
                                name="event_date"
                                id="eventDate"
                                class="form-control"
                                required
                            >

                        </div>


                        <div
                            class="col-md-4"
                            id="endDateWrapper"
                            style="display:none;"
                        >

                            <label class="form-label">
                                End Date
                            </label>


                            <input
                                type="date"
                                name="end_date"
                                id="endDate"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Start Time
                            </label>


                            <input
                                type="time"
                                name="start_time"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                End Time
                            </label>


                            <input
                                type="time"
                                name="end_time"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Company
                            </label>


                            <select
                                name="company_id"
                                class="form-select"
                            >

                                <option value="">
                                    Select Company
                                </option>


                                <?php while (
                                    $company =
                                    mysqli_fetch_assoc(
                                        $companies
                                    )
                                ): ?>

                                    <option
                                        value="<?= $company['id']; ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $company['nama_perusahaan']
                                        ); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Event Color
                            </label>


                            <div class="color-options">


                                <label
                                    class="color-option selected"
                                    style="background:#2563EB;"
                                >

                                    <input
                                        type="radio"
                                        name="event_color"
                                        value="#2563EB"
                                        checked
                                    >

                                </label>


                                <label
                                    class="color-option"
                                    style="background:#7C3AED;"
                                >

                                    <input
                                        type="radio"
                                        name="event_color"
                                        value="#7C3AED"
                                    >

                                </label>


                                <label
                                    class="color-option"
                                    style="background:#EA580C;"
                                >

                                    <input
                                        type="radio"
                                        name="event_color"
                                        value="#EA580C"
                                    >

                                </label>


                                <label
                                    class="color-option"
                                    style="background:#16A34A;"
                                >

                                    <input
                                        type="radio"
                                        name="event_color"
                                        value="#16A34A"
                                    >

                                </label>


                                <label
                                    class="color-option"
                                    style="background:#DC2626;"
                                >

                                    <input
                                        type="radio"
                                        name="event_color"
                                        value="#DC2626"
                                    >

                                </label>


                                <label
                                    class="color-option"
                                    style="background:#475569;"
                                >

                                    <input
                                        type="radio"
                                        name="event_color"
                                        value="#475569"
                                    >

                                </label>


                                <label
                                    class="color-option"
                                    style="background:#0F766E;"
                                >

                                    <input
                                        type="radio"
                                        name="event_color"
                                        value="#0F766E"
                                    >

                                </label>


                            </div>

                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Notes
                            </label>


                            <textarea
                                name="notes"
                                class="form-control"
                                rows="3"
                                placeholder="Additional notes..."
                            ></textarea>

                        </div>


                    </div>

                </div>


                <div class="modal-footer">


                    <button
                        type="button"
                        class="btn-secondary-custom"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="btn-primary-custom"
                    >

                        Save Event

                    </button>


                </div>


            </form>

        </div>

    </div>

</div>


<!-- =========================================================
     EVENT DETAILS MODAL
========================================================= -->

<div
    class="modal fade"
    id="eventDetailsModal"
    tabindex="-1"
>

    <div
        class="modal-dialog modal-dialog-centered"
    >

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Event Details
                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">


                <div class="detail-row">

                    <div class="detail-label">
                        Title
                    </div>


                    <div
                        class="detail-value"
                        id="detailTitle"
                    ></div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Type
                    </div>


                    <div
                        class="detail-value"
                        id="detailType"
                    ></div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Company
                    </div>


                    <div
                        class="detail-value"
                        id="detailCompany"
                    ></div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Date
                    </div>


                    <div
                        class="detail-value"
                        id="detailDate"
                    ></div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Time
                    </div>


                    <div
                        class="detail-value"
                        id="detailTime"
                    ></div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Notes
                    </div>


                    <div
                        class="detail-value"
                        id="detailNotes"
                    ></div>

                </div>


            </div>


            <div class="modal-footer">


                <a
                    href="#"
                    id="editEventBtn"
                    class="btn-primary-custom"
                    style="text-decoration:none;"
                >

                    Edit Event

                </a>


                <button
                    type="button"
                    class="btn-danger-custom"
                    id="deleteEventBtn"
                >

                    Delete

                </button>


                <button
                    type="button"
                    class="btn-secondary-custom"
                    data-bs-dismiss="modal"
                >

                    Close

                </button>


            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     DELETE CONFIRMATION
========================================================= -->

<div
    class="modal fade"
    id="deleteConfirmModal"
    tabindex="-1"
>

    <div
        class="modal-dialog modal-dialog-centered"
    >

        <div class="modal-content">


            <div
                class="modal-body text-center"
                style="padding:30px 25px;"
            >


                <div class="confirm-icon">

                    <i class="bi bi-trash3"></i>

                </div>


                <div class="confirm-title">

                    Delete Event?

                </div>


                <p class="confirm-text">

                    Are you sure you want to delete this event?
                    This action cannot be undone.

                </p>


            </div>


            <div
                class="modal-footer justify-content-center"
            >


                <button
                    type="button"
                    class="btn-secondary-custom"
                    data-bs-dismiss="modal"
                >

                    Cancel

                </button>


                <a
                    href="#"
                    id="confirmDeleteBtn"
                    class="btn-delete-confirm"
                >

                    Delete Event

                </a>


            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     NO EVENT MODAL
========================================================= -->

<div
    class="modal fade"
    id="noEventModal"
    tabindex="-1"
>

    <div
        class="modal-dialog modal-dialog-centered"
    >

        <div class="modal-content">

            <div
                class="modal-body text-center"
                style="padding:30px 25px;"
            >

                <div class="no-event-icon">

                    <i class="bi bi-calendar-x"></i>

                </div>

                <div class="no-event-title">

                    No Event

                </div>

                <p class="no-event-text">

                    There is no event scheduled on this date.

                </p>

            </div>

            <div class="modal-footer justify-content-center">

                <button
                    type="button"
                    class="btn-secondary-custom"
                    data-bs-dismiss="modal"
                >

                    Close

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     BOOTSTRAP
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =========================================================
     FULLCALENDAR
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"
></script>

<script>
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

    logoutLink.addEventListener('click', function (event) {
        event.preventDefault();

        logoutConfirmModal.classList.add('show');
    });

    logoutConfirmCancel.addEventListener('click', function () {
        logoutConfirmModal.classList.remove('show');
    });

    logoutConfirmModal.addEventListener('click', function (event) {
        if (event.target === logoutConfirmModal) {
            logoutConfirmModal.classList.remove('show');
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            logoutConfirmModal.classList.remove('show');
        }
    });

});
</script>

<script>


/* =========================================================
   SIDEBAR TOGGLE
========================================================= */

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

/* =========================================================
   THEME TOGGLE
========================================================= */

const themeToggle =
    document.getElementById('themeToggle');

const themeOptions =
    document.getElementById('themeOptions');

const themeOptionItems =
    document.querySelectorAll('.theme-option');


if (themeToggle && themeOptions) {

    themeToggle.addEventListener(
        'click',
        function () {

            themeOptions.classList.toggle('show');

            themeToggle.classList.toggle('open');

        }
    );

}


themeOptionItems.forEach(
    function (option) {

        option.addEventListener(
            'click',
            function () {

                const selectedTheme =
                    this.getAttribute(
                        'data-theme-value'
                    );


                /* Apply theme */

                document.documentElement.setAttribute(
                    'data-theme',
                    selectedTheme
                );


                /* Save theme */

                localStorage.setItem(
                    'careerFlowTheme',
                    selectedTheme
                );


                /* Update active option */

                themeOptionItems.forEach(
                    function (item) {

                        item.classList.remove(
                            'active'
                        );

                    }
                );


                this.classList.add(
                    'active'
                );


                /* Close menu */

                themeOptions.classList.remove(
                    'show'
                );

                themeToggle.classList.remove(
                    'open'
                );

            }
        );

    }
);


/* =========================================================
   LOAD SAVED THEME
========================================================= */

const currentTheme =
    localStorage.getItem('careerFlowTheme') || 'blue';


document.documentElement.setAttribute(
    'data-theme',
    currentTheme
);


themeOptionItems.forEach(
    function (option) {

        if (
            option.getAttribute(
                'data-theme-value'
            ) === currentTheme
        ) {

            option.classList.add(
                'active'
            );

        }

    }
);


/* =========================================================
   DATA
========================================================= */

const events =
    <?= json_encode(
        $events,
        JSON_UNESCAPED_UNICODE
    ); ?>;


/* =========================================================
   CALENDAR
========================================================= */

const calendarEl =
    document.getElementById(
        'calendar'
    );


const calendar =
    new FullCalendar.Calendar(

        calendarEl,

        {

            initialView:
                'dayGridMonth',

            height:
                'auto',

            headerToolbar: {

                left:
                    'prev,next today',

                center:
                    'title',

                right:
                    ''

            },

            buttonText: {

                today:
                    'Today'

            },

            events:
                events,


            eventDataTransform:
                function (event) {

                    return event;

                },


            eventContent:
                function (arg) {

                    const event =
                        arg.event;


                    const isRange =

                        !!event
                            .extendedProps
                            .end_date &&

                        event
                            .extendedProps
                            .end_date !==
                        event
                            .extendedProps
                            .event_date;


                    const wrapper =
                        document.createElement(
                            'div'
                        );


                    wrapper.className =
                        'custom-calendar-event';


                    if (!isRange) {

                        const dot =
                            document.createElement(
                                'span'
                            );


                        dot.className =
                            'custom-event-dot';


                        const title =
                            document.createElement(
                                'span'
                            );


                        title.className =
                            'custom-event-title';


                        title.textContent =
                            event.title;


                        wrapper.appendChild(
                            dot
                        );


                        wrapper.appendChild(
                            title
                        );


                        return {

                            domNodes: [
                                wrapper
                            ]

                        };

                    }


                    if (arg.isStart) {

                        wrapper.classList.add(
                            'range-event-content'
                        );


                        const dot =
                            document.createElement(
                                'span'
                            );


                        dot.className =
                            'custom-event-dot';


                        const title =
                            document.createElement(
                                'span'
                            );


                        title.className =
                            'custom-event-title';


                        title.textContent =
                            event.title;


                        wrapper.appendChild(
                            dot
                        );


                        wrapper.appendChild(
                            title
                        );


                        return {

                            domNodes: [
                                wrapper
                            ]

                        };

                    }


                    return {

                        domNodes: [
                            wrapper
                        ]

                    };

                },


            eventDidMount:
                function (info) {

                    const event =
                        info.event;


                    const color =

                        event
                            .extendedProps
                            .event_color

                        ||

                        '#2563EB';


                    const isRange =

                        !!event
                            .extendedProps
                            .end_date &&

                        event
                            .extendedProps
                            .end_date !==
                        event
                            .extendedProps
                            .event_date;


                    info.el.style.setProperty(

                        '--event-color',

                        color

                    );


                    if (isRange) {

                        info.el.classList.add(
                            'range-event'
                        );


                        if (info.isStart) {

                            info.el.classList.add(
                                'range-start'
                            );

                        }


                        if (info.isEnd) {

                            info.el.classList.add(
                                'range-end'
                            );

                        }


                        if (

                            !info.isStart &&
                            !info.isEnd

                        ) {

                            info.el.classList.add(
                                'range-middle'
                            );

                        }

                    } else {

                        info.el.classList.add(
                            'single-date-event'
                        );

                    }


                    const defaultTime =
                        info.el.querySelector(
                            '.fc-event-time'
                        );


                    if (defaultTime) {

                        defaultTime.remove();

                    }

                },


            eventClick:
                function (info) {

                    const event =
                        info.event;


                    const data = {

                        id:
                            event.id,

                        title:
                            event.title,

                        event_type:
                            event.extendedProps
                                .event_type,

                        event_color:
                            event.extendedProps
                                .event_color,

                        company:
                            event.extendedProps
                                .company,

                        notes:
                            event.extendedProps
                                .notes,

                        event_date:
                            event.extendedProps
                                .event_date,

                        end_date:
                            event.extendedProps
                                .end_date,

                        start_time:
                            event.extendedProps
                                .start_time,

                        end_time:
                            event.extendedProps
                                .end_time

                    };


                    showEventDetails(
                        data
                    );

                },


            dateClick:
                function (info) {

                    const clickedDate =
                        info.dateStr;


                    const eventsToday =
                        events.filter(
                            function (event) {

                                const startDate =
                                    event.event_date;

                                const endDate =
                                    event.end_date ||
                                    event.event_date;

                                return (
                                    clickedDate >= startDate &&
                                    clickedDate <= endDate
                                );

                            }
                        );


                    if (eventsToday.length === 0) {

                        const noEventModal =
                            document.getElementById(
                                'noEventModal'
                            );


                        if (noEventModal) {

                            const modal =
                                new bootstrap.Modal(
                                    noEventModal
                                );

                            modal.show();

                        }

                    }

                }


        }

    );


calendar.render();


/* =========================================================
   VIEW TOGGLE
========================================================= */

const calendarBtn =
    document.getElementById(
        'calendarBtn'
    );


const listBtn =
    document.getElementById(
        'listBtn'
    );


const calendarView =
    document.getElementById(
        'calendarView'
    );


const listView =
    document.getElementById(
        'listView'
    );


calendarBtn.addEventListener(

    'click',

    function () {

        calendarBtn.classList.add(
            'active'
        );


        listBtn.classList.remove(
            'active'
        );


        calendarView.style.display =
            'block';


        listView.style.display =
            'none';


        calendar.updateSize();

    }

);


listBtn.addEventListener(

    'click',

    function () {

        listBtn.classList.add(
            'active'
        );


        calendarBtn.classList.remove(
            'active'
        );


        calendarView.style.display =
            'none';


        listView.style.display =
            'block';


        renderList();

    }

);


/* =========================================================
   RENDER LIST
========================================================= */

function renderList() {

    const container =
        document.getElementById(
            'eventList'
        );


    container.innerHTML = '';


    if (!events.length) {

        container.innerHTML = `

            <div
                class="text-center py-5 text-muted"
            >

                No events yet.

            </div>

        `;


        return;

    }


    const grouped = {};


    events.forEach(

        function (event) {

            const date =
                event.event_date;


            if (!grouped[date]) {

                grouped[date] = [];

            }


            grouped[date].push(
                event
            );

        }

    );


    Object.keys(grouped)

        .sort()

        .forEach(

            function (date) {

                const dateTitle =
                    document.createElement(
                        'div'
                    );


                dateTitle.className =
                    'list-date';


                dateTitle.textContent =
                    formatDate(
                        date
                    );


                container.appendChild(
                    dateTitle
                );


                grouped[date].forEach(

                    function (event) {

                        const item =
                            document.createElement(
                                'div'
                            );


                        item.className =
                            'list-event';


                        item.style.setProperty(

                            '--event-color',

                            event.event_color

                        );


                        let dateRange =
                            formatDate(
                                event.event_date
                            );


                        if (

                            event.end_date &&

                            event.end_date !==
                            event.event_date

                        ) {

                            dateRange +=

                                ' - ' +

                                formatDateShort(
                                    event.end_date
                                );

                        }


                        let time = '';


                        if (
                            event.start_time
                        ) {

                            time =
                                event.start_time
                                    .substring(
                                        0,
                                        5
                                    );


                            if (
                                event.end_time
                            ) {

                                time +=

                                    ' - ' +

                                    event.end_time
                                        .substring(
                                            0,
                                            5
                                        );

                            }

                        }


                        item.innerHTML = `

                            <div
                                class="event-line"
                            ></div>


                            <div
                                class="list-event-content"
                            >

                                <div
                                    class="list-event-title"
                                >

                                    ${escapeHtml(
                                        event.title
                                    )}

                                </div>


                                ${
                                    event.company

                                    ?

                                    `

                                    <div
                                        class="list-event-company"
                                    >

                                        ${escapeHtml(
                                            event.company
                                        )}

                                    </div>

                                    `

                                    :

                                    ''
                                }


                                <div
                                    class="list-event-date-range"
                                >

                                    ${escapeHtml(
                                        dateRange
                                    )}

                                </div>


                                ${
                                    time

                                    ?

                                    `

                                    <div
                                        class="list-event-time"
                                    >

                                        ${escapeHtml(
                                            time
                                        )}

                                    </div>

                                    `

                                    :

                                    ''
                                }

                            </div>

                        `;


                        item.addEventListener(

                            'click',

                            function () {

                                showEventDetails(
                                    event
                                );

                            }

                        );


                        container.appendChild(
                            item
                        );

                    }

                );

            }

        );

}


/* =========================================================
   EVENT DETAILS
========================================================= */

function showEventDetails(data) {

    document.getElementById(
        'detailTitle'
    ).textContent =
        data.title || '-';


    document.getElementById(
        'detailType'
    ).textContent =
        data.event_type || '-';


    document.getElementById(
        'detailCompany'
    ).textContent =
        data.company || '-';


    let dateText =
        formatDate(
            data.event_date
        );


    if (

        data.end_date &&

        data.end_date !==
        data.event_date

    ) {

        dateText +=

            ' - ' +

            formatDate(
                data.end_date
            );

    }


    document.getElementById(
        'detailDate'
    ).textContent =
        dateText;


    let time = '-';


    if (data.start_time) {

        time =
            data.start_time.substring(
                0,
                5
            );


        if (data.end_time) {

            time +=

                ' - ' +

                data.end_time.substring(
                    0,
                    5
                );

        }

    }


    document.getElementById(
        'detailTime'
    ).textContent =
        time;


    document.getElementById(
        'detailNotes'
    ).textContent =
        data.notes || '-';


    document.getElementById(
        'editEventBtn'
    ).href =

        'calendar/edit.php?id=' +
        data.id;


    document.getElementById(
        'deleteEventBtn'
    ).onclick =

        function () {


            document.getElementById(
                'confirmDeleteBtn'
            ).href =

                'calendar/hapus.php?id=' +
                data.id;


            const detailsModal =
                bootstrap.Modal.getInstance(

                    document.getElementById(
                        'eventDetailsModal'
                    )

                );


            if (detailsModal) {

                detailsModal.hide();

            }


            const deleteModal =
                new bootstrap.Modal(

                    document.getElementById(
                        'deleteConfirmModal'
                    )

                );


            deleteModal.show();

        };


    const modal =
        new bootstrap.Modal(

            document.getElementById(
                'eventDetailsModal'
            )

        );


    modal.show();

}


/* =========================================================
   FORMAT DATE
========================================================= */

function formatDate(dateString) {

    if (!dateString) {

        return '-';

    }


    const date =
        new Date(
            dateString +
            'T00:00:00'
        );


    return date.toLocaleDateString(

        'en-US',

        {

            weekday:
                'long',

            year:
                'numeric',

            month:
                'long',

            day:
                'numeric'

        }

    );

}


/* =========================================================
   FORMAT DATE SHORT
========================================================= */

function formatDateShort(dateString) {

    if (!dateString) {

        return '-';

    }


    const date =
        new Date(
            dateString +
            'T00:00:00'
        );


    return date.toLocaleDateString(

        'en-US',

        {

            year:
                'numeric',

            month:
                'long',

            day:
                'numeric'

        }

    );

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(text) {

    if (!text) {

        return '';

    }


    return String(text)

        .replace(
            /&/g,
            '&amp;'
        )

        .replace(
            /</g,
            '&lt;'
        )

        .replace(
            />/g,
            '&gt;'
        )

        .replace(
            /"/g,
            '&quot;'
        )

        .replace(
            /'/g,
            '&#039;'
        );

}


/* =========================================================
   COLOR PICKER
========================================================= */

document
    .querySelectorAll(
        '.color-option'
    )
    .forEach(

        function (option) {

            option.addEventListener(

                'click',

                function () {

                    document
                        .querySelectorAll(
                            '.color-option'
                        )
                        .forEach(

                            function (item) {

                                item.classList.remove(
                                    'selected'
                                );

                            }

                        );


                    option.classList.add(
                        'selected'
                    );


                    const input =
                        option.querySelector(
                            'input'
                        );


                    if (input) {

                        input.checked =
                            true;

                    }

                }

            );

        }

    );


/* =========================================================
   DATE RANGE
========================================================= */

const dateType =
    document.getElementById(
        'dateType'
    );


const endDateWrapper =
    document.getElementById(
        'endDateWrapper'
    );


const eventDate =
    document.getElementById(
        'eventDate'
    );


const endDate =
    document.getElementById(
        'endDate'
    );


dateType.addEventListener(

    'change',

    function () {

        if (
            this.value === 'range'
        ) {

            endDateWrapper.style.display =
                'block';


            endDate.required =
                true;


            endDate.min =
                eventDate.value;


            if (
                !endDate.value &&
                eventDate.value
            ) {

                endDate.value =
                    eventDate.value;

            }

        } else {

            endDateWrapper.style.display =
                'none';


            endDate.required =
                false;


            endDate.value =
                '';

        }

    }

);


eventDate.addEventListener(

    'change',

    function () {

        endDate.min =
            this.value;


        if (

            endDate.value &&

            endDate.value <
            this.value

        ) {

            endDate.value =
                this.value;

        }

    }

);


/* =========================================================
   SUCCESS TOAST
========================================================= */

const successToast =
    document.getElementById(
        'successToast'
    );


if (successToast) {

    setTimeout(

        function () {

            successToast.style.opacity =
                '0';


            successToast.style.transform =
                'translateY(-10px)';


            setTimeout(

                function () {

                    successToast.remove();

                },

                300

            );

        },

        3000

    );

}

</script>

<script>
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

    /* OPEN LOGOUT MODAL */
    logoutLink.addEventListener('click', function (event) {
        event.preventDefault();

        logoutConfirmModal.classList.add('show');
    });

    /* CANCEL */
    logoutConfirmCancel.addEventListener('click', function () {
        logoutConfirmModal.classList.remove('show');
    });

    /* CLICK OUTSIDE */
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