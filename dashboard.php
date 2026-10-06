<?php
require_once 'config/auth.php';
require_login('login.php');
$isLecturer = is_lecturer_user();
$isAdmin = is_admin_user();
$role = current_role();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<main class="page">
<div class="topbar">
    <div>
        <h1>STUDENT MANAGEMENT SYSTEM</h1>
        <p class="muted">Welcome, <?php echo e($_SESSION['username']); ?> (<?php echo e($_SESSION['role']); ?>)</p>
    </div>
    <a class="button secondary" href="logout.php">Logout</a>
</div>

<div class="menu">
    <?php if($isAdmin){ ?>
    <a href="users/manage_users.php">User Management</a>
    <a href="departments/manage_departments.php">Department Management</a>
    <?php } ?>
    <?php if($isLecturer){ ?>
    <a href="students/add_student.php">Student Registration Form</a>
    <a href="students/view_students.php">Students Page</a>
    <a href="courses/add_course.php">Course Management Page</a>
    <a href="results/add_results.php">Marks Entry Page</a>
    <?php } ?>
    <a href="results/gpa_analysis.php">GPA Analysis Page</a>
    <a href="recommendations/recommendations.php">Recommendations</a>
    <a href="reports/reports.php">Reports</a>
    <?php if($isLecturer){ ?>
    <a href="reports/database_tables.php">Database Tables</a>
    <?php } ?>
    <a href="reports/performance_charts.php">Performance Charts</a>
</div>
</main>

</body>
</html>
