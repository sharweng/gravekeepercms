<?php
    session_start();
    include('../includes/notUserRedirect.php');
    session_destroy();
    header("Location: /gravekeepercms/");
    exit;
?>