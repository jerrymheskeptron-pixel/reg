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

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:#eef2f7;
padding:25px;
}

.container{
max-width:1400px;
margin:auto;
background:white;
border-radius:15px;
overflow:hidden;
box-shadow:0 10px 25px rgba(0,0,0,.2);
}

/* HEADER */

.header{
background:linear-gradient(135deg,#1d4ed8,#2563eb);
padding:25px;
color:white;
display:flex;
justify-content:space-between;
align-items:center;
}

.header h1{
font-size:34px;
}

.count{
font-size:18px;
background:rgba(255,255,255,.2);
padding:10px 18px;
border-radius:30px;
}

/* TOP BAR */

.toolbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:20px;
background:#f8fafc;
}

.search{
padding:10px 15px;
width:320px;
border:1px solid #ccc;
border-radius:8px;
font-size:15px;
}

.buttons a,
.buttons button{

padding:10px 18px;
border:none;
border-radius:8px;
cursor:pointer;
text-decoration:none;
font-weight:bold;
margin-left:10px;
}

.back{
background:#6c757d;
color:white;
}

.print{
background:#0d6efd;
color:white;
}

/* TABLE */

.table-container{
max-height:700px;
overflow:auto;
}

table{
width:100%;
border-collapse:collapse;
}

thead{
position:sticky;
top:0;
background:#2563eb;
color:white;
}

th{
padding:15px;
font-size:15px;
}

td{
padding:12px;
border-bottom:1px solid #eee;
}

tbody tr:nth-child(even){
background:#f8fafc;
}

tbody tr:hover{
background:#dbeafe;
}

.status{
background:#dc3545;
color:white;
padding:5px 12px;
border-radius:20px;
font-size:13px;
font-weight:bold;
display:inline-block;
}
@media print{

    body{
        background:white;
        padding:0;
    }

    .toolbar{
        display:none;
    }

    .header{
        background:white !important;
        color:black !important;
        box-shadow:none;
    }

    .header h1{
        color:black;
    }

    .container{
        box-shadow:none;
        border:none;
        width:100%;
    }

    .table-container{
        max-height:none;
        overflow:visible;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    th{
        background:#ddd !important;
        color:black !important;
        border:1px solid black;
    }

    td{
        border:1px solid black;
    }

    .status{
        background:none !important;
        color:black !important;
        padding:0;
    }

}
.action-buttons{
    white-space:nowrap;
}

.edit-btn{
    background:#0d6efd;
    color:#fff;
    padding:6px 12px;
    border-radius:5px;
    text-decoration:none;
    margin-right:5px;
    display:inline-block;
}

.edit-btn:hover{
    background:#0b5ed7;
}

.delete-btn{
    background:#dc3545;
    color:#fff;
    padding:6px 12px;
    border-radius:5px;
    text-decoration:none;
    display:inline-block;
}

.delete-btn:hover{
    background:#bb2d3b;
}

@media print{
    .no-print{
        display:none !important;
    }
}
</style>

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

</body>
</html>