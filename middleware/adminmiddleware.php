<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION['auth']) || ($_SESSION['auth_user']['role'] != 'admin')) {
        $_SESSION['message'] = 'Login to access this page';
        if(isset($_SESSION['auth'])) {
            unset($_SESSION['auth']);
            unset($_SESSION['auth_user']);
        }
        header('Location: /AutoMobile Project/login.php');
        exit();
    }