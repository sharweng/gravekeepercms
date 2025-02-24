<?php
    session_start();
    include("../includes/config.php");
    
    $mode = $_GET['mode'];
    if($mode != 'user')
        include('../includes/notAdminRedirect.php');

    $rev_id = $_POST['rev_id'];
    $sql = "DELETE FROM review WHERE rev_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $rev_id);
    $delete_result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($delete_result) {
        if($mode == 'user')
            header("Location: /gravekeepercms/review/index.php?mode=user");
        else
            header("Location: /gravekeepercms/review/");
    } 
?>