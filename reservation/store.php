<?php
session_start();
include("../includes/config.php");

if (!isset($_POST['plot_id']) || !isset($_POST['section_id']) || !isset($_POST['user_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='/gravekeepercms/section/';</script>";
    exit();
}

$plot_id = $_POST['plot_id'];
$section_id = $_POST['section_id'];
$user_id = $_POST['user_id'];

mysqli_begin_transaction($conn); // Start transaction

try {
    // Insert reservation record
    $reservation_sql = "INSERT INTO reservation (date_placed, date_reserved, stat_id, section_id, plot_id, user_id) 
                        VALUES (NOW(), NULL, 5, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $reservation_sql);
    mysqli_stmt_bind_param($stmt, "iii", $section_id, $plot_id, $user_id);
    mysqli_stmt_execute($stmt);

    // Update plot status to "Pending" (stat_id = 5)
    $update_plot_sql = "UPDATE plot SET stat_id = 5 WHERE plot_id = ?";
    $stmt = mysqli_prepare($conn, $update_plot_sql);
    mysqli_stmt_bind_param($stmt, "i", $plot_id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn); // Commit transaction

    echo "<script>alert('Reservation successful! Status: Pending'); window.location.href='/gravekeepercms/section/';</script>";
} catch (Exception $e) {
    mysqli_rollback($conn); // Rollback if something goes wrong
    echo "<script>alert('Reservation failed. Please try again.'); window.location.href='/gravekeepercms/section/';</script>";
}

mysqli_close($conn);
?>
