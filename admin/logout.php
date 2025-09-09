<?php
session_start();

if (isset($_SESSION['auth'])) {
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    session_destroy();
    session_start();
    $_SESSION['message'] = "Logged Out Successfully";
}

header('Location: ../login.php');
exit();