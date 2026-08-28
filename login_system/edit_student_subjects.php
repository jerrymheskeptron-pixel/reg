<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid Student ID.");
}


/* =========================================
   GET STUDENT INFORMATION
========================================= */

$student_sql = mysqli_query($conn, "
    SELECT id, student_no, full_name, course, year_level, section, semester, status
    FROM students
    WHERE id = $id
");

if (!$student_sql) {
    die("Database Error: " . mysqli_error($conn));
}

$student = mysqli_fetch_assoc($student_sql);

if (!$student) {
    die("Student not found.");
}
$course = isset($_GET['course']) && $_GET['course'] !== ''
    ? $_GET['course']
    : $student['course'];

$year = isset($_GET['year']) && $_GET['year'] !== ''
    ? $_GET['year']
    : $student['year_level'];

$semester = isset($_GET['semester']) && $_GET['semester'] !== ''
    ? $_GET['semester']
    : ($student['semester'] ?? '');

$section = isset($_GET['section']) && $_GET['section'] !== ''
    ? $_GET['section']
    : $student['section'];

$status = isset($_GET['status']) && $_GET['status'] !== ''
    ? $_GET['status']
    : ($student['status'] ?? 'Regular');  

/* =========================================
   SAVE SUBJECTS + COURSE + BLOCK
========================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $posted_course = $_POST['course'] ?? '';
$posted_section = $_POST['section'] ?? '';
$posted_status = $_POST['status'] ?? $status;

if ($posted_status !== 'Regular' && $posted_status !== 'Irregular') {
    die("Invalid student status.");
}
    if ($posted_course === '') {
        die("Course is required.");
    }

    if ($posted_section === '') {
        die("Section is required.");
    }

    $course_db = mysqli_real_escape_string(
        $conn,
        $posted_course
    );

    $section_db = mysqli_real_escape_string(
        $conn,
        $posted_section
    );


    /* =========================
       UPDATE STUDENT COURSE + BLOCK
    ========================= */

 $status_db = mysqli_real_escape_string(
    $conn,
    $posted_status
);

$update_student = mysqli_query(
    $conn,
    "UPDATE students
     SET course = '$course_db',
         section = '$section_db',
         status = '$status_db'
     WHERE id = $id"
);

    if (!$update_student) {
        die(
            "Error updating student course/block: "
            . mysqli_error($conn)
        );
    }


    /* =========================
       GET SELECTED SUBJECTS
    ========================= */

    $selected_subjects = $_POST['subjects'] ?? [];


    /* =========================
       DELETE OLD SUBJECTS
    ========================= */

    $delete_old = mysqli_query(
        $conn,
        "DELETE FROM student_subjects
         WHERE student_id = $id"
    );

    if (!$delete_old) {
        die(
            "Error removing old subjects: "
            . mysqli_error($conn)
        );
    }


    /* =========================
       INSERT NEW SUBJECTS
    ========================= */

    foreach ($selected_subjects as $subject_code) {

        $subject_code = mysqli_real_escape_string(
            $conn,
            $subject_code
        );


        /* GET SUBJECT INFORMATION */

        $subject_sql = mysqli_query(
            $conn,
            "SELECT subject_code, subject_name, units
             FROM subjects
             WHERE subject_code = '$subject_code'
             LIMIT 1"
        );

        if (!$subject_sql) {
            die(
                "Subject Database Error: "
                . mysqli_error($conn)
            );
        }


        $subject = mysqli_fetch_assoc($subject_sql);


        if ($subject) {

            $subject_name = mysqli_real_escape_string(
                $conn,
                $subject['subject_name']
            );

            $units = (float)$subject['units'];

            $semester_safe = mysqli_real_escape_string(
                $conn,
                $semester
            );


            $insert = mysqli_query(
                $conn,
                "INSERT INTO student_subjects
                (
                    student_id,
                    subject_code,
                    subject_name,
                    units,
                    semester
                )
                VALUES
                (
                    $id,
                    '$subject_code',
                    '$subject_name',
                    $units,
                    '$semester_safe'
                )"
            );


            if (!$insert) {
                die(
                    "Error saving subject: "
                    . mysqli_error($conn)
                );
            }
        }
    }


    /* =========================
       SUCCESS
    ========================= */

 echo "
<script>

    alert(
        'Student successfully updated to " . addslashes($posted_course) . " - Block " . addslashes($posted_section) . ".'
    );

    window.location.href = 'student_list.php';

</script>
";

    exit();
}


/* =========================================
   GET CURRENT STUDENT SUBJECTS
========================================= */

$current_subjects = [];

$current_sql = mysqli_query(
    $conn,
    "SELECT subject_code
     FROM student_subjects
     WHERE student_id = $id"
);

if (!$current_sql) {
    die("Current Subjects Error: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($current_sql)) {

    $current_subjects[] = $row['subject_code'];

}


/* =========================================
   GET SUBJECTS FROM DATABASE
========================================= */

/* =========================================
   GET SUBJECTS FROM DATABASE
========================================= */

if (strcasecmp($status, 'Irregular') === 0) {

    // IRREGULAR = ALL SUBJECTS
    $subjects_sql = mysqli_query(
        $conn,
        "SELECT subject_code, subject_name, units
         FROM subjects
         ORDER BY subject_code ASC"
    );

} else {

    // REGULAR = CURRICULUM SUBJECTS ONLY
    $course_safe = mysqli_real_escape_string($conn, $course);
    $year_safe = mysqli_real_escape_string($conn, $year);
    $semester_safe = mysqli_real_escape_string($conn, $semester);

    $subjects_sql = mysqli_query(
        $conn,
        "SELECT subject_code, subject_name, units
         FROM subjects
         WHERE course = '$course_safe'
           AND year_level = '$year_safe'
           AND semester = '$semester_safe'
         ORDER BY subject_code ASC"
    );
}

if (!$subjects_sql) {
    die("Subjects Error: " . mysqli_error($conn));
}

if (!$subjects_sql) {
    die("Subjects Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Student Subjects</title>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h1>
        <i class="fas fa-book"></i>
        Edit Student Subjects
    </h1>


    <!-- STUDENT INFORMATION -->

  <div class="student-info">

    <p>
        <strong>Student No:</strong>
        <?= htmlspecialchars($student['student_no']) ?>
    </p>

    <p>
        <strong>Full Name:</strong>
        <?= htmlspecialchars($student['full_name']) ?>
    </p>

    <p>
        <strong>Course:</strong>
        <?= htmlspecialchars($course) ?>
    </p>

    <p>
        <strong>Year Level:</strong>
        <?= htmlspecialchars($year) ?>
    </p>

    <p>
        <strong>Section:</strong>
        <?= htmlspecialchars($section) ?>
    </p>

    <p>
        <strong>Semester:</strong>
        <?= htmlspecialchars($semester) ?>
    </p>

</div>


    <form method="POST">

    <input type="hidden"
       name="course"
       value="<?= htmlspecialchars($course) ?>">

<input type="hidden"
       name="section"
       value="<?= htmlspecialchars($section) ?>">

<input type="hidden"
       name="status"
       value="<?= htmlspecialchars($status) ?>">

  <div class="subject-header">
    <h2>
        Available Subjects
    </h2>

    <span class="subject-count">
        <?= mysqli_num_rows($subjects_sql) ?> Subjects
    </span>
</div>

<?php if (strcasecmp($status, 'Irregular') === 0): ?>

<div class="subject-search">

    <input
        type="text"
        id="subjectSearch"
        placeholder="Search Subject Code..."
        onkeyup="searchSubjects()"
        autocomplete="off"
    >

    <button type="button" onclick="clearSubjectSearch()">
        <i class="fas fa-times"></i>
    </button>

</div>

<?php endif; ?>

<div class="select-all-box">

            <label>

                <input type="checkbox"
                       class="checkbox"
                       id="selectAll"
                       onclick="toggleAll(this)">

                Select / Unselect All

            </label>

        </div>


       <?php if(mysqli_num_rows($subjects_sql) > 0){ ?>

<div class="subject-table-container">

<table class="subject-table">

            <thead>

                <tr>

                    <th width="80">
                        Select
                    </th>

                    <th width="180">
                        Subject Code
                    </th>

                    <th>
                        Subject Name
                    </th>

                    <th width="100">
                        Units
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php while($subject = mysqli_fetch_assoc($subjects_sql)){ ?>

                <?php
                $checked = in_array(
                    $subject['subject_code'],
                    $current_subjects
                );
                ?>

                <tr class="subject-row"
    data-code="<?= htmlspecialchars($subject['subject_code']) ?>">

                    <td style="text-align:center;">

                        <input
                            type="checkbox"
                            name="subjects[]"
                            value="<?= htmlspecialchars($subject['subject_code']) ?>"
                            class="subject-checkbox checkbox"
                            <?= $checked ? 'checked' : '' ?>
                        >

                    </td>

                    <td class="subject-code">

                        <?= htmlspecialchars($subject['subject_code']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($subject['subject_name']) ?>

                    </td>

                    <td class="units">

                        <?= htmlspecialchars($subject['units']) ?>

                    </td>

                </tr>

            <?php } ?>

           </tbody>

</table>

</div>

<?php }else{ ?>

            <div class="no-subjects">

                <i class="fas fa-book-open"></i>

                <br><br>

                No subjects found in the database for:

                <br><br>

                <strong>
                    <?= htmlspecialchars($course) ?>
                    -
                    <?= htmlspecialchars($year) ?>
                    -
                    <?= htmlspecialchars($semester) ?>
                </strong>

            </div>

        <?php } ?>


        <div class="buttons">

            <a href="shift_course.php?id=<?= (int)$id ?>&new_course=<?= urlencode($course) ?>&new_section=<?= urlencode($section) ?>&status=<?= urlencode($status) ?>"
   class="back-btn">

                <i class="fas fa-arrow-left"></i>
                Back

            </a>

            <button type="submit"
                    class="save-btn">

                <i class="fas fa-save"></i>
                Save Subjects

            </button>

        </div>

    </form>

</div>


<script>

function toggleAll(source){

    const checkboxes =
        document.querySelectorAll(".subject-checkbox");

    checkboxes.forEach(function(checkbox){
        checkbox.checked = source.checked;
    });
}


/* =========================================
   SEARCH SUBJECT CODE
========================================= */

function searchSubjects(){

    const input = document.getElementById("subjectSearch");

    if (!input) return;

    const search = input.value
        .toLowerCase()
        .replace(/\s+/g, '');

    const rows = document.querySelectorAll(".subject-row");

    rows.forEach(function(row){

        const code = row
            .getAttribute("data-code")
            .toLowerCase()
            .replace(/\s+/g, '');

        if (code.includes(search)){
            row.style.display = "";
        }else{
            row.style.display = "none";
        }

    });
}


/* =========================================
   CLEAR SEARCH
========================================= */

function clearSubjectSearch(){

    const input = document.getElementById("subjectSearch");

    if (!input) return;

    input.value = "";

    searchSubjects();

    input.focus();
}

</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>