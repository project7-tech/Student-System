<?php
require_once '../config/auth.php';
require_roles(['administrator', 'admin'], '../login.php');
include '../config/db.php';
$message = '';

if(isset($_POST['save'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    $student_id = $_POST['student_id'] === '' ? null : (int)$_POST['student_id'];

    $query = "INSERT INTO users(username, password, role, student_id) VALUES(?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $password, $role, $student_id);

    if(mysqli_stmt_execute($stmt)){
        $message = "User added successfully.";
    } else {
        $message = "User could not be added.";
    }
}

$users = mysqli_query($conn, "SELECT users.id, users.username, users.role, students.fullname
                             FROM users
                             LEFT JOIN students ON users.student_id = students.id
                             ORDER BY users.role, users.username");
$students = mysqli_query($conn, "SELECT id, reg_no, fullname FROM students ORDER BY fullname");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>User Management</h2>
<?php if($message){ ?><div class="alert success"><?php echo e($message); ?></div><?php } ?>

<form method="POST">
    <label>Username</label>
    <input type="text" name="username" placeholder="Username" required>
    <label>Password</label>
    <input type="text" name="password" placeholder="Password" required>
    <label>Role</label>
    <select name="role" required>
        <option value="Administrator">Administrator</option>
        <option value="Lecturer">Lecturer</option>
        <option value="Student">Student</option>
    </select>
    <label>Linked Student Account</label>
    <select name="student_id">
        <option value="">Not a student user</option>
        <?php while($student = mysqli_fetch_assoc($students)){ ?>
        <option value="<?php echo e($student['id']); ?>"><?php echo e($student['fullname'] . ' - ' . $student['reg_no']); ?></option>
        <?php } ?>
    </select>
    <button type="submit" name="save">Save User</button>
</form>

<h3>System Users</h3>
<table>
    <tr><th>ID</th><th>Username</th><th>Role</th><th>Linked Student</th></tr>
    <?php while($user = mysqli_fetch_assoc($users)){ ?>
    <tr>
        <td><?php echo e($user['id']); ?></td>
        <td><?php echo e($user['username']); ?></td>
        <td><?php echo e($user['role']); ?></td>
        <td><?php echo e($user['fullname'] ?? ''); ?></td>
    </tr>
    <?php } ?>
</table>
</section>
</main>
</body>
</html>
