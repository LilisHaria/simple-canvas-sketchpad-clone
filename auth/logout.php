
<?php
session_start();

// Determine redirect based on user type
$redirect_url = '../index.php';
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'admin') {
        $redirect_url = '../auth/login_admin.php';
    } else {
        $redirect_url = '../index.php';
    }
}

session_destroy();
header('Location: ' . $redirect_url);
exit;
?>
