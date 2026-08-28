<?php
session_start();
include "db.php";

if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}

if(!isset($_GET['id'])){
    die("Student ID is missing.");
}

$id = intval($_GET['id']);

if($id <= 0){
    die("Invalid Student ID.");
}

/* Restore student */
$sql = "UPDATE students
        SET deleted = 0
        WHERE id = $id
        AND deleted = 1";

if(!mysqli_query($conn, $sql)){
    die("Restore Error: " . mysqli_error($conn));
}

/* Get student information for redirect */
$check = mysqli_query(
    $conn,
    "SELECT course, year_level, section
     FROM students
     WHERE id = $id"
);

if(!$check){
    die("Database Error: " . mysqli_error($conn));
}

$student = mysqli_fetch_assoc($check);

$course  = urlencode($student['course']);
$year    = urlencode($student['year_level']);
$section = urlencode($student['section']);

header(
    "Location: student_list.php?course=$course&year=$year&section=$section"
);
exit();
?>