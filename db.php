<?php
$conn = mysqli_connect("localhost", "root", "", "system_student");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>  