<?php
require_once '../config/auth.php';
require_login('../login.php');
include '../config/db.php';

$query = "SELECT students.fullname, courses.course_name, results.marks,
results.grade, results.gpa
FROM results
JOIN students ON results.student_id = students.id
JOIN courses ON results.course_id = courses.id" . student_scope_sql();

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Performance Reports</h2>

<table>
<tr>
    <th>Student</th>
    <th>Course</th>
    <th>Marks</th>
    <th>Grade</th>
    <th>GPA</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?php echo e($row['fullname']); ?></td>
    <td><?php echo e($row['course_name']); ?></td>
    <td><?php echo e($row['marks']); ?></td>
    <td><?php echo e($row['grade']); ?></td>
    <td><?php echo e($row['gpa']); ?></td>
</tr>
<?php } ?>

</table>
</section>
</main>
</body>
</html>
