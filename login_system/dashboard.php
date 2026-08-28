<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}
/* =========================
   DASHBOARD STATISTICS
========================= */

/* TOTAL ACTIVE STUDENTS */
$totalStudents = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM students
        WHERE status IN ('Regular', 'Irregular')
        AND deleted = 0
        AND student_list_deleted = 0
    ")
)['total'];


/* REGULAR STUDENTS */
$regularStudents = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM students
        WHERE status = 'Regular'
        AND deleted = 0
        AND student_list_deleted = 0
    ")
)['total'];


/* IRREGULAR STUDENTS */
$irregularStudents = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM students
        WHERE status = 'Irregular'
        AND deleted = 0
        AND student_list_deleted = 0
    ")
)['total'];


$totalCourses = 9;


// BSCA
$bsca = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BSCA'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];


// BSCRIM
$bscrim = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BSCRIM'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];


// BSIT
$bsit = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BSIT'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];


// BSBA FM
$bsbafm = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BSBA FM'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];


// BSBA MM
$bsbamm = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BSBA MM'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];


// BSBA HRDM
$bsbahrdm = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BSBA HRDM'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];


// BSHM
$bshm = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BSHM'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];


// BSED
$bsed = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BSED'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];


// BEED
$beed = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM students
     WHERE course = 'BEED'
     AND status != 'Withdraw'
     AND deleted = 0
     AND student_list_deleted = 0"
))['total'];

?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Information System</title>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,Helvetica,sans-serif;
}

body{
    background:#e9e9e9;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.container{
    width:1200px;
    max-width:95%;
    height:1050px;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
}

.header{
    height:170px;
    background:linear-gradient(135deg,#0f172a,#1e3a8a,#2563eb);
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    overflow:hidden;
}
.header::before{
    content:"";
    position:absolute;
    width:350px;
    height:350px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
    top:-160px;
    left:-80px;
}

.header::after{
    content:"";
    position:absolute;
    width:300px;
    height:300px;
    background:rgba(255,255,255,.05);
    border-radius:50%;
    right:-100px;
    bottom:-160px;
}
.logo{
    width:130px;
    height:130px;
    position:absolute;
    left:25px;
    border-radius:50%;
    background:white;
    padding:8px;
    box-shadow:0 10px 25px rgba(0,0,0,.35);
}

.header h1{
    color:#fff;
    font-size:64px;
    font-weight:900;
    letter-spacing:3px;
    text-shadow:0 4px 10px rgba(0,0,0,.35);
}


.main{
    display:flex;
    height:900px;
}


.sidebar{
    width:230px;
    background:#2f343a;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.menu-btn{
    width:100%;
    background:#ffffff;
    color:#000;
    border:none;
    padding:15px 20px;
    font-size:18px;
    font-weight:bold;
    cursor:pointer;

    display:flex;
    justify-content:space-between;
    align-items:center;

    transition:.3s;
}

.menu-btn:hover{
    background:#2f80ed;
    color:white;
}
.submenu{
    display:none;
}

.submenu a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:15px 22px;
    color:white;
    text-decoration:none;
    transition:.3s;
    cursor:pointer;
}

.submenu a:hover{
    background:#2f80ed;
    padding-left:30px;
}

.submenu a:active{
    background:#1d5fd0;
}
.submenu{
    display:none;
}

.submenu a{
    display:flex;
    align-items:center;
    gap:12px;

    padding:15px 22px;
    color:white;
    text-decoration:none;
    font-size:16px;

    transition:.3s;
}

.submenu a:hover{
    background:#2f80ed;
    padding-left:30px;
}

.logout{
    width:80%;
    margin:20px auto;
    padding:12px;
    background:#dc3545;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    font-weight:bold;
}

.logout:hover{
    background:#b02a37;
}
/* CONTENT */

.content{
    flex:1;
    padding:25px;
    background:
        radial-gradient(circle at top left, rgba(255,255,255,.18) 0%, transparent 30%),
        radial-gradient(circle at bottom right, rgba(255,255,255,.12) 0%, transparent 25%),
        linear-gradient(135deg,#3b82f6,#5d7fd0,#4f79c8);
}
.cards{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}
.dashboard-wrapper{
    display:flex;
    gap:25px;
    align-items:flex-start;
}

.cards{
    flex:1;
}

.card{
    width:100%;
    height:140px;
    background:white;
    border-radius:15px;
    padding:15px;

    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;

    cursor:pointer;

    box-shadow:0 8px 18px rgba(0,0,0,.15);
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
    box-shadow:0 12px 25px rgba(0,0,0,.2);
}

.card i{
    font-size:26px;
}

.card h3{
    font-size:18px;
    margin:5px 0;
}
.card span{
    font-size:18px;
}

.card small{
    font-size:14px;
    color:#777;
}
.course-menu{
    position: relative;
    width: 100%;
}

.course-dropdown{
    display:none;
    position:absolute;
    top:125px;
    left:0;
    width:190px;
    background:#2f343a;
    border-radius:8px;
    overflow:hidden;
    z-index:1000;
    box-shadow:0 5px 15px rgba(0,0,0,.3);
}

.course-dropdown{
    display:none;
    position:absolute;
    top:110%;
    left:0;
    width:240px;
    background:#fff;
    border-radius:15px;
    overflow:hidden;
    z-index:1000;
    box-shadow:0 15px 35px rgba(0,0,0,.25);
    animation:fadeDown .25s ease;
}

.course-dropdown .title{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    text-align:center;
    padding:15px;
    font-size:18px;
    font-weight:bold;
    letter-spacing:1px;
}

.course-dropdown a{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 18px;
    color:#333;
    text-decoration:none;
    font-weight:bold;
    border-bottom:1px solid #eee;
    transition:.25s;
}

.course-dropdown a:last-child{
    border-bottom:none;
}

.course-dropdown a:hover{
    background:#2563eb;
    color:#fff;
    padding-left:28px;
}

@keyframes fadeDown{
    from{
        opacity:0;
        transform:translateY(-10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
.section-container{
    width:280px;
    min-width:280px;
    background:#fff;
    border-radius:15px;
    padding:15px;
    display:none;

    position:sticky;
    top:20px;

    margin:0;

    box-shadow:0 10px 25px rgba(0,0,0,.2);
}

.section-container h2{
    margin-bottom:10px;
    font-size:22px;
    font-weight:bold;
    color:#333;
}
.section-list{
    display:flex;
    flex-direction:column;
    gap:10px;
}
.section-card{
    background:#f8f9fa;
    border-radius:10px;
    padding:15px;
    text-align:center;
    font-size:18px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

.section-card:hover{
    background:#2563eb;
    color:white;
}
body{
    font-family: Arial;
    background:#e9e9e9;
    padding:20px;
}
.bsit{
    border-top:8px solid #2196F3;
}

.bscrim{
    border-top:8px solid #FFC107;
}

.bshm{
    border-top:8px solid #4CAF50;
}

.bsca{
    border-top:8px solid #7E57C2;
}

.bsba{
    border-top:8px solid #E53935;
}

.bsed{
    border-top:8px solid #FF7043;
}

.beed{
    border-top:8px solid #00ACC1;
}
.card.bsca{
    border-top:8px solid #7E57C2;
}

.card.bscrim{
    border-top:8px solid #FFC107;
}

.card.bsit{
    border-top:8px solid #2196F3;
}

.card.bsbafm,
.card.bsbamm,
.card.bsbahrdm{
    border-top:8px solid #E53935;
}

.card.bshm{
    border-top:8px solid #4CAF50;
}

.card.bsed{
    border-top:8px solid #FF7043;
}

.card.beed{
    border-top:8px solid #00ACC1;
}
.stats-container{
    background:#fff;
    border-radius:12px;
    padding:12px 18px;
    margin-bottom:15px;
}
.stats-title{
    margin-bottom:20px;
    color:#2f80ed;
    font-size:28px;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.stat-box i{
    font-size:28px;
}
.stat-box:last-child{
    border-right:none;
}

.stat-box i{
    font-size:40px;
    margin-bottom:10px;
}

.stat-box h2{
    font-size:26px;
    margin:5px 0;
}
.stat-box p{
    font-size:14px;
}

.blue{color:#2f80ed;}
.green{color:#28a745;}
.orange{color:#f39c12;}
.purple{color:#8e44ad;}
.title-area{
    text-align:center;
}

.title-area p{
    color:#dbeafe;
    margin-top:6px;
    font-size:18px;
    letter-spacing:2px;
}
.welcome-box{
    position:absolute;
    right:25px;
    top:25px;

    background:rgba(255,255,255,.18);

    backdrop-filter:blur(12px);

    color:white;

    padding:12px 18px;

    border-radius:30px;

    font-size:17px;

    display:flex;

    align-items:center;

    gap:10px;

    box-shadow:0 5px 20px rgba(0,0,0,.25);
}

.welcome-box i{
    font-size:24px;
}
body.dark-mode{
    background:#111827;
}

body.dark-mode .content{
    background:#1f2937;
}

body.dark-mode .stats-container,
body.dark-mode .card,
body.dark-mode .section-container{
    background:#374151;
    color:white;
}

body.dark-mode .card small,
body.dark-mode .card span,
body.dark-mode .stat-box p,
body.dark-mode .section-container h2{
    color:#d1d5db;
}

body.dark-mode .section-card{
    background:#4b5563;
    color:white;
}

body.dark-mode .section-card:hover{
    background:#2563eb;
}
/* =========================
   DARK MODE
========================= */

body.dark-mode{
    background:#111827;
    color:#f9fafb;
}

body.dark-mode .container{
    box-shadow:0 10px 30px rgba(0,0,0,.5);
}

body.dark-mode .sidebar{
    background:#111827;
}

body.dark-mode .menu-btn{
    background:#1f2937;
    color:#f9fafb;
}

body.dark-mode .menu-btn:hover{
    background:#2563eb;
    color:#fff;
}

body.dark-mode .content{
    background:
        radial-gradient(circle at top left, rgba(255,255,255,.05) 0%, transparent 30%),
        radial-gradient(circle at bottom right, rgba(255,255,255,.04) 0%, transparent 25%),
        linear-gradient(135deg,#111827,#1f2937,#374151);
}

body.dark-mode .stats-container{
    background:#1f2937;
    color:#fff;
}

body.dark-mode .stats-title{
    color:#60a5fa;
}

body.dark-mode .stat-box{
    color:#fff;
}

body.dark-mode .stat-box p{
    color:#d1d5db;
}

body.dark-mode .card{
    background:#374151;
    color:#fff;
}

body.dark-mode .card small{
    color:#d1d5db;
}

body.dark-mode .course-dropdown{
    background:#1f2937;
}

body.dark-mode .course-dropdown a{
    color:#f9fafb;
    border-bottom-color:#374151;
}

body.dark-mode .course-dropdown a:hover{
    background:#2563eb;
    color:#fff;
}

body.dark-mode .section-container{
    background:#374151;
    color:#fff;
}

body.dark-mode .section-container h2{
    color:#fff;
}

body.dark-mode .section-card{
    background:#4b5563;
    color:#fff;
}

body.dark-mode .section-card:hover{
    background:#2563eb;
    color:#fff;
}

body.dark-mode .dashboard-quote{
    background:rgba(0,0,0,.25);
}
.logout-link{
    color:#ff6b6b !important;
    font-weight:bold;
    border-top:1px solid rgba(255,255,255,.15);
    margin-top:10px;
}

.logout-link:hover{
    background:#dc3545 !important;
    color:#fff !important;
}
.dashboard-quote{
    margin-top:10px;
    padding:20px;
    text-align:center;
    background:rgba(255,255,255,.12);
    backdrop-filter:blur(10px);
    border-left:6px solid #ffd54f;
    border-radius:15px;
    color:#fff;
    box-shadow:0 8px 20px rgba(0,0,0,.2);
}

.dashboard-quote i{
    font-size:30px;
    color:#ffd54f;
    margin-bottom:10px;
}

.dashboard-quote p{
    font-size:20px;
    font-style:italic;
    line-height:1.6;
    margin:10px 0;
}

.dashboard-quote span{
    display:block;
    margin-top:10px;
    font-size:16px;
    color:#dbeafe;
    font-weight:bold;
}
.modal{
    display:none;
    position:fixed;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,.6);
    z-index:9999;
}

.modal-content{
    width:95%;
    height:95%;
    margin:1.5% auto;
    background:#fff;
    border-radius:15px;
    position:relative;
    overflow:hidden;
}

.modal iframe{
    width:100%;
    height:100%;
    border:none;
}

.close-modal{
    position:absolute;
    top:10px;
    right:15px;
    font-size:35px;
    cursor:pointer;
    color:red;
    z-index:10000;
}
.modal{
    position:fixed;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,.6);

    display:flex;
    justify-content:center;
    align-items:center;

    opacity:0;
    visibility:hidden;

    transition:opacity .35s ease,
               visibility .35s ease;

    z-index:9999;
}

.modal.show{
    opacity:1;
    visibility:visible;
}

.modal-content{
    width:95%;
    height:95%;
    margin:1.5% auto;
    background:#fff;
    border-radius:15px;
    overflow:hidden;

    transform:scale(0.8);
    opacity:0;

    transition:all .35s ease;
}

.modal.show .modal-content{
    transform:scale(1);
    opacity:1;
}

.modal iframe{
    width:100%;
    height:100%;
    border:none;
}

.close-modal{
    position:absolute;
    top:10px;
    right:20px;
    font-size:35px;
    color:red;
    cursor:pointer;
    z-index:10000;
}
</style>

</head>
<body>

<div class="container">

<div class="header">

<img src="logo.png" class="logo">

<div class="title-area">

    <h1>STUDENT MASTERLIST</h1>

    <p>Student Information Management System</p>

</div>

<div class="welcome-box">

    <i class="fas fa-user-circle"></i>

    Welcome,
    <strong><?php echo $_SESSION['username']; ?></strong>

</div>

</div>

<div class="main">

<div class="sidebar">

    <div>

        <button class="menu-btn" onclick="toggleMenu()">
            <span>
                <i class="fas fa-folder-open"></i> Other's
            </span>
            <i class="fas fa-chevron-down"></i>
        </button>

<div id="submenu" class="submenu">

    <a href="irregular_students.php">
        <i class="fas fa-users"></i>
        All Irregular Students
    </a>

    <a href="shift_course.php">
        <i class="fas fa-right-left"></i>
        Shift Course
    </a>

    <a href="withdraw_students.php">
        <i class="fas fa-user-minus"></i>
        Withdraw Students
    </a>
<a href="#" onclick="openAdmitModal(); return false;">
    <i class="fas fa-user-check"></i>
    Admit Student
</a>
<a href="all_student_information.php">
    <i class="fas fa-address-card"></i>
    Student Information
</a>
<a href="class_list.php">
    <i class="fas fa-list-check"></i>
    Class List
</a>
    <a href="#" onclick="toggleDarkMode(); return false;">
        <i class="fas fa-moon"></i>
        Dark Mode
    </a>

    <a href="#" onclick="logout(); return false;" class="logout-link">
        <i class="fas fa-right-from-bracket"></i>
        Logout
    </a>

</div>

    </div>


</div>

<div class="content">

<div class="stats-container">

    <h2 class="stats-title">
        <i class="fas fa-chart-column"></i> Dashboard Statistics
    </h2>

    <div class="stats-grid">

        <div class="stat-box">
            <i class="fas fa-users blue"></i>
            <p>Total Students</p>
            <h2 class="blue"><?php echo $totalStudents; ?></h2>
        </div>

        <div class="stat-box">
            <i class="fas fa-user-check green"></i>
            <p>Regular Students</p>
            <h2 class="green"><?php echo $regularStudents; ?></h2>
        </div>

        <div class="stat-box">
            <i class="fas fa-user-clock orange"></i>
            <p>Irregular Students</p>
            <h2 class="orange"><?php echo $irregularStudents; ?></h2>
        </div>

        <div class="stat-box">
            <i class="fas fa-graduation-cap purple"></i>
            <p>Courses</p>
            <h2 class="purple"><?php echo $totalCourses; ?></h2>
        </div>

    </div>

</div>

<div class="dashboard-wrapper">

    <div class="cards">

   <div class="course-menu">

<div class="card bsca" onclick="toggleCourse('bscaMenu')">

    <i class="fas fa-calculator"></i>

    <h3>BSCA</h3>

    <span><?php echo $bsca; ?></span>

    <small>Students</small>

</div>

<div id="bscaMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BSCA','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSCA','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSCA','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSCA','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>
<div class="course-menu">

 <div class="card bscrim" onclick="toggleCourse('bscrimMenu')">

    <i class="fas fa-shield-halved"></i>

    <h3>BSCRIM</h3>

    <span><?php echo $bscrim; ?></span>

    <small>Students</small>

</div>

   <div id="bscrimMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BSCRIM','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSCRIM','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSCRIM','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSCRIM','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>
<div class="course-menu">

  <div class="card bsit" onclick="toggleCourse('bsitMenu')">

<i class="fas fa-laptop-code"></i>

<h3>BSIT</h3>

<span><?php echo $bsit; ?></span>

<small>Students</small>

</div>

<div id="bsitMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BSIT','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSIT','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSIT','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSIT','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>
<div class="course-menu">

     <div class="card bsbafm" onclick="toggleCourse('bsbafmMenu')">

    <i class="fas fa-chart-line"></i>

    <h3>BSBA FM</h3>

    <span><?php echo $bsbafm; ?></span>

    <small>Students</small>

</div>

    <div id="bsbafmMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BSBA FM','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA FM','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA FM','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA FM','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>
<div class="course-menu">

  <div class="card bsbamm" onclick="toggleCourse('bsbammMenu')">

    <i class="fas fa-chart-pie"></i>

    <h3>BSBA MM</h3>

    <span><?php echo $bsbamm; ?></span>

    <small>Students</small>

</div>
<div id="bsbammMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BSBA MM','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA MM','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA MM','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA MM','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>
<div class="course-menu">

   <div class="card bsbahrdm" onclick="toggleCourse('bsbahrdmMenu')">

    <i class="fas fa-users"></i>

    <h3>BSBA HRDM</h3>

    <span><?php echo $bsbahrdm; ?></span>

    <small>Students</small>

</div>

    <div id="bsbahrdmMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BSBA HRDM','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA HRDM','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA HRDM','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSBA HRDM','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>
  <div class="course-menu">

  <div class="card bshm" onclick="toggleCourse('bshmMenu')">

    <i class="fas fa-utensils"></i>

    <h3>BSHM</h3>

    <span><?php echo $bshm; ?></span>

    <small>Students</small>

</div>
 <div id="bshmMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BSHM','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSHM','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSHM','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSHM','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>
 <div class="course-menu">

  <div class="card bsed" onclick="toggleCourse('bsedMenu')">

    <i class="fas fa-book-open"></i>

    <h3>BSED</h3>

    <span><?php echo $bsed; ?></span>

    <small>Students</small>

</div>

    <div id="bsedMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BSED','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSED','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSED','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BSED','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>
 <div class="course-menu">

<div class="card beed" onclick="toggleCourse('beedMenu')">

    <i class="fas fa-school"></i>

    <h3>BEED</h3>

    <span><?php echo $beed; ?></span>

    <small>Students</small>

</div>

   <div id="beedMenu" class="course-dropdown">

    <div class="title">
        🎓 YEAR LEVEL
    </div>

    <a href="#" onclick="showSections('BEED','1ST YEAR'); return false;">
        📘 1ST YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BEED','2ND YEAR'); return false;">
        📗 2ND YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BEED','3RD YEAR'); return false;">
        📙 3RD YEAR
        <span>›</span>
    </a>

    <a href="#" onclick="showSections('BEED','4TH YEAR'); return false;">
        📕 4TH YEAR
        <span>›</span>
    </a>

</div>

</div>

    </div>

<div id="sectionContainer" class="section-container">

    <h2 id="sectionTitle"></h2>

    <div id="sectionList" class="section-list"></div>

</div>

</div> <!-- dashboard-wrapper -->

<div class="dashboard-quote">
    <i class="fas fa-quote-left"></i>

    <p>
        "Strength does not come from winning. Your struggles develop your strengths. When you go through hardships and decide not to surrender, that is strength."
    </p>

    <span>— Tanjiro ^-^ X</span>
</div>

</div> <!-- content -->
</div> <!-- content -->

</div> <!-- main -->

</div> <!-- container -->

<script>
function toggleMenu() {

    var menu = document.getElementById("submenu");

    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }

}

function logout(){

if(confirm("Are you sure you want to logout?")){

window.location.href="logout.php";

}

}
function toggleCourse(id){

    document.querySelectorAll(".course-dropdown").forEach(function(menu){
        if(menu.id !== id){
            menu.style.display = "none";
        }
    });

    const menu = document.getElementById(id);

    if(menu.style.display === "block"){
        menu.style.display = "none";
    }else{
        menu.style.display = "block";
    }
}


// Ilagay ito pagkatapos ng toggleCourse()
const courseSections = {

    BSIT: {
        "1ST YEAR": ["A","B"],
        "2ND YEAR": ["A","B"],
        "3RD YEAR": ["A"],
        "4TH YEAR": ["A"]
    },

    BSCRIM: {
        "1ST YEAR": ["A","B"],
        "2ND YEAR": ["A","B"],
        "3RD YEAR": ["A","B"],
        "4TH YEAR": ["A","B"],
    },

    BEED: {
        "1ST YEAR": ["A"],
        "2ND YEAR": ["A"],
        "3RD YEAR": ["A"],
        "4TH YEAR": ["A"]
    },

    BSCA: {
        "1ST YEAR": ["A","B","C","D","E","F","G"],
        "2ND YEAR": ["A","B","C","D","E","F","G"],
        "3RD YEAR": ["A","B","C","D","E","F","G"],
        "4TH YEAR": ["A","B","C","D","E","F","G"],
    },
    BSED: {
        "1ST YEAR": ["A"],
        "2ND YEAR": ["A"],
        "3RD YEAR": ["A"],
        "4TH YEAR": ["A"]
    },
    "BSBA FM": {
        "1ST YEAR": ["A"],
        "2ND YEAR": ["A"],
        "3RD YEAR": ["A"],
        "4TH YEAR": ["A"]
    },
     "BSBA MM": {
        "1ST YEAR": ["A"],
        "2ND YEAR": ["A"],
        "3RD YEAR": ["A"],
        "4TH YEAR": ["A"]
    },
     "BSBA HRDM": {
        "1ST YEAR": ["A"],
        "2ND YEAR": ["A"],
        "3RD YEAR": ["A"],
        "4TH YEAR": ["A"]
    },
    BSHM: {
        "1ST YEAR": ["A","B","C","D"],
        "2ND YEAR": ["A","B","C","D"],
        "3RD YEAR": ["A","B"],
        "4TH YEAR": ["A","B"],
    },
};

function showSections(course, year){

    const title = document.getElementById("sectionTitle");
    const list = document.getElementById("sectionList");
    const container = document.getElementById("sectionContainer");

    title.innerHTML = course + " - " + year + " SECTIONS";
    list.innerHTML = "";

    // SAFETY CHECK (IMPORTANT FIX)
    if (!courseSections[course] || !courseSections[course][year]) {
        alert("No data found for " + course + " " + year);
        return;
    }

    const sections = courseSections[course][year];

    sections.forEach(function(section){
        list.innerHTML += `
            <div class="section-card"
                 onclick="openStudentList('${course}','${year}','${section}')">
                ${section}
            </div>
        `;
    });

    container.style.display = "block";
container.scrollIntoView({
    behavior: "smooth",
    block: "start"
});
}
function openStudentList(course, year, section){

    window.location.href =
        "student_list.php?course=" + encodeURIComponent(course) +
        "&year=" + encodeURIComponent(year) +
        "&section=" + encodeURIComponent(section);

}
document.querySelectorAll(".course-menu").forEach(function(course){

    course.addEventListener("mouseleave", function(){

        const menu = this.querySelector(".course-dropdown");
        menu.style.display = "none";

    });

});
function toggleDarkMode(){

    document.body.classList.toggle("dark-mode");

    if(document.body.classList.contains("dark-mode")){
        localStorage.setItem("darkMode", "enabled");
    }else{
        localStorage.setItem("darkMode", "disabled");
    }

}
// KEEP DARK MODE AFTER REFRESH
document.addEventListener("DOMContentLoaded", function(){

    if(localStorage.getItem("darkMode") === "enabled"){
        document.body.classList.add("dark-mode");
    }

});
function openAdmitModal(){

    document.getElementById("admitModal").classList.add("show");

}

function closeAdmitModal(){

    document.getElementById("admitModal").classList.remove("show");

}

window.addEventListener("click", function(e){

    let modal = document.getElementById("admitModal");

    if(e.target == modal){
        closeAdmitModal();
    }

});
</script>
<div id="admitModal" class="modal">

    <div class="modal-content">

        <span class="close-modal" onclick="closeAdmitModal()">
            &times;
        </span>

        <iframe src="admit_students.php"></iframe>

    </div>

</div>
</div>
</body>
</html> 