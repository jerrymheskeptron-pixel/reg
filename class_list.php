<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

/* =========================
   GET FILTERS
========================= */

$school_year = $_GET['school_year'] ?? '2026-2027';
$semester    = $_GET['semester'] ?? '1ST SEM';
$course      = $_GET['course'] ?? '';
$year        = $_GET['year'] ?? '';
$section     = $_GET['section'] ?? '';
$subject_id  = $_GET['subject_id'] ?? '';
$instructor  = $_GET['instructor'] ?? '';

$students = [];
$subject = null;




/* =========================
   GET SUBJECT
========================= */

if ($subject_id != '') {

    $subject_id = (int)$subject_id;

    $subjectQuery = mysqli_query($conn, "
        SELECT *
        FROM subjects
        WHERE id = $subject_id
        LIMIT 1
    ");

    if ($subjectQuery && mysqli_num_rows($subjectQuery) > 0) {

        $subject = mysqli_fetch_assoc($subjectQuery);

    }
}


/* =========================
   GET STUDENTS
========================= */

if (
    $course != '' &&
    $year != '' &&
    $section != ''
) {

    $course_safe = mysqli_real_escape_string(
        $conn,
        trim($course)
    );

    $year_safe = mysqli_real_escape_string(
        $conn,
        trim($year)
    );

    $section_safe = mysqli_real_escape_string(
        $conn,
        trim($section)
    );

    $studentQuery = mysqli_query($conn, "

        SELECT *

        FROM students

        WHERE course = '$course_safe'

        AND year_level = '$year_safe'

        AND section = '$section_safe'

        AND (
            student_list_deleted = 0
            OR student_list_deleted IS NULL
        )

        AND status != 'Withdraw'

        ORDER BY
            last_name ASC,
            first_name ASC,
            full_name ASC

    ");

    if (!$studentQuery) {

        die(
            'Student Query Error: ' .
            mysqli_error($conn)
        );

    }

    while ($row = mysqli_fetch_assoc($studentQuery)) {

        $students[] = $row;

    }




    while ($row = mysqli_fetch_assoc($studentQuery)) {

        $students[] = $row;

    }

}

$totalStudents = count($students);


/* =========================
   GET ALL SUBJECTS
   ========================= */

$subjects = [];

$subjectList = mysqli_query($conn, "
    SELECT id, subject_code, subject_name, units
    FROM subjects
    ORDER BY subject_code ASC
");

if (!$subjectList) {
    die("Subject Query Error: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($subjectList)) {
    $subjects[] = $row;
}

/* =========================
   HELPER
========================= */

function h($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Official Class List</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>


<!-- =========================
     CONTROL PANEL
========================= -->

<div class="control-panel">


    <!-- HEADER -->

    <div class="control-header">

        <div class="control-header-left">

            <div class="header-icon">
                <i class="fas fa-list"></i>
            </div>

            <div>

                <div class="control-title">
                    Class List
                </div>

                <div class="control-subtitle">
                    Generate and print the official class list for any subject.
                </div>

            </div>

        </div>


        <div class="header-right-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>

    </div>


    <!-- FORM -->

    <form method="GET">

        <div class="control-form">


            <!-- FILTERS -->

            <div class="filters">


                <!-- SCHOOL YEAR -->

                <div class="filter-group">

                    <label>
                        <i class="fas fa-calendar"></i>
                        School Year
                    </label>

                    <select
                        name="school_year"
                        onchange="resetSubjectAndSubmit(this.form)"
                    >

                        <option value="2026-2027"
                            <?= $school_year == '2026-2027' ? 'selected' : '' ?>>
                            2026-2027
                        </option>

                        <option value="2027-2028"
                            <?= $school_year == '2027-2028' ? 'selected' : '' ?>>
                            2027-2028
                        </option>

                    </select>

                </div>


                <!-- SEMESTER -->

                <div class="filter-group">

                    <label>
                        <i class="fas fa-calendar-days"></i>
                        Semester
                    </label>

                    <select
                        name="semester"
                        onchange="resetSubjectAndSubmit(this.form)"
                    >

                        <option value="1ST SEM"
                            <?= $semester == '1ST SEM' ? 'selected' : '' ?>>
                            1ST SEM
                        </option>

                        <option value="2ND SEM"
                            <?= $semester == '2ND SEM' ? 'selected' : '' ?>>
                            2ND SEM
                        </option>

                    </select>

                </div>


                <!-- COURSE -->

                <div class="filter-group">

                    <label>
                        <i class="fas fa-briefcase"></i>
                        Course
                    </label>

                    <select
                        name="course"
                        onchange="resetSubjectAndSubmit(this.form)"
                    >

                        <option value="">
                            Select Course
                        </option>

                        <option value="BSCA"
                            <?= $course == 'BSCA' ? 'selected' : '' ?>>
                            BSCA
                        </option>

                        <option value="BSCRIM"
                            <?= $course == 'BSCRIM' ? 'selected' : '' ?>>
                            BSCRIM
                        </option>

                        <option value="BSIT"
                            <?= $course == 'BSIT' ? 'selected' : '' ?>>
                            BSIT
                        </option>

                        <option value="BSBA FM"
                            <?= $course == 'BSBA FM' ? 'selected' : '' ?>>
                            BSBA FM
                        </option>

                        <option value="BSBA MM"
                            <?= $course == 'BSBA MM' ? 'selected' : '' ?>>
                            BSBA MM
                        </option>

                        <option value="BSBA HRDM"
                            <?= $course == 'BSBA HRDM' ? 'selected' : '' ?>>
                            BSBA HRDM
                        </option>

                        <option value="BSHM"
                            <?= $course == 'BSHM' ? 'selected' : '' ?>>
                            BSHM
                        </option>

                        <option value="BSED"
                            <?= $course == 'BSED' ? 'selected' : '' ?>>
                            BSED
                        </option>

                        <option value="BEED"
                            <?= $course == 'BEED' ? 'selected' : '' ?>>
                            BEED
                        </option>

                    </select>

                </div>


                <!-- YEAR LEVEL -->

                <div class="filter-group">

                    <label>
                        <i class="fas fa-chart-simple"></i>
                        Year Level
                    </label>

                    <select
                        name="year"
                        onchange="resetSubjectAndSubmit(this.form)"
                    >

                        <option value="">
                            Select Year
                        </option>

                        <option value="1ST YEAR"
                            <?= $year == '1ST YEAR' ? 'selected' : '' ?>>
                            1ST YEAR
                        </option>

                        <option value="2ND YEAR"
                            <?= $year == '2ND YEAR' ? 'selected' : '' ?>>
                            2ND YEAR
                        </option>

                        <option value="3RD YEAR"
                            <?= $year == '3RD YEAR' ? 'selected' : '' ?>>
                            3RD YEAR
                        </option>

                        <option value="4TH YEAR"
                            <?= $year == '4TH YEAR' ? 'selected' : '' ?>>
                            4TH YEAR
                        </option>

                    </select>

                </div>


                <!-- SECTION -->

                <div class="filter-group section-filter">

                    <label>
                        <i class="fas fa-users"></i>
                        Section
                    </label>

                    <select
                        name="section"
                        onchange="resetSubjectAndSubmit(this.form)"
                    >

                        <option value="">
                            Select Section
                        </option>

                        <option value="A"
                            <?= $section == 'A' ? 'selected' : '' ?>>
                            A
                        </option>

                        <option value="B"
                            <?= $section == 'B' ? 'selected' : '' ?>>
                            B
                        </option>

                        <option value="C"
                            <?= $section == 'C' ? 'selected' : '' ?>>
                            C
                        </option>

                        <option value="D"
                            <?= $section == 'D' ? 'selected' : '' ?>>
                            D
                        </option>

                        <option value="E"
                            <?= $section == 'E' ? 'selected' : '' ?>>
                            E
                        </option>

                        <option value="F"
                            <?= $section == 'F' ? 'selected' : '' ?>>
                            F
                        </option>

                        <option value="G"
                            <?= $section == 'G' ? 'selected' : '' ?>>
                            G
                        </option>

                    </select>

                </div>

            </div>


            <!-- SUBJECT SEARCH -->

            <div class="subject-group">

                <div class="filter-group">

                    <label>
                        <i class="fas fa-search"></i>
                        Search Subject
                    </label>

                    <input
                        type="text"
                        id="subjectSearch"
                        list="subjectList"
                        placeholder="Search subject code or subject name..."
                        autocomplete="off"
                        value="<?= $subject ? h($subject['subject_code'].' - '.$subject['subject_name']) : '' ?>"
                    >

                    <datalist id="subjectList">

                        <?php foreach($subjects as $sub){ ?>

                            <option
                                value="<?= h($sub['subject_code'].' - '.$sub['subject_name']) ?>"
                                data-id="<?= (int)$sub['id'] ?>">
                            </option>

                        <?php } ?>

                    </datalist>


                    <input
                        type="hidden"
                        name="subject_id"
                        id="subject_id"
                        value="<?= (int)$subject_id ?>"
                    >

                </div>

            </div>


            <!-- INSTRUCTOR -->

            <div class="instructor-group">

                <label>
                    <i class="fas fa-user"></i>
                    Instructor
                </label>

                <input
                    type="text"
                    name="instructor"
                    value="<?= h($_GET['instructor'] ?? '') ?>"
                    placeholder="Enter instructor name..."
                    autocomplete="off"
                >

            </div>

        </div>


        <!-- BUTTONS -->

        <div class="buttons">

            <button
                type="submit"
                class="btn btn-blue"
            >

                <i class="fas fa-magnifying-glass"></i>

                Generate Class List

            </button>


            <button
                type="button"
                class="btn btn-green"
                onclick="window.print()"
            >

                <i class="fas fa-print"></i>

                Print Class List

            </button>


            <a
                href="dashboard.php"
                class="btn btn-gray"
            >

                <i class="fas fa-arrow-left"></i>

                Dashboard

            </a>

        </div>

    </form>

</div>



<!-- =========================
     OFFICIAL CLASS LIST
========================= -->

<div class="paper">


    <!-- HEADER -->

    <div class="school-header">

        <img src="logo.png"
             class="school-logo">

        <div class="school-info">

            <div class="school-name">
                SOUTHWESTERN INSTITUTE OF BUSINESS AND TECHNOLOGY, INC.
            </div>

            <div class="school-address">
                NAUTICAL HIGHWAY, PINAMALAYAN, ORIENTAL MINDORO
            </div>

            <div class="school-contact">
                Contact Nos: +63917-127-8500 | +63912-448-6518
            </div>

        </div>

    </div>


    <!-- TITLE -->

    <div class="class-title">

        <h2>
            OFFICIAL CLASS LIST
        </h2>

       <p>
    <?= h($semester) ?> - SCHOOL YEAR <?= h($school_year) ?>
</p>

    </div>


    <!-- SUBJECT INFORMATION -->

    <div class="subject-info">

        <div class="subject-row">

            <div class="subject-label">
                Subject Code:
            </div>

            <div class="subject-value">

                <?= $subject ? h($subject['subject_code']) : '—' ?>

            </div>

        </div>


        <div class="subject-row">

            <div class="subject-label">
                Description:
            </div>

            <div class="subject-value">

                <?= $subject ? h($subject['subject_name']) : '—' ?>

            </div>

        </div>


        <div class="subject-row">

            <div class="subject-label">
                Year/Course:
            </div>

            <div class="subject-value">

               <?= h($course) ?>
<?= h($year) ?>
<?= $section ? ' - BLOCK ' . h($section) : '' ?>
            </div>

        </div>

            <div class="subject-row"> 

            <div class="subject-label"> 
                Instructor: 
            </div> 

            <div class="subject-value"> 
                <?= $instructor ? h($instructor) : '—' ?> 
            </div> 

        </div>

    </div>
<div class="total">
        TOTAL STUDENTS: <?= $totalStudents ?>
    </div>
<!-- =========================
     STUDENT LIST
========================= -->

<?php if ($totalStudents > 0) { ?>

    <table class="student-table">

        <thead>
            <tr>
                <th class="number">No.</th>

                <th class="name">
                    NAME
                </th>

                <th class="student-number">
                    STUDENT NUMBER
                </th>

                <th class="status">
                    STATUS
                </th>
            </tr>
        </thead>

        <tbody>

        <?php
        $counter = 1;

        foreach ($students as $student) {
        ?>

            <tr>

                <td class="number">
                    <?= $counter++ ?>
                </td>

                <td class="name">
                    <?= h($student['full_name']) ?>
                </td>

                <td class="student-number">
                    <?= h($student['student_no']) ?>
                </td>

                <td class="status">
                    <?= strtolower(h($student['status'])) ?>
                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table>

    

<?php } elseif ($course && $year && $section) { ?>

    <div class="no-data">
        No students found for this class.
    </div>

<?php } else { ?>

    <div class="no-data">
        Select Semester, Course, Year, Section and Subject
        to generate the Official Class List.
    </div>

<?php } ?>


<!-- =========================
     SIGNATURES
========================= -->

<div class="signatures">

    <div>
        <div>Prepared by:</div>

        <div class="signature-line"></div>

        <div class="signature-name">
            JERRYMHE L. VILLALVA
        </div>

        <div class="signature-position">
            Registrar Staff
        </div>
    </div>


    <div>
        <div>Checked by:</div>

        <div class="signature-line"></div>

        <div class="signature-name">
            DANICA CLAIRE P. LAT, LPT., CRS
        </div>

        <div class="signature-position">
            Assistant Registrar
        </div>
    </div>


    <div>
        <div>Note by:</div>

        <div class="signature-line"></div>

        <div class="signature-name">
            JOHN NIELSEN M. DAYANGHIRANG, MBA
        </div>

        <div class="signature-position">
            College Registrar
        </div>
    </div>

</div>

<!-- DITO LANG DAPAT ANG CLOSING NG PAPER -->
</div>
<script>
const subjectSearch = document.getElementById('subjectSearch');
const subjectId = document.getElementById('subject_id');
const subjectForm = document.querySelector('form');

/* =========================
   FIND SUBJECT ID
========================= */

function findSubject() {

    const value = subjectSearch.value.trim();

    const options =
        document.querySelectorAll('#subjectList option');

    let found = false;

    options.forEach(function(option) {

        if (
            option.value.toLowerCase() ===
            value.toLowerCase()
        ) {

            subjectId.value = option.dataset.id;
            found = true;

        }

    });

    if (!found) {
        subjectId.value = '';
    }

}


/* =========================
   SEARCH WHILE TYPING
========================= */

subjectSearch.addEventListener('input', function() {
    findSubject();
});


/* =========================
   WHEN SUBJECT IS SELECTED
========================= */

subjectSearch.addEventListener('change', function() {
    findSubject();
});


/* =========================
   BEFORE GENERATING
========================= */

subjectForm.addEventListener('submit', function(e) {

    findSubject();

    if (subjectSearch.value.trim() !== '' &&
        subjectId.value === '') {

        e.preventDefault();

        alert(
            'Please select a valid subject from the list.'
        );

        subjectSearch.focus();

    }

});


/* =========================
   RESET SUBJECT
========================= */

function resetSubjectAndSubmit(form) {

    subjectSearch.value = '';
    subjectId.value = '';

    form.submit();

}
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>