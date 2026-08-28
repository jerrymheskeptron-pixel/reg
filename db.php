<?php
mysqli_report(MYSQLI_REPORT_OFF);
try {
    $conn = @mysqli_connect("localhost", "root", "", "system_student");
} catch (Throwable $e) {
    $conn = false;
}

if (!$conn) {
    // Database connection fallback
}
?>
