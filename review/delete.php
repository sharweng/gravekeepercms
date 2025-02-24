<?php
    session_start();
    include("../includes/config.php");
    include('../includes/notAdminRedirect.php');

    $rev_id = $_POST['rev_id'];
    $sql = "DELETE FROM review WHERE rev_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $rev_id);
    $delete_result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($delete_result) {
        header("Location: /gravekeepercms/review/");
    } 
?>