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

                        <h2 class="h4 mb-0 text-primary fw-bold d-inline-flex align-items-center withdraw-heading">
                            <i class="fas fa-user-minus me-2"></i>
                            Withdraw Students
                        </h2>

                        <span class="badge total-badge-red ms-2 d-inline-flex align-items-center">

                            <i class="fas fa-users me-2"></i>

                            Total Withdrawn: <?php echo $total; ?>

                        </span>

                    </div>

                    <small class="text-muted d-block mt-1 withdraw-subtitle">
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

                            <th class="ps-3 col-student-no">
                                Student No.
                            </th>

                            <th class="col-full-name">
                                Full Name
                            </th>

                            <th class="col-course">
                                Course
                            </th>

                            <th class="col-year">
                                Year
                            </th>

                            <th class="col-section">
                                Section
                            </th>

                            <th class="col-status">
                                Status
                            </th>

                            <th class="text-end pe-3 no-print col-action">
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

                                <span class="withdraw-empty-text">
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
