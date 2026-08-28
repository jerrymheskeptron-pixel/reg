<?php
include "db.php";

$keyword = $_GET['keyword'] ?? '';
$keyword = trim($keyword);

if ($keyword == '') {
    exit;
}

$keyword = mysqli_real_escape_string($conn, $keyword);

$sql = "
    SELECT subject_code, subject_name, units
    FROM subjects
    WHERE REPLACE(LOWER(subject_code), ' ', '')
    LIKE CONCAT(
        '%',
        REPLACE(LOWER('$keyword'), ' ', ''),
        '%'
    )
    ORDER BY subject_code
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo '
    <tr>
        <td colspan="3"
            style="text-align:center;color:red;padding:20px;">
            SQL ERROR: ' . htmlspecialchars(mysqli_error($conn)) . '
        </td>
    </tr>';
    exit;
}

if (mysqli_num_rows($result) == 0) {

    echo '
    <tr>
        <td colspan="3"
            style="text-align:center;color:#aaa;padding:20px;">
            No subject found.
        </td>
    </tr>';

    exit;
}

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

    echo '
    <tr>

        <td style="padding:10px;">

            <label style="
    display:flex;
    align-items:center;
    gap:8px;
    color:#374151;
    text-transform:none;
    font-size:14px;
    margin:0;
">

                <input
    type="checkbox"
    class="subject-checkbox"
    name="subjects[]"
    value="' . $code . '"
    data-code="' . $code . '"
    data-name="' . $name . '"
    data-units="' . $units . '"
    onchange="toggleSubject(this)"
    style="
        width:18px;
        height:18px;
        cursor:pointer;
    "
>

                <span style="font-weight:600;">
                    ' . $code . '
                </span>

            </label>

        </td>

        <td style="padding:10px;">
            ' . $name . '
        </td>

        <td style="padding:10px;">
            ' . $units . '
        </td>

    </tr>';
}
?>