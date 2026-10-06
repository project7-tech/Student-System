<?php
require_once 'config/session.php';
session_start();
session_destroy();
header("Location: login.php");
exit;
?>
