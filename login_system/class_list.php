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

<style>

/* =========================
   GENERAL
========================= */

*{
    box-sizing:border-box;
}

body{
    margin:0;
    min-height:100vh;
    padding:35px;
    background:
        linear-gradient(
            135deg,
            #edf4ff 0%,
            #e7eef8 50%,
            #dfe8f5 100%
        );
    font-family:Arial, Helvetica, sans-serif;
    color:#1e293b;
}


/* =========================
   CONTROL PANEL
========================= */

.control-panel{
    max-width:1000px;
    margin:0 auto 20px;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,.12);
}


/* =========================
   CONTROL HEADER
========================= */

.control-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:30px 50px;
    border-bottom:2px solid #dbeafe;
    background:
        linear-gradient(
            135deg,
            #ffffff,
            #f5f9ff
        );
}

.control-header-left{
    display:flex;
    align-items:center;
    gap:22px;
}

.header-icon{
    width:72px;
    height:72px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:18px;
    background:#eaf2ff;
    color:#2563eb;
    font-size:32px;
}

.control-title{
    font-size:42px;
    font-weight:800;
    color:#1d4ed8;
    margin:0;
    letter-spacing:-1px;
}

.control-subtitle{
    margin-top:6px;
    font-size:17px;
    color:#64748b;
}

.header-right-icon{
    width:78px;
    height:78px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    background:#eef5ff;
    color:#2563eb;
    font-size:34px;
}


/* =========================
   FORM AREA
========================= */

.control-form{
    padding:35px 50px 0;
}


/* =========================
   FILTERS
========================= */

.filters{
    display:grid;
    grid-template-columns:
        repeat(4, minmax(180px, 1fr));
    gap:26px 38px;
}

.filter-group{
    display:flex;
    flex-direction:column;
}

.filter-group label,
.instructor-group label{
    display:flex;
    align-items:center;
    gap:9px;
    font-size:18px;
    font-weight:700;
    color:#334155;
    margin-bottom:10px;
}

.filter-group label i,
.instructor-group label i{
    color:#2563eb;
    font-size:18px;
}


/* =========================
   INPUTS & SELECT
========================= */

.filter-group select,
.filter-group input,
.instructor-group input,
#subjectSearch{
    width:100%;
    height:76px;
    padding:0 22px;
    border:1px solid #cbd5e1;
    border-radius:12px;
    background:#fff;
    color:#1e293b;
    font-size:17px;
    outline:none;
    transition:.25s ease;
}

.filter-group select{
    cursor:pointer;
}

.filter-group select:focus,
.filter-group input:focus,
.instructor-group input:focus,
#subjectSearch:focus{
    border-color:#2563eb;
    box-shadow:
        0 0 0 4px rgba(37,99,235,.12);
}


/* =========================
   SECTION
========================= */

.section-filter{
    grid-column:1 / 2;
}


/* =========================
   SUBJECT
========================= */

.subject-group{
    margin-top:30px;
}

.subject-group .filter-group{
    width:100%;
}


/* =========================
   INSTRUCTOR
========================= */

.instructor-group{
    margin-top:26px;
}

.instructor-group input{
    width:100%;
}


/* =========================
   BUTTON AREA
========================= */

.buttons{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:24px;
    margin-top:35px;
    padding:30px 50px 40px;
    border-top:1px solid #dbeafe;
    background:#f8fbff;
}

.btn{
    height:82px;
    border:none;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:13px;
    color:white;
    text-decoration:none;
    cursor:pointer;
    font-weight:700;
    font-size:22px;
    transition:.25s ease;
    box-shadow:
        0 7px 18px rgba(15,23,42,.12);
}

.btn i{
    font-size:24px;
}

.btn:hover{
    transform:translateY(-3px);
    box-shadow:
        0 12px 25px rgba(15,23,42,.18);
}

.btn-blue{
    background:
        linear-gradient(
            135deg,
            #2563eb,
            #174bb8
        );
}

.btn-green{
    background:
        linear-gradient(
            135deg,
            #16a34a,
            #087f3d
        );
}

.btn-gray{
    background:
        linear-gradient(
            135deg,
            #475569,
            #293548
        );
}


/* =========================
   PAPER
========================= */

.paper{
    width:8.5in;
    min-height:14in;
    margin:0 auto;
    background:#fff;
    padding:0.55in 0.6in;
    box-shadow:0 10px 35px rgba(0,0,0,.15);
    display:flex;
    flex-direction:column;
}


/* =========================
   RESPONSIVE
========================= */

@media(max-width:1000px){

    body{
        padding:20px;
    }

    .filters{
        grid-template-columns:
            repeat(2, 1fr);
    }

    .control-header{
        padding:25px;
    }

    .control-form{
        padding:25px 25px 0;
    }

    .buttons{
        padding:25px;
    }

    .control-title{
        font-size:32px;
    }

}

@media(max-width:650px){

    .filters{
        grid-template-columns:1fr;
    }

    .section-filter{
        grid-column:auto;
    }

    .buttons{
        grid-template-columns:1fr;
    }

    .control-header{
        flex-direction:column;
        align-items:flex-start;
        gap:20px;
    }

    .header-right-icon{
        display:none;
    }

    .paper{
        width:100%;
        padding:25px;
    }

}


/* =========================
   HEADER
========================= */

.school-header{
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    min-height:75px;
}

.school-logo{
    position:absolute;
    left:0;
    width:65px;
    height:65px;
    object-fit:contain;
}

.school-info{
    text-align:center;
    line-height:1.25;
}

.school-name{
    font-size:11px;
    font-weight:bold;
}

.school-address{
    font-size:9px;
}

.school-contact{
    font-size:8px;
    font-style:italic;
}


/* =========================
   CLASS LIST TITLE
========================= */

.class-title{
    text-align:center;
    margin-top:15px;
}

.class-title h2{
    font-size:12px;
    margin:0;
}

.class-title p{
    font-size:10px;
    font-weight:bold;
    margin:3px 0;
}


/* =========================
   SUBJECT INFO
========================= */

.subject-info{
    margin-top:12px;
    font-size:11px;
}

.subject-row{
    display:flex;
    margin-bottom:4px;
    line-height:1.2;
}

.subject-label{
    width:100px;
    font-size:11px;
    font-weight:bold;
}

.subject-value{
    font-size:11px;
    font-weight:bold;
}

/* =========================
   STUDENT TABLE
========================= */

.student-table{
    width:100%;
    border-collapse:collapse;
    margin-top:12px;
    font-size:10px;
}

.student-table th{
    text-align:left;
    font-weight:bold;
    border-bottom:1px solid #000;
    padding:5px 3px;
    font-size:10px;
}

.student-table td{
    padding:4px 3px;
    vertical-align:top;
    font-size:10px;
}

.student-table .number{
    width:20px;
    text-align:right;
    padding-right:7px;
}

.student-table .name{
    width:55%;
}

.student-table .student-number{
    width:25%;
}

.student-table .status{
    width:20%;
    text-transform:lowercase;
}


/* =========================
   TOTAL
========================= */

.total{
    margin-top:5px;
    text-align:left;
    font-size:11px;
    font-weight:bold;
}


/* =========================
   SIGNATURES
========================= */

.signatures{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:25px;

    margin-top:auto;
    padding-top:30px;

    text-align:center;
    font-size:10px;
}

.signature-line{
    border-bottom:1px solid #000;
    height:30px;
    margin:0 8px;
}

.signature-name{
    font-size:10px;
    font-weight:bold;
    margin-top:5px;
}

.signature-position{
    font-size:9px;
    margin-top:2px;
}


/* =========================
   NO DATA
========================= */

.no-data{
    text-align:center;
    padding:30px;
    font-size:14px;
    color:#64748b;
}


/* =========================
   PRINT
========================= */

@media print{

    @page{
        size: Legal portrait;
        margin:0;
    }

    html,
    body{
        width:8.5in;
        height:14in;
        margin:0;
        padding:0;
        background:white;
    }

    .control-panel{
        display:none !important;
    }

    .paper{
        width:8.5in;
        height:14in;
        min-height:14in;
        margin:0;
        padding:0.55in 0.6in;
        box-shadow:none;
        overflow:hidden;
    }

    .student-table{
        width:100%;
    }

}


/* =========================
   RESPONSIVE
========================= */

@media(max-width:800px){

    .filters{
        grid-template-columns:1fr 1fr;
    }

    .paper{
        width:100%;
    }

}
#subjectSearch{
    width:100%;
    padding:11px 12px;
    border:1px solid #ccc;
    border-radius:6px;
    background:white;
    font-size:14px;
    outline:none;
}

#subjectSearch:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 2px rgba(37,99,235,.15);
}

#subjectSearch::placeholder{
    color:#94a3b8;
}
.instructor-group{
    margin-top:15px;
}

.instructor-group label{
    display:block;
    font-weight:bold;
    font-size:13px;
    margin-bottom:5px;
}

.instructor-group input{
    width:100%;
    padding:11px 12px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
    outline:none;
}

.instructor-group input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 2px rgba(37,99,235,.15);
}
.total-students-row{
    margin-top:6px;
}

.total-students-row .subject-value{
    font-weight:bold;
}
/* =========================
   EXTRA COMPACT CLASS LIST
========================= */

body{
    padding:15px;
}

.control-panel{
    max-width:900px;
    margin:0 auto 15px;
    border-radius:10px;
}

.control-header{
    padding:12px 18px;
}

.control-header-left{
    gap:10px;
}

.header-icon{
    width:36px;
    height:36px;
    border-radius:8px;
    font-size:15px;
}

.header-right-icon{
    width:36px;
    height:36px;
    font-size:15px;
}

.control-title{
    font-size:20px;
}

.control-subtitle{
    font-size:11px;
    margin-top:2px;
}

.control-form{
    padding:15px 18px 0;
}

.filters{
    grid-template-columns:repeat(5, 1fr);
    gap:10px;
}

.filter-group label,
.instructor-group label{
    font-size:11px;
    margin-bottom:4px;
}

.filter-group select,
.filter-group input,
.instructor-group input,
#subjectSearch{
    height:36px;
    padding:0 9px;
    font-size:12px;
    border-radius:6px;
}

.subject-group{
    margin-top:10px;
}

.instructor-group{
    margin-top:10px;
}

.buttons{
    margin-top:15px;
    padding:12px 18px;
    gap:8px;
}

.btn{
    height:36px;
    padding:0 14px;
    font-size:12px;
    border-radius:6px;
}

.btn i{
    font-size:12px;
}
</style>

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
</body>
</html>