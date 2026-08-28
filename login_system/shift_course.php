<?php
session_start();
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$new_course_selected = $_GET['new_course'] ?? '';
$new_section_selected = $_GET['new_section'] ?? '';

if ($id <= 0) {
    die("Invalid student ID.");
}


/* =========================
   PROCESS SHIFT COURSE
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)$_POST['id'];

    $new_course = mysqli_real_escape_string(
        $conn,
        $_POST['new_course']
    );

   $new_section = mysqli_real_escape_string(
    $conn,
    $_POST['new_section']
);

$new_status = $_POST['status'] ?? 'Regular';

$new_status = mysqli_real_escape_string(
    $conn,
    $new_status
);

    /* Kunin ang OLD course at OLD block */
 $get_old = mysqli_query(
    $conn,
    "SELECT course, section, status
     FROM students
     WHERE id = $id"
);

    if (!$get_old) {
        die("Database Error: " . mysqli_error($conn));
    }

    $old_student = mysqli_fetch_assoc($get_old);

    if (!$old_student) {
        die("Student not found.");
    }

    $old_course  = $old_student['course'];
$old_section = $old_student['section'];
$old_status  = $old_student['status'];


    /* Huwag payagan kung pareho lahat */
  if (
    $old_course === $new_course &&
    $old_section === $new_section &&
    $old_status === $new_status
) {

        echo "
        <script>
            alert('Please select a different course or block.');
            window.history.back();
        </script>
        ";

        exit;
    }


    /* UPDATE COURSE + BLOCK */
   $update = mysqli_query(
    $conn,
    "UPDATE students
     SET course = '$new_course',
         section = '$new_section',
         status = '$new_status'
     WHERE id = $id"
);


    if (!$update) {
        die("Update Error: " . mysqli_error($conn));
    }


    echo "
    <script>
        alert('Student successfully shifted from $old_course - Block $old_section to $new_course - Block $new_section.');
        window.location.href = 'student_list.php';
    </script>
    ";

    exit;
}


/* =========================
   GET STUDENT
========================= */

$sql = "SELECT * FROM students WHERE id = $id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found.");
}
/* =========================
   DISPLAY SELECTED COURSE/BLOCK
========================= */

$display_course = $new_course_selected !== ''
    ? $new_course_selected
    : $student['course'];

$display_section = $new_section_selected !== ''
    ? $new_section_selected
    : $student['section'];
?>

<!DOCTYPE html>
<html>

<head>

<title>Shift Course</title>

<style>

*{
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#e9e9e9;
    padding:30px;
}

.container{
    width:650px;
    max-width:95%;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.20);
}

h1{
    margin-top:0;
    color:#2563eb;
    text-align:center;
    margin-bottom:25px;
}


/* =========================
   STUDENT INFORMATION
========================= */

.student-info{
    background:#f1f5f9;
    padding:18px;
    border-radius:10px;
    margin-bottom:25px;
    border:1px solid #e2e8f0;
}

.student-info p{
    margin:10px 0;
}

.student-info strong{
    color:#334155;
}


/* =========================
   COURSE SHIFT AREA
========================= */

.course-shift{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:25px;
}

.course-box{
    flex:1;
    padding:20px;
    border-radius:10px;
    text-align:center;
    border:2px solid;
}

.course-box label{
    display:block;
    font-size:13px;
    font-weight:bold;
    margin-bottom:10px;
    text-transform:uppercase;
}

.old-course{
    background:#fff1f2;
    border-color:#ef4444;
}

.old-course label{
    color:#dc2626;
}

.new-course{
    background:#ecfdf5;
    border-color:#22c55e;
}

.new-course label{
    color:#16a34a;
}

.course-value{
    font-size:22px;
    font-weight:bold;
}

.arrow{
    font-size:28px;
    color:#64748b;
}


/* =========================
   SELECT
========================= */

label.main-label{
    display:block;
    font-weight:bold;
    margin-bottom:8px;
}

select{
    width:100%;
    padding:12px;
    border:1px solid #cbd5e1;
    border-radius:7px;
    font-size:15px;
    background:white;
    margin-bottom:20px;
}


/* =========================
   BUTTONS
========================= */

.buttons{
    display:flex;
    gap:10px;
}

button{
    flex:1;
    padding:12px 18px;
    border:none;
    border-radius:7px;
    color:white;
    background:#f59e0b;
    cursor:pointer;
    font-weight:bold;
    font-size:15px;
}

button:hover{
    background:#d97706;
}

.back-btn{
    flex:1;
    padding:12px 18px;
    background:#6c757d;
    color:white;
    text-decoration:none;
    border-radius:7px;
    text-align:center;
    font-weight:bold;
}

.back-btn:hover{
    background:#545b62;
}


/* =========================
   RESPONSIVE
========================= */

@media(max-width:600px){

    .course-shift{
        flex-direction:column;
    }

    .course-box{
        width:100%;
    }

    .arrow{
        transform:rotate(90deg);
    }

    .buttons{
        flex-direction:column;
    }

}
.subject-btn{
    flex:1;
    padding:12px 18px;
    background:#2563eb;
    color:white;
    text-decoration:none;
    border-radius:7px;
    text-align:center;
    font-weight:bold;
    font-size:15px;
}

.subject-btn:hover{
    background:#1d4ed8;
}
</style>

</head>

<body>


<div class="container">

    <h1>
        <i class="fas fa-right-left"></i>
        Shift Course
    </h1>


    <!-- =========================
         STUDENT INFORMATION
    ========================= -->

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
            <strong>Year Level:</strong>
            <?= htmlspecialchars($student['year_level']) ?>
        </p>

        <p>
            <strong>Section:</strong>
            <?= htmlspecialchars($student['section']) ?>
        </p>

    </div>


    <!-- =========================
         OLD COURSE → NEW COURSE
    ========================= -->

    <div class="course-shift">

        <div class="course-box old-course">

            <label>Old Course</label>

            <div class="course-value">
                <?= htmlspecialchars($student['course']) ?>
            </div>

        </div>


        <div class="arrow">
            <i class="fas fa-arrow-right"></i>
        </div>


        <div class="course-box new-course">

            <label>New Course</label>
<div class="course-value" id="newCourseDisplay">
    <?= $new_course_selected !== ''
        ? htmlspecialchars($new_course_selected)
        : '---'
    ?>
</div>

        </div>

    </div>


    <!-- =========================
         FORM
    ========================= -->

   <form method="POST">

    <input type="hidden"
           name="id"
           value="<?= (int)$student['id'] ?>">


    <!-- NEW COURSE -->

<!-- NEW COURSE -->

<label class="main-label">
    Select New Course
</label>

<select name="new_course"
        id="new_course"
        required
        onchange="showNewCourse(this.value)">

    <option value="">-- Select New Course --</option>

    <option value="BSCA"
        <?= $new_course_selected === 'BSCA' ? 'selected' : '' ?>>
        BSCA
    </option>

    <option value="BSCRIM"
        <?= $new_course_selected === 'BSCRIM' ? 'selected' : '' ?>>
        BSCRIM
    </option>

    <option value="BSIT"
        <?= $new_course_selected === 'BSIT' ? 'selected' : '' ?>>
        BSIT
    </option>

    <option value="BSBA FM"
        <?= $new_course_selected === 'BSBA FM' ? 'selected' : '' ?>>
        BSBA FM
    </option>

    <option value="BSBA MM"
        <?= $new_course_selected === 'BSBA MM' ? 'selected' : '' ?>>
        BSBA MM
    </option>

    <option value="BSBA HRDM"
        <?= $new_course_selected === 'BSBA HRDM' ? 'selected' : '' ?>>
        BSBA HRDM
    </option>

    <option value="BSHM"
        <?= $new_course_selected === 'BSHM' ? 'selected' : '' ?>>
        BSHM
    </option>

    <option value="BSED"
        <?= $new_course_selected === 'BSED' ? 'selected' : '' ?>>
        BSED
    </option>

    <option value="BEED"
        <?= $new_course_selected === 'BEED' ? 'selected' : '' ?>>
        BEED
    </option>

</select>

    <!-- NEW BLOCK -->

    <label class="main-label">
        Select New Block
    </label>

   <select name="new_section"
        id="new_section"
        required>

    <option value="">
        -- Select New Block --
    </option>

    <option value="A"
        <?= $new_section_selected === 'A' ? 'selected' : '' ?>>
        Block A
    </option>

    <option value="B"
        <?= $new_section_selected === 'B' ? 'selected' : '' ?>>
        Block B
    </option>

    <option value="C"
        <?= $new_section_selected === 'C' ? 'selected' : '' ?>>
        Block C
    </option>

    <option value="D"
        <?= $new_section_selected === 'D' ? 'selected' : '' ?>>
        Block D
    </option>

    <option value="E"
        <?= $new_section_selected === 'E' ? 'selected' : '' ?>>
        Block E
    </option>

    <option value="F"
        <?= $new_section_selected === 'F' ? 'selected' : '' ?>>
        Block F
    </option>

    <option value="G"
        <?= $new_section_selected === 'G' ? 'selected' : '' ?>>
        Block G
    </option>

</select>
<label class="main-label">
    Student Status
</label>

<select name="status" id="status" required>

    <option value="Regular"
        <?= ($student['status'] === 'Regular') ? 'selected' : '' ?>>
        Regular
    </option>

    <option value="Irregular"
        <?= ($student['status'] === 'Irregular') ? 'selected' : '' ?>>
        Irregular
    </option>

</select>

    <!-- BUTTONS -->

    <div class="buttons">

    <a href="javascript:history.back()"
       class="back-btn">

        <i class="fas fa-arrow-left"></i>
        Back

    </a>

  <a href="#"
   class="subject-btn"
   onclick="goToSubjects(); return false;">

    <i class="fas fa-book"></i>
    Edit Subjects

</a>

    <button type="submit">

        <i class="fas fa-right-left"></i>
        Shift Course

    </button>

</div>

</form>

</div>


<script>

/* =========================
   SHOW NEW COURSE
========================= */

function showNewCourse(course){

    const display =
        document.getElementById("newCourseDisplay");

    if(course === ""){

        display.innerHTML = "---";

    }else{

        display.innerHTML = course;

    }

}


/* =========================
   CONFIRM SHIFT
========================= */

function confirmShift(){

    const newCourse =
        document.getElementById("new_course").value;

    const oldCourse =
        "<?= htmlspecialchars($student['course'], ENT_QUOTES) ?>";


    if(newCourse === ""){

        alert("Please select a new course.");

        return false;

    }


    if(newCourse === oldCourse){

        alert(
            "The new course must be different from the old course."
        );

        return false;

    }


    return confirm(
        "Shift student from " +
        oldCourse +
        " to " +
        newCourse +
        "?"
    );

}
function goToSubjects(){

    const course =
        document.getElementById("new_course").value;

    const section =
        document.getElementById("new_section").value;

    const status =
        document.getElementById("status").value;

    if(course === ""){
        alert("Please select a new course first.");
        return;
    }

    if(section === ""){
        alert("Please select a new block first.");
        return;
    }

    const studentId =
        <?= (int)$student['id'] ?>;

    const year =
        <?= json_encode($student['year_level']) ?>;

    const semester =
        <?= json_encode($student['semester'] ?? '') ?>;

    window.location.href =
        "edit_student_subjects.php?id=" +
        studentId +
        "&course=" +
        encodeURIComponent(course) +
        "&year=" +
        encodeURIComponent(year) +
        "&semester=" +
        encodeURIComponent(semester) +
        "&section=" +
        encodeURIComponent(section) +
        "&status=" +
        encodeURIComponent(status);
}
</script>


</body>
</html>