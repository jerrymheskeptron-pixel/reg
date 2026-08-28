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
    margin: 0 0 15px 0 !important;
    border-radius: 0 !important;
}

.withdraw-top-card .card-body {
    padding: 15px 20px !important;
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
    padding: 0 15px 20px 15px !important;
}

.withdraw-content-area table {
    width: 100% !important;
}

/* Prevent global CSS from centering page */
body {
    display: block !important;
}

</style>

</head>


<body class="bg-light">

<div class="withdraw-page">

    <!-- =========================
         HEADER
    ========================== -->

<div class="card shadow-sm border-0 rounded-0 withdraw-top-card">

    <div class="card-body d-flex justify-content-between align-items-center">

        <!-- LEFT SIDE -->
        <div class="withdraw-title">

            <a href="dashboard.php"
               class="btn btn-outline-secondary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center me-3"
               style="width:40px; height:40px;">

                <i class="fas fa-arrow-left"></i>

            </a>

            <div>

                <h2 class="h4 mb-0 text-primary fw-bold">
                    <i class="fas fa-user-minus me-2"></i>
                    Withdraw Students
                </h2>

                <small class="text-muted">
                    List of students with withdrawn enrollment status
                </small>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div>

            <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill">

                <i class="fas fa-users me-1"></i>

                Total Withdrawn:
                <?php echo $total; ?>

            </span>

        </div>

    </div>

</div>



    <!-- =========================
         TABLE
    ========================== -->

    <div class="withdraw-content-area">

        <div class="card shadow-sm border-0 w-100">

            <div class="card-body p-0 w-100">

                <div class="table-responsive w-100">

                    <table class="table table-hover table-striped align-middle mb-0 w-100">

                        <thead class="table-dark">

                            <tr>

                                <th class="ps-3">
                                    Student No.
                                </th>

                                <th>
                                    Full Name
                                </th>

                                <th>
                                    Course
                                </th>

                                <th>
                                    Year
                                </th>

                                <th>
                                    Section
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-3 no-print">
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

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5 text-muted">

                                    <i class="fas fa-folder-open fa-3x mb-3 d-block text-secondary"></i>

                                    No withdrawn students found.

                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
