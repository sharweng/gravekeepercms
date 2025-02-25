<?php
session_start();
include("includes/config.php");

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<script>alert('You must be logged in to cancel a reservation.'); window.location.href='login.php';</script>";
    exit();
}

// Check if the reservation ID is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserv_id'])) {
    $reserv_id = intval($_POST['reserv_id']);

    // Verify that the reservation belongs to the logged-in user and is pending
    $check_sql = "SELECT * FROM reservation WHERE reserv_id = ? AND user_id = ? AND stat_id = (SELECT stat_id FROM status WHERE description = 'pending')";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "ii", $reserv_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Update the reservation status to "canceled"
        $update_sql = "UPDATE reservation SET stat_id = (SELECT stat_id FROM status WHERE description = 'canceled') WHERE reserv_id = ?";
        $stmt_update = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt_update, "i", $reserv_id);
        mysqli_stmt_execute($stmt_update);

        $_SESSION['success'] = "Reservation canceled successfully.";
    } else {
        $_SESSION['error'] = "Invalid reservation or already processed.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: reservation.php"); // Redirect to user's reservations page
exit();
?>
