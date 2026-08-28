<?php
include "db.php";

$id = $_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM students WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $student_no = $_POST['student_no'];
    $full_name  = $_POST['full_name'];
    $status     = $_POST['status'];

    mysqli_query($conn,"UPDATE students SET

        student_no='$student_no',
        full_name='$full_name',
        status='$status'

        WHERE id='$id'
    ");

    header("Location: student_list.php?course=".$row['course']."&year=".$row['year_level']."&section=".$row['section']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Student</title>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="box">

<h2>Edit Student</h2>

<form method="POST">

<input type="text"
name="student_no"
value="<?php echo $row['student_no']; ?>"
required>

<input type="text"
name="full_name"
value="<?php echo $row['full_name']; ?>"
required>

<select name="status">

<option value="Regular"
<?php if($row['status']=="Regular") echo "selected"; ?>>
Regular
</option>

<option value="Irregular"
<?php if($row['status']=="Irregular") echo "selected"; ?>>
Irregular
</option>

</select>

<button name="update">
Update Student
</button>

</form>

</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>