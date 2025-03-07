<?php
    session_start();
    include("../includes/config.php");
    include('../includes/notAdminRedirect.php');
    
    if(isset($_POST['create'])){
        if(empty($_POST['price'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a price. <br>';
        }else{
            $price = trim($_POST['price']);
            if(!preg_match("/^\d+(\.\d{1,2})?$/", $price)){
                $_SESSION['message'] = $_SESSION['message'].'Enter a valid price. <br>';
            }
        }    

        if((preg_match("/^\d+(\.\d{1,2})?$/", $price))){
            $sec_id = $_SESSION['sec_id']; // Get section ID from session
            $stat_id = $_POST['status'];

            $num_sql = "SELECT num_plot FROM section WHERE section_id = $sec_id";
            $num_res = mysqli_query($conn, $num_sql);
            $num_row = mysqli_fetch_assoc($num_res);

            // Fetch the latest plot number in this section
            $query = "SELECT MAX(CAST(SUBSTRING_INDEX(description, ' ', -1) AS UNSIGNED)) AS latest_plot FROM plot WHERE section_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $sec_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $latest_plot);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            $num = $num_row['num_plot']+1;

            $sql = "UPDATE section SET num_plot = ".$num." WHERE section_id = ?";
            echo $sql;
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $sec_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Calculate new plot number
            $new_plot_number = $latest_plot ? $latest_plot + 1 : 1;
            $desc = "Plot " . $new_plot_number;
            
            // Insert new plotss
            $sql = "INSERT INTO plot (description, section_id, stat_id, price) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "siid", $desc, $sec_id, $stat_id, $price);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Plot created successfully!";
            } else {
                $_SESSION['message'] = "Failed to create plot.";
            }
            mysqli_stmt_close($stmt);

            header("Location: /gravekeepercms/section/view_plot.php?id={$_SESSION['sec_id']}");
        }else{
            header("Location: /gravekeepercms/plot/create.php");
        }
        
    }

    if (isset($_POST['update'])) {
        if(empty($_POST['price'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a price. <br>';
        }else{
            $price = trim($_POST['price']);
            if(!preg_match("/^\d+(\.\d{1,2})?$/", $price)){
                $_SESSION['message'] = $_SESSION['message'].'Enter a valid price. <br>';
            }
        }    

        if((preg_match("/^\d+(\.\d{1,2})?$/", $price))){
            $plot_id = $_POST['plot_id'];
            $stat_id = $_POST['status'];
        
            $sql = "UPDATE plot SET stat_id = ?, price = ? WHERE plot_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "idi", $stat_id, $price, $plot_id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Plot updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update plot.";
            }
            mysqli_stmt_close($stmt);
        
            header("Location: /gravekeepercms/section/view_plot.php?id={$_SESSION['sec_id']}");
        }else{
            header("Location: /gravekeepercms/plot/edit.php?id={$_POST['plot_id']}");
        }
        
    }

?>