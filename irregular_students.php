<?php
session_start();
include 'db.php';

$result = mysqli_query($conn,"
SELECT *
FROM students
WHERE status='Irregular'
ORDER BY course, year_level, section, full_name
");

$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>

<title>All Irregular Students</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<div class="header">

<h1>
<i class="fas fa-users"></i>
All Irregular Students
</h1>

<div class="count">

Total :
<b><?php echo $total; ?></b>

</div>

</div>


<div class="toolbar">

<input
type="text"
id="search"
class="search"
placeholder="Search student..."
onkeyup="searchStudent()">

<div class="buttons">

<a href="dashboard.php" class="back">

<i class="fas fa-arrow-left"></i>

Dashboard

</a>

<button class="print" onclick="window.print()">

<i class="fas fa-print"></i>

Print

</button>

</div>

</div>


<div class="table-container">

<table id="studentTable">

<thead>

<tr>
    <th>Student No</th>
    <th>Full Name</th>
    <th>Course</th>
    <th>Year</th>
    <th>Section</th>
    <th>Status</th>
    <th class="no-print">Action</th>
</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['student_no']; ?></td>

<td><?php echo $row['full_name']; ?></td>

<td><?php echo $row['course']; ?></td>

<td><?php echo $row['year_level']; ?></td>

<td><?php echo $row['section']; ?></td>

<td>
    <span class="status">
        <?php echo ucfirst($row['status']); ?>
    </span>
</td>

<td class="no-print action-buttons">

    <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="edit-btn">
        <i class="fas fa-pen"></i> Edit
    </a>

    <a href="delete_student.php?id=<?php echo $row['id']; ?>"
       class="delete-btn"
       onclick="return confirm('Delete this student?');">
        <i class="fas fa-trash"></i> Delete
    </a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<script>

function searchStudent(){

let input=document.getElementById("search").value.toLowerCase();

let rows=document.querySelectorAll("#studentTable tbody tr");

rows.forEach(function(row){

let text=row.innerText.toLowerCase();

row.style.display=text.includes(input)?"":"none";

});

}

</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>