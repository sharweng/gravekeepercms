<?php
session_start();
include("../includes/config.php");

if (!isset($_POST['plot_id']) || !isset($_POST['section_id']) || !isset($_POST['user_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='/gravekeepercms/section/;</script>";
    exit();
}

$plot_id = $_POST['plot_id'];
$section_id = $_POST['section_id'];
$user_id = $_POST['user_id'];

// Set status to "Pending" (stat_id = 5)
$reservation_sql = "INSERT INTO reservation (date_placed, date_reserved, stat_id, section_id, plot_id, user_id) 
                    VALUES (NOW(), NULL, 5, '$section_id', '$plot_id', '$user_id')";

if (mysqli_query($conn, $reservation_sql)) {
    echo "<script>alert('Reservation successful! Status: Pending'); window.location.href='/gravekeepercms/section/';</script>";
} else {
    echo "<script>alert('Reservation failed. Please try again.'); window.location.href='/gravekeepercms/section/';</script>";
}
?>
