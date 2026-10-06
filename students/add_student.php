<?php
require_once '../config/auth.php';
require_roles(['administrator', 'admin', 'lecturer'], '../login.php');
include '../config/db.php';
$message = '';
$departments = mysqli_query($conn, "SELECT department_name FROM departments ORDER BY department_name");

if(isset($_POST['save'])){

    $reg_no = trim($_POST['reg_no']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);

    $query = "INSERT INTO students(reg_no, fullname, email, department) VALUES(?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $reg_no, $fullname, $email, $department);
    mysqli_stmt_execute($stmt);
    $student_id = mysqli_insert_id($conn);

    $login_username = $reg_no;
    $login_password = $reg_no . "123";
    $role = "Student";

    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($check, "s", $login_username);
    mysqli_stmt_execute($check);
    $existing = mysqli_stmt_get_result($check);

    if(mysqli_num_rows($existing) === 0){
        $userQuery = "INSERT INTO users(username, password, role, student_id) VALUES(?, ?, ?, ?)";
        $userStmt = mysqli_prepare($conn, $userQuery);
        mysqli_stmt_bind_param($userStmt, "sssi", $login_username, $login_password, $role, $student_id);
        mysqli_stmt_execute($userStmt);
        $message = "Student added successfully. Login username: $login_username, password: $login_password";
    } else {
        $message = "Student added successfully. A login account with username $login_username already exists.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<main class="page narrow">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Student Registration Form</h2>
<?php if($message){ ?><div class="alert success"><?php echo e($message); ?></div><?php } ?>

<form method="POST">
    <label>Registration Number</label>
    <input type="text" name="reg_no" placeholder="Registration Number" required>
    <label>Full Name</label>
    <input type="text" name="fullname" placeholder="Full Name" required>
    <label>Email</label>
    <input type="email" name="email" placeholder="Email" required>
    <label>Department</label>
    <select name="department" required>
        <option value="">Select department</option>
        <?php while($department = mysqli_fetch_assoc($departments)){ ?>
        <option value="<?php echo e($department['department_name']); ?>"><?php echo e($department['department_name']); ?></option>
        <?php } ?>
    </select>

    <button type="submit" name="save">Save Student</button>
</form>
</section>
</main>

</body>
</html>
