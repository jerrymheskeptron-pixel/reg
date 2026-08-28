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



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<div class="header">

<img src="logo.png" class="dashboard-logo">

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 