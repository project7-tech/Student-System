<?php
require_once '../config/auth.php';
require_roles(['administrator', 'admin', 'lecturer'], '../login.php');
include '../config/db.php';

$tables = ['users', 'students', 'courses', 'results'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Tables</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Database Tables</h2>

<?php foreach($tables as $table){
    $result = mysqli_query($conn, "DESCRIBE `$table`");
?>
    <h3><?php echo e(ucfirst($table)); ?> Table</h3>
    <table>
        <tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>
        <?php while($row = mysqli_fetch_assoc($result)){ ?>
        <tr>
            <td><?php echo e($row['Field']); ?></td>
            <td><?php echo e($row['Type']); ?></td>
            <td><?php echo e($row['Null']); ?></td>
            <td><?php echo e($row['Key']); ?></td>
            <td><?php echo e($row['Default']); ?></td>
        </tr>
        <?php } ?>
    </table>
<?php } ?>
</section>
</main>
</body>
</html>
