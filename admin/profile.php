<?php

session_start();

include "../config/koneksi.php";


/* =========================================================
   AMBIL PROFILE TERBARU
========================================================= */

$profileCheck = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT *
         FROM profile
         ORDER BY id DESC
         LIMIT 1"
    )
);


/* =========================================================
   CEK PROFILE LENGKAP
========================================================= */

$profileComplete = false;

if ($profileCheck) {

    $profileComplete =
        trim($profileCheck['nama'] ?? '') !== '' &&
        trim($profileCheck['nama_panggilan'] ?? '') !== '' &&
        trim($profileCheck['email'] ?? '') !== '' &&
        trim($profileCheck['no_hp'] ?? '') !== '' &&
        trim($profileCheck['lokasi'] ?? '') !== '' &&
        trim($profileCheck['pendidikan'] ?? '') !== '' &&
        trim($profileCheck['jurusan'] ?? '');
}


/* =========================================================
   SIMPAN PROFILE
========================================================= */

if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama'] ?? '');
    $nama_panggilan = trim($_POST['nama_panggilan'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $lokasi = trim($_POST['lokasi'] ?? '');
    $pendidikan = trim($_POST['pendidikan'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $portfolio = trim($_POST['portfolio'] ?? '');
    $github = trim($_POST['github'] ?? '');


    /* =====================================================
       VALIDASI FIELD WAJIB
    ===================================================== */

    if (
        $nama === '' ||
        $nama_panggilan === '' ||
        $email === '' ||
        $no_hp === '' ||
        $lokasi === '' ||
        $pendidikan === '' ||
        $jurusan === ''
    ) {

        header("Location: profile.php");
        exit;
    }


    /* =====================================================
       ESCAPE DATA
    ===================================================== */

    $nama = mysqli_real_escape_string(
        $koneksi,
        $nama
    );

    $nama_panggilan = mysqli_real_escape_string(
        $koneksi,
        $nama_panggilan
    );

    $email = mysqli_real_escape_string(
        $koneksi,
        $email
    );

    $no_hp = mysqli_real_escape_string(
        $koneksi,
        $no_hp
    );

    $lokasi = mysqli_real_escape_string(
        $koneksi,
        $lokasi
    );

    $pendidikan = mysqli_real_escape_string(
        $koneksi,
        $pendidikan
    );

    $jurusan = mysqli_real_escape_string(
        $koneksi,
        $jurusan
    );

    $linkedin = mysqli_real_escape_string(
        $koneksi,
        $linkedin
    );

    $portfolio = mysqli_real_escape_string(
        $koneksi,
        $portfolio
    );

    $github = mysqli_real_escape_string(
        $koneksi,
        $github
    );


    /* =====================================================
       UPDATE PROFILE JIKA SUDAH ADA
    ===================================================== */

    if ($profileCheck) {

        $id = intval($profileCheck['id']);

        $query = "
            UPDATE profile SET
                nama = '$nama',
                nama_panggilan = '$nama_panggilan',
                email = '$email',
                no_hp = '$no_hp',
                lokasi = '$lokasi',
                pendidikan = '$pendidikan',
                jurusan = '$jurusan',
                linkedin = '$linkedin',
                portfolio = '$portfolio',
                github = '$github'
            WHERE id = $id
        ";
    }


    /* =====================================================
       INSERT PROFILE JIKA BELUM ADA
    ===================================================== */

    else {

        $query = "
            INSERT INTO profile
            (
                nama,
                nama_panggilan,
                email,
                no_hp,
                lokasi,
                pendidikan,
                jurusan,
                linkedin,
                portfolio,
                github
            )
            VALUES
            (
                '$nama',
                '$nama_panggilan',
                '$email',
                '$no_hp',
                '$lokasi',
                '$pendidikan',
                '$jurusan',
                '$linkedin',
                '$portfolio',
                '$github'
            )
        ";
    }


    /* =====================================================
       EKSEKUSI
    ===================================================== */

    if (mysqli_query($koneksi, $query)) {

        $_SESSION['profile_completed'] = true;

        header("Location: profile.php?saved=1");
        exit;
    }
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Profile - Career Flow</title>


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
         THEME
    ====================================================== -->

    <link
        href="../assets/css/theme.css?v=3"
        rel="stylesheet"
    >


    <!-- =====================================================
         POPPINS
    ====================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         LOAD SELECTED THEME
    ====================================================== -->

    <script>

    (function () {

        const savedTheme =
            localStorage.getItem('careerFlowTheme');

        const theme =
            ['blue', 'pink', 'purple', 'black'].includes(savedTheme)
                ? savedTheme
                : 'blue';

        document.documentElement.setAttribute(
            'data-theme',
            theme
        );

    })();

    </script>


    <style>

        /* =====================================================
           GLOBAL
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        body {
            background: #F8FAFC;
            font-family: 'Poppins', sans-serif;
            color: #0F172A;
            margin: 0;
        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .navbar-custom {

            background:
                linear-gradient(
                    135deg,
                    var(--sidebar-1),
                    var(--sidebar-2),
                    var(--sidebar-3)
                );

            box-shadow:
                0 4px 15px rgba(15, 23, 42, .08);

            min-height: 68px;
        }


        /* =====================================================
           CAREER FLOW BRAND
        ===================================================== */

        .navbar-brand {

            font-family: 'Poppins', sans-serif;

            font-size: 30px;

            font-weight: 700;

            letter-spacing: -0.5px;

            text-decoration: none;

            padding: 0;

            line-height: 1;
        }

        .brand-career {
            color: #FFFFFF !important;
        }

        .brand-flow {
            color: var(--flow-color) !important;
        }


        /* =====================================================
           CAREER FLOW THEME
        ===================================================== */

        html[data-theme="blue"] .brand-flow {
            color: #60A5FA !important;
        }

        html[data-theme="pink"] .brand-flow {
            color: #F9A8D4 !important;
        }

        html[data-theme="purple"] .brand-flow {
            color: #C4B5FD !important;
        }

        html[data-theme="black"] .brand-flow {
            color: #D1D5DB !important;
        }


        /* =====================================================
           DASHBOARD BUTTON
        ===================================================== */

        .dashboard-btn {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            border: 1px solid rgba(255, 255, 255, .55);

            background: transparent;

            color: #FFFFFF;

            border-radius: 8px;

            font-family: 'Poppins', sans-serif;

            font-size: 16px;

            font-weight: 500;

            padding: 9px 16px;

            text-decoration: none;

            transition: all .2s ease;
        }

        .dashboard-btn:hover {

            background: rgba(255, 255, 255, .12);

            border-color: #FFFFFF;

            color: #FFFFFF;
        }


        /* =====================================================
           PAGE
        ===================================================== */

        .page {

            max-width: 900px;

            margin: 35px auto;

            padding: 0 20px;
        }


        /* =====================================================
           PROFILE CARD
        ===================================================== */

        .card-profile {

            background: #FFFFFF;

            border: 1px solid #E2E8F0;

            border-radius: 16px;

            box-shadow:
                0 8px 30px rgba(15, 23, 42, .06);

            overflow: hidden;
        }


        /* =====================================================
           PROFILE HEADER
        ===================================================== */

        .profile-header {

            color: #FFFFFF;

            padding: 30px;
        }


        /* =====================================================
           BLUE THEME
        ===================================================== */

        html[data-theme="blue"] .navbar-custom,
        html[data-theme="blue"] .profile-header {

            background:
                linear-gradient(
                    135deg,
                    #1E3A6D 0%,
                    #234A7A 50%,
                    #1F5A8A 100%
                ) !important;
        }


        /* =====================================================
           PINK THEME
        ===================================================== */

        html[data-theme="pink"] .navbar-custom,
        html[data-theme="pink"] .profile-header {

            background:
                linear-gradient(
                    135deg,
                    #9D174D 0%,
                    #BE185D 50%,
                    #DB2777 100%
                ) !important;
        }


        /* =====================================================
           PURPLE THEME
        ===================================================== */

        html[data-theme="purple"] .navbar-custom,
        html[data-theme="purple"] .profile-header {

            background:
                linear-gradient(
                    135deg,
                    #6D4BC3 0%,
                    #8066D8 50%,
                    #9278E3 100%
                ) !important;
        }


        /* =====================================================
           BLACK THEME
        ===================================================== */

        html[data-theme="black"] .navbar-custom,
        html[data-theme="black"] .profile-header {

            background:
                linear-gradient(
                    135deg,
                    #111827 0%,
                    #1F2937 50%,
                    #374151 100%
                ) !important;
        }


        /* =====================================================
           PROFILE ICON
        ===================================================== */

        .profile-icon {

            width: 60px;

            height: 60px;

            border-radius: 50%;

            color: #FFFFFF;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 25px;

            font-weight: 700;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, .12);

            flex-shrink: 0;
        }


        /* BLUE */

        html[data-theme="blue"] .profile-icon {
            background: #2563EB !important;
        }


        /* PINK */

        html[data-theme="pink"] .profile-icon {
            background: #EC4899 !important;
        }


        /* PURPLE */

        html[data-theme="purple"] .profile-icon {
            background: #8066D8 !important;
        }


        /* BLACK */

        html[data-theme="black"] .profile-icon {
            background: #6B7280 !important;
        }


        /* =====================================================
           PROFILE HEADER TEXT
        ===================================================== */

        .profile-header h3 {

            font-family: 'Poppins', sans-serif;

            font-size: 22px;

            font-weight: 700;

            color: #FFFFFF;

            margin-bottom: 5px !important;
        }

        .profile-header p {

            font-family: 'Poppins', sans-serif;

            font-size: 14px;

            font-weight: 400;

            color: rgba(255, 255, 255, .78);
        }


        /* =====================================================
           CARD BODY
        ===================================================== */

        .card-body {
            background: #FFFFFF;
        }


        /* =====================================================
           SECTION TITLE
        ===================================================== */

        .card-body h5 {

            font-family: 'Poppins', sans-serif;

            font-size: 18px;

            font-weight: 700;

            color: #0F172A;

            margin-bottom: 20px;
        }


        /* =====================================================
           FORM LABEL
        ===================================================== */

        .form-label {

            font-family: 'Poppins', sans-serif;

            font-size: 14px;

            font-weight: 600;

            color: #334155;

            margin-bottom: 8px;
        }


        /* =====================================================
           FORM INPUT
        ===================================================== */

        .form-control {

            height: 48px;

            border-radius: 8px;

            border: 1px solid #CBD5E1;

            background: #FFFFFF;

            color: #0F172A;

            font-family: 'Poppins', sans-serif;

            font-size: 14px;

            font-weight: 400;

            padding: 10px 13px;

            transition:
                border-color .2s ease,
                box-shadow .2s ease;
        }

        .form-control::placeholder {

            color: #94A3B8;

            font-family: 'Poppins', sans-serif;

            font-size: 14px;
        }


        /* =====================================================
           INPUT FOCUS - BLUE
        ===================================================== */

        html[data-theme="blue"] .form-control:focus {

            border-color: #2563EB !important;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, .15) !important;
        }


        /* =====================================================
           INPUT FOCUS - PINK
        ===================================================== */

        html[data-theme="pink"] .form-control:focus {

            border-color: #EC4899 !important;

            box-shadow:
                0 0 0 3px rgba(236, 72, 153, .15) !important;
        }


        /* =====================================================
           INPUT FOCUS - PURPLE
        ===================================================== */

        html[data-theme="purple"] .form-control:focus {

            border-color: #8066D8 !important;

            box-shadow:
                0 0 0 3px rgba(128, 102, 216, .15) !important;
        }


        /* =====================================================
           INPUT FOCUS - BLACK
        ===================================================== */

        html[data-theme="black"] .form-control:focus {

            border-color: #6B7280 !important;

            box-shadow:
                0 0 0 3px rgba(107, 114, 128, .15) !important;
        }


        /* =====================================================
           DIVIDER
        ===================================================== */

        hr {

            border: 0;

            border-top: 1px solid #E2E8F0;

            opacity: 1;
        }


        /* =====================================================
           SAVE BUTTON
        ===================================================== */

        .btn-save {

            border-radius: 8px;

            padding: 11px 22px;

            color: #FFFFFF !important;

            font-family: 'Poppins', sans-serif;

            font-size: 14px;

            font-weight: 600;

            transition:
                background .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }


        /* BLUE */

        html[data-theme="blue"] .btn-save {

            background: #2563EB !important;

            border-color: #2563EB !important;
        }

        html[data-theme="blue"] .btn-save:hover {

            background: #1D4ED8 !important;

            border-color: #1D4ED8 !important;
        }


        /* PINK */

        html[data-theme="pink"] .btn-save {

            background: #EC4899 !important;

            border-color: #EC4899 !important;
        }

        html[data-theme="pink"] .btn-save:hover {

            background: #DB2777 !important;

            border-color: #DB2777 !important;
        }


        /* PURPLE */

        html[data-theme="purple"] .btn-save {

            background: #8066D8 !important;

            border-color: #8066D8 !important;
        }

        html[data-theme="purple"] .btn-save:hover {

            background: #6D4BC3 !important;

            border-color: #6D4BC3 !important;
        }


        /* BLACK */

        html[data-theme="black"] .btn-save {

            background: #6B7280 !important;

            border-color: #6B7280 !important;
        }

        html[data-theme="black"] .btn-save:hover {

            background: #4B5563 !important;

            border-color: #4B5563 !important;
        }


        .btn-save:hover {

            color: #FFFFFF !important;

            transform: translateY(-1px);

            box-shadow:
                0 5px 12px var(--shadow-color);
        }

        .btn-save:active {

            color: #FFFFFF !important;

            transform: translateY(0);
        }


        /* =====================================================
           SUCCESS TOAST
        ===================================================== */

        .success-toast {

            display: flex;

            align-items: center;

            gap: 13px;

            background: #FFFFFF;

            border: 1px solid var(--accent-border);

            border-left: 4px solid var(--accent);

            border-radius: 10px;

            padding: 14px 17px;

            margin-bottom: 24px;

            box-shadow:
                0 8px 24px rgba(15, 23, 42, .08);

            animation:
                slideDown .3s ease;
        }


        /* =====================================================
           SUCCESS ICON
        ===================================================== */

        .success-icon {

            width: 34px;

            height: 34px;

            border-radius: 50%;

            background: var(--accent-light);

            color: var(--accent);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 17px;

            flex-shrink: 0;
        }


        /* =====================================================
           SUCCESS TEXT
        ===================================================== */

        .success-title {

            font-family: 'Poppins', sans-serif;

            font-size: 14px;

            font-weight: 700;

            color: #0F172A;
        }

        .success-text {

            font-family: 'Poppins', sans-serif;

            font-size: 13px;

            font-weight: 400;

            color: #64748B;

            margin-top: 2px;

            line-height: 1.5;
        }


        /* =====================================================
           TOAST ANIMATION
        ===================================================== */

        @keyframes slideDown {

            from {

                opacity: 0;

                transform: translateY(-8px);
            }

            to {

                opacity: 1;

                transform: translateY(0);
            }
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 768px) {

            .navbar-custom {
                min-height: 62px;
            }

            .navbar-brand {
                font-size: 23px;
            }

            .dashboard-btn {

                font-size: 13px;

                padding: 7px 12px;
            }

            .page {

                margin: 25px auto;

                padding: 0 15px;
            }

            .profile-header {
                padding: 24px;
            }

            .profile-icon {

                width: 54px;

                height: 54px;

                font-size: 21px;
            }

            .profile-header h3 {
                font-size: 19px;
            }

            .profile-header p {
                font-size: 13px;
            }

            .card-body {
                padding: 25px !important;
            }

            .card-body h5 {
                font-size: 16px;
            }

            .form-label {
                font-size: 13px;
            }

            .form-control {
                font-size: 13px;
            }

            .form-control::placeholder {
                font-size: 13px;
            }
        }

    </style>

</head>


<body>


    <!-- =====================================================
         NAVBAR
    ===================================================== -->

    <nav class="navbar navbar-custom px-4 py-3">

        <?php if ($profileComplete): ?>

            <a
                class="navbar-brand"
                href="dashboard.php"
                id="careerFlowLink"
            >
                <span class="brand-career">Career</span><span class="brand-flow">Flow</span>
            </a>

            <a
                href="dashboard.php"
                class="dashboard-btn"
                id="dashboardLink"
            >
                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        <?php else: ?>

            <a
                class="navbar-brand"
                href="profile.php"
                id="careerFlowLink"
            >
                <span class="brand-career">Career</span><span class="brand-flow">Flow</span>
            </a>

        <?php endif; ?>

    </nav>


    <!-- =====================================================
         PAGE
    ===================================================== -->

    <div class="page">

        <div class="card card-profile">


            <!-- =================================================
                 PROFILE HEADER
            ================================================== -->

            <div class="profile-header">

                <div
                    class="d-flex align-items-center gap-3"
                >

                    <div class="profile-icon">

                        <i class="bi bi-person-fill"></i>

                    </div>


                    <div>

                        <h3 class="fw-bold mb-1">
                            Your Profile
                        </h3>

                        <p class="mb-0">
                            Complete your personal information
                        </p>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 CARD BODY
            ================================================== -->

            <div class="card-body p-4 p-md-5">


                <!-- =================================================
                     SUCCESS MESSAGE
                ================================================== -->

                <?php if (isset($_GET['saved'])): ?>

                    <div
                        class="success-toast"
                        id="successToast"
                    >

                        <div class="success-icon">

                            <i class="bi bi-check-lg"></i>

                        </div>


                        <div>

                            <div class="success-title">
                                PROFILE SAVED!
                            </div>

                            <div class="success-text">
                                Your profile has been successfully saved.
                            </div>

                        </div>

                    </div>

                <?php endif; ?>


                <!-- =================================================
                     FORM
                ================================================== -->

                <form method="POST">


                    <!-- =================================================
                         PERSONAL INFORMATION
                    ================================================== -->

                    <h5 class="fw-bold mb-4">
                        Personal Information
                    </h5>


                    <div class="row g-3">


                        <!-- Full Name -->

                        <div class="col-md-6">

                            <label
                                class="form-label fw-semibold"
                            >
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?php echo htmlspecialchars($profileCheck['nama'] ?? ''); ?>"
                                placeholder="Your full name"
                                required
                            >

                        </div>


                        <!-- Preferred Name -->

                        <div class="col-md-6">

                            <label
                                class="form-label fw-semibold"
                            >
                                Preferred Name
                            </label>

                            <input
                                type="text"
                                name="nama_panggilan"
                                class="form-control"
                                value="<?php echo htmlspecialchars($profileCheck['nama_panggilan'] ?? ''); ?>"
                                placeholder="What should we call you?"
                                required
                            >

                        </div>


                        <!-- Email -->

                        <div class="col-md-6">

                            <label
                                class="form-label fw-semibold"
                            >
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?php echo htmlspecialchars($profileCheck['email'] ?? ''); ?>"
                                placeholder="your@email.com"
                                required
                            >

                        </div>


                        <!-- Phone Number -->

                        <div class="col-md-6">

                            <label
                                class="form-label fw-semibold"
                            >
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="no_hp"
                                class="form-control"
                                value="<?php echo htmlspecialchars($profileCheck['no_hp'] ?? ''); ?>"
                                placeholder="08xxxxxxxxxx"
                                required
                            >

                        </div>


                        <!-- Location -->

                        <div class="col-md-6">

                            <label
                                class="form-label fw-semibold"
                            >
                                Location
                            </label>

                            <input
                                type="text"
                                name="lokasi"
                                class="form-control"
                                value="<?php echo htmlspecialchars($profileCheck['lokasi'] ?? ''); ?>"
                                placeholder="City, Province"
                                required
                            >

                        </div>

                    </div>


                    <hr class="my-5">


                    <!-- =================================================
                         EDUCATION
                    ================================================== -->

                    <h5 class="fw-bold mb-4">
                        Education
                    </h5>


                    <div class="row g-3">


                        <!-- University -->

                        <div class="col-md-6">

                            <label
                                class="form-label fw-semibold"
                            >
                                University
                            </label>

                            <input
                                type="text"
                                name="pendidikan"
                                class="form-control"
                                value="<?php echo htmlspecialchars($profileCheck['pendidikan'] ?? ''); ?>"
                                placeholder="University name"
                                required
                            >

                        </div>


                        <!-- Major -->

                        <div class="col-md-6">

                            <label
                                class="form-label fw-semibold"
                            >
                                Major
                            </label>

                            <input
                                type="text"
                                name="jurusan"
                                class="form-control"
                                value="<?php echo htmlspecialchars($profileCheck['jurusan'] ?? ''); ?>"
                                placeholder="Your major"
                                required
                            >

                        </div>

                    </div>


                    <hr class="my-5">


                    <!-- =================================================
                         PROFESSIONAL LINKS
                    ================================================== -->

                    <h5 class="fw-bold mb-4">
                        Professional Links
                    </h5>


                    <!-- LinkedIn -->

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold"
                        >
                            LinkedIn
                        </label>

                        <input
                            type="text"
                            name="linkedin"
                            class="form-control"
                            value="<?php echo htmlspecialchars($profileCheck['linkedin'] ?? ''); ?>"
                            placeholder="https://linkedin.com/in/yourname"
                        >

                    </div>


                    <!-- Portfolio -->

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold"
                        >
                            Portfolio
                        </label>

                        <input
                            type="text"
                            name="portfolio"
                            class="form-control"
                            value="<?php echo htmlspecialchars($profileCheck['portfolio'] ?? ''); ?>"
                            placeholder="https://yourportfolio.com"
                        >

                    </div>


                    <!-- GitHub -->

                    <div class="mb-4">

                        <label
                            class="form-label fw-semibold"
                        >
                            GitHub
                        </label>

                        <input
                            type="text"
                            name="github"
                            class="form-control"
                            value="<?php echo htmlspecialchars($profileCheck['github'] ?? ''); ?>"
                            placeholder="https://github.com/yourusername"
                        >

                    </div>


                    <!-- =================================================
                         SAVE BUTTON
                    ================================================== -->

                    <div class="text-end">

                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-primary btn-save"
                        >

                            <i class="bi bi-check-lg me-1"></i>

                            Save Profile

                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>


    <!-- =====================================================
         SUCCESS TOAST SCRIPT
    ===================================================== -->

    <script>

        const successToast =
            document.getElementById(
                'successToast'
            );

        if (successToast) {

            setTimeout(() => {

                successToast.style.opacity =
                    '0';

                successToast.style.transform =
                    'translateY(-8px)';

                successToast.style.transition =
                    'all .3s ease';


                setTimeout(() => {

                    successToast.remove();

                }, 300);

            }, 3000);

        }

    </script>


</body>

</html>