<?php
    if($_SESSION['roleDesc'] == 'user'){
        $_SESSION['message'] = 'Access restricted: You must be an administrator to access that page. Please log in to continue.';
        header("Location: /gravekeepercms/user/login.php");
        exit();
    }elseif($_SESSION['roleDesc'] == 'deactivated'){
        $_SESSION['message'] = 'Account deactivated: Your account is currently inactive. Please contact support for assistance.';
        session_destroy();
        header("Location: /gravekeepercms/user/login.php");
        exit();
    }elseif($_SESSION['roleDesc'] != 'admin'){
        $_SESSION['message'] = 'Access denied: You must be a registered user to access that page. Please log in or sign up to continue.';
        header("Location: /gravekeepercms/user/login.php");
        exit();
    }
?>