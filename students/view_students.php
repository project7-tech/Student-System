<?php
require_once '../config/auth.php';
require_roles(['administrator', 'admin', 'lecturer'], '../login.php');
include '../config/db.php';

$query = "SELECT * FROM students";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<main class="page">
<a class="back-link" href="../dashboard.php">&larr; Dashboard</a>
<section class="panel">
<h2>Student List</h2>

<table>
<tr>
    <th>ID</th>
    <th>Reg No</th>
    <th>Name</th>
    <th>Email</th>
    <th>Department</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['reg_no']; ?></td>
    <td><?php echo $row['fullname']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['department']; ?></td>
</tr>
<?php } ?>

</table>
</section>
</main>

</body>
</html>
