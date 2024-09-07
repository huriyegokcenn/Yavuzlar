<?php
include 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . ($_SESSION['role'] === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'));
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>