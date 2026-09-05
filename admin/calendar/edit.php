<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../../config/koneksi.php";


/* =========================
   CEK ID
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../calendar.php");
    exit;
}

$id = (int) $_GET['id'];


/* =========================
   AMBIL EVENT
========================= */

$query = mysqli_query(
    $koneksi,
    "SELECT *
     FROM calendar_events
     WHERE id = $id"
);

$event = mysqli_fetch_assoc($query);

if (!$event) {
    header("Location: ../calendar.php");
    exit;
}


/* =========================
   AMBIL COMPANY
========================= */

$companies = mysqli_query(
    $koneksi,
    "SELECT *
     FROM companies
     ORDER BY nama_perusahaan ASC"
);


/* =========================
   PROSES UPDATE
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['title'] ?? '')
    );

    $event_type = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['event_type'] ?? '')
    );

    $event_date = mysqli_real_escape_string(
        $koneksi,
        $_POST['event_date'] ?? ''
    );

    $end_date_input = trim(
        $_POST['end_date'] ?? ''
    );

    /*
     * Kalau Single Date,
     * end_date dikosongkan.
     *
     * Kalau Date Range,
     * end_date disimpan.
     */

    $end_date = !empty($end_date_input)
        ? "'" . mysqli_real_escape_string(
            $koneksi,
            $end_date_input
        ) . "'"
        : "NULL";


    $start_time = !empty($_POST['start_time'])
        ? "'" . mysqli_real_escape_string(
            $koneksi,
            $_POST['start_time']
        ) . "'"
        : "NULL";


    $end_time = !empty($_POST['end_time'])
        ? "'" . mysqli_real_escape_string(
            $koneksi,
            $_POST['end_time']
        ) . "'"
        : "NULL";


    $company_id = !empty($_POST['company_id'])
        ? (int) $_POST['company_id']
        : "NULL";


    $event_color = mysqli_real_escape_string(
        $koneksi,
        $_POST['event_color'] ?? '#2563EB'
    );


    $notes = mysqli_real_escape_string(
        $koneksi,
        $_POST['notes'] ?? ''
    );


    /* =========================
       VALIDASI TANGGAL
    ========================= */

    if (
        !empty($end_date_input) &&
        $end_date_input < $_POST['event_date']
    ) {

        $error =
            "End date cannot be earlier than start date.";

    } else {


        /* =========================
           UPDATE
        ========================= */

        $update = mysqli_query(
            $koneksi,
            "UPDATE calendar_events SET
                title = '$title',
                event_type = '$event_type',
                event_date = '$event_date',
                end_date = $end_date,
                start_time = $start_time,
                end_time = $end_time,
                company_id = $company_id,
                event_color = '$event_color',
                notes = '$notes'
             WHERE id = $id"
        );


        if ($update) {

            header(
                "Location: ../calendar.php?success=updated"
            );

            exit;
        }


        $error =
            "Failed to update event.";
    }
}


/* =========================
   TENTUKAN DATE TYPE
========================= */

$dateType =
    !empty($event['end_date']) &&
    $event['end_date'] !== $event['event_date']
        ? 'range'
        : 'single';

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Event - CareerFlow</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Theme -->

    <link
        href="../../assets/css/theme.css?v=3"
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

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            background: #F8FAFC;
            font-family: Arial, sans-serif;
            color: #1E293B;
        }


        .main {
            max-width: 850px;
            margin: 0 auto;
            padding: 40px 25px;
        }


        .page-header {
            margin-bottom: 25px;
        }


        .page-title {
            margin: 0;
            font-size: 27px;
            font-weight: 700;
            color: var(--accent);
        }


        .page-subtitle {
            margin-top: 6px;
            color: #64748B;
            font-size: 14px;
        }


        .card-custom {
            background: white;
            border:
                1px solid #E2E8F0;
            border-radius: 14px;
            padding: 25px;
            box-shadow:
                0 4px 15px
                rgba(15, 23, 42, .04);
        }


        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
        }


        .form-control,
        .form-select {
            border:
                1px solid #CBD5E1;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
        }


        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow:
                0 0 0 3px
                var(--accent-soft);
        }


        .color-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }


        .color-option {
            position: relative;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            border:
                2px solid transparent;
            transition: .2s ease;
        }


        .color-option:hover {
            transform:
                scale(1.08);
        }


        .color-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }


        .color-option.selected {
            border-color: #0F172A;
            box-shadow:
                0 0 0 2px white,
                0 0 0 4px #0F172A;
        }


        .btn-secondary-custom {
            border:
                1px solid #CBD5E1;
            background: white;
            color: #475569;
            border-radius: 8px;
            padding: 9px 15px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }


        .btn-secondary-custom:hover {
            background: #F8FAFC;
            color: #334155;
        }


        .btn-primary-custom {
            border: none;
            background: var(--accent);
            color: white;
            border-radius: 8px;
            padding: 9px 15px;
            font-size: 13px;
            font-weight: 600;
            transition: .2s ease;
        }


        .btn-primary-custom:hover {
            background: var(--accent-hover);
            color: white;
        }

    </style>

</head>


<body>


<div class="main">


    <!-- =========================
         HEADER
    ========================= -->

    <div class="page-header">

        <h1 class="page-title">
            Edit Event
        </h1>

        <p class="page-subtitle">
            Update your career event details.
        </p>

    </div>


    <!-- =========================
         CARD
    ========================= -->

    <div class="card-custom">


        <?php if (isset($error)): ?>

            <div class="alert alert-danger">

                <?= htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <form method="POST">


            <div class="row g-3">


                <!-- =========================
                     TITLE
                ========================= -->

                <div class="col-md-8">

                    <label class="form-label">
                        Event Title
                    </label>


                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $event['title']
                        ); ?>"
                        required
                    >

                </div>


                <!-- =========================
                     TYPE
                ========================= -->

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


                        <?php

                        $types = [
                            'Interview',
                            'Assessment',
                            'Technical Test',
                            'Deadline',
                            'Follow Up',
                            'Other'
                        ];

                        ?>


                        <?php foreach (
                            $types as $type
                        ): ?>

                            <option
                                value="<?= htmlspecialchars(
                                    $type
                                ); ?>"
                                <?= $event['event_type'] === $type
                                    ? 'selected'
                                    : ''; ?>
                            >

                                <?= htmlspecialchars(
                                    $type
                                ); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- =========================
                     DATE TYPE
                ========================= -->

                <div class="col-md-4">

                    <label class="form-label">
                        Date Type
                    </label>


                    <select
                        id="dateType"
                        class="form-select"
                    >

                        <option
                            value="single"
                            <?= $dateType === 'single'
                                ? 'selected'
                                : ''; ?>
                        >
                            Single Date
                        </option>


                        <option
                            value="range"
                            <?= $dateType === 'range'
                                ? 'selected'
                                : ''; ?>
                        >
                            Date Range
                        </option>

                    </select>

                </div>


                <!-- =========================
                     START DATE
                ========================= -->

                <div class="col-md-4">

                    <label class="form-label">
                        Start Date
                    </label>


                    <input
                        type="date"
                        name="event_date"
                        id="eventDate"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $event['event_date']
                        ); ?>"
                        required
                    >

                </div>


                <!-- =========================
                     END DATE
                ========================= -->

                <div
                    class="col-md-4"
                    id="endDateWrapper"
                    style="<?= $dateType === 'range'
                        ? 'display:block;'
                        : 'display:none;'; ?>"
                >

                    <label class="form-label">
                        End Date
                    </label>


                    <input
                        type="date"
                        name="end_date"
                        id="endDate"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $event['end_date'] ?? ''
                        ); ?>"
                        <?= $dateType === 'range'
                            ? 'required'
                            : ''; ?>
                    >

                </div>


                <!-- =========================
                     START TIME
                ========================= -->

                <div class="col-md-4">

                    <label class="form-label">
                        Start Time
                    </label>


                    <input
                        type="time"
                        name="start_time"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $event['start_time'] ?? ''
                        ); ?>"
                    >

                </div>


                <!-- =========================
                     END TIME
                ========================= -->

                <div class="col-md-4">

                    <label class="form-label">
                        End Time
                    </label>


                    <input
                        type="time"
                        name="end_time"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $event['end_time'] ?? ''
                        ); ?>"
                    >

                </div>


                <!-- =========================
                     COMPANY
                ========================= -->

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
                                <?= (string)$event['company_id']
                                    === (string)$company['id']
                                    ? 'selected'
                                    : ''; ?>
                            >

                                <?= htmlspecialchars(
                                    $company['nama_perusahaan']
                                ); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- =========================
                     COLOR
                ========================= -->

                <div class="col-md-12">

                    <label class="form-label">
                        Event Color
                    </label>


                    <div class="color-options">


                        <?php

                        $colors = [
                            '#2563EB',
                            '#7C3AED',
                            '#EA580C',
                            '#16A34A',
                            '#DC2626',
                            '#475569',
                            '#0F766E'
                        ];

                        ?>


                        <?php foreach (
                            $colors as $color
                        ): ?>


                            <label
                                class="color-option
                                <?= strtoupper(
                                    $event['event_color']
                                ) === strtoupper($color)
                                    ? 'selected'
                                    : ''; ?>"
                                style="
                                    background:
                                    <?= $color; ?>;
                                "
                            >


                                <input
                                    type="radio"
                                    name="event_color"
                                    value="<?= $color; ?>"
                                    <?= strtoupper(
                                        $event['event_color']
                                    ) === strtoupper($color)
                                        ? 'checked'
                                        : ''; ?>
                                >


                            </label>


                        <?php endforeach; ?>


                    </div>

                </div>


                <!-- =========================
                     NOTES
                ========================= -->

                <div class="col-md-12">

                    <label class="form-label">
                        Notes
                    </label>


                    <textarea
                        name="notes"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars(
                        $event['notes'] ?? ''
                    ); ?></textarea>

                </div>


            </div>


            <!-- =========================
                 ACTION
            ========================= -->

            <div
                class="d-flex
                       justify-content-end
                       gap-2
                       mt-4"
            >


                <a
                    href="../calendar.php"
                    class="btn-secondary-custom"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn-primary-custom"
                >
                    Save Changes
                </button>


            </div>


        </form>


    </div>


</div>


<script>


/* =========================
   COLOR PICKER
========================= */

document
    .querySelectorAll(
        '.color-option'
    )
    .forEach(
        function(option) {

            option.addEventListener(
                'click',
                function() {

                    document
                        .querySelectorAll(
                            '.color-option'
                        )
                        .forEach(
                            function(item) {

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


/* =========================
   DATE RANGE
========================= */

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


/* =========================
   TOGGLE
========================= */

dateType.addEventListener(
    'change',
    function() {


        if (this.value === 'range') {


            endDateWrapper.style.display =
                'block';


            endDate.required =
                true;


            endDate.min =
                eventDate.value;


            /*
             * Kalau belum ada
             * end date,
             * samakan dengan
             * start date.
             */

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


/* =========================
   MIN END DATE
========================= */

eventDate.addEventListener(
    'change',
    function() {


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


/* =========================
   INITIAL MIN DATE
========================= */

if (eventDate.value) {

    endDate.min =
        eventDate.value;

}

</script>


</body>

</html>