<?php
session_start();
include "db.php";

if(isset($_POST['save'])){

    /* ==========================================
       BASIC STUDENT INFORMATION
    ========================================== */

    $student_no  = mysqli_real_escape_string($conn, trim($_POST['student_no']));
    $last_name   = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $first_name  = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $middle_name = mysqli_real_escape_string($conn, trim($_POST['middle_name']));
    $ext_name    = mysqli_real_escape_string($conn, trim($_POST['ext_name']));

    $birthday = mysqli_real_escape_string($conn, $_POST['birthday'] ?? '');
    $gender   = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');
    $status   = mysqli_real_escape_string($conn, $_POST['status'] ?? 'Regular');

    /* ==========================================
       ENROLLMENT INFORMATION
    ========================================== */

    $school_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['school_year'] ?? '2026-2027')
    );

    $course = mysqli_real_escape_string(
        $conn,
        trim($_POST['course'])
    );

    $year_level = mysqli_real_escape_string(
        $conn,
        trim($_POST['year_level'])
    );

    $section = mysqli_real_escape_string(
        $conn,
        trim($_POST['section'])
    );

    $semester = mysqli_real_escape_string(
        $conn,
        trim($_POST['semester'])
    );

    /* ==========================================
       CONTACT DETAILS
    ========================================== */

    $mobile = mysqli_real_escape_string(
        $conn,
        trim($_POST['mobile'])
    );

    $email = mysqli_real_escape_string(
        $conn,
        trim($_POST['email'])
    );

    $address = mysqli_real_escape_string(
        $conn,
        trim($_POST['address'])
    );

    $guardian_name = mysqli_real_escape_string(
        $conn,
        trim($_POST['guardian_name'])
    );

    $guardian_contact = mysqli_real_escape_string(
        $conn,
        trim($_POST['guardian_contact'])
    );

    /* ==========================================
       EDUCATIONAL BACKGROUND
    ========================================== */

    $grade13_school = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade13_school'])
    );

    $grade13_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade13_year'])
    );

    $grade46_school = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade46_school'])
    );

    $grade46_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade46_year'])
    );

    $grade7_school = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade7_school'])
    );

    $grade7_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade7_year'])
    );

    $grade8_school = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade8_school'])
    );

    $grade8_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade8_year'])
    );

    $grade9_school = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade9_school'])
    );

    $grade9_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade9_year'])
    );

    $grade10_school = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade10_school'])
    );

    $grade10_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade10_year'])
    );

    $grade11_school = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade11_school'])
    );

    $grade11_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade11_year'])
    );

    $grade12_school = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade12_school'])
    );

    $grade12_year = mysqli_real_escape_string(
        $conn,
        trim($_POST['grade12_year'])
    );

    $last_school_attended = mysqli_real_escape_string(
        $conn,
        trim($_POST['last_school_attended'])
    );

    /* ==========================================
       FULL NAME
    ========================================== */

    $full_name = $last_name . ", " . $first_name;

    if($middle_name != ""){
        $full_name .= " " . $middle_name;
    }

    if($ext_name != ""){
        $full_name .= " " . $ext_name;
    }

    /* ==========================================
       CHECK IRREGULAR SUBJECTS
    ========================================== */

    if(
        $status == "Irregular" &&
        (!isset($_POST['subjects']) || empty($_POST['subjects']))
    ){

        die("
            <div style='
                font-family:Arial;
                margin:50px;
            '>

                <h2 style='color:red;'>
                    No subjects were selected.
                </h2>

                <p>
                    Please check at least one subject
                    before registering the student.
                </p>

                <a href='javascript:history.back()'>
                    Go Back
                </a>

            </div>
        ");
    }

    /* ==========================================
       START TRANSACTION
    ========================================== */

    mysqli_begin_transaction($conn);

    try {

        /* ==========================================
           CHECK IF STUDENT NUMBER ALREADY EXISTS
        ========================================== */

        $check_student = mysqli_query($conn, "
            SELECT id
            FROM students
            WHERE student_no = '$student_no'
            LIMIT 1
        ");

        if(!$check_student){
            throw new Exception(
                "Student Check Error: " .
                mysqli_error($conn)
            );
        }

        if(mysqli_num_rows($check_student) > 0){

            /*
             * Student already exists.
             *
             * We DO NOT create another student.
             * This is important for 2ND SEM enrollment.
             */

            $existing_student = mysqli_fetch_assoc(
                $check_student
            );

            $id = intval($existing_student['id']);

        }else{

            /* ==========================================
               NEW STUDENT
            ========================================== */

            $query = mysqli_query($conn, "
                INSERT INTO students(
                    student_no,
                    last_name,
                    first_name,
                    middle_name,
                    ext_name,
                    birthday,
                    gender,
                    full_name,
                    course,
                    year_level,
                    semester,
                    section,
                    mobile,
                    email,
                    address,
                    guardian_name,
                    guardian_contact,
                    grade13_school,
                    grade13_year,
                    grade46_school,
                    grade46_year,
                    grade7_school,
                    grade7_year,
                    grade8_school,
                    grade8_year,
                    grade9_school,
                    grade9_year,
                    grade10_school,
                    grade10_year,
                    grade11_school,
                    grade11_year,
                    grade12_school,
                    grade12_year,
                    last_school_attended,
                    status
                )
                VALUES (
                    '$student_no',
                    '$last_name',
                    '$first_name',
                    '$middle_name',
                    '$ext_name',
                    '$birthday',
                    '$gender',
                    '$full_name',
                    '$course',
                    '$year_level',
                    '$semester',
                    '$section',
                    '$mobile',
                    '$email',
                    '$address',
                    '$guardian_name',
                    '$guardian_contact',
                    '$grade13_school',
                    '$grade13_year',
                    '$grade46_school',
                    '$grade46_year',
                    '$grade7_school',
                    '$grade7_year',
                    '$grade8_school',
                    '$grade8_year',
                    '$grade9_school',
                    '$grade9_year',
                    '$grade10_school',
                    '$grade10_year',
                    '$grade11_school',
                    '$grade11_year',
                    '$grade12_school',
                    '$grade12_year',
                    '$last_school_attended',
                    '$status'
                )
            ");

            if(!$query){

                throw new Exception(
                    "Student Save Error: " .
                    mysqli_error($conn)
                );
            }

            $id = mysqli_insert_id($conn);
        }

        /* ==========================================
           CHECK EXISTING ENROLLMENT
        ========================================== */

        $check_enrollment = mysqli_query($conn, "
            SELECT id
            FROM student_enrollments
            WHERE student_id = '$id'
            AND school_year = '$school_year'
            AND semester = '$semester'
            LIMIT 1
        ");

        if(!$check_enrollment){

            throw new Exception(
                "Enrollment Check Error: " .
                mysqli_error($conn)
            );
        }

        if(mysqli_num_rows($check_enrollment) > 0){

            throw new Exception(
                "This student is already enrolled for "
                . $school_year
                . " / "
                . $semester
                . "."
            );
        }

        /* ==========================================
           CREATE ENROLLMENT
        ========================================== */

        $enrollment_query = mysqli_query($conn, "
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
                '$id',
                '$school_year',
                '$semester',
                '$course',
                '$year_level',
                '$section',
                '$status'
            )
        ");

        if(!$enrollment_query){

            throw new Exception(
                "Enrollment Save Error: " .
                mysqli_error($conn)
            );
        }

        $enrollment_id = mysqli_insert_id($conn);

        /* ==========================================
           SAVE ENROLLMENT SUBJECTS
        ========================================== */

        if(
            isset($_POST['subjects']) &&
            is_array($_POST['subjects'])
        ){

            $selected_subjects = array_unique(
                array_map(
                    'trim',
                    $_POST['subjects']
                )
            );

            foreach($selected_subjects as $subject_code){

                if($subject_code == ""){
                    continue;
                }

                $subject_code_safe =
                    mysqli_real_escape_string(
                        $conn,
                        $subject_code
                    );

                /* GET SUBJECT */

                $subject_query = mysqli_query($conn, "
                    SELECT
                        id,
                        subject_code,
                        subject_name,
                        units
                    FROM subjects
                    WHERE subject_code = '$subject_code_safe'
                    LIMIT 1
                ");

                if(!$subject_query){

                    throw new Exception(
                        "Subject Query Error: " .
                        mysqli_error($conn)
                    );
                }

                if(mysqli_num_rows($subject_query) > 0){

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

                    /* ==================================
                       SAVE TO ENROLLMENT_SUBJECTS
                    ================================== */

                    $save_enrollment_subject =
                        mysqli_query($conn, "
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

                    if(!$save_enrollment_subject){

                        throw new Exception(
                            "Enrollment Subject Save Error: " .
                            mysqli_error($conn)
                        );
                    }

                    /* ==================================
                       ALSO SAVE TO OLD TABLE
                       FOR COMPATIBILITY
                    ================================== */

                    $check_old_subject = mysqli_query($conn, "
                        SELECT id
                        FROM student_subjects
                        WHERE student_id = '$id'
                        AND subject_code = '$subject_code_db'
                        AND semester = '$semester'
                        LIMIT 1
                    ");

                    if(!$check_old_subject){

                        throw new Exception(
                            "Old Subject Check Error: " .
                            mysqli_error($conn)
                        );
                    }

                    if(mysqli_num_rows($check_old_subject) == 0){

                        $save_old_subject = mysqli_query($conn, "
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
                                '$id',
                                '$subject_code_db',
                                '$subject_name',
                                '$units',
                                '$semester'
                            )
                        ");

                        if(!$save_old_subject){

                            throw new Exception(
                                "Old Subject Save Error: " .
                                mysqli_error($conn)
                            );
                        }
                    }
                }
            }
        }

        /* ==========================================
           COMMIT EVERYTHING
        ========================================== */

        mysqli_commit($conn);

        header(
            "Location: all_student_information.php?id=" .
            $id
        );

        exit();

    } catch(Exception $e){

        /* ==========================================
           ROLLBACK IF ANYTHING FAILS
        ========================================== */

        mysqli_rollback($conn);

        die("
            <div style='
                font-family:Arial;
                margin:50px;
                max-width:700px;
            '>

                <h2 style='color:red;'>
                    Registration Error
                </h2>

                <p>
                    " . htmlspecialchars(
                        $e->getMessage()
                    ) . "
                </p>

                <br>

                <a href='javascript:history.back()'>
                    Go Back
                </a>

            </div>
        ");
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Admit Student</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- FONT AWESOME -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="header">

STUDENT INFORMATION SYSTEM

</div>

<div class="container">

<form method="POST" enctype="multipart/form-data">
<div id="selectedSubjectsContainer"></div>
<div class="form-container">
    <!-- STUDENT INFORMATION -->

<div class="card">

<h2>Student Information</h2>


<div class="form-group">
<label>Student Number</label>
<input type="text" name="student_no" placeholder="Enter Student Number" required>
</div>


<div class="two-col">

<div class="form-group">
<label>Last Name</label>
<input type="text" name="last_name" placeholder="Last Name" required>
</div>


<div class="form-group">
<label>First Name</label>
<input type="text" name="first_name" placeholder="First Name" required>
</div>

</div>



<div class="two-col">

<div class="form-group">
<label>Middle Name</label>
<input type="text" name="middle_name" placeholder="Middle Name">
</div>


<div class="form-group">
<label>Extension</label>
<input type="text" name="ext_name" placeholder="Jr., Sr., III">
</div>

</div>



<div class="two-col">

<div class="form-group">
<label>Birthday</label>
<input type="date" name="birthday">
</div>


<div class="form-group">
<label>Gender</label>

<select name="gender">

<option value="">Select Gender</option>
<option>Male</option>
<option>Female</option>

</select>

</div>
</div>

<div class="form-group">

<label>Student Status</label>

<div style="display:flex; gap:25px; margin-top:8px;">

<input type="radio"
       name="status"
       value="Regular"
       checked
       onchange="loadSubjects()">

Regular

<input type="radio"
       name="status"
       value="Irregular"
       onchange="loadSubjects()">

Irregular

</div>

</div>


<div class="form-group">

<label>Course</label>

<select id="course" name="course" required onchange="loadSubjects()">

    <option value="">Select Course</option>

    <option value="BSCA">BSCA</option>
    <option value="BSCRIM">BSCRIM</option>
    <option value="BSIT">BSIT</option>

    <option value="BSBA FM">BSBA FM</option>
    <option value="BSBA MM">BSBA MM</option>
    <option value="BSBA HRDM">BSBA HRDM</option>

    <option value="BSHM">BSHM</option>
    <option value="BSED">BSED</option>
    <option value="BEED">BEED</option>

</select>

</div>



<div class="three-col">

<div class="form-group">

<label>School Year</label>

<select id="school_year" name="school_year" required>

    <option value="">Select School Year</option>

    <option value="2026-2027">2026-2027</option>
    <option value="2027-2028">2027-2028</option>
    <option value="2028-2029">2028-2029</option>

</select>

<label>Year Level</label>

<select id="year_level" name="year_level" onchange="loadSubjects()" required>

<option value="">Select Year</option>
<option>1ST YEAR</option>
<option>2ND YEAR</option>
<option>3RD YEAR</option>
<option>4TH YEAR</option>

</select>

</div>

<div class="form-group">

<label>Semester</label>

<select id="semester" name="semester" onchange="loadSubjects()" required>

<option value="">Select Semester</option>
<option>1ST SEM</option>
<option>2ND SEM</option>
<option>SUMMER</option>

</select>

</div>

<div class="form-group">

<label>Section</label>

<input type="text" name="section" placeholder="Section">

</div>

</div>


</div>



<!-- STUDENT PHOTO -->



<!-- CONTACT DETAILS -->

<div class="card">

<h2>Contact Details</h2>


<div class="form-group">
<label>Mobile Number</label>
<input type="text" name="mobile" placeholder="09XXXXXXXXX">
</div>


<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" placeholder="example@gmail.com">
</div>


<div class="form-group">
<label>Complete Address</label>
<textarea name="address" rows="3" placeholder="Enter Address"></textarea>
</div>



<h2 style="margin-top:25px;">Emergency Contact</h2>


<div class="form-group">
<label>Guardian Name</label>
<input type="text" name="guardian_name" placeholder="Guardian Name">
</div>


<div class="form-group">
<label>Guardian Contact Number</label>
<input type="text" name="guardian_contact" placeholder="Contact Number">
</div>


</div>



<!-- EDUCATIONAL BACKGROUND -->

<div class="card">

<h2>Educational Background</h2>


<table class="education-table">


<tr>

<th>Level</th>
<th>School Name</th>
<th>School Year</th>

</tr>



<tr>

<td>Grade 1 - 3</td>

<td>
<input type="text" name="grade13_school">
</td>

<td>
<input type="text" name="grade13_year">
</td>

</tr>




<tr>

<td>Grade 4 - 6</td>

<td>
<input type="text" name="grade46_school">
</td>

<td>
<input type="text" name="grade46_year">
</td>

</tr>




<tr>

<td>Grade 7</td>

<td>
<input type="text" name="grade7_school">
</td>

<td>
<input type="text" name="grade7_year">
</td>

</tr>




<tr>

<td>Grade 8</td>

<td>
<input type="text" name="grade8_school">
</td>

<td>
<input type="text" name="grade8_year">
</td>

</tr>




<tr>

<td>Grade 9</td>

<td>
<input type="text" name="grade9_school">
</td>

<td>
<input type="text" name="grade9_year">
</td>

</tr>




<tr>

<td>Grade 10</td>

<td>
<input type="text" name="grade10_school">
</td>

<td>
<input type="text" name="grade10_year">
</td>

</tr>




<tr>

<td>Grade 11</td>

<td>
<input type="text" name="grade11_school">
</td>

<td>
<input type="text" name="grade11_year">
</td>

</tr>




<tr>

<td>Grade 12</td>

<td>
<input type="text" name="grade12_school">
</td>

<td>
<input type="text" name="grade12_year">
</td>

</tr>


</table>



<div class="form-group" style="margin-top:20px;">

<label>Last School Attended</label>

<input type="text" name="last_school_attended">

</div>


</div>

<!-- ================= STUDENT SUBJECTS ================= -->

<div class="card subjects-card" style="grid-column:1 / span 3;">

    <h2>Student Subjects</h2>

    <div class="subjects-layout">

        <!-- LEFT -->
        <div class="available-subjects">

           <div id="searchContainer"
     style="display:none; margin-bottom:15px;">

    <input
        type="text"
        id="searchSubject"
        placeholder="Search Subject Code..."
        oninput="searchIrregularSubjects()"
        style="
            width:300px;
            padding:10px;
            border-radius:6px;
        ">

</div>

            <div class="subjects-title">
                Available Subjects
            </div>

            <table class="education-table">

                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Units</th>
                    </tr>
                </thead>

                <tbody id="subject_list">

                    <tr>
                        <td colspan="3"
                            style="text-align:center;">
                            Select Course and Year Level
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>


        <!-- RIGHT -->
        <div class="selected-subjects">

            <div class="subjects-title">
                Selected Subjects
            </div>

            <div id="selectedSubjectsList"
                 class="selected-subjects-list">

                <div class="no-selected">
                    No subjects selected.
                </div>

            </div>

            <div class="selected-total">

                <span>TOTAL UNITS:</span>

                <strong id="selectedTotalUnits">
                    0
                </strong>

            </div>

        </div>

    </div>

</div>

<!-- BUTTONS -->

<div class="buttons">

<a href="dashboard.php" class="back-btn">
✖ Close
</a>

<button type="submit" name="save" class="save-btn">
💾 Save and Register Student
</button>

</div>

</form>

</div>
<!-- BUTTONS -->

<!-- end form-container -->

</div> <!-- end container -->

<script>

let selectedSubjects = {};


// ==========================================
// LOAD SUBJECTS
// ==========================================

function loadSubjects() {

    const course = document.getElementById("course").value;
    const year = document.getElementById("year_level").value;
    const semester = document.getElementById("semester").value;

    const statusElement = document.querySelector(
        "input[name='status']:checked"
    );

    if (!statusElement) {
        return;
    }

    const status = statusElement.value;

    const searchContainer =
        document.getElementById("searchContainer");

    const searchInput =
        document.getElementById("searchSubject");

    const subjectList =
        document.getElementById("subject_list");


    // ==========================================
// IRREGULAR
// ==========================================

if (status === "Irregular") {

    // CLEAR PREVIOUS SUBJECTS
    selectedSubjects = {};

    // CLEAR HIDDEN INPUTS
    updateHiddenSubjects();

    // CLEAR SELECTED SUBJECTS PANEL
    renderSelectedSubjects();

    // RESET TOTAL
    updateTotalUnits();

    // SHOW SEARCH BOX
    searchContainer.style.display = "block";

    // CLEAR SEARCH
    searchInput.value = "";

    // SHOW MESSAGE
    subjectList.innerHTML = `
        <tr>
            <td colspan="3"
                style="
                    text-align:center;
                    color:#aaa;
                    padding:20px;
                ">
                Search a Subject Code to add subjects.
            </td>
        </tr>
    `;

    return;
}
    // ==========================================
    // REGULAR
    // ==========================================

    searchContainer.style.display = "none";

    searchInput.value = "";

    selectedSubjects = {};

    updateHiddenSubjects();
    updateTotalUnits();


    if (
        course === "" ||
        year === "" ||
        semester === ""
    ) {

        subjectList.innerHTML = `
            <tr>
                <td colspan="3"
                    style="
                        text-align:center;
                        color:#aaa;
                        padding:20px;
                    ">
                    Select Course, Year and Semester
                </td>
            </tr>
        `;

        return;
    }


    subjectList.innerHTML = `
        <tr>
            <td colspan="3"
                style="
                    text-align:center;
                    padding:20px;
                ">
                Loading subjects...
            </td>
        </tr>
    `;


    fetch(
    "load_subjects.php" +
    "?course=" + encodeURIComponent(course) +
    "&year=" + encodeURIComponent(year) +
    "&semester=" + encodeURIComponent(semester) +
    "&status=" + encodeURIComponent(status)
)

    .then(response => {

        if (!response.ok) {
            throw new Error(
                "HTTP Error: " + response.status
            );
        }

        return response.text();

    })

    .then(data => {

    subjectList.innerHTML = data;

    // ======================================
    // SELECT ALL REGULAR SUBJECTS
    // ======================================

    selectedSubjects = {};

    document
        .querySelectorAll(
            "#subject_list input.subject-checkbox"
        )
        .forEach(function(checkbox) {

            checkbox.checked = true;

            const code = checkbox.dataset.code;
            const name = checkbox.dataset.name;
            const units = checkbox.dataset.units;

            selectedSubjects[code] = {
                code: code,
                name: name,
                units: units
            };

        });

    // Create hidden inputs for saving
    updateHiddenSubjects();

    // Update selected subjects
renderSelectedSubjects();

// Calculate total units
updateTotalUnits();

})

    .catch(error => {

        console.error("LOAD SUBJECT ERROR:", error);

        subjectList.innerHTML = `
            <tr>
                <td colspan="3"
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


// ==========================================
// IRREGULAR SUBJECT SEARCH
// ==========================================

function searchIrregularSubjects() {

    const searchBox =
        document.getElementById("searchSubject");

    const subjectList =
        document.getElementById("subject_list");

    if (!searchBox || !subjectList) {
        return;
    }

    const keyword = searchBox.value.trim();


    if (keyword === "") {

        subjectList.innerHTML = `
            <tr>
                <td colspan="3"
                    style="
                        text-align:center;
                        color:#aaa;
                        padding:20px;
                    ">
                    Search a Subject Code to add subjects.
                </td>
            </tr>
        `;

        updateTotalUnits();

        return;
    }


    subjectList.innerHTML = `
        <tr>
            <td colspan="3"
                style="
                    text-align:center;
                    color:#aaa;
                    padding:20px;
                ">
                Searching...
            </td>
        </tr>
    `;


    fetch(
        "search_subject.php?keyword=" +
        encodeURIComponent(keyword)
    )

    .then(response => {

        if (!response.ok) {
            throw new Error(
                "HTTP Error " + response.status
            );
        }

        return response.text();

    })

    .then(data => {

        subjectList.innerHTML = data;


        // ======================================
        // RESTORE SELECTED SUBJECTS
        // ======================================

        document
            .querySelectorAll(".subject-checkbox")
            .forEach(function(checkbox) {

                const code =
                    checkbox.dataset.code;

                if (selectedSubjects[code]) {

                    checkbox.checked = true;
                }

            });


        updateTotalUnits();

    })

    .catch(error => {

        console.error("SEARCH ERROR:", error);

        subjectList.innerHTML = `
            <tr>
                <td colspan="3"
                    style="
                        text-align:center;
                        color:red;
                        padding:20px;
                    ">
                    Search error: ${error.message}
                </td>
            </tr>
        `;

    });
}


// ==========================================
// TOGGLE SUBJECT
// ==========================================

function toggleSubject(checkbox) {

    const code = checkbox.dataset.code;
    const name = checkbox.dataset.name;
    const units = parseFloat(checkbox.dataset.units) || 0;

    if (checkbox.checked) {

        selectedSubjects[code] = {
            code: code,
            name: name,
            units: units
        };

    } else {

        delete selectedSubjects[code];
    }

    // Save subjects for PHP
    updateHiddenSubjects();

    // Update right side
    renderSelectedSubjects();

    // Update total
    updateTotalUnits();
}
function renderSelectedSubjects() {

    const container =
        document.getElementById("selectedSubjectsList");

    if (!container) {
        return;
    }

    container.innerHTML = "";

    const subjects =
        Object.values(selectedSubjects);

    if (subjects.length === 0) {

        container.innerHTML = `
            <div class="no-selected">
                No subjects selected.
            </div>
        `;

        return;
    }

    subjects.forEach(function(subject) {

        const item =
            document.createElement("div");

        item.className = "selected-item";

        item.innerHTML = `
            <div class="selected-code">
                ${subject.code}
            </div>

            <div class="selected-name">
                ${subject.name}
            </div>

            <div class="selected-units">
                ${subject.units}
            </div>

            <button
                type="button"
                class="delete-subject-btn"
                title="Remove Subject"
                onclick="deleteSelectedSubject('${subject.code.replace(/'/g, "\\'")}')"
            >
                <i class="fa-solid fa-trash"></i>
            </button>
        `;

        container.appendChild(item);

    });
}
function deleteSelectedSubject(code) {

    // Remove subject from selected list
    delete selectedSubjects[code];

    // Update hidden inputs
    updateHiddenSubjects();

    // Update selected subjects on the right
    renderSelectedSubjects();

    // Update total units
    updateTotalUnits();

    // Uncheck subject on the left side
    document
        .querySelectorAll("#subject_list .subject-checkbox")
        .forEach(function(checkbox) {

            if (checkbox.dataset.code === code) {
                checkbox.checked = false;
            }

        });
}

// ==========================================
// CREATE HIDDEN INPUTS
// ==========================================

function updateHiddenSubjects() {

    const container =
        document.getElementById(
            "selectedSubjectsContainer"
        );

    if (!container) {
        return;
    }


    container.innerHTML = "";


    Object.values(selectedSubjects)
        .forEach(function(subject) {

            const input =
                document.createElement("input");

            input.type = "hidden";

            input.name = "subjects[]";

            input.value = subject.code;

            container.appendChild(input);

        });
}


// ==========================================
// TOTAL UNITS
// ==========================================

function updateTotalUnits() {

    const totalElement =
        document.getElementById("selectedTotalUnits");

    if (!totalElement) {
        return;
    }

    let total = 0;

    Object.values(selectedSubjects)
        .forEach(function(subject) {

            total +=
                parseFloat(subject.units) || 0;

        });

    totalElement.textContent = total;
}

</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>