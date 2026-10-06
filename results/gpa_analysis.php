<?php
require_once '../config/auth.php';
require_login('../login.php');
include '../config/db.php';

$where = student_scope_sql();
$query = "SELECT students.id, students.reg_no, students.fullname,
                 COUNT(results.id) AS total_courses,
                 AVG(results.marks) AS average_marks,
                 AVG(results.gpa) AS average_gpa
          FROM students
          LEFT JOIN results ON students.id = results.student_id
          $where
          GROUP BY students.id, students.reg_no, students.fullname
          ORDER BY average_gpa DESC, students.fullname";
$result = mysqli_query($conn, $query);

$gpaScale = [
    ['A+', '97-100', '4.0'],
    ['A', '93-96', '4.0'],
    ['A-', '90-92', '3.7'],
    ['B+', '87-89', '3.3'],
    ['B', '83-86', '3.0'],
    ['B-', '80-82', '2.7'],
    ['C+', '77-79', '2.3'],
    ['C', '73-76', '2.0'],
    ['C-', '70-72', '1.7'],
    ['D+', '67-69', '1.3'],
    ['D', '63-66', '1.0'],
    ['D-', '60-62', '0.7'],
    ['F', 'Below 60', '0.0'],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>GPA Analysis</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>GPA Analysis Page</h2>

<div class="gpa-table-card">
    <h3>GPA Table</h3>
    <table class="gpa-scale-table">
        <tr>
            <th>Letter Grade</th>
            <th>Percent Grade</th>
            <th>4.0 Scale GPA</th>
        </tr>
        <?php foreach($gpaScale as $grade){ ?>
        <tr>
            <td><?php echo e($grade[0]); ?></td>
            <td><?php echo e($grade[1]); ?></td>
            <td><?php echo e($grade[2]); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<h3>Student GPA Summary</h3>
<table>
    <tr>
        <th>Reg No</th>
        <th>Student</th>
        <th>Courses Taken</th>
        <th>Average Marks</th>
        <th>Average GPA</th>
        <th>Performance</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)){
        $avgGpa = $row['average_gpa'] === null ? 0 : (float)$row['average_gpa'];
        $status = $avgGpa >= 3.5 ? 'Excellent' : ($avgGpa >= 2.5 ? 'Good' : ($avgGpa > 0 ? 'Needs Improvement' : 'No Marks Yet'));
    ?>
    <tr>
        <td><?php echo e($row['reg_no']); ?></td>
        <td><?php echo e($row['fullname']); ?></td>
        <td><?php echo e($row['total_courses']); ?></td>
        <td><?php echo $row['average_marks'] === null ? '0.00' : e(number_format($row['average_marks'], 2)); ?></td>
        <td><?php echo e(number_format($avgGpa, 2)); ?></td>
        <td><span class="badge"><?php echo e($status); ?></span></td>
    </tr>
    <?php } ?>
</table>
</section>
</main>
</body>
</html>
