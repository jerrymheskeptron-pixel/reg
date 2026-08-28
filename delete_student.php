<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Student ID is missing.");
}

$id = intval($_GET['id']);

if ($id <= 0) {
    die("Invalid Student ID.");
}


/* =========================
   GET STUDENT INFORMATION
========================= */

$check = mysqli_query(
    $conn,
    "SELECT id, full_name, course, year_level, section, status, deleted
     FROM students
     WHERE id = $id"
);

if (!$check) {
    die("Database Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($check) == 0) {
    die("Student not found. ID: " . $id);
}

$student = mysqli_fetch_assoc($check);


/* =========================
   ALREADY DELETED
========================= */

if ((int)$student['deleted'] === 1) {

    if ($student['status'] === 'Withdraw') {

        header("Location: withdraw_students.php");
        exit();

    } else {

        $course  = urlencode($student['course']);
        $year    = urlencode($student['year_level']);
        $section = urlencode($student['section']);

        header(
            "Location: student_list.php?course=$course&year=$year&section=$section"
        );
        exit();
    }
}


/* =========================
   SOFT DELETE
========================= */

$sql = "UPDATE students
        SET deleted = 1
        WHERE id = $id
        AND deleted = 0";

if (!mysqli_query($conn, $sql)) {
    die("Delete Error: " . mysqli_error($conn));
}


/* =========================
   VERIFY DELETE
========================= */

$verify = mysqli_query(
    $conn,
    "SELECT deleted
     FROM students
     WHERE id = $id"
);

if (!$verify) {
    die("Verification Error: " . mysqli_error($conn));
}

$verified = mysqli_fetch_assoc($verify);


/* =========================
   REDIRECT
========================= */

if ((int)$verified['deleted'] === 1) {

    /*
     * Kung Withdraw student,
     * balik sa Withdraw Students.
     */
    if ($student['status'] === 'Withdraw') {

        header("Location: withdraw_students.php");
        exit();

    }

    /*
     * Kung normal student,
     * balik sa Student List.
     */
    $course  = urlencode($student['course']);
    $year    = urlencode($student['year_level']);
    $section = urlencode($student['section']);

    header(
        "Location: student_list.php?course=$course&year=$year&section=$section"
    );

    exit();
}


/* =========================
   ERROR
========================= */

die(
    "Student was not deleted.<br>" .
    "ID: " . $id . "<br>" .
    "Name: " . htmlspecialchars($student['full_name']) . "<br>" .
    "Current deleted value: " . htmlspecialchars($verified['deleted'])
);

?>