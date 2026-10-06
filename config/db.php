<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "student_system";
$port = 3306;

$conn = @mysqli_connect($host, $user, $password, $database, $port);

if(!$conn){
    die(
        "<div style='font-family:Arial,sans-serif;max-width:720px;margin:60px auto;padding:24px;border:1px solid #d9e2ec;border-radius:8px;background:#fff;line-height:1.6'>" .
        "<h2 style='margin-top:0;color:#102a43'>Database connection failed</h2>" .
        "<p>The system could not connect to MySQL at <strong>127.0.0.1:3306</strong>.</p>" .
        "<p>Open the XAMPP Control Panel, start <strong>MySQL</strong>, then make sure the <strong>student_system</strong> database has been imported from <code>database/student_system.sql</code>.</p>" .
        "<p><strong>Technical message:</strong> " . htmlspecialchars(mysqli_connect_error(), ENT_QUOTES, 'UTF-8') . "</p>" .
        "</div>"
    );
}

$studentIdColumn = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'student_id'");
if ($studentIdColumn && mysqli_num_rows($studentIdColumn) === 0) {
    mysqli_query($conn, "ALTER TABLE users ADD student_id INT NULL");
}

$studentAccount = mysqli_query($conn, "SELECT id FROM students WHERE reg_no = 'STU001' LIMIT 1");
if ($studentAccount && mysqli_num_rows($studentAccount) === 0) {
    mysqli_query($conn, "INSERT INTO students(reg_no, fullname, email, department)
        VALUES('STU001', 'Student User', 'student@example.com', 'Computer Science')");
}

$defaultStudentId = null;
$studentAccount = mysqli_query($conn, "SELECT id FROM students WHERE reg_no = 'STU001' LIMIT 1");
if ($studentAccount && mysqli_num_rows($studentAccount) > 0) {
    $defaultStudentId = (int)mysqli_fetch_assoc($studentAccount)['id'];
}

$defaultUsers = [
    ['admin', 'admin123', 'Administrator', null],
    ['lecturer', 'lecturer123', 'Lecturer', null],
    ['student', 'student123', 'Student', $defaultStudentId],
];

foreach ($defaultUsers as $defaultUser) {
    $checkUser = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($checkUser, "s", $defaultUser[0]);
    mysqli_stmt_execute($checkUser);
    $userExists = mysqli_stmt_get_result($checkUser);

    if ($userExists && mysqli_num_rows($userExists) === 0) {
        $insertUser = mysqli_prepare($conn, "INSERT INTO users(username, password, role, student_id) VALUES(?, ?, ?, ?)");
        mysqli_stmt_bind_param($insertUser, "sssi", $defaultUser[0], $defaultUser[1], $defaultUser[2], $defaultUser[3]);
        mysqli_stmt_execute($insertUser);
    }
}

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(150) NOT NULL,
    department_code VARCHAR(50) NOT NULL UNIQUE
)");

$departmentCount = mysqli_query($conn, "SELECT COUNT(*) AS total FROM departments");
if ($departmentCount) {
    $departmentTotal = mysqli_fetch_assoc($departmentCount)['total'] ?? 0;
    if ((int)$departmentTotal === 0) {
        mysqli_query($conn, "INSERT INTO departments(department_name, department_code) VALUES
            ('Computer Science', 'CS'),
            ('Information Technology', 'IT'),
            ('Business Administration', 'BA')");
    }
}
?>
