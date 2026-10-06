<?php
require_once 'config/session.php';
session_start();
$error = '';

if(isset($_POST['login'])){
    include 'config/db.php';

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];
        $_SESSION['student_id'] = $user['student_id'] ?? null;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">

<div class="login-box">
    <h2>STUDENT MANAGEMENT SYSTEM</h2>

    <?php if($error){ ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
