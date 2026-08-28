<?php

include "db.php";

$student_id = intval($_GET['id'] ?? 0);

if($student_id <= 0){
    echo "
    <div class='no-subjects'>
        Invalid student ID.
    </div>";
    exit();
}


/* ==========================================
   GET STUDENT STATUS
========================================== */

$student_query = mysqli_query($conn, "
    SELECT status
    FROM students
    WHERE id = $student_id
    LIMIT 1
");

if(!$student_query){

    echo "
    <div class='no-subjects'>
        Database Error: " . htmlspecialchars(mysqli_error($conn)) . "
    </div>";

    exit();
}


if(mysqli_num_rows($student_query) == 0){

    echo "
    <div class='no-subjects'>
        Student not found.
    </div>";

    exit();
}


$student = mysqli_fetch_assoc($student_query);

$status = $student['status'];


/* ==========================================
   GET STUDENT SUBJECTS
========================================== */

$result = mysqli_query($conn, "
    SELECT subject_code, subject_name, units, semester
    FROM student_subjects
    WHERE student_id = $student_id
    ORDER BY subject_code ASC
");

if(!$result){

    echo "
    <div class='no-subjects'>
        Database Error: " . htmlspecialchars(mysqli_error($conn)) . "
    </div>";

    exit();
}


/* ==========================================
   CHECK SUBJECTS
========================================== */

if(mysqli_num_rows($result) == 0){

    echo "
    <div class='student-info'>
        <div class='info-box'>
            <strong>Status:</strong>
            <span>" . htmlspecialchars($status) . "</span>
        </div>
    </div>

    <div class='no-subjects'>
        No subjects found for this student.
    </div>";

    exit();
}


/* ==========================================
   COMPUTE TOTAL UNITS
========================================== */

$total_units = 0;

$subjects = [];

while($row = mysqli_fetch_assoc($result)){

    $subjects[] = $row;

    $total_units += floatval($row['units']);

}

?>


<!-- ==========================================
     STUDENT INFORMATION
========================================== -->

<div class="student-info">

    <div class="info-box">

        <span class="info-label">
            Student Status
        </span>

        <span class="status-badge 
            <?php echo strtolower($status) == 'regular' ? 'regular' : 'irregular'; ?>">

            <?php echo htmlspecialchars($status); ?>

        </span>

    </div>


    <div class="info-box">

        <span class="info-label">
            Total Units
        </span>

        <span class="total-units">

            <?php echo $total_units; ?>

        </span>

    </div>

</div>


<!-- ==========================================
     SUBJECT TABLE
========================================== -->

<table class="subject-popup-table">

    <thead>

        <tr>

            <th>Subject Code</th>

            <th>Subject Name</th>

            <th>Units</th>

            <th>Semester</th>

        </tr>

    </thead>


    <tbody>

        <?php foreach($subjects as $row){ ?>

            <tr>

                <td>
                    <?php echo htmlspecialchars($row['subject_code']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['subject_name']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['units']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['semester']); ?>
                </td>

            </tr>

        <?php } ?>

    </tbody>


    <tfoot>

        <tr>

            <td colspan="2" style="text-align:right;">
                <strong>Total Units:</strong>
            </td>

            <td>
                <strong>
                    <?php echo $total_units; ?>
                </strong>
            </td>

            <td></td>

        </tr>

    </tfoot>

</table>