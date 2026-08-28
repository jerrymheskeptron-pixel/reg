
<?php

$course = $_GET['course'];
$year = $_GET['year'];
$section = $_GET['section'];

?>

<h1><?php echo $course; ?></h1>

<h2><?php echo $year; ?></h2>

<h3>Section <?php echo $section; ?></h3>

<table border="1" cellpadding="10">
<tr>
    <th>Student No.</th>
    <th>Name</th>
</tr>

<tr>
    <td>20260001</td>
    <td>Juan Dela Cruz</td>
</tr>

<tr>
    <td>20260002</td>
    <td>Maria Santos</td>
</tr>

</table>