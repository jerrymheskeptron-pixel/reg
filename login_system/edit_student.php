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

<style>

body{
    font-family:Arial;
    background:#e9e9e9;
}

.box{
    width:500px;
    margin:50px auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.2);
}

input,select{
    width:100%;
    padding:10px;
    margin:8px 0;
}

button{
    background:#2f80ed;
    color:white;
    border:none;
    padding:10px;
    width:100%;
    cursor:pointer;
}

button:hover{
    background:#145cc0;
}

</style>

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

</body>
</html>