<?php

session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$course  = trim($_POST['course'] ?? '');
$year    = trim($_POST['year'] ?? '');
$section = trim($_POST['section'] ?? '');

if ($course === '' || $year === '' || $section === '') {
    die("Missing course, year, or section.");
}

$course  = mysqli_real_escape_string($conn, $course);
$year    = mysqli_real_escape_string($conn, $year);
$section = mysqli_real_escape_string($conn, $section);

/*
    DELETE FROM THIS STUDENT LIST ONLY

    Hindi binubura ang student record.
    Hindi binabago ang deleted.

    student_list_deleted = 1
*/

$sql = "
    UPDATE students
    SET student_list_deleted = 1
    WHERE course = '$course'
      AND year_level = '$year'
      AND section = '$section'
";

if (!mysqli_query($conn, $sql)) {
    die("Error deleting students from Student List: " . mysqli_error($conn));
}

/*
    Balik sa parehong Student List
*/

header(
    "Location: student_list.php?" .
    "course=" . urlencode($course) .
    "&year=" . urlencode($year) .
    "&section=" . urlencode($section)
);

exit();

?>