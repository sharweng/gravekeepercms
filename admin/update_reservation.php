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

        if (isset($_POST['confirm'])) {
            // Get current date for date_reserved
            $date_reserved = date("Y-m-d");

            // Update reservation status to "confirmed" and set the date_reserved
            $update_reserv = "UPDATE reservation 
                              SET stat_id = (SELECT stat_id FROM status WHERE description = 'confirmed'), 
                                  date_reserved = ? 
                              WHERE reserv_id = ?";
            
            $update_plot = "UPDATE plot 
                            SET stat_id = (SELECT stat_id FROM status WHERE description = 'occupied') 
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
            $update_reserv = "UPDATE reservation 
                              SET stat_id = (SELECT stat_id FROM status WHERE description = 'canceled') 
                              WHERE reserv_id = ?";
            
            $stmt1 = mysqli_prepare($conn, $update_reserv);
            mysqli_stmt_bind_param($stmt1, "i", $reserv_id);
            mysqli_stmt_execute($stmt1);

            $_SESSION['success'] = "Reservation canceled successfully.";
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
