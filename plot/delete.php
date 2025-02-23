<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

    session_start();
    include("../includes/config.php");

    $sec_id = $_SESSION['sec_id'];

    $plot_id = $_POST['plot_id'];
    $sql = "DELETE FROM plot WHERE plot_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $plot_id);
    $delete_result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $num_sql = "SELECT num_plot FROM section WHERE section_id = {$_SESSION['sec_id']}";
    $num_res = mysqli_query($conn, $num_sql);
    $num_row = mysqli_fetch_assoc($num_res);

    $num = $num_row['num_plot']-1;

    $sql = "UPDATE section SET num_plot = ".$num." WHERE section_id = ?";
    echo $sql;
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['sec_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($delete_result) {
        header("Location: /gravekeepercms/section/view_plot.php?id=".$_SESSION['sec_id']);
    } 
?>