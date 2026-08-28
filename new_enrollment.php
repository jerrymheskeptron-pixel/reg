<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$message = "";
$message_type = "";

/* =========================================================
   SAVE NEW ENROLLMENT
========================================================= */

if (isset($_POST['save_enrollment'])) {

    $student_id = intval($_POST['student_id'] ?? 0);

    $school_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['school_year'] ?? '')
    );

    $semester = mysqli_real_escape_string(
        $conn,
        trim($_POST['semester'] ?? '')
    );

    $course = mysqli_real_escape_string(
        $conn,
        trim($_POST['course'] ?? '')
    );

    $year_level = mysqli_real_escape_string(
        $conn,
        trim($_POST['year_level'] ?? '')
    );

    $section = mysqli_real_escape_string(
        $conn,
        trim($_POST['section'] ?? '')
    );

    $status = mysqli_real_escape_string(
        $conn,
        trim($_POST['status'] ?? 'Regular')
    );


    /* =====================================================
       BASIC VALIDATION
    ===================================================== */

    if ($student_id <= 0) {
        die("Invalid student.");
    }

    if (
        $school_year == '' ||
        $semester == '' ||
        $course == '' ||
        $year_level == '' ||
        $section == ''
    ) {
        die("
            <h2 style='font-family:Arial;color:red;'>
                Please complete the enrollment information.
            </h2>
            <a href='javascript:history.back()'>Go Back</a>
        ");
    }


    /* =====================================================
       CHECK STUDENT
    ===================================================== */

    $student_check = mysqli_query($conn, "
        SELECT id, student_no
        FROM students
        WHERE id = '$student_id'
        LIMIT 1
    ");

    if (!$student_check) {
        die("Student Query Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($student_check) == 0) {
        die("Student not found.");
    }


    /* =====================================================
       CHECK IF ALREADY ENROLLED
    ===================================================== */

    $check_enrollment = mysqli_query($conn, "
        SELECT id
        FROM student_enrollments
        WHERE student_id = '$student_id'
        AND school_year = '$school_year'
        AND semester = '$semester'
        LIMIT 1
    ");

    if (!$check_enrollment) {
        die(
            "Enrollment Check Error: " .
            mysqli_error($conn)
        );
    }

    if (mysqli_num_rows($check_enrollment) > 0) {

        die("
            <div style='
                font-family:Arial;
                margin:50px;
            '>

                <h2 style='color:red;'>
                    Already Enrolled
                </h2>

                <p>
                    This student is already enrolled for
                    <strong>$school_year - $semester</strong>.
                </p>

                <a href='new_enrollment.php'>
                    Back to New Enrollment
                </a>

            </div>
        ");
    }


    /* =====================================================
       IRREGULAR MUST HAVE SUBJECTS
    ===================================================== */

    if (
        $status == "Irregular" &&
        (
            !isset($_POST['subjects']) ||
            !is_array($_POST['subjects']) ||
            count($_POST['subjects']) == 0
        )
    ) {

        die("
            <div style='
                font-family:Arial;
                margin:50px;
            '>

                <h2 style='color:red;'>
                    No subjects selected.
                </h2>

                <p>
                    Please select at least one subject
                    for an irregular student.
                </p>

                <a href='javascript:history.back()'>
                    Go Back
                </a>

            </div>
        ");
    }


    /* =====================================================
       START TRANSACTION
    ===================================================== */

    mysqli_begin_transaction($conn);

    try {

        /* =================================================
           CREATE ENROLLMENT
        ================================================= */

        $save_enrollment = mysqli_query($conn, "
            INSERT INTO student_enrollments
            (
                student_id,
                school_year,
                semester,
                course,
                year_level,
                section,
                status
            )
            VALUES
            (
                '$student_id',
                '$school_year',
                '$semester',
                '$course',
                '$year_level',
                '$section',
                '$status'
            )
        ");

        if (!$save_enrollment) {
            throw new Exception(
                "Enrollment Save Error: " .
                mysqli_error($conn)
            );
        }

        $enrollment_id = mysqli_insert_id($conn);


        /* =================================================
           SAVE SUBJECTS
        ================================================= */

        if (
            isset($_POST['subjects']) &&
            is_array($_POST['subjects'])
        ) {

            $selected_subjects = array_unique(
                array_map(
                    'trim',
                    $_POST['subjects']
                )
            );

            foreach ($selected_subjects as $subject_code) {

                if ($subject_code == '') {
                    continue;
                }

                $subject_code_safe =
                    mysqli_real_escape_string(
                        $conn,
                        $subject_code
                    );


                /* =========================================
                   FIND SUBJECT IN DATABASE

                   IMPORTANT:
                   NO COURSE FILTER HERE.

                   This means ALL subjects in the
                   subjects table can be searched.
                ========================================= */

                $subject_query = mysqli_query($conn, "
                    SELECT
                        id,
                        subject_code,
                        subject_name,
                        units
                    FROM subjects
                    WHERE subject_code =
                          '$subject_code_safe'
                    LIMIT 1
                ");

                if (!$subject_query) {
                    throw new Exception(
                        "Subject Query Error: " .
                        mysqli_error($conn)
                    );
                }


                if (
                    mysqli_num_rows($subject_query) > 0
                ) {

                    $subject =
                        mysqli_fetch_assoc(
                            $subject_query
                        );

                    $subject_id =
                        intval($subject['id']);

                    $subject_code_db =
                        mysqli_real_escape_string(
                            $conn,
                            trim(
                                $subject['subject_code']
                            )
                        );

                    $subject_name =
                        mysqli_real_escape_string(
                            $conn,
                            $subject['subject_name']
                        );

                    $units =
                        intval($subject['units']);


                    /* =====================================
                       SAVE TO enrollment_subjects
                    ===================================== */

                    $save_subject = mysqli_query($conn, "
                        INSERT INTO enrollment_subjects
                        (
                            enrollment_id,
                            subject_id,
                            subject_code,
                            subject_name,
                            units
                        )
                        VALUES
                        (
                            '$enrollment_id',
                            '$subject_id',
                            '$subject_code_db',
                            '$subject_name',
                            '$units'
                        )
                    ");

                    if (!$save_subject) {

                        throw new Exception(
                            "Subject Save Error: " .
                            mysqli_error($conn)
                        );
                    }


                    /* =====================================
                       ALSO SAVE TO OLD TABLE

                       This keeps your existing system
                       compatible.
                    ===================================== */

                    $check_old = mysqli_query($conn, "
                        SELECT id
                        FROM student_subjects
                        WHERE student_id = '$student_id'
                        AND subject_code =
                            '$subject_code_db'
                        AND semester = '$semester'
                        LIMIT 1
                    ");

                    if (!$check_old) {

                        throw new Exception(
                            "Old Subject Check Error: " .
                            mysqli_error($conn)
                        );
                    }


                    if (mysqli_num_rows($check_old) == 0) {

                        $save_old = mysqli_query($conn, "
                            INSERT INTO student_subjects
                            (
                                student_id,
                                subject_code,
                                subject_name,
                                units,
                                semester
                            )
                            VALUES
                            (
                                '$student_id',
                                '$subject_code_db',
                                '$subject_name',
                                '$units',
                                '$semester'
                            )
                        ");

                        if (!$save_old) {

                            throw new Exception(
                                "Old Subject Save Error: " .
                                mysqli_error($conn)
                            );
                        }
                    }
                }
            }
        }


        /* =================================================
           UPDATE CURRENT STUDENT INFORMATION

           The student remains ONE student.
           Only enrollment changes.
        ================================================= */

        mysqli_query($conn, "
            UPDATE students
            SET
                course = '$course',
                year_level = '$year_level',
                semester = '$semester',
                section = '$section',
                status = '$status'
            WHERE id = '$student_id'
        ");


        /* =================================================
           COMMIT
        ================================================= */

        mysqli_commit($conn);


        header(
            "Location: all_student_information.php?id=" .
            $student_id
        );

        exit();


    } catch (Exception $e) {

        mysqli_rollback($conn);

        die("
            <div style='
                font-family:Arial;
                margin:50px;
                max-width:700px;
            '>

                <h2 style='color:red;'>
                    Enrollment Error
                </h2>

                <p>
                    " .
                    htmlspecialchars(
                        $e->getMessage()
                    )
                    . "
                </p>

                <br>

                <a href='javascript:history.back()'>
                    Go Back
                </a>

            </div>
        ");
    }
}


/* =========================================================
   LOAD STUDENT
========================================================= */

$student = null;

if (isset($_GET['student_id'])) {

    $student_id = intval($_GET['student_id']);

    if ($student_id > 0) {

        $student_query = mysqli_query($conn, "
            SELECT *
            FROM students
            WHERE id = '$student_id'
            LIMIT 1
        ");

        if ($student_query) {

            $student =
                mysqli_fetch_assoc(
                    $student_query
                );
        }
    }
}


/* =========================================================
   SEARCH STUDENT BY NUMBER
========================================================= */

if (
    isset($_GET['student_no']) &&
    trim($_GET['student_no']) != ''
) {

    $student_no = mysqli_real_escape_string(
        $conn,
        trim($_GET['student_no'])
    );

    $student_query = mysqli_query($conn, "
        SELECT *
        FROM students
        WHERE student_no = '$student_no'
        LIMIT 1
    ");

    if ($student_query) {

        $student =
            mysqli_fetch_assoc(
                $student_query
            );
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>New Enrollment</title>

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="header">

    <i class="fa-solid fa-user-graduate"></i>
    NEW ENROLLMENT

</div>

<div class="container">


<!-- =====================================================
     SEARCH STUDENT
===================================================== -->

<div class="card">

    <h2>
        <i class="fa-solid fa-magnifying-glass"></i>
        Search Existing Student
    </h2>

    <form method="GET">

        <div class="search-student">

            <input
                type="text"
                name="student_no"
                placeholder="Enter Student Number"
                value="<?= htmlspecialchars(
                    $_GET['student_no'] ?? ''
                ) ?>"
                required
            >

            <button
                type="submit"
                class="search-btn"
            >
                <i class="fa-solid fa-search"></i>
                Search
            </button>

        </div>

    </form>

</div>


<?php if($student): ?>

<form method="POST">

<input
    type="hidden"
    name="student_id"
    value="<?= intval($student['id']) ?>"
>


<!-- =====================================================
     STUDENT INFORMATION
===================================================== -->

<div class="card">

    <h2>
        <i class="fa-solid fa-user"></i>
        Student Information
    </h2>

    <div class="student-info">

        <div class="info-box">

            <label>Student Number</label>

            <strong>
                <?= htmlspecialchars(
                    $student['student_no']
                ) ?>
            </strong>

        </div>

        <div class="info-box">

            <label>Student Name</label>

            <strong>
                <?= htmlspecialchars(
                    $student['full_name']
                ) ?>
            </strong>

        </div>

        <div class="info-box">

            <label>Current Course</label>

            <strong>
                <?= htmlspecialchars(
                    $student['course']
                ) ?>
            </strong>

        </div>

        <div class="info-box">

            <label>Current Year Level</label>

            <strong>
                <?= htmlspecialchars(
                    $student['year_level']
                ) ?>
            </strong>

        </div>

    </div>

</div>


<!-- =====================================================
     ENROLLMENT INFORMATION
===================================================== -->

<div class="card">

    <h2>
        <i class="fa-solid fa-school"></i>
        Enrollment Information
    </h2>

    <div class="enrollment-grid">


        <div class="form-group">

            <label>School Year</label>

            <select
                name="school_year"
                required
            >

                <option value="">
                    Select School Year
                </option>

                <option value="2026-2027">
                    2026-2027
                </option>

                <option value="2027-2028">
                    2027-2028
                </option>

                <option value="2028-2029">
                    2028-2029
                </option>

            </select>

        </div>


        <div class="form-group">

            <label>Semester</label>

            <select
                id="semester"
                name="semester"
                onchange="loadSubjects()"
                required
            >

                <option value="">
                    Select Semester
                </option>

                <option value="1ST SEM">
                    1ST SEM
                </option>

                <option value="2ND SEM">
                    2ND SEM
                </option>

                <option value="SUMMER">
                    SUMMER
                </option>

            </select>

        </div>


        <div class="form-group">

            <label>Course</label>

            <select
                id="course"
                name="course"
                onchange="loadSubjects()"
                required
            >

                <option value="">
                    Select Course
                </option>

                <option value="BSCA">BSCA</option>
                <option value="BSCRIM">BSCRIM</option>
                <option value="BSIT">BSIT</option>

                <option value="BSBA FM">
                    BSBA FM
                </option>

                <option value="BSBA MM">
                    BSBA MM
                </option>

                <option value="BSBA HRDM">
                    BSBA HRDM
                </option>

                <option value="BSHM">BSHM</option>
                <option value="BSED">BSED</option>
                <option value="BEED">BEED</option>

            </select>

        </div>


        <div class="form-group">

            <label>Year Level</label>

            <select
                id="year_level"
                name="year_level"
                onchange="loadSubjects()"
                required
            >

                <option value="">
                    Select Year
                </option>

                <option value="1ST YEAR">
                    1ST YEAR
                </option>

                <option value="2ND YEAR">
                    2ND YEAR
                </option>

                <option value="3RD YEAR">
                    3RD YEAR
                </option>

                <option value="4TH YEAR">
                    4TH YEAR
                </option>

            </select>

        </div>


        <div class="form-group">

            <label>Section</label>

            <input
                type="text"
                name="section"
                placeholder="Enter Section"
                required
            >

        </div>


        <div class="form-group">

            <label>Status</label>

            <select
                id="status"
                name="status"
                onchange="loadSubjects()"
                required
            >

                <option value="Regular">
                    Regular
                </option>

                <option value="Irregular">
                    Irregular
                </option>

            </select>

        </div>

    </div>

</div>


<!-- =====================================================
     SUBJECTS
===================================================== -->

<div class="card">

    <h2>
        <i class="fa-solid fa-book"></i>
        Enrollment Subjects
    </h2>

    <div class="subjects-layout">


        <!-- LEFT -->

        <div class="available-subjects">

            <div
                id="searchContainer"
                class="subject-search"
                style="display:none;"
            >

                <input
                    type="text"
                    id="searchSubject"
                    placeholder="Search ANY Subject Code..."
                    oninput="searchIrregularSubjects()"
                >

            </div>


            <table class="subject-table">

                <thead>

                    <tr>

                        <th style="width:70px;">
                            Select
                        </th>

                        <th>
                            Subject Code
                        </th>

                        <th>
                            Subject Name
                        </th>

                        <th style="width:70px;">
                            Units
                        </th>

                    </tr>

                </thead>

                <tbody id="subject_list">

                    <tr>

                        <td
                            colspan="4"
                            class="empty-message"
                        >
                            Select Course, Year Level
                            and Semester.
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        <!-- RIGHT -->

        <div class="selected-subjects">

            <div style="
                font-size:15px;
                font-weight:700;
                margin-bottom:12px;
            ">
                Selected Subjects
            </div>


            <div
                id="selectedSubjectsList"
                class="selected-list"
            >

                <div class="no-selected">
                    No subjects selected.
                </div>

            </div>


            <div class="total">

                <span>
                    TOTAL UNITS
                </span>

                <strong id="selectedTotalUnits">
                    0
                </strong>

            </div>

        </div>

    </div>

</div>


<!-- =====================================================
     HIDDEN SUBJECT INPUTS
===================================================== -->

<div id="selectedSubjectsContainer"></div>


<!-- =====================================================
     BUTTONS
===================================================== -->

<div class="buttons">

    <a
        href="dashboard.php"
        class="back-btn"
    >
        <i class="fa-solid fa-xmark"></i>
        Close
    </a>

    <button
        type="submit"
        name="save_enrollment"
        class="save-btn"
    >
        <i class="fa-solid fa-save"></i>
        Save Enrollment
    </button>

</div>

</form>


<?php else: ?>


<div class="card">

    <div class="empty-message">

        <i
            class="fa-solid fa-user-slash"
            style="font-size:35px;"
        ></i>

        <br><br>

        Search a student number to begin
        enrollment.

    </div>

</div>


<?php endif; ?>

</div>


<script>

/* =========================================================
   SELECTED SUBJECTS
========================================================= */

let selectedSubjects = {};


/* =========================================================
   LOAD REGULAR SUBJECTS
========================================================= */

function loadSubjects(){

    const course =
        document.getElementById("course").value;

    const year =
        document.getElementById("year_level").value;

    const semester =
        document.getElementById("semester").value;

    const status =
        document.getElementById("status").value;

    const subjectList =
        document.getElementById("subject_list");

    const searchContainer =
        document.getElementById("searchContainer");

    const searchInput =
        document.getElementById("searchSubject");


    /* =====================================================
       IRREGULAR
    ===================================================== */

    if(status === "Irregular"){

        searchContainer.style.display = "block";

        selectedSubjects = {};

        updateHiddenSubjects();

        renderSelectedSubjects();

        updateTotalUnits();

        searchInput.value = "";

        subjectList.innerHTML = `
            <tr>
                <td colspan="4"
                    class="empty-message">

                    Search ANY subject code
                    to add a subject.

                </td>
            </tr>
        `;

        return;
    }


    /* =====================================================
       REGULAR
    ===================================================== */

    searchContainer.style.display = "none";

    searchInput.value = "";

    selectedSubjects = {};

    updateHiddenSubjects();

    renderSelectedSubjects();

    updateTotalUnits();


    if(
        course === "" ||
        year === "" ||
        semester === ""
    ){

        subjectList.innerHTML = `
            <tr>
                <td colspan="4"
                    class="empty-message">

                    Select Course, Year Level
                    and Semester.

                </td>
            </tr>
        `;

        return;
    }


    subjectList.innerHTML = `
        <tr>
            <td colspan="4"
                class="empty-message">

                Loading subjects...

            </td>
        </tr>
    `;


    fetch(
        "load_subjects.php" +
        "?course=" +
        encodeURIComponent(course) +

        "&year=" +
        encodeURIComponent(year) +

        "&semester=" +
        encodeURIComponent(semester) +

        "&status=" +
        encodeURIComponent(status)
    )

    .then(response => {

        if(!response.ok){

            throw new Error(
                "HTTP Error: " +
                response.status
            );

        }

        return response.text();

    })

    .then(data => {

        subjectList.innerHTML = data;

        selectedSubjects = {};


        /*
         * Automatically select all regular subjects.
         */

        document
            .querySelectorAll(
                "#subject_list input.subject-checkbox"
            )
            .forEach(function(checkbox){

                checkbox.checked = true;

                const code =
                    checkbox.dataset.code;

                const name =
                    checkbox.dataset.name;

                const units =
                    parseFloat(
                        checkbox.dataset.units
                    ) || 0;


                selectedSubjects[code] = {

                    code: code,
                    name: name,
                    units: units

                };

            });


        updateHiddenSubjects();

        renderSelectedSubjects();

        updateTotalUnits();

    })

    .catch(error => {

        console.error(error);

        subjectList.innerHTML = `
            <tr>
                <td colspan="4"
                    style="
                        text-align:center;
                        color:red;
                        padding:20px;
                    ">

                    Unable to load subjects.

                </td>
            </tr>
        `;

    });

}


/* =========================================================
   SEARCH ALL SUBJECTS
========================================================= */

function searchIrregularSubjects(){

    const keyword =
        document.getElementById(
            "searchSubject"
        ).value.trim();

    const subjectList =
        document.getElementById(
            "subject_list"
        );


    if(keyword === ""){

        subjectList.innerHTML = `
            <tr>
                <td colspan="4"
                    class="empty-message">

                    Search ANY subject code
                    to add a subject.

                </td>
            </tr>
        `;

        return;
    }


    subjectList.innerHTML = `
        <tr>
            <td colspan="4"
                class="empty-message">

                Searching...

            </td>
        </tr>
    `;


    /*
     * IMPORTANT:
     *
     * search_subject.php searches the
     * entire subjects table.
     *
     * It does NOT send course/year/semester.
     */

    fetch(
        "search_subject.php?keyword=" +
        encodeURIComponent(keyword)
    )

    .then(response => {

        if(!response.ok){

            throw new Error(
                "HTTP Error " +
                response.status
            );

        }

        return response.text();

    })

    .then(data => {

        subjectList.innerHTML = data;


        /*
         * Restore selected subjects
         */

        document
            .querySelectorAll(
                "#subject_list input.subject-checkbox"
            )
            .forEach(function(checkbox){

                const code =
                    checkbox.dataset.code;

                if(selectedSubjects[code]){

                    checkbox.checked = true;

                }

            });

    })

    .catch(error => {

        console.error(error);

        subjectList.innerHTML = `
            <tr>
                <td colspan="4"
                    style="
                        text-align:center;
                        color:red;
                        padding:20px;
                    ">

                    Search error.

                </td>
            </tr>
        `;

    });

}


/* =========================================================
   TOGGLE SUBJECT
========================================================= */

function toggleSubject(checkbox){

    const code =
        checkbox.dataset.code;

    const name =
        checkbox.dataset.name;

    const units =
        parseFloat(
            checkbox.dataset.units
        ) || 0;


    if(checkbox.checked){

        selectedSubjects[code] = {

            code: code,
            name: name,
            units: units

        };

    }else{

        delete selectedSubjects[code];

    }


    updateHiddenSubjects();

    renderSelectedSubjects();

    updateTotalUnits();

}


/* =========================================================
   RENDER SELECTED SUBJECTS
========================================================= */

function renderSelectedSubjects(){

    const container =
        document.getElementById(
            "selectedSubjectsList"
        );


    container.innerHTML = "";


    const subjects =
        Object.values(selectedSubjects);


    if(subjects.length === 0){

        container.innerHTML = `
            <div class="no-selected">

                No subjects selected.

            </div>
        `;

        return;
    }


    subjects.forEach(function(subject){

        const item =
            document.createElement("div");

        item.className =
            "selected-item";


        item.innerHTML = `

            <div class="selected-code">

                ${escapeHtml(subject.code)}

            </div>

            <div class="selected-name">

                ${escapeHtml(subject.name)}

            </div>

            <div class="selected-units">

                ${subject.units}

            </div>

            <button
                type="button"
                class="delete-btn"
                onclick="deleteSelectedSubject(
                    '${subject.code.replace(
                        /'/g,
                        "\\'"
                    )}'
                )"
            >

                <i class="fa-solid fa-trash"></i>

            </button>

        `;


        container.appendChild(item);

    });

}


/* =========================================================
   DELETE SELECTED SUBJECT
========================================================= */

function deleteSelectedSubject(code){

    delete selectedSubjects[code];


    updateHiddenSubjects();

    renderSelectedSubjects();

    updateTotalUnits();


    /*
     * Uncheck on left side if visible.
     */

    document
        .querySelectorAll(
            "#subject_list .subject-checkbox"
        )
        .forEach(function(checkbox){

            if(
                checkbox.dataset.code === code
            ){

                checkbox.checked = false;

            }

        });

}


/* =========================================================
   HIDDEN SUBJECT INPUTS
========================================================= */

function updateHiddenSubjects(){

    const container =
        document.getElementById(
            "selectedSubjectsContainer"
        );


    container.innerHTML = "";


    Object.values(selectedSubjects)
        .forEach(function(subject){

            const input =
                document.createElement(
                    "input"
                );

            input.type = "hidden";

            input.name =
                "subjects[]";

            input.value =
                subject.code;


            container.appendChild(input);

        });

}


/* =========================================================
   TOTAL UNITS
========================================================= */

function updateTotalUnits(){

    const totalElement =
        document.getElementById(
            "selectedTotalUnits"
        );


    let total = 0;


    Object.values(selectedSubjects)
        .forEach(function(subject){

            total +=
                parseFloat(
                    subject.units
                ) || 0;

        });


    totalElement.textContent = total;

}


/* =========================================================
   HTML ESCAPE
========================================================= */

function escapeHtml(text){

    const div =
        document.createElement("div");

    div.textContent = text;

    return div.innerHTML;

}

</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>