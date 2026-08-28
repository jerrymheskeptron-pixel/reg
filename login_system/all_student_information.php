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

<style>

body{
    font-family:Arial;
    background:#f1f5f9;
    padding:30px;
}

h2{
    color:#2563eb;
}

.table-container{
    width:100%;
    max-height:600px;
    overflow:auto;
    margin-top:20px;
}

table{
    width:max-content;
    min-width:100%;
    border-collapse:collapse;
}

th{
    background:#2563eb;
    color:white;
    padding:12px;

    position:sticky;
    top:0;
    z-index:10;

    white-space:nowrap;
}

td{
    padding:10px;
    border-bottom:1px solid #ddd;
    white-space:nowrap;
}

tr:hover{
    background:#f5f5f5;
}

.back{
    display:inline-block;
    background:#2563eb;
    color:white;
    padding:10px 20px;
    text-decoration:none;
    border-radius:5px;
}

.back:hover{
    background:#1d4ed8;
}

.delete-btn{
    display:inline-block;
    background:#dc2626;
    color:white;
    border:none;
    padding:7px 14px;
    border-radius:5px;
    cursor:pointer;
    font-weight:bold;
    text-decoration:none;
    font-size:13px;
}

.delete-btn:hover{
    background:#b91c1c;
}


/* TOP BUTTONS */

.top-buttons{
    display:flex;
    gap:10px;
    align-items:center;
    margin-bottom:20px;
}


/* DELETE ALL */

.delete-all-btn{
    background:#dc2626;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
    font-size:14px;
}

.delete-all-btn:hover{
    background:#b91c1c;
}


/* NO STUDENTS */

.no-students{
    text-align:center;
    padding:30px;
    color:#64748b;
    font-weight:bold;
    font-size:16px;
}
.title-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

.title-row h2{
    margin:0;
    color:#2563eb;
}

.total-box{
    background:#2563eb;
    color:white;
    padding:10px 18px;
    border-radius:7px;
    font-size:16px;
    font-weight:bold;
    box-shadow:0 3px 8px rgba(0,0,0,.15);
}

.total-box i{
    margin-right:6px;
}

.total-box strong{
    font-size:20px;
    margin-left:5px;
}
.excel-btn{
    display:inline-block;
    background:#198754;
    color:white;
    padding:10px 18px;
    border-radius:6px;
    text-decoration:none;
    font-weight:bold;
    font-size:14px;
}

.excel-btn:hover{
    background:#146c43;
}
</style>

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


</body>
</html>