<?php
$sessionPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'sessions';

if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}

if (session_status() === PHP_SESSION_NONE) {
    session_save_path($sessionPath);
}
?>
