<?php
    session_start();
    include("../includes/config.php");
    include('../includes/notAdminRedirect.php');

    if(isset($_POST['section_id'])) {
        $section_id = mysqli_real_escape_string($conn, $_POST['section_id']);
        // Only return available plots (stat_id = 3)
        $plot_sql = "SELECT * FROM plot WHERE section_id = '$section_id' AND stat_id = 3";
        $plot_res = mysqli_query($conn, $plot_sql);
        
        $plots = array();
        while($row = mysqli_fetch_array($plot_res)) {
            $plots[] = array(
                'plot_id' => $row['plot_id'],
                'description' => $row['description']
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($plots);
    }
?>