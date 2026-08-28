<!DOCTYPE html>
<html>
<head>

<title>Student List</title>

<!-- FONT AWESOME -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
body{
    font-family: Arial;
    background:#e9e9e9;
    padding:20px;
}

.container-box{
    width: 900px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}

input, select{
    padding:10px;
    margin:5px;
    border:1px solid #ccc;
    border-radius:6px;
}

button{
    padding:10px 15px;
    background:#2f80ed;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#145cc0;
}

table{
    width:100%;
    border-collapse: collapse;
    margin-top:15px;
}

th{
    background:#2f80ed;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

a{
    text-decoration:none;
}
.btn-back{
    display:inline-block;
    padding:10px 18px;
    background:#6c757d;
    color:#fff !important;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
    transition:.3s;
}
.btn-print{
    display:inline-block;
    background:#0d6efd;
    color:#fff;
    padding:10px 18px;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

.btn-print:hover{
    background:#0b5ed7;
}
.btn-back:hover{
    background:#545b62;
    color:#fff !important;
}
.button-group{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}

.print-btn{
    background:#28a745;
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:5px;
    cursor:pointer;
    font-size:15px;
}

.print-btn:hover{
    background:#218838;
}
@media print {

    body{
        font-size: 11px;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    th{
        font-size:11px;
        padding:4px;
    }

    td{
        font-size:10px;   /* Mas maliit ang names */
        padding:3px;
    }

    h2{
        font-size:16px;
        margin-bottom:5px;
    }

    p{
        font-size:14px;
        margin-bottom:5px;
    }

    .button-group,
    .no-print,
    form,
    h3{
        display:none !important;
    }
}
@media print {

    .no-print {
        display: none !important;
    }

}


.edit-btn:hover{
    background:#0056b3;
}

.delete-btn:hover{
    background:#b02a37;
}
.page-container{
    width:85%;
    max-width:1200px;
    margin:30px auto;
}

.content-box{
    background:#fff;
    border-radius:15px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.top-buttons{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}
.table-container{
    max-height:500px;      /* taas ng scrolling area */
    overflow-y:auto;
    overflow-x:hidden;
    border:1px solid #ddd;
    border-radius:10px;
    margin-top:20px;
}
table{
    width:100%;
    min-width:900px;
    border-collapse:collapse;
}
.table-container thead th{
    position:sticky;
    top:0;
    background:#2f80ed;
    color:white;
    z-index:10;
}
.action-buttons{
    white-space: nowrap;
}

.action-buttons .edit-btn,
.action-buttons .delete-btn{
    display:inline-block;
    padding:6px 12px;
    margin:2px;
}

.edit-btn{
    background:#007bff;
    color:#fff;
}

.delete-btn{
    background:#dc3545;
    color:#fff;
}
@media print {

    @page{
        size: A4 portrait;
        margin: 0.4in;
    }

    body{
        zoom:100%;
        font-size:13px;
        background:#fff;
    }

    .content-box{
        width:100%;
        max-width:100%;
        padding:0;
        margin:0;
        box-shadow:none;
        border:none;
    }

    .table-container{
        max-height:none !important;
        overflow:visible !important;
        border:none;
    }

    table{
        width:100%;
        font-size:13px;
    }

    th{
        font-size:13px;
        padding:8px;
    }

    td{
        font-size:12px;
        padding:6px;
    }

    .button-group,
    form,
    .no-print{
        display:none !important;
    }
}
.btn-export{
    display:inline-block;
    background:#28a745;
    color:#fff !important;
    padding:10px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
    transition:.3s;
}

.btn-export:hover{
    background:#218838;
    color:#fff !important;
}
.student-name{
    color:#2563eb;
    cursor:pointer;
    font-weight:500;
}

.student-name:hover{
    text-decoration:underline;
    color:#1d4ed8;
}
.subject-modal{
    display:none;

    position:fixed;
    left:0;
    top:0;

    width:100%;
    height:100%;

    background:rgba(0,0,0,0.55);

    z-index:99999;

    align-items:center;
    justify-content:center;
}

.subject-modal-box{
    width:800px;
    max-width:92%;

    max-height:85vh;

    background:white;

    border-radius:12px;

    overflow:hidden;

    box-shadow:0 15px 40px rgba(0,0,0,0.35);

    animation:popup .2s ease;
}

@keyframes popup{

    from{
        transform:scale(.85);
        opacity:0;
    }

    to{
        transform:scale(1);
        opacity:1;
    }

}

.subject-modal-header{
    display:flex;
    justify-content:space-between;
    align-items:center;

    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;

    padding:18px 20px;

    box-shadow:0 3px 10px rgba(0,0,0,.15);
}

.subject-modal-header h2{
    margin:0;
    font-size:20px;
}

.close-modal{
    border:none;
    background:rgba(255,255,255,.15);
    color:white;

    width:35px;
    height:35px;

    border-radius:50%;

    font-size:24px;
    line-height:30px;

    cursor:pointer;
    transition:.2s;
}

.close-modal:hover{
    background:rgba(255,255,255,.3);
    transform:rotate(90deg);
}

#subjectContent{

    padding:20px;

    max-height:70vh;

    overflow-y:auto;
}


/* STUDENT INFORMATION */

.student-details{

    background:#f1f5f9;

    border-radius:8px;

    padding:15px;

    margin-bottom:20px;

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:10px 20px;
}

.student-details div{
    padding:3px 0;
}

.student-details strong{
    color:#334155;
}


/* SUBJECT TABLE */

.subject-table{

    width:100%;

    border-collapse:collapse;
}

.subject-table th{

    background:#2563eb;

    color:white;

    padding:10px;

    text-align:left;
}

.subject-table td{

    padding:10px;

    border-bottom:1px solid #ddd;
}

.subject-table tr:hover{

    background:#f8fafc;
}

.loading{

    text-align:center;

    padding:40px;

    color:#64748b;
}

.no-subjects{

    text-align:center;

    padding:35px;

    color:#64748b;
}


/* MGA EXISTING CSS MO DITO */


/* ==============================
   STUDENT INFO
============================== */

.student-info{
    display:flex;
    gap:15px;
    margin-bottom:18px;
}

.info-box{
    flex:1;
    background:linear-gradient(135deg,#f8fafc,#eef4ff);
    border:1px solid #dbe5f1;
    border-radius:10px;
    padding:12px 16px;

    display:flex;
    align-items:center;
    justify-content:space-between;

    box-shadow:0 3px 8px rgba(0,0,0,0.06);
}

.info-label{
    color:#64748b;
    font-size:13px;
    font-weight:bold;
    text-transform:uppercase;
    letter-spacing:.5px;
}

.status-badge{
    padding:6px 13px;
    border-radius:20px;
    color:white;
    font-size:13px;
    font-weight:bold;
}

.status-badge.regular{
    background:#16a34a;
}

.status-badge.irregular{
    background:#f59e0b;
}

.total-units{
    background:#2563eb;
    color:white;
    padding:6px 13px;
    border-radius:20px;
    font-size:14px;
    font-weight:bold;
}


/* ==============================
   SUBJECT TABLE
============================== */

.subject-popup-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    overflow:hidden;
    border:1px solid #e2e8f0;
    border-radius:8px;
}

.subject-popup-table th{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    color:white;
    padding:11px;
    text-align:center;
    font-size:14px;
}

.subject-popup-table td{
    padding:10px 12px;
    border-bottom:1px solid #e5e7eb;
    text-align:center;
    font-size:14px;
}

.subject-popup-table tbody tr{
    transition:.2s;
}

.subject-popup-table tbody tr:hover{
    background:#f1f5ff;
}

.subject-popup-table tbody tr:last-child td{
    border-bottom:none;
}


/* Subject code */

.subject-popup-table td:first-child{
    font-weight:bold;
    color:#2563eb;
}


/* Units */

.subject-popup-table td:nth-child(3){
    font-weight:bold;
}


/* ==============================
   TOTAL UNITS FOOTER
============================== */

.subject-popup-table tfoot td{
    background:#f8fafc;
    padding:12px;
    border-top:2px solid #2563eb;
    border-bottom:none;
}

.subject-popup-table tfoot td:first-child{
    color:#475569;
}

.subject-popup-table tfoot td:nth-child(3){
    color:#2563eb;
    font-size:16px;
}
.student-name{
    color:#2563eb;
    font-weight:500;
    cursor:pointer;
    transition:0.2s;
}

.student-name:hover{
    color:#1d4ed8;
    text-decoration:underline;
}
.active-badge{
    display:inline-block;
    background:#16a34a;
    color:white;
    padding:5px 10px;
    border-radius:15px;
    font-size:12px;
    font-weight:bold;
}

.inactive-badge{
    display:inline-block;
    background:#dc2626;
    color:white;
    padding:5px 10px;
    border-radius:15px;
    font-size:12px;
    font-weight:bold;
}
.inactive-action{
    display:inline-block;
    background:#dc2626;
    color:white !important;
    padding:6px 12px;
    border-radius:5px;
    font-weight:bold;
    text-decoration:none;
    cursor:pointer;
}

.inactive-action:hover{
    background:#b91c1c;
}
.delete-all-btn{
    background:#dc2626;
    color:white;
    padding:10px 18px;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}

.delete-all-btn:hover{
    background:#b91c1c;
}
.top-buttons{
    display:flex;
    gap:8px;
    align-items:center;
    margin-bottom:15px;
}

.icon-btn{
    width:48px;
    height:44px;
    border:none;
    border-radius:7px;

    display:flex;
    align-items:center;
    justify-content:center;

    color:#fff;
    text-decoration:none;

    cursor:pointer;
    font-size:17px;

    transition:0.2s;
}

.icon-btn:hover{
    transform:translateY(-2px);
    opacity:0.9;
}

/* Back */
.back-btn{
    background:#6b7280;
}

/* Print */
.print-btn{
    background:#1268e8;
}

/* Excel */
.excel-btn{
    background:#16a34a;
}

/* Delete All */
.delete-all-btn{
    background:#dc2626;
}

.icon-btn:active{
    transform:scale(0.95);
}
/* ACTION BUTTONS */

/* ==============================
   ACTION BUTTONS - EQUAL SIZE
============================== */

.action-buttons{
    white-space:nowrap;
    text-align:center;
}

.action-buttons a{
    display:inline-flex !important;

    width:38px !important;
    height:38px !important;

    padding:0 !important;
    margin:2px !important;

    box-sizing:border-box;

    align-items:center;
    justify-content:center;

    border:none;
    border-radius:6px;

    color:#fff !important;
    text-decoration:none;

    font-size:14px;
    line-height:1;

    vertical-align:middle;

    transition:0.2s;
}

/* EDIT */
.action-buttons .edit-btn{
    background:#007bff;
}

.action-buttons .edit-btn:hover{
    background:#0056b3;
    transform:translateY(-2px);
}

/* DELETE */
.action-buttons .delete-btn{
    background:#dc3545;
}

.action-buttons .delete-btn:hover{
    background:#b02a37;
    transform:translateY(-2px);
}

/* SHIFT COURSE */
.action-buttons .drop-btn{
    background:#f59e0b;
}

.action-buttons .drop-btn:hover{
    background:#d97706;
    transform:translateY(-2px);
}

/* WITHDRAW */
.action-buttons .withdraw-btn{
    background:#7c3aed;
}

.action-buttons .withdraw-btn:hover{
    background:#5b21b6;
    transform:translateY(-2px);
}

/* RESTORE */
.action-buttons .inactive-action{
    display:inline-flex !important;

    width:38px !important;
    height:38px !important;

    padding:0 !important;
    margin:2px !important;

    box-sizing:border-box;

    align-items:center;
    justify-content:center;

    border-radius:6px;

    vertical-align:middle;
}


/* EDIT */

.edit-btn{
    background:#007bff;
}

.edit-btn:hover{
    background:#0056b3;
    transform:translateY(-2px);
}


/* DELETE */

.delete-btn{
    background:#dc3545;
}

.delete-btn:hover{
    background:#b02a37;
    transform:translateY(-2px);
}


/* DROP / SHIFT COURSE */

.drop-btn{
    background:#f59e0b;
}

.drop-btn:hover{
    background:#d97706;
    transform:translateY(-2px);
}


/* WITHDRAW */

.withdraw-btn{
    background:#7c3aed;
}

.withdraw-btn:hover{
    background:#5b21b6;
    transform:translateY(-2px);
}
@media print {

    .withdraw-row{
        display:none !important;
    }

}
</style>

</head>
<body>
<?php

$course = $_GET['course'] ?? '';
$year = $_GET['year'] ?? '';
$section = $_GET['section'] ?? '';

include "db.php";


$filter = "WHERE course='$course'
AND year_level='$year'
AND section='$section'
AND student_list_deleted = 0
AND status != 'Withdraw'";

// SEARCH
if(isset($_GET['search']) && $_GET['search'] != ''){

    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $filter .= " AND (
        student_no LIKE '%$search%'
        OR full_name LIKE '%$search%'
    )";
}

// STATUS
if(isset($_GET['status']) && $_GET['status'] != ''){

    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $filter .= " AND status='$status'";
}

$sql = "
    SELECT *
    FROM students
    WHERE course = '$course'
      AND year_level = '$year'
      AND section = '$section'
      AND student_list_deleted = 0
    ORDER BY deleted ASC, last_name ASC, first_name ASC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

// TOTAL STUDENTS
$totalSql = "
    SELECT COUNT(*) AS total
    FROM students
    WHERE course = '$course'
      AND year_level = '$year'
      AND section = '$section'
      AND deleted = 0
      AND student_list_deleted = 0
      AND status != 'Withdraw'
";

$totalResult = mysqli_query($conn, $totalSql);

if (!$totalResult) {
    die("Total Error: " . mysqli_error($conn));
}

$totalStudents = mysqli_fetch_assoc($totalResult)['total'];

?>

<div class="page-container">
<div class="content-box">

<div class="top-buttons">

    <a href="dashboard.php"
       class="icon-btn back-btn"
       title="Back to Dashboard">
        <i class="fas fa-arrow-left"></i>
    </a>

    <button onclick="window.print()"
            class="icon-btn print-btn"
            title="Print">
        <i class="fas fa-print"></i>
    </button>

    <a href="export_excel.php"
       class="icon-btn excel-btn"
       title="Export Excel">
        <i class="fas fa-file-excel"></i>
    </a>

   <form method="POST"
      action="delete_all_student_list.php"
      style="display:inline;"
      onsubmit="return confirm('WARNING!\n\nAre you sure you want to delete ALL students in this section?');">

    <input type="hidden"
           name="course"
           value="<?= htmlspecialchars($course) ?>">

    <input type="hidden"
           name="year"
           value="<?= htmlspecialchars($year) ?>">

    <input type="hidden"
           name="section"
           value="<?= htmlspecialchars($section) ?>">

    <button type="submit"
            class="icon-btn delete-all-btn"
            title="Delete All Students">

        <i class="fas fa-trash"></i>

    </button>

</form>

</div>
<h2>
    Student List
</h2>
<p><strong>Total Students: <?php echo $totalStudents; ?></strong></p>
<?php
echo $course." - ".$year." - SECTION ".$section;
?>
    

<form method="GET">

    <!-- Panatilihin ang Course, Year at Section -->
    <input type="hidden" name="course" value="<?php echo $course; ?>">
    <input type="hidden" name="year" value="<?php echo $year; ?>">
    <input type="hidden" name="section" value="<?php echo $section; ?>">

    <input type="text"
           name="search"
           placeholder="Search Student No or Name"
           value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

    <select name="status" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="Regular"
        <?php if(isset($_GET['status']) && $_GET['status']=="Regular") echo "selected"; ?>>
            Regular
        </option>

        <option value="Irregular"
        <?php if(isset($_GET['status']) && $_GET['status']=="Irregular") echo "selected"; ?>>
            Irregular
        </option>
    </select>

    <input type="submit" value="Filter">
<a href="student_list.php?course=<?php echo urlencode($course); ?>&year=<?php echo urlencode($year); ?>&section=<?php echo urlencode($section); ?>"
class="btn-back">
<i class="fas fa-undo"></i> Reset
</a>

    <br>

    <!-- Table -->
     <div class="table-container">
    <table>

<thead>
<tr>
    <th>Student No</th>
    <th>Full Name</th>
    <th>Status</th>
    <th class="no-print" style="width:150px;">Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr class="<?= $row['status'] === 'Withdraw' ? 'withdraw-row' : '' ?>">

    <td>
        <?php echo htmlspecialchars($row['student_no']); ?>
    </td>

    <td>
        <span class="student-name"
              onclick="showSubjects(<?= (int)$row['id'] ?>)">
            <?= htmlspecialchars($row['full_name']) ?>
        </span>
    </td>

    <td>

        <?php if((int)$row['deleted'] === 1){ ?>

            <span class="inactive-badge">
                INACTIVE
            </span>

        <?php }else{ ?>

            <span class="active-badge">
                <?= htmlspecialchars($row['status']) ?>
            </span>

        <?php } ?>

    </td>

    <td class="no-print action-buttons">

        <!-- EDIT -->
        <a href="edit_student.php?id=<?= (int)$row['id'] ?>"
           class="edit-btn"
           title="Edit Student">

            <i class="fas fa-pen"></i>

        </a>


        <?php if((int)$row['deleted'] === 0){ ?>

            <!-- DELETE -->
            <a href="delete_student.php?id=<?= (int)$row['id'] ?>"
               class="delete-btn"
               title="Delete Student"
               onclick="return confirm('Delete this student?');">

                <i class="fas fa-trash"></i>

            </a>


            <!-- DROP / SHIFT COURSE -->
            <a href="shift_course.php?id=<?= (int)$row['id'] ?>"
   class="drop-btn"
   title="Shift Course">

    <i class="fas fa-right-left"></i>

</a>


            <!-- WITHDRAW -->
 <a href="withdraw_students.php?id=<?= (int)$row['id'] ?>"
   class="withdraw-btn"
   title="Withdraw Student"
   onclick="return confirm('Withdraw this student?');">

    <i class="fas fa-user-minus"></i>

</a>


        <?php }else{ ?>

            <!-- RESTORE -->
            <a href="restore_student.php?id=<?= (int)$row['id'] ?>"
               class="inactive-action"
               title="Activate Student"
               onclick="return confirm('Activate this student again?');">

                <i class="fas fa-user-check"></i>

            </a>

        <?php } ?>

    </td>

</tr>

<?php } ?>

</tbody>

</table>
</div>


<!-- =========================
     STUDENT SUBJECTS MODAL
========================= -->

<div id="subjectModal" class="subject-modal">

    <div class="subject-modal-box">

        <div class="subject-modal-header">

            <h2>
                <i class="fas fa-book-open"></i>
                Student Subjects
            </h2>

            <button type="button"
                    class="close-modal"
                    onclick="closeSubjects()">

                &times;

            </button>

        </div>

        <div id="subjectContent">

            <div class="loading">
                Loading subjects...
            </div>

        </div>

    </div>

</div>


<script>

function showSubjects(studentId){

    const modal = document.getElementById("subjectModal");
    const content = document.getElementById("subjectContent");

    modal.style.display = "flex";

    content.innerHTML = `
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            Loading subjects...
        </div>
    `;

    fetch("get_student_subjects.php?id=" + studentId)

    .then(response => response.text())

    .then(data => {

        content.innerHTML = data;

    })

    .catch(error => {

        console.error(error);

        content.innerHTML = `
            <div class="no-subjects">
                Unable to load student subjects.
            </div>
        `;

    });

}


function closeSubjects(){

    document.getElementById("subjectModal").style.display = "none";

}


window.addEventListener("click", function(event){

    const modal = document.getElementById("subjectModal");

    if(event.target === modal){

        closeSubjects();

    }

});


document.addEventListener("keydown", function(event){

    if(event.key === "Escape"){

        closeSubjects();

    }

});

</script>
</div>
</div>
</body>
</html>