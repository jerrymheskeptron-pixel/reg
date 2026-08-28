<?php

include "db.php";

$course   = $_GET['course'] ?? '';
$year     = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';
$status   = $_GET['status'] ?? 'Regular';
$keyword  = $_GET['keyword'] ?? '';

$course   = trim($course);
$year     = trim($year);
$semester = trim($semester);
$keyword  = trim($keyword);

$course   = mysqli_real_escape_string($conn, $course);
$year     = mysqli_real_escape_string($conn, $year);
$semester = mysqli_real_escape_string($conn, $semester);
$keyword  = mysqli_real_escape_string($conn, $keyword);


// ======================================
// IRREGULAR
// ======================================

if ($status == "Irregular") {

    if ($keyword == '') {

        echo "
        <tr>
            <td colspan='3'
                style='text-align:center;color:#aaa;padding:20px;'>
                Search a Subject Code to add subjects.
            </td>
        </tr>";

        exit();
    }

    $keyword_search = str_replace(' ', '', strtolower($keyword));

    $sql = "
        SELECT subject_code, subject_name, units
        FROM subjects
        WHERE REPLACE(LOWER(subject_code), ' ', '')
        LIKE '%$keyword_search%'
        ORDER BY subject_code
    ";


// ======================================
// REGULAR
// ======================================

} else {

    if ($course == '' || $year == '' || $semester == '') {

        echo "
        <tr>
            <td colspan='3'
                style='text-align:center;color:#aaa;padding:20px;'>
                Select Course, Year and Semester
            </td>
        </tr>";

        exit();
    }

    $sql = "
        SELECT subject_code, subject_name, units
        FROM subjects
        WHERE TRIM(course) = TRIM('$course')
        AND TRIM(year_level) = TRIM('$year')
        AND TRIM(semester) = TRIM('$semester')
        ORDER BY subject_code
    ";
}


// ======================================
// EXECUTE QUERY
// ======================================

$result = mysqli_query($conn, $sql);

if (!$result) {

    echo "
    <tr>
        <td colspan='3'
            style='text-align:center;color:red;padding:20px;'>
            Database Error: "
            . htmlspecialchars(mysqli_error($conn)) .
        "</td>
    </tr>";

    exit();
}


// ======================================
// DISPLAY SUBJECTS
// ======================================

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        $code = htmlspecialchars(
            $row['subject_code'],
            ENT_QUOTES,
            'UTF-8'
        );

        $name = htmlspecialchars(
            $row['subject_name'],
            ENT_QUOTES,
            'UTF-8'
        );

        $units = htmlspecialchars(
            $row['units'],
            ENT_QUOTES,
            'UTF-8'
        );

        echo "
        <tr>

            <td style='padding:10px;'>

                <label style='
    display:flex;
    align-items:center;
    gap:8px;
    color:#374151;
    font-size:14px;
    margin:0;
'>

                    <input
                        type='checkbox'
                        class='subject-checkbox'
                        name='subject_display[]'
                        value='{$code}'
                        data-code='{$code}'
                        data-name='{$name}'
                        data-units='{$units}'
                        onchange='toggleSubject(this)'
                        style='
                            width:18px;
                            height:18px;
                            cursor:pointer;
                        '
                    >

                    <span style='font-weight:600;'>
                        {$code}
                    </span>

                </label>

            </td>

            <td style='padding:10px;'>
                {$name}
            </td>

            <td style='padding:10px;'>
                {$units}
            </td>

        </tr>";
    }

} else {

    echo "
    <tr>
        <td colspan='3'
            style='text-align:center;color:#aaa;padding:20px;'>
            No subjects found.
        </td>
    </tr>";
}

?>