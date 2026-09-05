<?php

session_start();

include "config/koneksi.php";


/* =========================================================
   CEK PROFILE
========================================================= */

$profileComplete = false;
$namaPanggilan = '';
$profile = null;

/*
   Jika sudah pernah menyimpan profile,
   maka profile dianggap sudah tersedia.

   Profile.php melakukan validasi field wajib
   sebelum menyimpan data.
*/
$profileResult = mysqli_query(
    $koneksi,
    "SELECT *
     FROM profile
     WHERE TRIM(COALESCE(nama_panggilan, '')) <> ''
     ORDER BY id DESC
     LIMIT 1"
);

if ($profileResult) {

    $profile = mysqli_fetch_assoc($profileResult);

    if ($profile) {

        $profileComplete = true;

        $namaPanggilan =
            trim($profile['nama_panggilan']);

    }
}


/* =========================================================
   LOGIN
========================================================= */

if (isset($_GET['login'])) {

    $_SESSION['login'] = true;

    /* =====================================================
       PROFILE SUDAH ADA
       LANGSUNG DASHBOARD
    ===================================================== */

    if ($profileComplete) {

        header("Location: admin/dashboard.php");
        exit;

    }


    /* =====================================================
       PROFILE BELUM ADA
       KE PROFILE
    ===================================================== */

    $_SESSION['profile_started'] = true;

    header("Location: admin/profile.php");
    exit;
}


/* =========================================================
   LOGIN BUTTON
========================================================= */

if ($profileComplete) {

    $loginLink = "login.php?login=1";

    $loginText =
        "Login as " .
        htmlspecialchars($namaPanggilan);

} else {

    $loginLink = "login.php?login=1";

    $loginText = "Login";
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

    <title>CareerFlow</title>


    <!-- =================================================
         THEME PRELOAD
    ================================================== -->

    <script>

        (function () {

            const savedTheme =
                localStorage.getItem('careerFlowTheme');

            const theme =
                ['blue', 'pink', 'purple', 'black']
                    .includes(savedTheme)
                    ? savedTheme
                    : 'blue';

            document.documentElement.setAttribute(
                'data-theme',
                theme
            );

        })();

    </script>


    <!-- =================================================
         BOOTSTRAP
    ================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =================================================
         BOOTSTRAP ICONS
    ================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >


    <!-- =================================================
         GOOGLE FONT
    ================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- =================================================
         GLOBAL THEME
    ================================================== -->

    <link
        rel="stylesheet"
        href="assets/css/theme.css?v=3"
    >


    <style>

        * {
            box-sizing: border-box;
        }


        /* =====================================================
           THEME VARIABLES
        ===================================================== */

        :root {

            --login-primary: #2563EB;
            --login-primary-dark: #1D4ED8;
            --login-soft: #EFF6FF;
            --login-soft-2: #DBEAFE;
            --login-body: #F8FAFC;
            --login-text: #0F172A;
            --login-muted: #64748B;
            --login-white: #FFFFFF;
            --login-border: #E2E8F0;
            --login-modal-overlay: rgba(15, 23, 42, .45);

        }


        /* =====================================================
           BLUE
        ===================================================== */

        html[data-theme="blue"] {

            --login-primary: #2563EB;
            --login-primary-dark: #1D4ED8;
            --login-soft: #EFF6FF;
            --login-soft-2: #DBEAFE;
            --login-body: #F8FAFC;
            --login-text: #0F172A;
            --login-muted: #64748B;
            --login-border: #E2E8F0;

        }


        /* =====================================================
           PINK
        ===================================================== */

        html[data-theme="pink"] {

            --login-primary: #EC4899;
            --login-primary-dark: #DB2777;
            --login-soft: #FCE7F3;
            --login-soft-2: #FBCFE8;
            --login-body: #FFF7FB;
            --login-text: #3F172B;
            --login-muted: #8B6475;
            --login-border: #F5D0E1;

        }


        /* =====================================================
           PURPLE
        ===================================================== */

        html[data-theme="purple"] {

            --login-primary: #8B5CF6;
            --login-primary-dark: #7C3AED;
            --login-soft: #F3E8FF;
            --login-soft-2: #E9D5FF;
            --login-body: #FAF8FF;
            --login-text: #24163D;
            --login-muted: #75658F;
            --login-border: #E9D5FF;

        }


        /* =====================================================
           BLACK
        ===================================================== */

        html[data-theme="black"] {

            --login-primary: #A1A1AA;
            --login-primary-dark: #71717A;
            --login-soft: #27272A;
            --login-soft-2: #3F3F46;
            --login-body: #09090B;
            --login-text: #F4F4F5;
            --login-muted: #A1A1AA;
            --login-white: #18181B;
            --login-border: #3F3F46;

        }


        /* =====================================================
           BODY
        ===================================================== */

        body {

            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: var(--login-body);
            color: var(--login-text);

        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .main-navbar {

            position: relative;
            z-index: 10;
            width: 100%;
            padding: 22px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--login-white);
            border-bottom:
                1px solid var(--login-border);
            backdrop-filter: blur(10px);

        }


        /* =====================================================
           LOGO
        ===================================================== */

        .brand {

            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;

        }


        .brand-icon {

            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
            background: var(--login-soft-2);
            box-shadow:
                0 6px 15px rgba(0, 0, 0, .08);

        }


        .brand-info {

            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.15;

        }


        .brand-name {

            font-size: 22px;
            font-weight: 800;
            color: var(--login-text);
            letter-spacing: -.5px;

        }


        .brand-name span {

            color: var(--login-primary);

        }


        .brand-by {

            margin-top: 3px;
            font-size: 9px;
            font-weight: 500;
            color: #94A3B8;
            letter-spacing: .1px;

        }


        .brand-by span {

            color: var(--login-primary);
            font-weight: 600;

        }


        /* =====================================================
           NAV BUTTON
        ===================================================== */

        .nav-login {

            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 9px;
            background: var(--login-primary);
            color: #FFFFFF;
            text-decoration: none;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;
            box-shadow:
                0 5px 14px rgba(0, 0, 0, .12);

        }


        .nav-login:hover {

            color: #FFFFFF;
            background: var(--login-primary-dark);
            transform: translateY(-2px);

        }


        /* =====================================================
           HERO
        ===================================================== */

        .hero {

            min-height: calc(100vh - 87px);
            padding: 70px 7%;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;

        }


        /* =====================================================
           BACKGROUND DECORATION
        ===================================================== */

        .hero::before {

            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: var(--login-soft);
            top: -220px;
            right: -150px;
            z-index: 0;

        }


        .hero::after {

            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: var(--login-soft-2);
            bottom: -180px;
            left: -120px;
            z-index: 0;

        }


        /* =====================================================
           HERO CONTENT
        ===================================================== */

        .hero-content {

            position: relative;
            z-index: 1;
            max-width: 650px;

        }


        /* =====================================================
           HERO BADGE
        ===================================================== */

        .hero-badge {

            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            margin-bottom: 22px;
            border-radius: 50px;
            background: var(--login-soft);
            color: var(--login-primary);
            font-size: 13px;
            font-weight: 600;

        }


        .hero-badge i {

            font-size: 14px;

        }


        /* =====================================================
           HERO TITLE
        ===================================================== */

        .hero h1 {

            margin: 0 0 8px;
            font-size: clamp(42px, 6vw, 68px);
            line-height: 1.08;
            font-weight: 800;
            letter-spacing: -2px;
            color: var(--login-text);

        }


        .hero h1 span {

            color: var(--login-primary);

        }


        /* =====================================================
           MADE BY
        ===================================================== */

        .made-by {

            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            color: #94A3B8;

        }


        .made-by span {

            color: var(--login-primary);
            font-weight: 600;

        }


        /* =====================================================
           HERO DESCRIPTION
        ===================================================== */

        .hero p {

            max-width: 590px;
            margin: 0;
            color: var(--login-muted);
            font-size: 17px;
            line-height: 1.8;

        }


        /* =====================================================
           HERO VISUAL
        ===================================================== */

        .hero-visual {

            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;

        }


        /* =====================================================
           VISUAL CARD
        ===================================================== */

        .visual-card {

            width: 340px;
            min-height: 310px;
            padding: 28px;
            border-radius: 24px;
            background: var(--login-white);
            border: 1px solid var(--login-border);
            box-shadow:
                0 20px 50px rgba(15, 23, 42, .10);
            position: relative;

        }


        /* =====================================================
           VISUAL HEADER
        ===================================================== */

        .visual-header {

            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;

        }


        .visual-title {

            font-size: 15px;
            font-weight: 700;
            color: var(--login-text);

        }


        .visual-dot {

            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #22C55E;

        }


        /* =====================================================
           APPLICATION ITEM
        ===================================================== */

        .application-item {

            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px;
            margin-bottom: 11px;
            border-radius: 12px;
            background: var(--login-body);

        }


        .application-icon {

            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--login-soft);
            color: var(--login-primary);
            font-size: 17px;

        }


        .application-info {

            flex: 1;

        }


        .application-name {

            font-size: 12px;
            font-weight: 700;
            color: var(--login-text);

        }


        .application-status {

            font-size: 10px;
            color: #94A3B8;
            margin-top: 2px;

        }


        /* =====================================================
           FLOATING CARD
        ===================================================== */

        .floating-card {

            position: absolute;
            right: -35px;
            bottom: 15px;
            padding: 14px 18px;
            border-radius: 13px;
            background: var(--login-white);
            border: 1px solid var(--login-border);
            box-shadow:
                0 12px 30px rgba(15, 23, 42, .12);
            display: flex;
            align-items: center;
            gap: 10px;

        }


        .floating-icon {

            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #DCFCE7;
            color: #16A34A;

        }


        .floating-text {

            font-size: 11px;
            font-weight: 600;
            color: var(--login-muted);

        }


        /* =====================================================
           LOGIN MODAL
        ===================================================== */

        .login-modal {

            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: var(--login-modal-overlay);
            backdrop-filter: blur(5px);

        }


        .login-modal.show {

            display: flex;

        }


        .login-modal-box {

            width: 100%;
            max-width: 390px;
            padding: 30px;
            border-radius: 20px;
            background: var(--login-white);
            border: 1px solid var(--login-border);
            box-shadow:
                0 25px 70px rgba(0, 0, 0, .20);
            text-align: center;
            animation: loginModalIn .2s ease;

        }


        @keyframes loginModalIn {

            from {

                opacity: 0;
                transform:
                    translateY(10px)
                    scale(.97);

            }

            to {

                opacity: 1;
                transform:
                    translateY(0)
                    scale(1);

            }

        }


        .login-modal-icon {

            width: 58px;
            height: 58px;
            margin: 0 auto 18px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--login-soft);
            color: var(--login-primary);
            font-size: 25px;

        }


        .login-modal-title {

            margin-bottom: 8px;
            font-size: 20px;
            font-weight: 700;
            color: var(--login-text);

        }


        .login-modal-text {

            margin-bottom: 25px;
            font-size: 13px;
            line-height: 1.6;
            color: var(--login-muted);

        }


        .login-modal-buttons {

            display: flex;
            gap: 10px;

        }


        .login-modal-btn {

            flex: 1;
            padding: 11px 16px;
            border-radius: 9px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;

        }


        .login-modal-no {

            background: transparent;
            color: var(--login-muted);
            border: 1px solid var(--login-border);

        }


        .login-modal-no:hover {

            background: var(--login-body);
            color: var(--login-text);

        }


        .login-modal-yes {

            background: var(--login-primary);
            color: #FFFFFF;
            border: 1px solid var(--login-primary);

        }


        .login-modal-yes:hover {

            background: var(--login-primary-dark);
            border-color: var(--login-primary-dark);

        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 900px) {

            .hero {

                padding-top: 55px;
                padding-bottom: 70px;

            }

            .hero-visual {

                display: none;

            }

            .hero-content {

                max-width: 700px;

            }

        }


        @media (max-width: 600px) {

            .main-navbar {

                padding: 18px 5%;

            }

            .brand-name {

                font-size: 19px;

            }

            .brand-icon {

                width: 38px;
                height: 38px;
                font-size: 21px;

            }

            .brand-by {

                font-size: 8px;

            }

            .nav-login {

                padding: 9px 15px;
                font-size: 13px;

            }

            .hero {

                min-height: calc(100vh - 77px);
                padding: 50px 7%;

            }

            .hero h1 {

                font-size: 42px;
                letter-spacing: -1.5px;

            }

            .hero p {

                font-size: 15px;

            }

            .login-modal-box {

                padding: 25px 20px;

            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="main-navbar">

    <a
        href="login.php"
        class="brand"
    >

        <div class="brand-icon">
            💼
        </div>


        <div class="brand-info">

            <div class="brand-name">
                Career<span>Flow</span>
            </div>


            <div class="brand-by">
                by <span>purpllblue</span>
            </div>

        </div>

    </a>


    <?php if ($profileComplete): ?>

        <button
            type="button"
            class="nav-login"
            onclick="openLoginModal()"
        >

            <i class="bi bi-box-arrow-in-right"></i>

            <?php echo $loginText; ?>

        </button>

    <?php else: ?>

        <a
            href="<?php echo $loginLink; ?>"
            class="nav-login"
        >

            <i class="bi bi-box-arrow-in-right"></i>

            <?php echo $loginText; ?>

        </a>

    <?php endif; ?>


</nav>


<!-- =====================================================
     HERO
===================================================== -->

<section class="hero">


    <div class="hero-content">


        <div class="hero-badge">

            <i class="bi bi-stars"></i>

            Your Career, Organized

        </div>


        <h1>

            Manage Your

            <span>Career Journey</span>

        </h1>


        <div class="made-by">

            by <span>purpllblue</span>

        </div>


        <p>

            Keep track of your job applications, companies,
            interviews, reminders, and career progress
            in one simple and organized place.

        </p>


    </div>


    <!-- =================================================
         VISUAL
    ================================================== -->

    <div class="hero-visual">


        <div class="visual-card">


            <div class="visual-header">

                <div class="visual-title">
                    Application Overview
                </div>

                <div class="visual-dot"></div>

            </div>


            <div class="application-item">

                <div class="application-icon">

                    <i class="bi bi-buildings"></i>

                </div>


                <div class="application-info">

                    <div class="application-name">
                        Company Application
                    </div>

                    <div class="application-status">
                        Application submitted
                    </div>

                </div>

            </div>


            <div class="application-item">

                <div class="application-icon">

                    <i class="bi bi-calendar-check"></i>

                </div>


                <div class="application-info">

                    <div class="application-name">
                        Interview
                    </div>

                    <div class="application-status">
                        Upcoming interview
                    </div>

                </div>

            </div>


            <div class="application-item">

                <div class="application-icon">

                    <i class="bi bi-check-circle"></i>

                </div>


                <div class="application-info">

                    <div class="application-name">
                        Career Progress
                    </div>

                    <div class="application-status">
                        Keep moving forward
                    </div>

                </div>

            </div>


            <div class="floating-card">


                <div class="floating-icon">

                    <i class="bi bi-graph-up-arrow"></i>

                </div>


                <div class="floating-text">

                    Stay organized.<br>

                    Keep moving forward.

                </div>


            </div>


        </div>


    </div>


</section>


<!-- =====================================================
     LOGIN CONFIRM MODAL
===================================================== -->

<?php if ($profileComplete): ?>

<div
    class="login-modal"
    id="loginModal"
    onclick="closeLoginModal(event)"
>


    <div
        class="login-modal-box"
        onclick="event.stopPropagation()"
    >


        <div class="login-modal-icon">

            <i class="bi bi-person-check"></i>

        </div>


        <div class="login-modal-title">

            Login as

            <?php echo htmlspecialchars($namaPanggilan); ?>?

        </div>


        <div class="login-modal-text">

            Your profile has already been saved.
            Do you want to continue to your dashboard?

        </div>


        <div class="login-modal-buttons">


            <button
                type="button"
                class="login-modal-btn login-modal-no"
                onclick="closeLoginModal()"
            >

                NO

            </button>


            <button
                type="button"
                class="login-modal-btn login-modal-yes"
                onclick="continueLogin()"
            >

                YES

            </button>


        </div>


    </div>


</div>

<?php endif; ?>


<script>


/* =====================================================
   OPEN LOGIN MODAL
===================================================== */

function openLoginModal() {

    const modal =
        document.getElementById('loginModal');

    if (modal) {

        modal.classList.add('show');

        document.body.style.overflow = 'hidden';

    }

}


/* =====================================================
   CLOSE LOGIN MODAL
===================================================== */

function closeLoginModal(event) {

    if (
        event &&
        event.target !== event.currentTarget
    ) {

        return;

    }


    const modal =
        document.getElementById('loginModal');

    if (modal) {

        modal.classList.remove('show');

        document.body.style.overflow = '';

    }

}


/* =====================================================
   CONTINUE LOGIN
===================================================== */

function continueLogin() {

    window.location.href =
        'login.php?login=1';

}


/* =====================================================
   ESC KEY
===================================================== */

document.addEventListener(
    'keydown',
    function (event) {

        if (event.key === 'Escape') {

            closeLoginModal();

        }

    }
);

</script>


</body>

</html>