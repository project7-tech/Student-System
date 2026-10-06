<?php
require_once '../config/auth.php';
require_roles(['administrator', 'admin', 'lecturer'], '../login.php');
include '../config/db.php';
$message = '';

if(isset($_POST['save'])){

    $student_id = (int)$_POST['student_id'];
    $course_id = (int)$_POST['course_id'];
    $marks = (int)$_POST['marks'];

    if($marks >= 70){
        $grade = 'A';
        $gpa = 4.0;
    } elseif($marks >= 60){
        $grade = 'B';
        $gpa = 3.0;
    } elseif($marks >= 50){
        $grade = 'C';
        $gpa = 2.0;
    } else {
        $grade = 'D';
        $gpa = 1.0;
    }

    $query = "INSERT INTO results(student_id, course_id, marks, grade, gpa) VALUES(?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiisd", $student_id, $course_id, $marks, $grade, $gpa);
    mysqli_stmt_execute($stmt);

    $message = "Marks uploaded and GPA calculated.";
}

$students = mysqli_query($conn, "SELECT id, reg_no, fullname FROM students ORDER BY fullname");
$courses = mysqli_query($conn, "SELECT id, course_code, course_name FROM courses ORDER BY course_code");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Marks Entry</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page narrow">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Marks Entry Page</h2>
<?php if($message){ ?><div class="alert success"><?php echo e($message); ?></div><?php } ?>

<form method="POST">
    <label>Student</label>
    <select name="student_id" required>
        <option value="">Select student</option>
        <?php while($student = mysqli_fetch_assoc($students)){ ?>
        <option value="<?php echo e($student['id']); ?>"><?php echo e($student['fullname'] . ' - ' . $student['reg_no']); ?></option>
        <?php } ?>
    </select>

    <label>Course</label>
    <select name="course_id" required>
        <option value="">Select course</option>
        <?php while($course = mysqli_fetch_assoc($courses)){ ?>
        <option value="<?php echo e($course['id']); ?>"><?php echo e($course['course_code'] . ' - ' . $course['course_name']); ?></option>
        <?php } ?>
    </select>

    <label>Marks</label>
    <input type="number" name="marks" min="0" max="100" placeholder="Marks" required>

    <button type="submit" name="save">Upload Marks</button>
</form>
</section>
</main>
</body>
</html>
