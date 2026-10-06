<?php
require_once __DIR__ . '/session.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function current_role() {
    return strtolower($_SESSION['role'] ?? '');
}

function is_lecturer_user() {
    $role = current_role();
    return in_array($role, ['administrator', 'admin', 'lecturer'], true);
}

function is_admin_user() {
    $role = current_role();
    return in_array($role, ['administrator', 'admin'], true);
}

function require_login($redirect = null) {
    if (!isset($_SESSION['username'])) {
        $redirect = $redirect ?: auth_login_path();
        header("Location: $redirect");
        exit;
    }
}

function require_roles($roles, $redirect = null) {
    require_login($redirect);
    $allowed = array_map('strtolower', $roles);
    if (!in_array(current_role(), $allowed, true)) {
        header("Location: " . auth_dashboard_path());
        exit;
    }
}

function auth_login_path() {
    return strpos($_SERVER['SCRIPT_NAME'], '/config/') !== false ? '../login.php' : '/Student System/login.php';
}

function auth_dashboard_path() {
    return '/Student System/dashboard.php';
}

function student_scope_sql() {
    if (current_role() === 'student' && !empty($_SESSION['student_id'])) {
        return " WHERE students.id = " . (int)$_SESSION['student_id'];
    }
    return "";
}
?>
