<!DOCTYPE html>
<html>
<head>

<title>Student List</title>

<!-- FONT AWESOME -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php

$course = $_GET['course'] ?? '';
$year = $_GET['year'] ?? '';
$section = $_GET['section'] ?? '';

include "db.php";


$filter = "WHERE course='$course'
AND year_level='$year'
AND section='$section'
AND student_list_deleted = 0
AND status != 'Withdraw'";

// SEARCH
if(isset($_GET['search']) && $_GET['search'] != ''){

    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $filter .= " AND (
        student_no LIKE '%$search%'
        OR full_name LIKE '%$search%'
    )";
}

// STATUS
if(isset($_GET['status']) && $_GET['status'] != ''){

    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $filter .= " AND status='$status'";
}

$sql = "
    SELECT *
    FROM students
    WHERE course = '$course'
      AND year_level = '$year'
      AND section = '$section'
      AND student_list_deleted = 0
    ORDER BY deleted ASC, last_name ASC, first_name ASC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

// TOTAL STUDENTS
$totalSql = "
    SELECT COUNT(*) AS total
    FROM students
    WHERE course = '$course'
      AND year_level = '$year'
      AND section = '$section'
      AND deleted = 0
      AND student_list_deleted = 0
      AND status != 'Withdraw'
";

$totalResult = mysqli_query($conn, $totalSql);

if (!$totalResult) {
    die("Total Error: " . mysqli_error($conn));
}

$totalStudents = mysqli_fetch_assoc($totalResult)['total'];

?>

<div class="page-container">
<div class="content-box">

<div class="top-buttons">

    <a href="dashboard.php"
       class="icon-btn back-btn"
       title="Back to Dashboard">
        <i class="fas fa-arrow-left"></i>
    </a>

    <button onclick="window.print()"
            class="icon-btn print-btn"
            title="Print">
        <i class="fas fa-print"></i>
    </button>

    <a href="export_excel.php"
       class="icon-btn excel-btn"
       title="Export Excel">
        <i class="fas fa-file-excel"></i>
    </a>

   <form method="POST"
      action="delete_all_student_list.php"
      style="display:inline;"
      onsubmit="return confirm('WARNING!\n\nAre you sure you want to delete ALL students in this section?');">

    <input type="hidden"
           name="course"
           value="<?= htmlspecialchars($course) ?>">

    <input type="hidden"
           name="year"
           value="<?= htmlspecialchars($year) ?>">

    <input type="hidden"
           name="section"
           value="<?= htmlspecialchars($section) ?>">

    <button type="submit"
            class="icon-btn delete-all-btn"
            title="Delete All Students">

        <i class="fas fa-trash"></i>

    </button>

</form>

</div>
<h2>
    Student List
</h2>
<p><strong>Total Students: <?php echo $totalStudents; ?></strong></p>
<?php
echo $course." - ".$year." - SECTION ".$section;
?>


<form method="GET">

    <!-- Panatilihin ang Course, Year at Section -->
    <input type="hidden" name="course" value="<?php echo $course; ?>">
    <input type="hidden" name="year" value="<?php echo $year; ?>">
    <input type="hidden" name="section" value="<?php echo $section; ?>">

    <input type="text"
           name="search"
           placeholder="Search Student No or Name"
           value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

    <select name="status" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="Regular"
        <?php if(isset($_GET['status']) && $_GET['status']=="Regular") echo "selected"; ?>>
            Regular
        </option>

        <option value="Irregular"
        <?php if(isset($_GET['status']) && $_GET['status']=="Irregular") echo "selected"; ?>>
            Irregular
        </option>
    </select>

    <input type="submit" value="Filter">
<a href="student_list.php?course=<?php echo urlencode($course); ?>&year=<?php echo urlencode($year); ?>&section=<?php echo urlencode($section); ?>"
class="btn-back">
<i class="fas fa-undo"></i> Reset
</a>

    <br>

    <!-- Table -->
     <div class="table-container">
    <table>

<thead>
<tr>
    <th>Student No</th>
    <th>Full Name</th>
    <th>Status</th>
    <th class="no-print" style="width:150px;">Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr class="<?= $row['status'] === 'Withdraw' ? 'withdraw-row' : '' ?>">

    <td>
        <?php echo htmlspecialchars($row['student_no']); ?>
    </td>

    <td>
        <span class="student-name"
              onclick="showSubjects(<?= (int)$row['id'] ?>)">
            <?= htmlspecialchars($row['full_name']) ?>
        </span>
    </td>

    <td>

        <?php if((int)$row['deleted'] === 1){ ?>

            <span class="inactive-badge">
                INACTIVE
            </span>

        <?php }else{ ?>

            <span class="active-badge">
                <?= htmlspecialchars($row['status']) ?>
            </span>

        <?php } ?>

    </td>

    <td class="no-print action-buttons">

        <!-- EDIT -->
        <a href="edit_student.php?id=<?= (int)$row['id'] ?>"
           class="edit-btn"
           title="Edit Student">

            <i class="fas fa-pen"></i>

        </a>


        <?php if((int)$row['deleted'] === 0){ ?>

            <!-- DELETE -->
            <a href="delete_student.php?id=<?= (int)$row['id'] ?>"
               class="delete-btn"
               title="Delete Student"
               onclick="return confirm('Delete this student?');">

                <i class="fas fa-trash"></i>

            </a>


            <!-- DROP / SHIFT COURSE -->
            <a href="shift_course.php?id=<?= (int)$row['id'] ?>"
   class="drop-btn"
   title="Shift Course">

    <i class="fas fa-right-left"></i>

</a>


            <!-- WITHDRAW -->
 <a href="withdraw_students.php?id=<?= (int)$row['id'] ?>"
   class="withdraw-btn"
   title="Withdraw Student"
   onclick="return confirm('Withdraw this student?');">

    <i class="fas fa-user-minus"></i>

</a>


        <?php }else{ ?>

            <!-- RESTORE -->
            <a href="restore_student.php?id=<?= (int)$row['id'] ?>"
               class="inactive-action"
               title="Activate Student"
               onclick="return confirm('Activate this student again?');">

                <i class="fas fa-user-check"></i>

            </a>

        <?php } ?>

    </td>

</tr>

<?php } ?>

</tbody>

</table>
</div>


<!-- =========================
     STUDENT SUBJECTS MODAL
========================= -->

<div id="subjectModal" class="subject-modal">

    <div class="subject-modal-box">

        <div class="subject-modal-header">

            <h2>
                <i class="fas fa-book-open"></i>
                Student Subjects
            </h2>

            <button type="button"
                    class="close-modal"
                    onclick="closeSubjects()">

                &times;

            </button>

        </div>

        <div id="subjectContent">

            <div class="loading">
                Loading subjects...
            </div>

        </div>

    </div>

</div>


<script>

function showSubjects(studentId){

    const modal = document.getElementById("subjectModal");
    const content = document.getElementById("subjectContent");

    modal.style.display = "flex";

    content.innerHTML = `
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            Loading subjects...
        </div>
    `;

    fetch("get_student_subjects.php?id=" + studentId)

    .then(response => response.text())

    .then(data => {

        content.innerHTML = data;

    })

    .catch(error => {

        console.error(error);

        content.innerHTML = `
            <div class="no-subjects">
                Unable to load student subjects.
            </div>
        `;

    });

}


function closeSubjects(){

    document.getElementById("subjectModal").style.display = "none";

}


window.addEventListener("click", function(event){

    const modal = document.getElementById("subjectModal");

    if(event.target === modal){

        closeSubjects();

    }

});


document.addEventListener("keydown", function(event){

    if(event.key === "Escape"){

        closeSubjects();

    }

});

</script>
</div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>