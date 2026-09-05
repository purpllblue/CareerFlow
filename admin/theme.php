<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "../config/koneksi.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>CareerFlow</title>

    <!-- =====================================================
         RESTORE THEME
    ====================================================== -->

    <script>
        (function () {
            const savedTheme =
                localStorage.getItem('careerFlowTheme') || 'blue';

            document.documentElement.setAttribute(
                'data-theme',
                savedTheme
            );
        })();
    </script>

    <!-- =====================================================
         BOOTSTRAP
    ====================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- =====================================================
         THEME CSS
    ====================================================== -->

    <link
        href="assets/css/theme.css?v=3"
        rel="stylesheet"
    >

    <!-- =====================================================
         POPPINS
    ====================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        /* =====================================================
           RESET
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #F8FAFC;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
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

            background:
                linear-gradient(
                    180deg,
                    #1E3A6D 0%,
                    #234A7A 50%,
                    #1F5A8A 100%
                );

            padding: 24px 14px;
            z-index: 1000;

            display: flex;
            flex-direction: column;

            border-right:
                1px solid
                rgba(255,255,255,.08);

            box-shadow:
                8px 0 25px
                rgba(15,23,42,.10),
                2px 0 6px
                rgba(15,23,42,.08);

            transition:
                width .25s ease,
                box-shadow .25s ease;
        }


        /* =====================================================
           LOGO
        ===================================================== */

        .sidebar .logo {
            color: #FFFFFF;
            font-size: 30px;
            font-weight: 700;
            padding: 0 14px;
            margin-bottom: 35px;

            white-space: nowrap;
            overflow: hidden;

            text-shadow:
                0 2px 4px
                rgba(15,23,42,.20);
        }

        .sidebar .logo span {
            color: var(--flow-color);
        }


        /* =====================================================
           MENU TITLE
        ===================================================== */

        .menu-title {
            color:
                rgba(255,255,255,.75);

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
            color:
                rgba(255,255,255,.90);

            padding: 13px 14px;

            border-radius: 8px;

            display: flex;
            align-items: center;

            gap: 11px;

            font-size: 15px;

            text-decoration: none;

            transition:
                all .2s ease;

            white-space: nowrap;
            position: relative;
        }

        .nav-link:hover {
            color: #FFFFFF;

            background:
                rgba(255,255,255,.12);

            box-shadow:
                0 8px 16px
                rgba(0,0,0,.20),
                0 3px 6px
                rgba(0,0,0,.12);

            transform:
                translateX(6px)
                translateY(-3px);

            border-right:
                2px solid
                rgba(255,255,255,.25);
        }

        .nav-link i {
            font-size: 19px;
            min-width: 18px;
            text-align: center;
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


        /* =====================================================
           THEME OPTIONS
        ===================================================== */

        .theme-options {
            display: none;
            margin-top: 2px;
            margin-bottom: 5px;
        }

        .theme-options.show {
            display: block;
        }

        .theme-option {
            color: rgba(255,255,255,.82);

            padding: 9px 14px 9px 48px;

            border-radius: 7px;

            display: flex;
            align-items: center;

            gap: 9px;

            font-size: 14px;

            text-decoration: none;

            cursor: pointer;

            transition:
                all .2s ease;
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


        /* =====================================================
           LOGOUT
        ===================================================== */

        .nav-link.logout {
            font-weight: 600;
            border-radius: 10px;

            transition:
                all .2s ease;
        }


        /* BLUE */

        html[data-theme="blue"]
        .nav-link.logout {
            color: #1E40AF !important;
            background: #DBEAFE !important;
        }

        html[data-theme="blue"]
        .nav-link.logout:hover {
            color: #1E3A8A !important;
            background: #BFDBFE !important;
        }


        /* PINK */

        html[data-theme="pink"]
        .nav-link.logout {
            color: #9D174D !important;
            background: #FCE7F3 !important;
        }

        html[data-theme="pink"]
        .nav-link.logout:hover {
            color: #831843 !important;
            background: #FBCFE8 !important;
        }


        /* PURPLE */

        html[data-theme="purple"]
        .nav-link.logout {
            color: #5B21B6 !important;
            background: #EDE9FE !important;
        }

        html[data-theme="purple"]
        .nav-link.logout:hover {
            color: #4C1D95 !important;
            background: #DDD6FE !important;
        }


        /* BLACK */

        html[data-theme="black"]
        .nav-link.logout {
            color: #374151 !important;
            background: #E5E7EB !important;
        }

        html[data-theme="black"]
        .nav-link.logout:hover {
            color: #1F2937 !important;
            background: #D1D5DB !important;
        }

        .nav-link.logout i {
            color: inherit !important;
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
                1px solid
                var(--accent-border);

            border-radius: 50%;

            background: #FFFFFF;
            color: var(--accent);

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;

            transition:
                all .2s ease;

            z-index: 1001;
        }

        .sidebar-toggle:hover {
            color: var(--accent);

            border-color:
                var(--accent);

            transform:
                scale(1.05);
        }


        /* =====================================================
           COLLAPSED SIDEBAR
        ===================================================== */

        body.sidebar-collapsed
        .sidebar {
            width: 72px;

            box-shadow:
                6px 0 20px
                rgba(15,23,42,.16);
        }

        body.sidebar-collapsed
        .logo {
            font-size: 0;

            padding-left: 10px;
            padding-right: 10px;
        }

        body.sidebar-collapsed
        .logo::before {
            content: "CF";

            font-size: 18px;
            font-weight: 700;
        }

        body.sidebar-collapsed
        .menu-title {
            opacity: 0;

            height: 0;

            padding-top: 0;
            padding-bottom: 0;

            margin-top: 0;
            margin-bottom: 0;

            overflow: hidden;
        }

        body.sidebar-collapsed
        .nav-link {
            justify-content: center;

            padding-left: 0;
            padding-right: 0;

            gap: 0;

            transform:
                translateX(0)
                translateY(0);
        }

        body.sidebar-collapsed
        .nav-link:hover {
            transform:
                translateX(3px)
                translateY(-2px);
        }

        body.sidebar-collapsed
        .nav-link span {
            display: none;
        }

        body.sidebar-collapsed
        .sidebar-toggle i {
            transform:
                rotate(180deg);
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
           MAIN
        ===================================================== */

        .main {
            margin-left: 240px;
            padding: 30px;

            min-height: 100vh;

            transition:
                margin-left .25s ease;
        }

        body.sidebar-collapsed
        .main {
            margin-left: 72px;
        }


        /* =====================================================
           PAGE HEADER
        ===================================================== */

        .page-header {
            margin-bottom: 24px;
        }

        .main .page-title {
            margin: 0 0 9px;

            font-size: 36px !important;
            font-weight: 700 !important;
        }

        .page-subtitle {
            margin: 0;

            font-size: 17px;
            color: #64748B;
        }


        /* =====================================================
           BLUE
        ===================================================== */

        html[data-theme="blue"]
        .sidebar {
            background:
                linear-gradient(
                    180deg,
                    #1E3A6D 0%,
                    #234A7A 50%,
                    #1F5A8A 100%
                ) !important;
        }


        /* =====================================================
           PINK
        ===================================================== */

        html[data-theme="pink"]
        .sidebar {
            background:
                linear-gradient(
                    180deg,
                    #9D174D 0%,
                    #BE185D 50%,
                    #DB2777 100%
                ) !important;
        }


        /* =====================================================
           PURPLE
        ===================================================== */

        html[data-theme="purple"]
        .sidebar {
            background:
                linear-gradient(
                    180deg,
                    #6D4BC3 0%,
                    #8066D8 50%,
                    #9278E3 100%
                ) !important;
        }


        /* =====================================================
           BLACK
        ===================================================== */

        html[data-theme="black"]
        .sidebar {
            background:
                linear-gradient(
                    180deg,
                    #111827 0%,
                    #1F2937 50%,
                    #374151 100%
                ) !important;
        }


        /* =====================================================
           LOGO COLORS
        ===================================================== */

        html[data-theme="blue"]
        .logo span {
            color: #60A5FA !important;
        }

        html[data-theme="pink"]
        .logo span {
            color: #F9A8D4 !important;
        }

        html[data-theme="purple"]
        .logo span {
            color: #C4B5FD !important;
        }

        html[data-theme="black"]
        .logo span {
            color: #D1D5DB !important;
        }


        /* =====================================================
           MOBILE
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

            .sidebar-toggle {
                display: none;
            }

            .sidebar .logo {
                margin-bottom: 20px;
            }

            .main .page-title {
                font-size: 30px !important;
            }

            .page-subtitle {
                font-size: 15px;
            }
        }


        /* =====================================================
           SMALL MOBILE
        ===================================================== */

        @media (max-width: 480px) {

            .main {
                padding: 15px;
            }
        }

    </style>

</head>


<body>


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


        <!-- MAIN -->

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
            href="logout.php"
            class="nav-link logout"
        >

            <i class="bi bi-box-arrow-right"></i>

            <span>
                Logout
            </span>

        </a>

    </div>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <div class="main">

        <div class="page-header">

            <h1 class="page-title">
                CareerFlow
            </h1>

            <p class="page-subtitle">
                Manage your CareerFlow preferences.
            </p>

        </div>

    </div>


    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->

    <script>


        /* =====================================================
           THEME
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


        const currentTheme =
            localStorage.getItem(
                'careerFlowTheme'
            ) || 'blue';


        document.documentElement.setAttribute(
            'data-theme',
            currentTheme
        );


        /* Mark current theme */

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


        /* Open / close Theme */

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


        /* Select Theme */

        themeOptionElements.forEach(
            function (option) {

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


                        this.classList.add(
                            'active'
                        );

                    }
                );

            }
        );


        /* =====================================================
           SIDEBAR
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
                    sidebarToggle.querySelector(
                        'i'
                    );


                const sidebarState =
                    localStorage.getItem(
                        'careerFlowSidebar'
                    );


                if (
                    sidebarState === 'true'
                ) {

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


</body>

</html>