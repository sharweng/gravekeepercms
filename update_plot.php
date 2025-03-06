<?php
include("../includes/config.php");

// Get today's date
date_default_timezone_set("Asia/Manila");
$today = date("Y-m-d");

// Query to find plots with today's burial date
$burial_sql = "SELECT b.plot_id FROM burial b INNER JOIN plot p ON b.plot_id = p.plot_id WHERE b.burial_date = ? AND p.stat_id = 7";
$stmt = mysqli_prepare($conn, $burial_sql);
mysqli_stmt_bind_param($stmt, "s", $today);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

mysqli_begin_transaction($conn); // Start transaction

try {
    while ($row = mysqli_fetch_assoc($result)) {
        $plot_id = $row['plot_id'];

        // Update plot status to 'occupied'
        $update_plot = "UPDATE plot 
                        SET stat_id = 4
                        WHERE plot_id = ?";
        
        $stmt_update = mysqli_prepare($conn, $update_plot);
        mysqli_stmt_bind_param($stmt_update, "i", $plot_id);
        mysqli_stmt_execute($stmt_update);
    }

    mysqli_commit($conn); // Commit transaction
} catch (Exception $e) {
    mysqli_rollback($conn); // Rollback changes if something fails
    echo "Error updating plot statuses: " . $e->getMessage();
}

mysqli_close($conn);
?>