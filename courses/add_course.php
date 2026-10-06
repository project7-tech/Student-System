<?php
require_once '../config/auth.php';
require_roles(['administrator', 'admin', 'lecturer'], '../login.php');
include '../config/db.php';
$message = '';

if(isset($_POST['save'])){

    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);

    $query = "INSERT INTO courses(course_name, course_code) VALUES(?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $course_name, $course_code);
    mysqli_stmt_execute($stmt);

    $message = "Course added successfully.";
}

$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_code");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Course Management Page</h2>
<?php if($message){ ?><div class="alert success"><?php echo e($message); ?></div><?php } ?>

<form method="POST">
    <label>Course Name</label>
    <input type="text" name="course_name" placeholder="Course Name" required>
    <label>Course Code</label>
    <input type="text" name="course_code" placeholder="Course Code" required>
    <button type="submit" name="save">Save Course</button>
</form>

<h3>Available Courses</h3>
<table>
    <tr><th>ID</th><th>Course Code</th><th>Course Name</th></tr>
    <?php while($course = mysqli_fetch_assoc($courses)){ ?>
    <tr>
        <td><?php echo e($course['id']); ?></td>
        <td><?php echo e($course['course_code']); ?></td>
        <td><?php echo e($course['course_name']); ?></td>
    </tr>
    <?php } ?>
</table>
</section>
</main>
</body>
</html>
