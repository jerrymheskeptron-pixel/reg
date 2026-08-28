<?php

session_start();
include "db.php";

if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}


/*
 * EXPORT ACTIVE STUDENTS ONLY
 * deleted = 0
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


/*
 * Excel filename
 */

$filename = "Student_Information_" . date("Y-m-d") . ".xls";


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

?>

<table border="1">

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

</tr>


<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

    <td><?= htmlspecialchars($row['student_no']) ?></td>

    <td><?= htmlspecialchars($row['full_name']) ?></td>

    <td><?= htmlspecialchars($row['course']) ?></td>

    <td><?= htmlspecialchars($row['year_level']) ?></td>

    <td><?= htmlspecialchars($row['semester']) ?></td>

    <td><?= htmlspecialchars($row['section']) ?></td>

    <td><?= htmlspecialchars($row['gender']) ?></td>

    <td><?= htmlspecialchars($row['birthday']) ?></td>

    <td><?= htmlspecialchars($row['mobile']) ?></td>

    <td><?= htmlspecialchars($row['email']) ?></td>

    <td><?= htmlspecialchars($row['status']) ?></td>

    <td><?= htmlspecialchars($row['address']) ?></td>

    <td><?= htmlspecialchars($row['guardian_name']) ?></td>

    <td><?= htmlspecialchars($row['guardian_contact']) ?></td>


    <td><?= htmlspecialchars($row['grade13_school']) ?></td>

    <td><?= htmlspecialchars($row['grade13_year']) ?></td>


    <td><?= htmlspecialchars($row['grade46_school']) ?></td>

    <td><?= htmlspecialchars($row['grade46_year']) ?></td>


    <td><?= htmlspecialchars($row['grade7_school']) ?></td>

    <td><?= htmlspecialchars($row['grade7_year']) ?></td>


    <td><?= htmlspecialchars($row['grade8_school']) ?></td>

    <td><?= htmlspecialchars($row['grade8_year']) ?></td>


    <td><?= htmlspecialchars($row['grade9_school']) ?></td>

    <td><?= htmlspecialchars($row['grade9_year']) ?></td>


    <td><?= htmlspecialchars($row['grade10_school']) ?></td>

    <td><?= htmlspecialchars($row['grade10_year']) ?></td>


    <td><?= htmlspecialchars($row['grade11_school']) ?></td>

    <td><?= htmlspecialchars($row['grade11_year']) ?></td>


    <td><?= htmlspecialchars($row['grade12_school']) ?></td>

    <td><?= htmlspecialchars($row['grade12_year']) ?></td>

</tr>

<?php } ?>

</table>