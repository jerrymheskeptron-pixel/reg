<?php

include "db.php";

$student_no=$_POST['student_no'];

$full_name=$_POST['full_name'];

$status=$_POST['status'];

$course=$_POST['course'];

$year_level=$_POST['year_level'];

$section=$_POST['section'];

$sql="INSERT INTO students
(student_no,full_name,status,course,year_level,section)

VALUES

('$student_no',
'$full_name',
'$status',
'$course',
'$year_level',
'$section')";

if(mysqli_query($conn, $sql)){
    echo "Student saved successfully!";
    header("Location: all_student_information.php");
    exit();
}else{
    die("SQL Error: " . mysqli_error($conn));
}
?>