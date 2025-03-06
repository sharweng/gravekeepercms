<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    $check_sql = "SELECT plot_id FROM reservation WHERE reserv_id = ? AND user_id = ? AND stat_id = (SELECT stat_id FROM status WHERE description = 'pending')";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "ii", $reserv_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $plot_id = $row['plot_id']; // Get the plot_id from the reservation
        $sel_dec = "SELECT d.dec_id FROM deceased d INNER JOIN burial b ON d.dec_id = b.dec_id
            INNER JOIN reservation r ON r.plot_id = b.plot_id WHERE b.plot_id = {$plot_id}";
        echo $sel_dec;
        $select_res = mysqli_query($conn, $sel_dec);
        $select_row = mysqli_fetch_assoc($select_res);
        $dec_id = $select_row['dec_id'];

        mysqli_begin_transaction($conn); // Start transaction

        try {

            $del_dec = "DELETE FROM deceased WHERE dec_id = ?";
            $stmt_del_dec = mysqli_prepare($conn, $del_dec);
            mysqli_stmt_bind_param($stmt_del_dec, "i", $dec_id);
            mysqli_stmt_execute($stmt_del_dec);
            
            // Update the reservation status to "canceled"
            $update_reservation_sql = "UPDATE reservation SET stat_id = (SELECT stat_id FROM status WHERE description = 'canceled') WHERE reserv_id = ?";
            $stmt_update_reservation = mysqli_prepare($conn, $update_reservation_sql);
            mysqli_stmt_bind_param($stmt_update_reservation, "i", $reserv_id);
            mysqli_stmt_execute($stmt_update_reservation);

            // Revert the plot status to "Available" (stat_id = 3)
            $update_plot_sql = "UPDATE plot SET stat_id = 3 WHERE plot_id = ?";
            $stmt_update_plot = mysqli_prepare($conn, $update_plot_sql);
            mysqli_stmt_bind_param($stmt_update_plot, "i", $plot_id);
            mysqli_stmt_execute($stmt_update_plot);

            mysqli_commit($conn); // Commit transaction

            $_SESSION['success'] = "Reservation canceled successfully, and plot is now available.";
        } catch (Exception $e) {
            mysqli_rollback($conn); // Rollback changes if something fails
            $_SESSION['message'] = "Failed to cancel reservation. Please try again.";
        }
    } else {
        $_SESSION['message'] = "Invalid reservation or already processed.";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

header("Location: reservation.php"); // Redirect to user's reservations page
exit();
?>
