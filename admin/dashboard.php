<?php

session_start();


/* =========================================================
   CEK LOGIN
========================================================= */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}


/* =========================================================
   CEK PROFILE
========================================================= */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";


/* =========================================================
   PROFILE
========================================================= */

$profile = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT *
         FROM profile
         ORDER BY id DESC
         LIMIT 1"
    )
);

$nama = $profile['nama'] ?? '';
$nama_panggilan = $profile['nama_panggilan'] ?? '';
$jurusan = $profile['jurusan'] ?? '';
$pendidikan = $profile['pendidikan'] ?? '';
$linkedin = $profile['linkedin'] ?? '';
$portfolio = $profile['portfolio'] ?? '';
$github = $profile['github'] ?? '';


/* =========================================================
   NOTE COLORS
========================================================= */

$colors = [
    '#DBEAFE',
    '#DDD6FE',
    '#FAF4DD',
    '#EFDBFA',
    '#FFEDD5',
    '#FCE7F3',
    '#DCFCE7'
];


/* =========================================================
   STATISTICS
========================================================= */

$total = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM lamaran"
    )
)['total'];

$applied = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM lamaran
         WHERE status = 'Applied'"
    )
)['total'];

$interview = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM lamaran
         WHERE status = 'Interview'"
    )
)['total'];

$offer = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM lamaran
         WHERE status = 'Offer'"
    )
)['total'];

$rejected = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM lamaran
         WHERE status = 'Rejected'"
    )
)['total'];


/* =========================================================
   RECENT APPLICATIONS
========================================================= */

$data = mysqli_query(
    $koneksi,
    "SELECT
        lamaran.*,
        companies.nama_perusahaan
     FROM lamaran
     LEFT JOIN companies
        ON lamaran.company_id = companies.id
     ORDER BY lamaran.id DESC
     LIMIT 5"
);


/* =========================================================
   NOTES
========================================================= */

$notes = mysqli_query(
    $koneksi,
    "SELECT *
     FROM notes
     ORDER BY id DESC"
);


/* =========================================================
   DATE
========================================================= */

$today = new DateTime(
    date('Y-m-d')
);

$tomorrow = clone $today;
$tomorrow->modify('+1 day');


/* =========================================================
   REMINDERS
========================================================= */

$reminders = [];


/* =========================================================
   CALENDAR QUOTES
========================================================= */

$eventQuotes = [

    'Interview' => [
        "Tomorrow is the day!!! Please jangan overthinking duluan :p",
        "Interview tomorrow. Time to act like you totally know what you're doing :D",
        "HR is waiting. Your anxiety is also waiting. Choose wisely :p",
        "Good luck! Senyum dulu, panik belakangan ><"
    ],

    'Assessment' => [
        "Assessment time! Otak, please cooperate today :')",
        "It's just a test kok... bukan kiamat. Probably :p",
        "Brain.exe, please don't stop working now ><",
        "May your answers be right and your guesses be lucky :D"
    ],

    'Technical Test' => [
        "Technical test! Saatnya buka soal sambil pura-pura tenang :p",
        "Debugging era starts now. Stay calm, bestie ><",
        "Let's cook! Jangan sampai yang cooked malah laptopnya :D",
        "You got this! Kalau error, ya... kita error bareng :')"
    ],

    'Follow Up' => [
        "Psst... maybe it's time to say hello again :p",
        "HR belum bales? Let's gently remind them that you exist ><",
        "Time to follow up! Jangan malu, ini bukan confessing :D",
        "Just checking in... and also checking if HR remembers us :')"
    ],

    'default' => [
        "Something is happening today! Please jangan lupa :p",
        "It's happening! Stay calm and pretend everything is under control :D",
        "Today's agenda said: surprise! ><",
        "Okay bestie, time to actually do the thing :p"
    ]
];


/* =========================================================
   CALENDAR EVENTS
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


while ($event = mysqli_fetch_assoc($queryEvents)) {

    $eventStart = new DateTime(
        $event['event_date']
    );

    $eventEnd = !empty($event['end_date'])
        ? new DateTime($event['end_date'])
        : clone $eventStart;

    $isToday =
        $today >= $eventStart &&
        $today <= $eventEnd;

    $isTomorrow =
        $tomorrow >= $eventStart &&
        $tomorrow <= $eventEnd;

    if (!$isToday && !$isTomorrow) {
        continue;
    }

    $eventType =
        $event['event_type'] ?: 'Event';

    $quotes =
        $eventQuotes[$eventType]
        ?? $eventQuotes['default'];

    $quote =
        $quotes[array_rand($quotes)];

    if ($isToday) {
        $label = 'Today';
        $priority = 1;
    } else {
        $label = 'Tomorrow';
        $priority = 2;
    }

    $reminders[] = [

        'label' =>
            $label,

        'title' =>
            $event['title'],

        'company' =>
            $event['nama_perusahaan'] ?? '',

        'date' =>
            $event['event_date'],

        'quote' =>
            $quote,

        'priority' =>
            $priority
    ];
}


/* =========================================================
   DEADLINE QUOTES
========================================================= */

$deadlineQuotes = [

    'h5' => [
        "D-5! Masih aman... but please jangan ditinggal dulu :p",
        "Five days left! Jangan lupa mulai prepare dari sekarang ><",
        "Closing date masih beberapa hari lagi. Pelan-pelan tapi jangan lupa :D"
    ],

    'h3' => [
        "D-3! Masih aman... but please jangan ditinggal dulu :p",
        "Closing date is getting closer. Time to stop saying 'nanti' ><",
        "Almost closing date! Jangan sampai jadi manusia last minute :D"
    ],

    'h1' => [
        "D-1!!! Please jangan jadi manusia last minute :p",
        "Tomorrow is the closing date. Run bestie, RUN ><",
        "Closing date besok! Tinggal sedikit lagi, gaskeun :D"
    ],

    'today' => [
        "Closing date today!!! Please jangan submit jam 23:59:59 :')",
        "IT'S D-DAY!!! Open that application and finish it :p",
        "Today is the closing date. No pressure... except the closing date :D"
    ],

    'overdue' => [
        "Oops... closing date-nya lewat :') Let's pretend that didn't happen.",
        "Bestie... closing date-nya udah lewat. Wake up please :p",
        "Well... that could've gone better ><",
        "It's past the closing date. No panic, just move on :D"
    ],

    'followup' => [
        "Still no reply? Maybe HR is buffering :p",
        "HR belum bales? Let's gently remind them that they exist ><",
        "No news yet. HR said: let me disappear real quick :')",
        "Time to follow up! Jangan malu, ini bukan confessing :D"
    ]
];


/* =========================================================
   APPLICATION DEADLINES
========================================================= */

$queryDeadlines = mysqli_query(
    $koneksi,
    "SELECT
        lamaran.*,
        companies.nama_perusahaan
     FROM lamaran
     LEFT JOIN companies
        ON lamaran.company_id = companies.id
     WHERE deadline IS NOT NULL
     ORDER BY deadline ASC"
);


while ($row = mysqli_fetch_assoc($queryDeadlines)) {

    $deadline = new DateTime(
        $row['deadline']
    );

    $difference =
        (int) $today
            ->diff($deadline)
            ->format('%r%a');

    $label = '';
    $quote = '';
    $priority = 99;


    /* FOLLOW UP */

    if (
        $difference < 0 &&
        (
            $row['status'] === 'Applied' ||
            $row['status'] === 'Under Review'
        )
    ) {

        $label = 'Follow Up';

        $quotes =
            $deadlineQuotes['followup'];

        $quote =
            $quotes[array_rand($quotes)];

        $priority = 3;
    }


    /* OVERDUE */

    elseif ($difference < 0) {

        $label = 'Overdue';

        $quotes =
            $deadlineQuotes['overdue'];

        $quote =
            $quotes[array_rand($quotes)];

        $priority = 4;
    }


    /* TODAY */

    elseif ($difference === 0) {

        $label = 'Today';

        $quotes =
            $deadlineQuotes['today'];

        $quote =
            $quotes[array_rand($quotes)];

        $priority = 1;
    }


    /* H-1 */

    elseif ($difference === 1) {

        $label = 'H-1';

        $quotes =
            $deadlineQuotes['h1'];

        $quote =
            $quotes[array_rand($quotes)];

        $priority = 2;
    }


    /* H-3 */

    elseif ($difference === 3) {

        $label = 'H-3';

        $quotes =
            $deadlineQuotes['h3'];

        $quote =
            $quotes[array_rand($quotes)];

        $priority = 3;
    }


    /* H-5 */

    elseif ($difference === 5) {

        $label = 'H-5';

        $quotes =
            $deadlineQuotes['h5'];

        $quote =
            $quotes[array_rand($quotes)];

        $priority = 3;
    }


    if ($label !== '') {

        $reminders[] = [

            'label' =>
                $label,

            'title' =>
                $row['posisi'],

            'company' =>
                $row['nama_perusahaan']
                    ?: $row['perusahaan']
                    ?: 'Unknown Company',

            'date' =>
                $row['deadline'],

            'quote' =>
                $quote,

            'priority' =>
                $priority
        ];
    }
}


/* =========================================================
   SORT REMINDERS
========================================================= */

usort(
    $reminders,
    function ($a, $b) {

        if (
            $a['priority'] ===
            $b['priority']
        ) {

            return strcmp(
                $a['date'],
                $b['date']
            );
        }

        return
            $a['priority']
            <=>
            $b['priority'];
    }
);

$reminders =
    array_slice(
        $reminders,
        0,
        6
    );


/* =========================================================
   SUCCESS
========================================================= */

$success =
    $_GET['success'] ?? '';


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
    Dashboard - CareerFlow
</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
    rel="stylesheet"
>

<link
    href="assets/css/theme.css"
    rel="stylesheet"
>

<link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
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
    font-family: 'Poppins', sans-serif;
    background: #F8FAFC;
    color: #0F172A;
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

    background: linear-gradient(
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
        8px 0 25px rgba(15, 23, 42, .18),
        2px 0 6px rgba(15, 23, 42, .10);

    transition:
        width .25s ease,
        box-shadow .25s ease;
}


/* LOGO */

.logo {
    color: white;

    font-size: 23px;
    font-weight: 700;

    padding: 0 14px;

    margin-bottom: 35px;

    white-space: nowrap;
    overflow: hidden;

    transition:
        all .25s ease;

    text-shadow:
        0 2px 4px rgba(15, 23, 42, .20);
}

.logo span {
    color: var(--flow-color);
}


/* MENU TITLE */

.menu-title {
    color: rgba(255, 255, 255, .7) !important;

    font-size: 12px;
    font-weight: 700;

    padding: 0 14px;

    margin-bottom: 10px;

    text-transform: uppercase;

    letter-spacing: .7px;
}

.account-title {
    margin-top: 25px;
}


/* NAVIGATION */

.nav-link {
    color: #FFFFFF !important;

    padding: 12px 14px;

    border-radius: 8px;

    display: flex;
    align-items: center;

    gap: 11px;

    font-size: 14px;

    text-decoration: none;

    transition:
        all .2s ease;

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
    font-size: 18px;

    min-width: 18px;

    text-align: center;

    transition:
        transform .2s ease;
}

.nav-link:hover i {
    transform:
        translateY(-1px);
}

.nav-link.logout {
    font-weight: 600;
    border-radius: 10px;
}

html[data-theme="blue"] .nav-link.logout {
    color: #1E40AF !important;
    background: #DBEAFE !important;
}

html[data-theme="blue"] .nav-link.logout:hover {
    color: #1E3A8A !important;
    background: #BFDBFE !important;
}

html[data-theme="pink"] .nav-link.logout {
    color: #9D174D !important;
    background: #FCE7F3 !important;
}

html[data-theme="pink"] .nav-link.logout:hover {
    color: #831843 !important;
    background: #FBCFE8 !important;
}

html[data-theme="purple"] .nav-link.logout {
    color: #5B21B6 !important;
    background: #EDE9FE !important;
}

html[data-theme="purple"] .nav-link.logout:hover {
    color: #4C1D95 !important;
    background: #DDD6FE !important;
}

html[data-theme="black"] .nav-link.logout {
    color: #374151 !important;
    background: #E5E7EB !important;
}

html[data-theme="black"] .nav-link.logout:hover {
    color: #1F2937 !important;
    background: #D1D5DB !important;
}

.nav-link.logout:hover {
    transform: none !important;
}


/* SPACER */

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
    border: 1px solid var(--accent-border);
    border-radius: 50%;
    background: white;
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .2s ease;
    z-index: 1001;
}

.sidebar-toggle:hover {
    color: var(--accent);
    border-color: var(--accent);
    transform: scale(1.05);
}

/* =====================================================
   SIDEBAR TOGGLE — THEME CURSOR
===================================================== */

html[data-theme="blue"] .sidebar-toggle {
    cursor: pointer;
}

html[data-theme="pink"] .sidebar-toggle {
    cursor: pointer;
}

html[data-theme="purple"] .sidebar-toggle {
    cursor: pointer;
}

html[data-theme="black"] .sidebar-toggle {
    cursor: pointer;
}

/* =====================================================
   SIDEBAR TOGGLE — THEME GLOW
===================================================== */

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
   WELCOME
===================================================== */

.welcome {
    margin-bottom: 22px;
}

.welcome h1 {
    font-size: 30px;
    font-weight: 700;

    margin-bottom: 7px;
}


/* WELCOME — THEME */

html[data-theme="blue"] .welcome h1 {
    color: #2563EB;
}

html[data-theme="pink"] .welcome h1 {
    color: #EC4899;
}

html[data-theme="purple"] .welcome h1 {
    color: #8066D8;
}

html[data-theme="black"] .welcome h1 {
    color: #6B7280;
}


.welcome p {
    color: #64748B;

    font-size: 15px;

    margin: 0;
}

.dashboard-header {
    display: flex;

    justify-content: space-between;
    align-items: flex-start;

    margin-bottom: 22px;
}


/* =====================================================
   PROFILE SUMMARY
===================================================== */

.profile-summary {
    text-align: right;

    padding-top: 2px;
}

.profile-summary-name {
    font-size: 15px;
    font-weight: 700;

    color: #0F172A;

    margin-bottom: 3px;
}

/* =====================================================
   PROFILE SUMMARY NAME — SESUAI TEMA
===================================================== */

/* BLUE */
html[data-theme="blue"] .profile-summary-name {
    color: #2563EB;
}

/* PINK */
html[data-theme="pink"] .profile-summary-name {
    color: #EC4899;
}

/* PURPLE */
html[data-theme="purple"] .profile-summary-name {
    color: #8066D8;
}

/* BLACK */
html[data-theme="black"] .profile-summary-name {
    color: #6B7280;
}

.profile-summary-info {
    font-size: 12px;

    color: #64748B;

    line-height: 1.5;
}

.profile-summary-links {
    display: flex;

    justify-content: flex-end;

    gap: 8px;

    margin-top: 8px;
}

.profile-summary-links a {
    width: 30px;
    height: 30px;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    border:
        1px solid #E2E8F0;

    border-radius: 7px;

    background: #FFFFFF;

    color: #64748B;

    text-decoration: none;

    font-size: 15px;

    transition:
        all .2s ease;
}

/* =====================================================
   PROFILE LINKS — HOVER SESUAI TEMA
===================================================== */

.profile-summary-links a:hover {
    transform:
        translateY(-1px);
}


/* BLUE */

html[data-theme="blue"] .profile-summary-links a:hover {
    color: #2563EB;

    background: #EFF6FF;

    border-color: #BFDBFE;
}


/* PINK */

html[data-theme="pink"] .profile-summary-links a:hover {
    color: #EC4899;

    background: #FCE7F3;

    border-color: #F9A8D4;
}


/* PURPLE */

html[data-theme="purple"] .profile-summary-links a:hover {
    color: #8066D8;

    background: #F3EEFF;

    border-color: #C4B5FD;
}


/* BLACK */

html[data-theme="black"] .profile-summary-links a:hover {
    color: #6B7280;

    background: #F1F3F5;

    border-color: #9CA3AF;
}

/* =====================================================
   QUOTE
===================================================== */

.quote-banner {
    position: relative;
    padding: 23px 28px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;

    border: 1px solid;
    box-shadow: 0 4px 12px var(--shadow-color);
}


/* =========================
   BLUE — PASTEL BLUE
========================= */

html[data-theme="blue"] .quote-banner {
    background: linear-gradient(
        135deg,
        #EAF3FF 0%,
        #DCEBFF 100%
    ) !important;

    border-color: #BFDBFE !important;
}


/* =========================
   PINK — PASTEL PINK
========================= */

html[data-theme="pink"] .quote-banner {
    background: linear-gradient(
        135deg,
        #FFF0F7 0%,
        #FCE1EE 100%
    ) !important;

    border-color: #F9C2DC !important;
}


/* =========================
   PURPLE — PASTEL PURPLE
========================= */

html[data-theme="purple"] .quote-banner {
    background: linear-gradient(
        135deg,
        #F3EEFF 0%,
        #E8DEFF 100%
    ) !important;

    border-color: #D4C4F7 !important;
}


/* =========================
   BLACK — PASTEL GREY
========================= */

html[data-theme="black"] .quote-banner {
    background: linear-gradient(
        135deg,
        #F1F3F5 0%,
        #E2E5E9 100%
    ) !important;

    border-color: #D1D5DB !important;
}


/* =========================
   KUTIP
========================= */

.quote-banner::before {
    content: "“";
    position: absolute;
    top: -12px;
    left: 18px;

    font-size: 80px;
    font-family: Georgia, serif;
    line-height: 1;
}


/* =========================
   BINTANG
========================= */

.quote-banner::after {
    content: "✦";
    position: absolute;
    right: 24px;
    bottom: 12px;

    font-size: 22px;
}


/* =========================
   WARNA KUTIP + BINTANG
========================= */

html[data-theme="blue"] .quote-banner::before,
html[data-theme="blue"] .quote-banner::after {
    color: #93C5FD !important;
}

html[data-theme="pink"] .quote-banner::before,
html[data-theme="pink"] .quote-banner::after {
    color: #F3A6C8 !important;
}

html[data-theme="purple"] .quote-banner::before,
html[data-theme="purple"] .quote-banner::after {
    color: #B9A0E8 !important;
}

html[data-theme="black"] .quote-banner::before,
html[data-theme="black"] .quote-banner::after {
    color: #9CA3AF !important;
}


/* =========================
   TEKS QUOTE
========================= */

.quote-banner p {
    position: relative;
    z-index: 1;

    margin: 0;
    padding-left: 18px;
    padding-right: 35px;

    max-width: 850px;

    font-size: 18px;
    line-height: 1.65;
    font-weight: 600;
}


/* WARNA TEKS SESUAI TEMA */

html[data-theme="blue"] .quote-banner p {
    color: #1E40AF !important;
}

html[data-theme="pink"] .quote-banner p {
    color: #9D174D !important;
}

html[data-theme="purple"] .quote-banner p {
    color: #5B21B6 !important;
}

html[data-theme="black"] .quote-banner p {
    color: #374151 !important;
}


/* =====================================================
   STATISTICS
===================================================== */

.stat-card {
    background: white;

    border:
        1px solid #E2E8F0;

    border-radius: 12px;

    padding: 21px;

    height: 100%;

    transition:
        all .25s ease;
}

.stat-card:hover {
    transform:
        translateY(-3px);

    border-color:
        #BFDBFE;

    box-shadow:
        0 10px 25px
        rgba(15, 23, 42, .07);
}

.stat-icon {
    width: 40px;
    height: 40px;

    border-radius: 8px;

    background: #EFF6FF;

    color: #2563EB;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-bottom: 14px;

    font-size: 18px;
}

.stat-icon.total {
    background: #E0E7FF;
    color: #4F46E5;
}

.stat-icon.applied {
    background: #DBEAFE;
    color: #2563EB;
}

.stat-icon.interview {
    background: #EDE9FE;
    color: #7C3AED;
}

.stat-icon.offer {
    background: #DCFCE7;
    color: #16A34A;
}

.stat-icon.rejected {
    background: #FEE2E2;
    color: #DC2626;
}

.stat-label {
    font-size: 13px;

    color: #64748B;

    margin-bottom: 5px;
}

.stat-value {
    font-size: 28px;

    font-weight: 700;
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
        21px 24px;

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

    font-size: 16px;

    font-weight: 700;
}

.card-header-custom p {
    margin:
        5px 0 0;

    color: #94A3B8;

    font-size: 12px;
}


/* =====================================================
   REMINDERS
===================================================== */

.reminder-card {
    background: white;

    border:
        1px solid #E2E8F0;

    border-radius: 12px;

    padding: 19px;

    height: 100%;

    position: relative;

    overflow: hidden;

    transition:
        all .25s ease;
}

.reminder-card:hover {
    transform:
        translateY(-3px);

    border-color:
        #BFDBFE;

    box-shadow:
        0 10px 25px
        rgba(15, 23, 42, .07);
}

.reminder-card::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;
    bottom: 0;

    width: 4px;

    background:
        #2563EB;
}

.reminder-label {
    display: inline-flex;

    align-items: center;

    padding:
        5px 9px;

    border-radius: 6px;

    background:
        #EFF6FF;

    color:
        #2563EB;

    font-size: 11px;

    font-weight: 700;

    letter-spacing: .5px;

    margin-bottom: 12px;
}

.reminder-title {
    font-size: 15px;

    font-weight: 700;

    color: #0F172A;

    margin-bottom: 5px;
}

.reminder-company {
    font-size: 13px;

    color: #64748B;

    margin-bottom: 12px;
}

.reminder-date {
    font-size: 12px;

    color: #94A3B8;

    margin-bottom: 13px;
}

.reminder-quote {
    background:
        #F8FAFC;

    border-radius: 8px;

    padding:
        11px 12px;

    color: #475569;

    font-size: 12px;

    line-height: 1.6;
}

.reminder-empty {
    padding:
        35px 20px;

    text-align: center;

    color: #94A3B8;

    font-size: 14px;
}

.reminder-empty i {
    font-size: 28px;

    display: block;

    margin-bottom: 8px;

    color: #CBD5E1;
}


/* =====================================================
   NOTES
===================================================== */

.notes-grid {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 18px;

    padding: 24px;
}

.note-card {
    min-height: 210px;

    padding: 20px;

    padding-bottom: 58px;

    border-radius: 8px;

    position: relative;

    box-shadow:
        0 5px 15px
        rgba(15, 23, 42, .08);

    transition:
        all .25s ease;

    overflow: hidden;
}

.note-card:hover {
    transform:
        translateY(-3px);

    box-shadow:
        0 10px 25px
        rgba(15, 23, 42, .10);
}

.note-title {
    font-size: 16px;

    font-weight: 700;

    color: #0F172A;

    margin-bottom: 12px;
}

.note-content {
    color: #475569;

    font-size: 13px;

    line-height: 1.7;

    word-break:
        break-word;
}

.note-content p {
    margin:
        0 0 8px;
}

.note-content p:last-child {
    margin-bottom: 0;
}

.note-content ul,
.note-content ol {
    margin:
        6px 0;

    padding-left:
        20px;
}

.note-content li {
    margin-bottom:
        3px;
}

.note-date {
    font-size: 11px;

    color: #64748B;

    margin-top:
        15px;
}

.note-actions {
    position: absolute;

    right: 14px;
    bottom: 12px;

    display: flex;

    gap: 5px;
}

.note-action {
    width: 32px;
    height: 32px;

    border:
        1px solid #E2E8F0;

    border-radius: 7px;

    background:
        rgba(255, 255, 255, .7);

    color: #64748B;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    font-size: 14px;

    cursor: pointer;

    transition:
        all .2s ease;
}

.note-action:hover {
    transform:
        translateY(-1px);
}

.note-action.edit:hover {
    color: #2563EB;

    background:
        #EFF6FF;

    border-color:
        #BFDBFE;
}

.note-action.delete:hover {
    color: #DC2626;

    background:
        #FEF2F2;

    border-color:
        #FECACA;
}

.note-empty {
    padding:
        40px 20px;

    text-align: center;

    color: #94A3B8;

    font-size: 14px;
}


/* =====================================================
   NOTE EDITOR
===================================================== */

.note-editor {
    border:
        1px solid #CBD5E1;

    border-radius: 8px;

    overflow: hidden;

    background: white;
}

.note-toolbar {
    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 4px;

    padding: 8px;

    background:
        #F8FAFC;

    border-bottom:
        1px solid #E2E8F0;
}

.note-toolbar button {
    width: 34px;
    height: 32px;

    border:
        1px solid transparent;

    border-radius: 6px;

    background:
        transparent;

    color:
        #475569;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    transition:
        all .2s ease;
}

.note-toolbar button:hover {
    background:
        #E2E8F0;

    color:
        #2563EB;
}

.note-toolbar button:active {
    background:
        #DBEAFE;

    color:
        #2563EB;
}

.toolbar-divider {
    width: 1px;

    height: 22px;

    background:
        #CBD5E1;

    margin:
        0 4px;
}

.note-editor-area {
    min-height:
        180px;

    padding:
        14px 16px;

    outline: none;

    color:
        #334155;

    font-size:
        14px;

    line-height:
        1.75;
}

.note-editor-area:focus {
    box-shadow:
        inset 0 0 0 1px #93C5FD;
}

.note-editor-area p {
    margin:
        0 0 10px;
}

.note-editor-area ul,
.note-editor-area ol {
    margin-top: 6px;

    margin-bottom: 10px;

    padding-left: 24px;
}

.note-editor-area li {
    margin-bottom: 4px;
}

.note-editor-area:empty::before {
    content:
        "Write your note here...";

    color:
        #94A3B8;
}


/* =====================================================
   NOTE COLOR
===================================================== */

.note-color-picker {
    display: flex;

    gap: 9px;

    flex-wrap: wrap;
}

.note-color-option {
    cursor: pointer;
}

.note-color-option input {
    display: none;
}

.note-color-option span {
    display: block;

    width: 30px;
    height: 30px;

    border-radius: 50%;

    border:
        2px solid transparent;

    transition:
        all .2s ease;
}

.note-color-option input:checked + span {
    border-color:
        #2563EB;

    box-shadow:
        0 0 0 2px white,
        0 0 0 4px #2563EB;
}


/* =====================================================
   DELETE MODAL
===================================================== */

.action-modal-icon {
    width: 48px;
    height: 48px;

    margin:
        0 auto 14px;

    border-radius:
        50%;

    background:
        #EFF6FF;

    color:
        #2563EB;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 22px;
}

.action-modal-icon.danger {
    background:
        #FEF2F2;

    color:
        #DC2626;
}

.action-modal-title {
    font-size: 18px;

    font-weight: 700;

    color:
        #0F172A;

    text-align:
        center;

    margin-bottom:
        7px;
}

.action-modal-text {
    font-size: 14px;

    color:
        #64748B;

    text-align:
        center;

    margin-bottom:
        20px;
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

    background:
        #FFFFFF;

    border:
        1px solid #BFDBFE;

    border-left:
        4px solid #2563EB;

    border-radius:
        10px;

    box-shadow:
        0 10px 30px
        rgba(15, 23, 42, .12);

    padding:
        14px 16px;

    display: flex;

    align-items:
        flex-start;

    gap: 12px;

    animation:
        toastSlideIn .3s ease;
}

.success-toast-icon {
    width: 34px;
    height: 34px;

    flex-shrink: 0;

    border-radius: 50%;

    background:
        #DBEAFE;

    color:
        #2563EB;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 17px;
}

.success-toast-content {
    flex: 1;
}

.success-toast-title {
    font-size: 14px;

    font-weight: 700;

    color:
        #0F172A;

    margin-bottom:
        2px;
}

.success-toast-text {
    font-size: 12px;

    color:
        #64748B;

    line-height:
        1.5;
}

.success-toast-close {
    border: 0;

    background:
        transparent;

    color:
        #94A3B8;

    padding: 0;

    font-size: 17px;

    cursor: pointer;

    transition:
        color .2s ease;
}

.success-toast-close:hover {
    color:
        #475569;
}

/* =====================================================
   SUCCESS TOAST — SESUAI TEMA
===================================================== */


/* BLUE */

html[data-theme="blue"] .success-toast {
    border-color: #BFDBFE;
    border-left-color: #2563EB;
}

html[data-theme="blue"] .success-toast-icon {
    background: #DBEAFE;
    color: #2563EB;
}


/* PINK */

html[data-theme="pink"] .success-toast {
    border-color: #F9A8D4;
    border-left-color: #EC4899;
}

html[data-theme="pink"] .success-toast-icon {
    background: #FCE7F3;
    color: #EC4899;
}


/* PURPLE */

html[data-theme="purple"] .success-toast {
    border-color: #C4B5FD;
    border-left-color: #8066D8;
}

html[data-theme="purple"] .success-toast-icon {
    background: #EDE9FE;
    color: #8066D8;
}


/* BLACK */

html[data-theme="black"] .success-toast {
    border-color: #9CA3AF;
    border-left-color: #6B7280;
}

html[data-theme="black"] .success-toast-icon {
    background: #E5E7EB;
    color: #6B7280;
}

/* =====================================================
   SUCCESS TOAST TEXT — SESUAI TEMA
===================================================== */

html[data-theme="blue"] .success-toast-title {
    color: #1E40AF;
}

html[data-theme="pink"] .success-toast-title {
    color: #9D174D;
}

html[data-theme="purple"] .success-toast-title {
    color: #5B21B6;
}

html[data-theme="black"] .success-toast-title {
    color: #374151;
}


.success-toast-close:hover {
    color: var(--accent);
}

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
   TABLE
===================================================== */

.table {
    margin: 0;
}

.table th {
    font-size: 12px;

    font-weight: 600;

    color:
        #64748B;

    background:
        #F8FAFC;

    border-bottom:
        1px solid #E2E8F0;

    padding:
        14px 18px;
}

.table td {
    font-size: 13px;

    color:
        #334155;

    padding:
        15px 18px;

    border-bottom:
        1px solid #F1F5F9;
}

.table tbody tr:hover {
    background:
        #F8FAFC;
}

.recent-company {
    font-weight: 700;
    color: #0F172A;
}


/* =====================================================
   STATUS
===================================================== */

.status-badge {
    display: inline-flex;

    padding:
        5px 10px;

    border-radius:
        6px;

    font-size:
        11px;

    font-weight:
        600;
}

.status-applied {
    background:
        #DBEAFE;

    color:
        #2563EB;
}

.status-interview {
    background:
        #EDE9FE;

    color:
        #7C3AED;
}

.status-offer {
    background:
        #DCFCE7;

    color:
        #16A34A;
}

.status-rejected {
    background:
        #FEE2E2;

    color:
        #DC2626;
}

.status-default {
    background:
        #F1F5F9;

    color:
        #475569;
}


/* =====================================================
   DEADLINE
===================================================== */

.deadline-warning {
    color:
        #EA580C !important;

    font-weight:
        600;
}

.deadline-urgent {
    color:
        #DC2626 !important;

    font-weight:
        600;
}

.deadline-overdue {
    color:
        #DC2626 !important;

    font-weight:
        700;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 1100px) {

    .notes-grid {
        grid-template-columns:
            repeat(2, 1fr);
    }
}

@media (max-width: 768px) {

    .dashboard-header {
        flex-direction: column;
        gap: 12px;
    }

    .profile-summary {
        text-align: left;
    }

    .profile-summary-links {
        justify-content: flex-start;
    }

    .sidebar {
        position: relative;

        width: 100%;
        height: auto;
    }

    .main {
        margin-left: 0;

        padding: 20px;
    }

    .notes-grid {
        grid-template-columns:
            1fr;
    }

    .success-toast {
        left: 20px;
        right: 20px;

        min-width:
            auto;
    }
}


/* =====================================================
   LARGE FONT OVERRIDE
===================================================== */

/* GLOBAL */

body {
    font-size: 15px;
}


/* SIDEBAR */

.logo {
    font-size: 30px;
}

.menu-title {
    font-size: 13px;
}

.nav-link {
    font-size: 15px;
    padding: 13px 14px;
}

.nav-link i {
    font-size: 19px;
}


/* WELCOME */

.welcome h1 {
    font-size: 36px;
    margin-bottom: 9px;
}

.welcome p {
    font-size: 17px;
}


/* PROFILE SUMMARY */

.profile-summary-name {
    font-size: 18px;
    margin-bottom: 5px;
}

.profile-summary-info {
    font-size: 14px;
    line-height: 1.6;
}

.profile-summary-links {
    gap: 9px;
    margin-top: 10px;
}

.profile-summary-links a {
    width: 34px;
    height: 34px;
    font-size: 17px;
}


/* QUOTE */

.quote-banner {
    padding: 26px 32px;
}

.quote-banner p {
    font-size: 20px;
    line-height: 1.7;
}


/* STATISTICS */

.stat-card {
    padding: 24px;
}

.stat-icon {
    width: 44px;
    height: 44px;
    font-size: 20px;
    margin-bottom: 16px;
}

.stat-label {
    font-size: 15px;
    margin-bottom: 7px;
}

.stat-value {
    font-size: 34px;
}


/* CARD HEADER */

.card-header-custom {
    padding: 23px 26px;
}

.card-header-custom h5 {
    font-size: 19px;
}

.card-header-custom p {
    font-size: 14px;
    margin-top: 6px;
}


/* REMINDERS */

.reminder-card {
    padding: 22px;
}

.reminder-label {
    font-size: 12px;
    padding: 6px 11px;
    margin-bottom: 14px;
}

.reminder-title {
    font-size: 17px;
    margin-bottom: 6px;
    line-height: 1.45;
}

.reminder-company {
    font-size: 14px;
    margin-bottom: 14px;
    font-weight: 700 !important;
}

.reminder-date {
    font-size: 13px;
    margin-bottom: 15px;
}

.reminder-quote {
    font-size: 13px;
    line-height: 1.7;
    padding: 13px 14px;
}

.reminder-empty {
    font-size: 15px;
    padding: 40px 20px;
}

.reminder-empty i {
    font-size: 32px;
}


/* NOTES */

.notes-grid {
    gap: 20px;
    padding: 26px;
}

.note-card {
    min-height: 230px;
    padding: 23px;
    padding-bottom: 62px;
}

.note-title {
    font-size: 18px;
    margin-bottom: 14px;
    line-height: 1.4;
}

.note-content {
    font-size: 14px;
    line-height: 1.8;
}

.note-content p {
    margin-bottom: 9px;
}

.note-content ul,
.note-content ol {
    padding-left: 22px;
}

.note-date {
    font-size: 12px;
    margin-top: 17px;
}

.note-actions {
    right: 16px;
    bottom: 14px;
    gap: 6px;
}

.note-action {
    width: 35px;
    height: 35px;
    font-size: 15px;
}

.note-empty {
    padding: 45px 20px;
    font-size: 15px;
}


/* NOTE EDITOR */

.note-toolbar {
    gap: 5px;
    padding: 10px;
}

.note-toolbar button {
    width: 37px;
    height: 35px;
    font-size: 15px;
}

.toolbar-divider {
    height: 24px;
}

.note-editor-area {
    min-height: 200px;
    padding: 16px 18px;
    font-size: 15px;
    line-height: 1.8;
}

.note-editor-area p {
    margin-bottom: 11px;
}

.note-editor-area ul,
.note-editor-area ol {
    padding-left: 26px;
}


/* MODAL */

.action-modal-title {
    font-size: 20px;
}

.action-modal-text {
    font-size: 15px;
}


/* SUCCESS TOAST */

.success-toast {
    min-width: 350px;
    padding: 16px 18px;
}

.success-toast-icon {
    width: 38px;
    height: 38px;
    font-size: 19px;
}

.success-toast-title {
    font-size: 15px;
}

.success-toast-text {
    font-size: 13px;
}


/* TABLE */

.table th {
    font-size: 14px;
    padding: 18px 20px;
}

.table td {
    font-size: 15px;
    padding: 19px 20px;
}


/* STATUS */

.status-badge {
    padding: 6px 11px;
    font-size: 12px;
}


/* BUTTONS */

.btn {
    font-size: 14px;
}

.btn-sm {
    font-size: 13px;
}


/* FORM DI MODAL */

.form-label {
    font-size: 14px;
    font-weight: 500;
}

.form-control {
    font-size: 14px;
}


/* MOBILE */

@media (max-width: 768px) {

    .welcome h1 {
        font-size: 30px;
    }

    .welcome p {
        font-size: 15px;
    }

    .profile-summary-name {
        font-size: 17px;
    }

    .profile-summary-info {
        font-size: 13px;
    }

    .quote-banner {
        padding: 22px 24px;
    }

    .quote-banner p {
        font-size: 17px;
    }

    .stat-value {
        font-size: 30px;
    }

    .card-header-custom h5 {
        font-size: 17px;
    }

    .reminder-title {
        font-size: 16px;
    }

    .note-title {
        font-size: 17px;
    }

    .note-content {
        font-size: 14px;
    }

    .table th {
        font-size: 12px;
    }

    .table td {
        font-size: 13px;
    }
}


/* =====================================================
   STATISTICS - 5 CARDS
===================================================== */

.statistics-row {
    display: grid;

    grid-template-columns:
        repeat(5, minmax(0, 1fr));

    gap: 12px;
}

.statistics-row .stat-card {
    padding: 18px;

    min-width: 0;
}

.statistics-row .stat-icon {
    width: 38px;
    height: 38px;

    font-size: 17px;

    margin-bottom: 11px;
}

.statistics-row .stat-label {
    font-size: 13px;

    margin-bottom: 4px;

    white-space: nowrap;
}

.statistics-row .stat-value {
    font-size: 27px;
}


/* TABLET */

@media (max-width: 1100px) {

    .statistics-row {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }

}


/* MOBILE */

@media (max-width: 768px) {

    .statistics-row {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


/* FORCE SIDEBAR MENU TEXT WHITE */

.sidebar .nav-link {
    color: #FFFFFF !important;
}

.sidebar .nav-link i {
    color: #FFFFFF !important;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    color: #FFFFFF !important;
}

.sidebar .nav-link:hover i,
.sidebar .nav-link.active i {
    color: #FFFFFF !important;
}


/* MENU TITLE */

.sidebar .menu-title {
    color: rgba(255, 255, 255, 0.7) !important;
}


/* =====================================================
   THEME COLORS - DASHBOARD
===================================================== */

/* BLUE */

html[data-theme="blue"] .sidebar {
    background: linear-gradient(
        180deg,
        #1E3A6D 0%,
        #234A7A 50%,
        #1F5A8A 100%
    ) !important;
}

html[data-theme="blue"] .nav-link.active {
    background: linear-gradient(
        135deg,
        #2563EB 0%,
        #1D4ED8 100%
    ) !important;
}


/* PINK */

html[data-theme="pink"] .sidebar {
    background: linear-gradient(
        180deg,
        #9D174D 0%,
        #BE185D 50%,
        #DB2777 100%
    ) !important;
}

html[data-theme="pink"] .nav-link.active {
    background: linear-gradient(
        135deg,
        #EC4899 0%,
        #DB2777 100%
    ) !important;
}


/* PURPLE */

html[data-theme="purple"] .sidebar {
    background: linear-gradient(
        180deg,
        #6D4BC3 0%,
        #8066D8 50%,
        #9278E3 100%
    ) !important;
}

html[data-theme="purple"] .nav-link.active {
    background: linear-gradient(
        135deg,
        #8066D8 0%,
        #6D4BC3 100%
    ) !important;
}


/* BLACK */

html[data-theme="black"] .sidebar {
    background: linear-gradient(
        180deg,
        #111827 0%,
        #1F2937 50%,
        #374151 100%
    ) !important;
}

html[data-theme="black"] .nav-link.active {
    background: linear-gradient(
        135deg,
        #6B7280 0%,
        #4B5563 100%
    ) !important;
}


/* MENU TEXT */

.sidebar .nav-link:not(.logout) {
    color: #FFFFFF !important;
}

.sidebar .nav-link:not(.logout) i {
    color: #FFFFFF !important;
}

.sidebar .menu-title {
    color: rgba(255, 255, 255, 0.7) !important;
}


/* LOGO FLOW */

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
   LOGOUT — SESUAI TEMA
===================================================== */

.nav-link.logout {
    font-weight: 600;
    border-radius: 10px;
    transition: all .2s ease;
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


/* ICON IKUT WARNA LOGOUT */

.nav-link.logout i,
.nav-link.logout:hover i {
    color: inherit !important;
}


/* LOGOUT HOVER - TETAP BERGERAK */

.nav-link.logout {
    transition: all .2s ease !important;
}

.nav-link.logout:hover {
    transform:
        translateX(6px)
        translateY(-3px) !important;
}


/* =====================================================
   BUTTON PRIMARY — SESUAI TEMA
===================================================== */

.btn-primary {
    border: none !important;
    color: #FFFFFF !important;
    font-weight: 600 !important;
    transition: all .2s ease;
    box-shadow: 0 4px 10px var(--shadow-color);
}


/* BLUE */

html[data-theme="blue"] .btn-primary {
    background: linear-gradient(
        135deg,
        #2563EB 0%,
        #1D4ED8 100%
    ) !important;
}

html[data-theme="blue"] .btn-primary:hover {
    background: linear-gradient(
        135deg,
        #1D4ED8 0%,
        #2563EB 100%
    ) !important;
}


/* PINK */

html[data-theme="pink"] .btn-primary {
    background: linear-gradient(
        135deg,
        #EC4899 0%,
        #DB2777 100%
    ) !important;
}

html[data-theme="pink"] .btn-primary:hover {
    background: linear-gradient(
        135deg,
        #DB2777 0%,
        #EC4899 100%
    ) !important;
}


/* PURPLE */

html[data-theme="purple"] .btn-primary {
    background: linear-gradient(
        135deg,
        #8066D8 0%,
        #6D4BC3 100%
    ) !important;
}

html[data-theme="purple"] .btn-primary:hover {
    background: linear-gradient(
        135deg,
        #6D4BC3 0%,
        #8066D8 100%
    ) !important;
}


/* BLACK */

html[data-theme="black"] .btn-primary {
    background: linear-gradient(
        135deg,
        #6B7280 0%,
        #4B5563 100%
    ) !important;
}

html[data-theme="black"] .btn-primary:hover {
    background: linear-gradient(
        135deg,
        #4B5563 0%,
        #6B7280 100%
    ) !important;
}


/* FOCUS / ACTIVE */

.btn-primary:focus,
.btn-primary:active {
    color: #FFFFFF !important;
    border: none !important;
    box-shadow:
        0 0 0 3px var(--accent-border),
        0 4px 10px var(--shadow-color) !important;
}


/* =====================================================
   SETTINGS TOGGLE — SESUAI TEMA
===================================================== */

.form-switch .form-check-input {
    cursor: pointer;
    width: 2.8em;
    height: 1.5em;
    background-color: #D1D5DB !important;
    border-color: #D1D5DB !important;
}


/* BLUE */

html[data-theme="blue"] .form-switch .form-check-input:checked {
    background-color: #2563EB !important;
    border-color: #2563EB !important;
}


/* PINK */

html[data-theme="pink"] .form-switch .form-check-input:checked {
    background-color: #EC4899 !important;
    border-color: #EC4899 !important;
}


/* PURPLE */

html[data-theme="purple"] .form-switch .form-check-input:checked {
    background-color: #8066D8 !important;
    border-color: #8066D8 !important;
}


/* BLACK */

html[data-theme="black"] .form-switch .form-check-input:checked {
    background-color: #6B7280 !important;
    border-color: #6B7280 !important;
}


/* FOCUS */

.form-switch .form-check-input:focus {
    box-shadow:
        0 0 0 3px var(--accent-border) !important;

    border-color:
        var(--accent) !important;
}

/* =====================================================
   VIEW ALL — RECENT APPLICATIONS
===================================================== */

.view-all-btn {
    font-weight: 600;
    transition: all .2s ease !important;
}


/* BLUE */

html[data-theme="blue"] .view-all-btn {
    color: #2563EB !important;
    background: #EFF6FF !important;
    border-color: #BFDBFE !important;
}

html[data-theme="blue"] .view-all-btn:hover {
    color: #1D4ED8 !important;
    background: #DBEAFE !important;
    border-color: #93C5FD !important;
}


/* PINK */

html[data-theme="pink"] .view-all-btn {
    color: #EC4899 !important;
    background: #FCE7F3 !important;
    border-color: #F9A8D4 !important;
}

html[data-theme="pink"] .view-all-btn:hover {
    color: #DB2777 !important;
    background: #FBCFE8 !important;
    border-color: #F9A8D4 !important;
}


/* PURPLE */

html[data-theme="purple"] .view-all-btn {
    color: #8066D8 !important;
    background: #F3EEFF !important;
    border-color: #C4B5FD !important;
}

html[data-theme="purple"] .view-all-btn:hover {
    color: #6D4BC3 !important;
    background: #EDE9FE !important;
    border-color: #B9A0E8 !important;
}


/* BLACK */

html[data-theme="black"] .view-all-btn {
    color: #6B7280 !important;
    background: #F1F3F5 !important;
    border-color: #9CA3AF !important;
}

html[data-theme="black"] .view-all-btn:hover {
    color: #4B5563 !important;
    background: #E5E7EB !important;
    border-color: #9CA3AF !important;
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
            'note_added',
            'note_updated',
            'note_deleted'
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
                $success === 'note_added'
            ): ?>

                ADD NOTE

            <?php elseif (
                $success === 'note_updated'
            ): ?>

                NOTE UPDATED!

            <?php elseif (
                $success === 'note_deleted'
            ): ?>

                DELETE NOTE
            <?php endif; ?>

        </div>

        <div class="success-toast-text">

            <?php if (
                $success === 'note_added'
            ): ?>

                Your note has been added successfully.

            <?php elseif (
                $success === 'note_updated'
            ): ?>

                Your note has been updated successfully.

            <?php elseif (
                $success === 'note_deleted'
            ): ?>

                Your note has been deleted successfully.

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
    ====================================================== -->

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
    href="logout.php"
    class="logout-confirm-yes"
>
    Logout
</a>

        </div>

    </div>
</div>

        <!-- MAIN -->

        <div class="menu-title">
            Main
        </div>


        <a
            href="dashboard.php"
            class="nav-link active"
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


        <a
            href="profile.php"
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
    <span>
        Logout
    </span>
</a>
    </div>

<!-- =====================================================
     MAIN
===================================================== -->

<div class="main">


<!-- WELCOME -->

<div class="dashboard-header">

    <div class="welcome">

        <h1>
            Welcome<?= $nama_panggilan
                ? ', ' . htmlspecialchars($nama_panggilan)
                : '' ?>!
        </h1>

        <p>
            Keep track of your job applications and stay organized.
        </p>

    </div>


    <?php if ($profile): ?>

        <div class="profile-summary">

            <div class="profile-summary-name">
                <?= htmlspecialchars($nama) ?>
            </div>


            <?php if ($jurusan): ?>

                <div class="profile-summary-info">
                    <?= htmlspecialchars($jurusan) ?>
                </div>

            <?php endif; ?>


            <?php if ($pendidikan): ?>

                <div class="profile-summary-info">
                    <?= htmlspecialchars($pendidikan) ?>
                </div>

            <?php endif; ?>


            <?php if (
                $linkedin ||
                $portfolio ||
                $github
            ): ?>

                <div class="profile-summary-links">

                    <?php if ($linkedin): ?>

                        <a
                            href="<?= htmlspecialchars($linkedin) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="LinkedIn"
                        >
                            <i class="bi bi-linkedin"></i>
                        </a>

                    <?php endif; ?>


                    <?php if ($portfolio): ?>

                        <a
                            href="<?= htmlspecialchars($portfolio) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Portfolio"
                        >
                            <i class="bi bi-globe2"></i>
                        </a>

                    <?php endif; ?>


                    <?php if ($github): ?>

                        <a
                            href="<?= htmlspecialchars($github) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="GitHub"
                        >
                            <i class="bi bi-github"></i>
                        </a>

                    <?php endif; ?>

                </div>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>


<!-- =====================================================
     QUOTE
===================================================== -->

<div class="quote-banner">

    <p>
        Every application is one step closer to the right opportunity.
        <br>
        Keep moving forward! ^_^
    </p>

</div>


<!-- =====================================================
     STATISTICS
===================================================== -->

<div class="statistics-row mb-4">


    <div class="stat-card">

        <div class="stat-icon total">
            <i class="bi bi-briefcase"></i>
        </div>

        <div class="stat-label">
            Total Applications
        </div>

        <div class="stat-value">
            <?= $total ?>
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon applied">
            <i class="bi bi-send"></i>
        </div>

        <div class="stat-label">
            Applied
        </div>

        <div class="stat-value">
            <?= $applied ?>
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon interview">
            <i class="bi bi-chat-left-text"></i>
        </div>

        <div class="stat-label">
            Interviews
        </div>

        <div class="stat-value">
            <?= $interview ?>
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon offer">
            <i class="bi bi-trophy"></i>
        </div>

        <div class="stat-label">
            Offers
        </div>

        <div class="stat-value">
            <?= $offer ?>
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon rejected">
            <i class="bi bi-x-circle"></i>
        </div>

        <div class="stat-label">
            Rejected
        </div>

        <div class="stat-value">
            <?= $rejected ?>
        </div>

    </div>

</div>


<!-- =====================================================
     REMINDERS
===================================================== -->

<div class="main-card mb-4">

    <div class="card-header-custom">

        <div>

            <h5>
                Reminders
            </h5>

            <p>
                Things that might need your attention
            </p>

        </div>

    </div>


    <?php if (count($reminders) > 0): ?>

        <div class="p-4">

            <div class="row g-3">

                <?php foreach (
                    $reminders as $reminder
                ): ?>

                    <div class="col-xl-4 col-md-6">

                        <div class="reminder-card">

                            <div class="reminder-label">
                                <?= htmlspecialchars(
                                    $reminder['label']
                                ) ?>
                            </div>


                            <div class="reminder-title">
                                <?= htmlspecialchars(
                                    $reminder['title']
                                ) ?>
                            </div>


                            <?php if (
                                !empty(
                                    $reminder['company']
                                )
                            ): ?>

                                <div class="reminder-company">
                                    <strong>
                                        <?= htmlspecialchars(
                                            $reminder['company']
                                        ) ?>
                                    </strong>
                                </div>

                            <?php endif; ?>


                            <div class="reminder-date">

                                <i class="bi bi-calendar3"></i>

                                <?= formatDateId(
                                    $reminder['date']
                                ) ?>

                            </div>


                            <div class="reminder-quote">

                                “<?= htmlspecialchars(
                                    $reminder['quote']
                                ) ?>”

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    <?php else: ?>

        <div class="reminder-empty">

            <i class="bi bi-check2-circle"></i>

            No reminders for now.
            You're all caught up.

        </div>

    <?php endif; ?>

</div>


<!-- =====================================================
     NOTES
===================================================== -->

<div class="main-card mb-4">

    <div class="card-header-custom">

        <div>

            <h5>
                Notes
            </h5>

            <p>
                Quick things you don't want to forget
            </p>

        </div>


        <button
            type="button"
            class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#addNoteModal"
        >

            <i class="bi bi-plus-lg"></i>

            Add Note

        </button>

    </div>


    <?php if (
        mysqli_num_rows($notes) > 0
    ): ?>

        <div class="notes-grid">

            <?php while (
                $note =
                    mysqli_fetch_assoc($notes)
            ): ?>

                <div
                    class="note-card"
                    style="
                        background:
                        <?= htmlspecialchars(
                            $note['note_color']
                        ) ?>;
                    "
                >

                    <div class="note-title">

                        <?= htmlspecialchars(
                            $note['title']
                        ) ?>

                    </div>


                    <div class="note-content">

                        <?= $note['content'] ?>

                    </div>


                    <div class="note-date">

                        <i class="bi bi-clock"></i>

                        <?= formatDateId(
                            date(
                                'Y-m-d',
                                strtotime(
                                    $note['updated_at']
                                )
                            )
                        ) ?>

                    </div>


                    <div class="note-actions">

                        <button
                            type="button"
                            class="note-action edit"
                            data-bs-toggle="modal"
                            data-bs-target="#editNoteModal<?= $note['id'] ?>"
                            title="Edit"
                        >
                            <i class="bi bi-pencil"></i>
                        </button>


                        <button
                            type="button"
                            class="note-action delete"
                            onclick='confirmDeleteNote(
                                <?= (int) $note['id'] ?>,
                                <?= json_encode($note['title']) ?>
                            )'
                            title="Delete"
                        >
                            <i class="bi bi-trash3"></i>
                        </button>

                    </div>

                </div>


                <!-- EDIT NOTE MODAL -->

                <div
                    class="modal fade"
                    id="editNoteModal<?= $note['id'] ?>"
                    tabindex="-1"
                    aria-hidden="true"
                >

                    <div
                        class="modal-dialog modal-lg modal-dialog-centered"
                    >

                        <div
                            class="modal-content border-0 shadow"
                        >

                            <form
                                method="POST"
                                action="notes/edit.php"
                                onsubmit="
                                    return prepareNoteContent(
                                        'editEditor<?= $note['id'] ?>',
                                        'editContent<?= $note['id'] ?>'
                                    );
                                "
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $note['id'] ?>"
                                >


                                <div class="modal-header">

                                    <h5 class="modal-title">
                                        Edit Note
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                    ></button>

                                </div>


                                <div class="modal-body">

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Title
                                        </label>

                                        <input
                                            type="text"
                                            name="title"
                                            class="form-control"
                                            value="<?= htmlspecialchars(
                                                $note['title']
                                            ) ?>"
                                            required
                                        >

                                    </div>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Content
                                        </label>


                                        <div class="note-editor">

                                            <div class="note-toolbar">

                                                <button
                                                    type="button"
                                                    onclick="formatNote('bold', this)"
                                                    title="Bold"
                                                >
                                                    <i class="bi bi-type-bold"></i>
                                                </button>


                                                <button
                                                    type="button"
                                                    onclick="formatNote('italic', this)"
                                                    title="Italic"
                                                >
                                                    <i class="bi bi-type-italic"></i>
                                                </button>


                                                <button
                                                    type="button"
                                                    onclick="formatNote('underline', this)"
                                                    title="Underline"
                                                >
                                                    <i class="bi bi-type-underline"></i>
                                                </button>


                                                <span class="toolbar-divider"></span>


                                                <button
                                                    type="button"
                                                    onclick="formatNote('insertUnorderedList', this)"
                                                    title="Bullet List"
                                                >
                                                    <i class="bi bi-list-ul"></i>
                                                </button>


                                                <button
                                                    type="button"
                                                    onclick="formatNote('insertOrderedList', this)"
                                                    title="Numbering"
                                                >
                                                    <i class="bi bi-list-ol"></i>
                                                </button>


                                                <span class="toolbar-divider"></span>


                                                <button
                                                    type="button"
                                                    onclick="formatNote('justifyLeft', this)"
                                                    title="Align Left"
                                                >
                                                    <i class="bi bi-text-left"></i>
                                                </button>


                                                <button
                                                    type="button"
                                                    onclick="formatNote('justifyCenter', this)"
                                                    title="Align Center"
                                                >
                                                    <i class="bi bi-text-center"></i>
                                                </button>


                                                <button
                                                    type="button"
                                                    onclick="formatNote('justifyRight', this)"
                                                    title="Align Right"
                                                >
                                                    <i class="bi bi-text-right"></i>
                                                </button>


                                                <button
                                                    type="button"
                                                    onclick="formatNote('justifyFull', this)"
                                                    title="Justify"
                                                >
                                                    <i class="bi bi-justify"></i>
                                                </button>

                                            </div>


                                            <div
                                                id="editEditor<?= $note['id'] ?>"
                                                class="note-editor-area"
                                                contenteditable="true"
                                            ><?= $note['content'] ?></div>

                                        </div>


                                        <input
                                            type="hidden"
                                            name="content"
                                            id="editContent<?= $note['id'] ?>"
                                        >

                                    </div>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Note Color
                                        </label>


                                        <div class="note-color-picker">

                                            <?php foreach (
                                                $colors as $color
                                            ): ?>

                                                <label class="note-color-option">

                                                    <input
                                                        type="radio"
                                                        name="note_color"
                                                        value="<?= $color ?>"
                                                        <?= strtoupper(
                                                            $note['note_color']
                                                        ) === strtoupper($color)
                                                            ? 'checked'
                                                            : '' ?>
                                                    >

                                                    <span
                                                        style="
                                                            background:
                                                            <?= $color ?>;
                                                        "
                                                    ></span>

                                                </label>

                                            <?php endforeach; ?>

                                        </div>

                                    </div>

                                </div>


                                <div class="modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-light"
                                        data-bs-dismiss="modal"
                                    >
                                        Cancel
                                    </button>


                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        <i class="bi bi-check2"></i>

                                        Save Changes

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="note-empty">

            <i
                class="
                    bi
                    bi-journal-text
                    fs-3
                    d-block
                    mb-2
                "
            ></i>

            No notes yet.

        </div>

    <?php endif; ?>

</div>


<!-- =====================================================
     RECENT APPLICATIONS
===================================================== -->

<div class="main-card">

    <div class="card-header-custom">

        <div>

            <h5>
                Recent Applications
            </h5>

            <p>
                Your latest job applications
            </p>

        </div>


        <a
    href="lamaran/index.php"
    class="btn btn-light btn-sm view-all-btn"
>
    View All
</a>

    </div>


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

                </tr>

            </thead>


            <tbody>

                <?php if (
                    mysqli_num_rows($data) > 0
                ): ?>

                    <?php while (
                        $row =
                            mysqli_fetch_assoc($data)
                    ): ?>

                        <?php

                        $company =
                            $row['nama_perusahaan']
                            ?: $row['perusahaan']
                            ?: 'Unknown Company';

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
                                $deadlineDiff <= 1
                            ) {

                                $deadlineClass =
                                    'deadline-urgent';

                            } elseif (
                                $deadlineDiff <= 3
                            ) {

                                $deadlineClass =
                                    'deadline-warning';
                            }
                        }


                        $statusClass =
                            'status-default';

                        if (
                            $row['status'] === 'Applied'
                        ) {

                            $statusClass =
                                'status-applied';

                        } elseif (
                            $row['status'] === 'Interview'
                        ) {

                            $statusClass =
                                'status-interview';

                        } elseif (
                            $row['status'] === 'Offer'
                        ) {

                            $statusClass =
                                'status-offer';

                        } elseif (
                            $row['status'] === 'Rejected'
                        ) {

                            $statusClass =
                                'status-rejected';
                        }

                        ?>


                        <tr>

                            <td>
                                <span class="recent-company">
                                    <?= htmlspecialchars($company) ?>
                                </span>
                            </td>


                            <td>
                                <?= htmlspecialchars(
                                    $row['posisi']
                                ) ?>
                            </td>


                            <td>
                                <?= formatDateId(
                                    $row['tanggal_lamar']
                                ) ?>
                            </td>


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


                            <td>

                                <span
                                    class="
                                        status-badge
                                        <?= $statusClass ?>
                                    "
                                >

                                    <?= htmlspecialchars(
                                        $row['status']
                                    ) ?>

                                </span>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="5"
                            class="text-center py-5 text-muted"
                        >
                            No applications yet.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


</div>


<!-- =====================================================
     ADD NOTE MODAL
===================================================== -->

<div
    class="modal fade"
    id="addNoteModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div
        class="modal-dialog modal-lg modal-dialog-centered"
    >

        <div
            class="modal-content border-0 shadow"
        >

            <form
                method="POST"
                action="notes/tambah.php"
                onsubmit="
                    return prepareNoteContent(
                        'addNoteEditor',
                        'addNoteContent'
                    );
                "
            >

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Note
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Title
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="Note title"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Content
                        </label>


                        <div class="note-editor">

                            <div class="note-toolbar">

                                <button
                                    type="button"
                                    onclick="formatNote('bold', this)"
                                    title="Bold"
                                >
                                    <i class="bi bi-type-bold"></i>
                                </button>


                                <button
                                    type="button"
                                    onclick="formatNote('italic', this)"
                                    title="Italic"
                                >
                                    <i class="bi bi-type-italic"></i>
                                </button>


                                <button
                                    type="button"
                                    onclick="formatNote('underline', this)"
                                    title="Underline"
                                >
                                    <i class="bi bi-type-underline"></i>
                                </button>


                                <span class="toolbar-divider"></span>


                                <button
                                    type="button"
                                    onclick="formatNote('insertUnorderedList', this)"
                                    title="Bullet List"
                                >
                                    <i class="bi bi-list-ul"></i>
                                </button>


                                <button
                                    type="button"
                                    onclick="formatNote('insertOrderedList', this)"
                                    title="Numbering"
                                >
                                    <i class="bi bi-list-ol"></i>
                                </button>


                                <span class="toolbar-divider"></span>


                                <button
                                    type="button"
                                    onclick="formatNote('justifyLeft', this)"
                                    title="Align Left"
                                >
                                    <i class="bi bi-text-left"></i>
                                </button>


                                <button
                                    type="button"
                                    onclick="formatNote('justifyCenter', this)"
                                    title="Align Center"
                                >
                                    <i class="bi bi-text-center"></i>
                                </button>


                                <button
                                    type="button"
                                    onclick="formatNote('justifyRight', this)"
                                    title="Align Right"
                                >
                                    <i class="bi bi-text-right"></i>
                                </button>


                                <button
                                    type="button"
                                    onclick="formatNote('justifyFull', this)"
                                    title="Justify"
                                >
                                    <i class="bi bi-justify"></i>
                                </button>

                            </div>


                            <div
                                id="addNoteEditor"
                                class="note-editor-area"
                                contenteditable="true"
                            ></div>

                        </div>


                        <input
                            type="hidden"
                            name="content"
                            id="addNoteContent"
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Note Color
                        </label>


                        <div class="note-color-picker">

                            <?php foreach (
                                $colors as $index => $color
                            ): ?>

                                <label class="note-color-option">

                                    <input
                                        type="radio"
                                        name="note_color"
                                        value="<?= $color ?>"
                                        <?= $index === 0
                                            ? 'checked'
                                            : '' ?>
                                    >

                                    <span
                                        style="
                                            background:
                                            <?= $color ?>;
                                        "
                                    ></span>

                                </label>

                            <?php endforeach; ?>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
    type="submit"
    class="btn btn-primary"
    id="addNoteSubmitBtn"
>
    <i class="bi bi-plus-lg"></i>
    <span id="addNoteSubmitText">Add Note</span>
</button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- =====================================================
     DELETE CONFIRMATION MODAL
===================================================== -->

<div
    class="modal fade"
    id="deleteNoteModal"
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

                <div class="action-modal-icon danger">

                    <i class="bi bi-trash3"></i>

                </div>


                <div class="action-modal-title">

                    Delete this note?

                </div>


                <div class="action-modal-text">

                    <span
                        id="deleteNoteName"
                    ></span>

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
                        id="confirmDeleteNoteButton"
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
     BOOTSTRAP
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
>

</script>


<script>

/* =====================================================
   FORMAT NOTE
===================================================== */

function formatNote(
    command,
    button
) {

    const editor =
        button
            .closest('.note-editor')
            .querySelector(
                '.note-editor-area'
            );

    editor.focus();

    document.execCommand(
        command,
        false,
        null
    );

    editor.focus();
}


/* =====================================================
   PREPARE NOTE CONTENT
===================================================== */

function prepareNoteContent(
    editorId,
    inputId
) {

    const editor =
        document.getElementById(
            editorId
        );

    const input =
        document.getElementById(
            inputId
        );

    if (
        !editor ||
        !input
    ) {
        return false;
    }

    const content =
        editor.innerHTML.trim();

    if (
        content === '' ||
        content === '<br>'
    ) {

        alert(
            'Please write something in the note.'
        );

        editor.focus();

        return false;
    }

    input.value =
        content;

    return true;
}


/* =====================================================
   DELETE NOTE
===================================================== */

function confirmDeleteNote(
    id,
    title
) {

    document.getElementById(
        'deleteNoteName'
    ).textContent =
        '"' + title + '"';


    document.getElementById(
        'confirmDeleteNoteButton'
    ).href =
        'notes/hapus.php?id=' + id;


    const modalElement =
        document.getElementById(
            'deleteNoteModal'
        );


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );


    modal.show();
}


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
   CLEAR ADD NOTE EDITOR
===================================================== */

const addNoteModal =
    document.getElementById(
        'addNoteModal'
    );

if (addNoteModal) {

    addNoteModal.addEventListener(
        'hidden.bs.modal',
        function () {

            const editor =
                document.getElementById(
                    'addNoteEditor'
                );

            if (editor) {

                editor.innerHTML =
                    '';

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
/* =====================================================
   LOGOUT CONFIRMATION
===================================================== */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const logoutLink =
            document.getElementById('logoutLink');

        const logoutConfirmModal =
            document.getElementById(
                'logoutConfirmModal'
            );

        const logoutConfirmCancel =
            document.getElementById(
                'logoutConfirmCancel'
            );

        if (
            !logoutLink ||
            !logoutConfirmModal ||
            !logoutConfirmCancel
        ) {
            return;
        }

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
);

</script>

</body>

</html>