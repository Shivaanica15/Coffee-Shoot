<?php
session_start();

// Logout should be used by logged-in users; if not logged in, just go to login.
if (!isset($_SESSION['email'])) {
    header('Location: ../login_signup.php');
    exit();
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();
header('Location: ../login_signup.php');
exit();
