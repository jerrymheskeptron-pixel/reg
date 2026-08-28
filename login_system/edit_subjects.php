<?php
session_start();
include "db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid Student ID.");
}


/* =========================
   GET STUDENT
========================= */

$result = mysqli_query($conn, "
    SELECT *
    FROM students
    WHERE id = $id
");

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found.");
}


/* =========================
   GET SELECTED NEW COURSE
========================= */

$course = $_GET['course'] ?? '';
$year   = $_GET['year'] ?? $student['year_level'];

$course = mysqli_real_escape_string($conn, $course);
$year   = mysqli_real_escape_string($conn, $year);


/*
   Kung walang bagong course na ipinasa,
   gamitin ang current course ng student.
*/
if ($course == '') {
    $course = $student['course'];
}


/* =========================
   GET SUBJECTS
========================= */

$subjects = mysqli_query($conn, "
    SELECT *
    FROM subjects
    WHERE course = '$course'
    AND year_level = '$year'
    ORDER BY subject_code ASC
");

if (!$subjects) {
    die("Subjects Database Error: " . mysqli_error($conn));
}

$total_subjects = mysqli_num_rows($subjects);

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

</div>


<!-- COURSE / YEAR -->

<div class="course-info">

    <div class="info-box">

        <strong>NEW COURSE</strong>

        <span>
            <?= htmlspecialchars($course) ?>
        </span>

    </div>

    <div class="info-box">

        <strong>YEAR LEVEL</strong>

        <span>
            <?= htmlspecialchars($year) ?>
        </span>

    </div>

</div>


<!-- SUBJECTS -->

<div class="subject-title">

    <h2>Available Subjects</h2>

    <span class="subject-count">
        <?= $total_subjects ?> Subjects
    </span>

</div>


<?php if($total_subjects > 0){ ?>

<form method="POST" action="save_student_subjects.php">

    <input type="hidden"
           name="student_id"
           value="<?= (int)$student['id'] ?>">

    <input type="hidden"
           name="course"
           value="<?= htmlspecialchars($course) ?>">

    <input type="hidden"
           name="year_level"
           value="<?= htmlspecialchars($year) ?>">


    <div class="select-all">

        <label>

            <input type="checkbox"
                   id="selectAll"
                   onclick="toggleAll(this)">

            <strong>Select / Unselect All</strong>

        </label>

    </div>


    <table>

        <thead>

        <tr>

            <th>Select</th>

            <th>Subject Code</th>

            <th>Subject Name</th>

            <th>Units</th>

        </tr>

        </thead>

        <tbody>

        <?php while($row = mysqli_fetch_assoc($subjects)){ ?>

        <tr>

            <td>

                <input type="checkbox"
                       name="subjects[]"
                       value="<?= (int)$row['id'] ?>"
                       class="subject-check"
                       checked>

            </td>

            <td class="subject-code">

                <?= htmlspecialchars($row['subject_code']) ?>

            </td>

            <td>

                <?= htmlspecialchars($row['subject_name']) ?>

            </td>

            <td>

                <?= htmlspecialchars($row['units']) ?>

            </td>

        </tr>

        <?php } ?>

        </tbody>

    </table>


    <div class="buttons">

        <a href="javascript:history.back()"
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

<?php }else{ ?>

<div class="no-subjects">

    <i class="fas fa-book-open"
       style="font-size:35px;"></i>

    <p>
        No subjects found for
        <strong><?= htmlspecialchars($course) ?></strong>
        -
        <strong><?= htmlspecialchars($year) ?></strong>.
    </p>

</div>

<div class="buttons">

    <a href="javascript:history.back()"
       class="back-btn">

        <i class="fas fa-arrow-left"></i>
        Back

    </a>

</div>

<?php } ?>

</div>


<script>

function toggleAll(source){

    const checkboxes =
        document.querySelectorAll(".subject-check");

    checkboxes.forEach(function(checkbox){

        checkbox.checked = source.checked;

    });

}

</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>