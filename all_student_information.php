<?php

session_start();
include "db.php";

if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}


/*
 * Ipinapakita lamang ang ACTIVE students.
 * Kapag deleted = 1, hindi na sila lalabas dito.
 */
$result = mysqli_query($conn,"
    SELECT *
    FROM students
    WHERE deleted = 0
    ORDER BY last_name ASC, first_name ASC
");

if(!$result){
    die("Database Error: " . mysqli_error($conn));
}

$totalStudents = mysqli_num_rows($result);

?>


<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Student Information</title>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>


<body>


<div class="container">


<div class="top-buttons">

    <a href="dashboard.php" class="back">
        ← Dashboard
    </a>

    <a href="export_student_information.php" class="excel-btn">
        <i class="fas fa-file-excel"></i>
        Export to Excel
    </a>

    <form method="POST"
          action="delete_all_information.php"
          onsubmit="return confirm('WARNING!\n\nAre you sure you want to delete ALL student information?\n\nAll students will be marked as DELETED.');"
          style="display:inline;">

        <button type="submit" class="delete-all-btn">
            🗑️ Delete All Information
        </button>

    </form>

</div>


<div class="title-row">

    <h2>Student Information</h2>

    <div class="total-box">
        <i class="fas fa-users"></i>
        Total Students:
        <strong><?= $totalStudents ?></strong>
    </div>

</div>


<div class="table-container">

<table>

<thead>

<tr>

<th>Student No</th>
<th>Name</th>
<th>Course</th>
<th>Year</th>
<th>Semester</th>
<th>Section</th>
<th>Gender</th>
<th>Birthday</th>
<th>Mobile</th>
<th>Email</th>
<th>Status</th>
<th>Address</th>
<th>Guardian Name</th>
<th>Guardian No.</th>

<th>Grade 1-3</th>
<th>School Year</th>

<th>Grade 4-6</th>
<th>School Year</th>

<th>Grade 7</th>
<th>School Year</th>

<th>Grade 8</th>
<th>School Year</th>

<th>Grade 9</th>
<th>School Year</th>

<th>Grade 10</th>
<th>School Year</th>

<th>Grade 11</th>
<th>School Year</th>

<th>Grade 12</th>
<th>School Year</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php if(mysqli_num_rows($result) > 0){ ?>


    <?php while($row = mysqli_fetch_assoc($result)){ ?>


        <tr>

            <td>
                <?= htmlspecialchars($row['student_no']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['full_name']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['course']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['year_level']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['semester']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['section']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['gender']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['birthday']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['mobile']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['email']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['status']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['address']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['guardian_name']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['guardian_contact']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade13_school']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade13_year']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade46_school']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade46_year']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade7_school']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade7_year']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade8_school']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade8_year']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade9_school']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade9_year']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade10_school']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade10_year']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade11_school']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade11_year']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade12_school']) ?>
            </td>


            <td>
                <?= htmlspecialchars($row['grade12_year']) ?>
            </td>


            <td>

                <a href="delete_student.php?id=<?= (int)$row['id'] ?>"
                   class="delete-btn"
                   onclick="return confirm('Are you sure you want to delete this student?');">

                    🗑️ Delete

                </a>

            </td>

        </tr>


    <?php } ?>


<?php }else{ ?>


    <tr>

        <td colspan="31" class="no-students">

            No active students found.

        </td>

    </tr>


<?php } ?>


</tbody>

</table>

</div>

</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>