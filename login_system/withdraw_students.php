<?php
session_start();
include "db.php";

/* =========================
   PROCESS WITHDRAW STUDENT
========================= */

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    if ($id <= 0) {
        die("Invalid student ID.");
    }

    $sql = "UPDATE students
            SET status = 'Withdraw'
            WHERE id = $id";

    if (!mysqli_query($conn, $sql)) {
        die("Error withdrawing student: " . mysqli_error($conn));
    }

    header("Location: withdraw_students.php");
    exit();
}


/* =========================
   GET WITHDRAW STUDENTS
========================= */

$result = mysqli_query($conn, "
    SELECT *
    FROM students
    WHERE status = 'Withdraw'
    AND deleted = 0
    ORDER BY course, year_level, section, full_name
");

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>

<head>

<title>Withdraw Students</title>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    font-family:Arial;
    background:#e9e9e9;
    padding:20px;
}

.edit-btn{
    background:#0d6efd;
    color:white;
    padding:6px 12px;
    border-radius:5px;
    text-decoration:none;
}

.delete-btn{
    background:#dc3545;
    color:white;
    padding:6px 12px;
    border-radius:5px;
    text-decoration:none;
}

.no-print{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th{
    background:#2f80ed;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

</style>

</head>

<body>

<h1>Withdraw Students</h1>

<p>
    <strong>Total Withdraw Students:</strong>
    <?php echo $total; ?>
</p>

<table border="1" cellspacing="0" cellpadding="10" width="100%">

<tr>
    <th>Student No.</th>
    <th>Full Name</th>
    <th>Course</th>
    <th>Year</th>
    <th>Section</th>
    <th>Status</th>
    <th class="no-print">Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

    <td>
        <?php echo htmlspecialchars($row['student_no']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['full_name']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['course']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['year_level']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['section']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['status']); ?>
    </td>

    <td class="no-print">

        <a href="edit_student.php?id=<?php echo $row['id']; ?>"
           class="edit-btn">

            <i class="fas fa-pen"></i>
            Edit

        </a>

        <a href="delete_student.php?id=<?php echo $row['id']; ?>"
           class="delete-btn"
           onclick="return confirm('Delete this student?')">

            <i class="fas fa-trash"></i>
            Delete

        </a>

    </td>

</tr>

<?php } ?>

</table>

</body>
</html>