<?php

include "db.php";

$course  = $_GET['course'] ?? '';
$year    = $_GET['year'] ?? '';
$section = $_GET['section'] ?? '';

$course  = mysqli_real_escape_string($conn, $course);
$year    = mysqli_real_escape_string($conn, $year);
$section = mysqli_real_escape_string($conn, $section);


// Excel headers
header("Content-Type: application/vnd.ms-excel");
header(
    "Content-Disposition: attachment; filename="
    . $course . "_"
    . $year . "_Section_"
    . $section . ".xls"
);

echo "Student No\tFull Name\tStatus\n";


$sql = mysqli_query($conn,"
    SELECT student_no, full_name, status
    FROM students
    WHERE course = '$course'
    AND year_level = '$year'
    AND section = '$section'
    AND deleted = 0
    AND student_list_deleted = 0
    ORDER BY full_name ASC
");


if(!$sql){
    die("Export Error: " . mysqli_error($conn));
}


while($row = mysqli_fetch_assoc($sql)){

    echo $row['student_no'] . "\t";
    echo $row['full_name'] . "\t";
    echo $row['status'] . "\n";

}

?>