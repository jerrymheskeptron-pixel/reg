<?php

session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$sql = "UPDATE students
        SET deleted = 1
        WHERE deleted = 0";

if (!mysqli_query($conn, $sql)) {

    die(
        "<h2>Delete Error</h2>" .
        "<p>" . htmlspecialchars(mysqli_error($conn)) . "</p>"
    );

}

$affected = mysqli_affected_rows($conn);

echo "
<!DOCTYPE html>
<html>
<head>
    <title>Delete All Students</title>
</head>
<body style='font-family:Arial;padding:40px;'>

<h2>Delete All Result</h2>

<p>
    Students marked as deleted:
    <strong>$affected</strong>
</p>

<p>
    SQL executed successfully.
</p>

<a href='all_student_information.php'>
    Back to Student Information
</a>

</body>
</html>
";

?>