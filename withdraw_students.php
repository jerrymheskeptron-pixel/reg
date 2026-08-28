<?php
session_start();
@include "db.php";

/* =========================
   PROCESS WITHDRAW STUDENT
========================= */

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    if ($id <= 0) {
        die("Invalid student ID.");
    }

    $sql = "UPDATE students
            SET status = 'Withdraw'
            WHERE id = $id";

    if (!mysqli_query($conn, $sql)) {
        die("Error withdrawing student: " . mysqli_error($conn));
    }

    header("Location: withdraw_students.php");
    exit();
}


/* =========================
   GET WITHDRAW STUDENTS
========================= */

if (!$conn) {
    $total = 0;
    $result = [];
} else {

    $result = mysqli_query($conn, "
        SELECT *
        FROM students
        WHERE status = 'Withdraw'
        AND deleted = 0
        ORDER BY course, year_level, section, full_name
    ");

    if (!$result) {
        die("Database Error: " . mysqli_error($conn));
    }

    $total = mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Withdraw Students - Student Information System</title>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="stylesheet" href="style.css">

<!-- PAGE POSITION FIX -->
<style>

html,
body {
    margin: 0 !important;
    padding: 0 !important;
    background-color: #f8f9fa !important;
    display: block !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
}

/* Override global .card styles from style.css */
.withdraw-page .card {
    display: block !important;
    height: auto !important;
    max-height: none !important;
    min-height: 0 !important;
    justify-content: normal !important;
    align-items: normal !important;
    text-align: left !important;
    cursor: default !important;
    box-shadow: none !important;
    border: none !important;
    padding: 0 !important;
    background-color: transparent !important;
    border-radius: 0 !important;
}

/* Main page */
.withdraw-page {
    width: 100%;
    margin: 0 !important;
    padding: 0 !important;
}

/* Header nasa upper-left */
.withdraw-top-card {
    width: 100%;
    margin: 0 0 10px 0 !important;
}

.withdraw-top-card .card-body {
    padding: 12px 15px !important;
    display: flex !important;
    align-items: center !important;
}

/* Left side */
.withdraw-title {
    display: flex;
    align-items: center;
    justify-content: flex-start !important;
    text-align: left !important;
}

/* Table */
.withdraw-content-area {
    width: 100%;
    margin: 0 !important;
    padding: 0 15px 15px 15px !important;
}

.withdraw-table-container {
    width: 100%;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.withdraw-content-area table {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0 !important;
    margin-bottom: 0 !important;
}

.withdraw-content-area table thead th {
    position: relative !important;
    top: 0 !important;
    z-index: 1 !important;
    background: #212529 !important;
    background-color: #212529 !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 13px !important;
    border: none !important;
    padding: 14px 15px !important;
    vertical-align: middle !important;
}

.withdraw-empty-row {
    background-color: #f0f0f0 !important;
}

.withdraw-empty-cell {
    background-color: #f0f0f0 !important;
    padding: 50px 20px !important;
    border: none !important;
}

.back-btn-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #ffffff;
    border: 1px solid #eaeaea;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    margin-right: 15px;
    flex-shrink: 0;
    color: #6c757d;
}

.back-btn-circle:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.total-badge-red {
    background-color: #dc3545 !important;
    color: #ffffff !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    border-radius: 50rem !important;
    padding: 6px 16px !important;
}

</style>

</head>


<body>

<div class="withdraw-page">

    <!-- =========================
         HEADER
    ========================== -->

    <div class="withdraw-top-card">

        <div class="card-body">

            <!-- LEFT SIDE -->
            <div class="withdraw-title">

                <a href="dashboard.php" class="back-btn-circle">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div>

                    <div class="d-flex align-items-center gap-2">

                        <h2 class="h4 mb-0 text-primary fw-bold d-inline-flex align-items-center" style="color: #0d6efd !important;">
                            <i class="fas fa-user-minus me-2"></i>
                            Withdraw Students
                        </h2>

                        <span class="badge total-badge-red ms-2 d-inline-flex align-items-center">

                            <i class="fas fa-users me-2"></i>

                            Total Withdrawn: <?php echo $total; ?>

                        </span>

                    </div>

                    <small class="text-muted d-block mt-1" style="font-size: 12px; color: #6c757d !important;">
                        List of students with withdrawn enrollment status
                    </small>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================
         TABLE
    ========================== -->

    <div class="withdraw-content-area">

        <div class="withdraw-table-container">

            <div class="table-responsive w-100">

                <table class="table align-middle mb-0 w-100">

                    <thead>

                        <tr>

                            <th class="ps-3" style="width: 15%;">
                                Student No.
                            </th>

                            <th style="width: 25%;">
                                Full Name
                            </th>

                            <th style="width: 12%;">
                                Course
                            </th>

                            <th style="width: 10%;">
                                Year
                            </th>

                            <th style="width: 10%;">
                                Section
                            </th>

                            <th style="width: 13%;">
                                Status
                            </th>

                            <th class="text-end pe-3 no-print" style="width: 15%;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($total > 0) { ?>

                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                            <tr>

                                <td class="ps-3 fw-bold text-secondary">

                                    <?php
                                    echo htmlspecialchars($row['student_no']);
                                    ?>

                                </td>


                                <td class="fw-semibold">

                                    <?php
                                    echo htmlspecialchars($row['full_name']);
                                    ?>

                                </td>


                                <td>

                                    <span class="badge bg-info text-dark">

                                        <?php
                                        echo htmlspecialchars($row['course']);
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars($row['year_level']);
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars($row['section']);
                                    ?>

                                </td>


                                <td>

                                    <span class="badge bg-danger">

                                        <?php
                                        echo htmlspecialchars($row['status']);
                                        ?>

                                    </span>

                                </td>


                                <td class="text-end pe-3 no-print">

                                    <a href="edit_student.php?id=<?php echo $row['id']; ?>"
                                       class="btn btn-sm btn-outline-primary me-1">

                                        <i class="fas fa-pen me-1"></i>
                                        Edit

                                    </a>


                                    <a href="delete_student.php?id=<?php echo $row['id']; ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Delete this student?')">

                                        <i class="fas fa-trash me-1"></i>
                                        Delete

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr class="withdraw-empty-row">

                            <td colspan="7"
                                class="text-center withdraw-empty-cell text-muted">

                                <i class="fas fa-folder fa-2x mb-2 text-secondary d-block"></i>

                                <span style="font-size: 13px; color: #6c757d;">
                                    No withdrawn students found.
                                </span>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
