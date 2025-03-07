<?php
session_start();
include("../includes/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserv_id'])) {
    $reserv_id = intval($_POST['reserv_id']);

    // Fetch the current reservation details
    $reserv_sql = "SELECT plot_id FROM reservation WHERE reserv_id = ?";
    $stmt = mysqli_prepare($conn, $reserv_sql);
    mysqli_stmt_bind_param($stmt, "i", $reserv_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $plot_id = $row['plot_id'];

        mysqli_begin_transaction($conn); // Start transaction

        try {
            if (isset($_POST['confirm'])) {
                // Get current date for date_reserved
                $date_reserved = date("Y-m-d");

                // Update reservation status to "confirmed" and set the date_reserved
                $update_reserv = "UPDATE reservation 
                                  SET stat_id = (SELECT stat_id FROM status WHERE description = 'confirmed'), 
                                      date_reserved = ? 
                                  WHERE reserv_id = ?";
                
                $update_plot = "UPDATE plot 
                                SET stat_id = (SELECT stat_id FROM status WHERE description = 'reserved') 
                                WHERE plot_id = ?";

                $stmt1 = mysqli_prepare($conn, $update_reserv);
                mysqli_stmt_bind_param($stmt1, "si", $date_reserved, $reserv_id);
                mysqli_stmt_execute($stmt1);

                $stmt2 = mysqli_prepare($conn, $update_plot);
                mysqli_stmt_bind_param($stmt2, "i", $plot_id);
                mysqli_stmt_execute($stmt2);

                $_SESSION['success'] = "Reservation confirmed successfully.";
            } elseif (isset($_POST['cancel'])) {
                // Update reservation status to "canceled"

                $plot_id = $row['plot_id']; // Get the plot_id from the reservation
                $sel_dec = "SELECT d.dec_id FROM deceased d INNER JOIN burial b ON d.dec_id = b.dec_id
                    INNER JOIN reservation r ON r.plot_id = b.plot_id WHERE b.plot_id = {$plot_id}";
                echo $sel_dec;
                $select_res = mysqli_query($conn, $sel_dec);
                $select_row = mysqli_fetch_assoc($select_res);
                $dec_id = $select_row['dec_id'];

                $del_dec = "DELETE FROM deceased WHERE dec_id = ?";
                $stmt_del_dec = mysqli_prepare($conn, $del_dec);
                mysqli_stmt_bind_param($stmt_del_dec, "i", $dec_id);
                mysqli_stmt_execute($stmt_del_dec);

                $update_reserv = "UPDATE reservation 
                                  SET stat_id = (SELECT stat_id FROM status WHERE description = 'canceled') 
                                  WHERE reserv_id = ?";

                $update_plot = "UPDATE plot 
                                SET stat_id = 3 
                                WHERE plot_id = ?"; // Revert plot status to 3 (Available)

                $stmt1 = mysqli_prepare($conn, $update_reserv);
                mysqli_stmt_bind_param($stmt1, "i", $reserv_id);
                mysqli_stmt_execute($stmt1);

                $stmt2 = mysqli_prepare($conn, $update_plot);
                mysqli_stmt_bind_param($stmt2, "i", $plot_id);
                mysqli_stmt_execute($stmt2);

                $_SESSION['success'] = "Reservation canceled successfully, and plot is now available.";
            }

            mysqli_commit($conn); // Commit transaction
        } catch (Exception $e) {
            mysqli_rollback($conn); // Rollback changes if something fails
            $_SESSION['error'] = "Error processing request. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Reservation not found.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: reservation.php");
exit();
?>
