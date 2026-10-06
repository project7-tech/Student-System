<?php
require_once '../config/auth.php';
require_roles(['administrator', 'admin'], '../login.php');
include '../config/db.php';
$message = '';

if(isset($_POST['save'])){
    $department_name = trim($_POST['department_name']);
    $department_code = strtoupper(trim($_POST['department_code']));

    $query = "INSERT INTO departments(department_name, department_code) VALUES(?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $department_name, $department_code);

    if(mysqli_stmt_execute($stmt)){
        $message = "Department added successfully.";
    } else {
        $message = "Department could not be added. The code may already exist.";
    }
}

$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY department_name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Department Management</h2>
<?php if($message){ ?><div class="alert success"><?php echo e($message); ?></div><?php } ?>

<form method="POST">
    <label>Department Name</label>
    <input type="text" name="department_name" placeholder="Department Name" required>
    <label>Department Code</label>
    <input type="text" name="department_code" placeholder="Department Code" required>
    <button type="submit" name="save">Save Department</button>
</form>

<h3>Available Departments</h3>
<table>
    <tr><th>ID</th><th>Department Code</th><th>Department Name</th></tr>
    <?php while($department = mysqli_fetch_assoc($departments)){ ?>
    <tr>
        <td><?php echo e($department['id']); ?></td>
        <td><?php echo e($department['department_code']); ?></td>
        <td><?php echo e($department['department_name']); ?></td>
    </tr>
    <?php } ?>
</table>
</section>
</main>
</body>
</html>
