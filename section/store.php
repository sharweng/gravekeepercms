<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    session_start();
    include("../includes/config.php");
    include('../includes/notAdminRedirect.php');

    $_SESSION['name'] = trim($_POST['name']);
    $_SESSION['num_plot'] = $_POST['num_plot'];
    $_SESSION['min_price'] = $_POST['min_price'];
    $_SESSION['max_price'] = $_POST['max_price'];

    if(isset($_POST['create'])){

        if(empty($_POST['name'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a name. <br>';
        }else{
            $name = trim($_POST['name']);
            if(!preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $name)){
                $_SESSION['message'] = $_SESSION['message'].'Name must only contain up to 50 letters, numbers, spaces, hyphens, and underscores. <br>';
            }
        }

        if(empty($_POST['num_plot'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter the number of plots. <br>';
        }else{
            $num_plot = $_POST['num_plot'];
            if(!preg_match("/^[1-9]\d*$/", $num_plot)){
                $_SESSION['message'] = $_SESSION['message'].'Number of plots must be a positive whole number. <br>';
            }
        }

        if(empty($_POST['min_price'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter the minimum price. <br>';
        }else{
            $min_price = $_POST['min_price'];
            if(!is_numeric($min_price) || $min_price < 0){
                $_SESSION['message'] = $_SESSION['message'].'Minimum price must be a positive number. <br>';
            }
        }

        if(empty($_POST['max_price'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter the maximum price. <br>';
        }else{
            $max_price = $_POST['max_price'];
            if(!is_numeric($max_price) || $max_price < 0){
                $_SESSION['message'] = $_SESSION['message'].'Maximum price must be a positive number. <br>';
            }
        }

        // Check if max price is greater than or equal to min price
        if(isset($min_price) && isset($max_price) && $max_price < $min_price){
            $_SESSION['message'] = $_SESSION['message'].'Maximum price cannot be lower than minimum price. <br>';
        }

        if(empty($_FILES['img-path']['name'][0])){
            $_SESSION['message'] = $_SESSION['message'].'Upload a picture. <br>';
            header("Location: create.php");
        }

        if((preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $name))&&
        (preg_match("/^[1-9]\d*$/", $num_plot)) && 
        is_numeric($min_price) && $min_price >= 0 && 
        is_numeric($max_price) && $max_price >= 0 && 
        $max_price >= $min_price){
            $source = $_FILES['img-path']['tmp_name'];
            $target = 'images/' . $_FILES['img-path']['name'];
            move_uploaded_file($source, $target) or die("Couldn't copy");

            // Insert section using prepared statement
            $sql = "INSERT INTO section (sec_name, sec_img, num_plot, min_price, max_price) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssidi", $name, $target, $num_plot, $min_price, $max_price);
            
            if (mysqli_stmt_execute($stmt)) {
                // Get last inserted section_id
                $section_id = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt);

                // Insert plots using prepared statement
                $sql = "INSERT INTO plot (description, section_id, stat_id, price) 
                        VALUES (?, ?, 3, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sid", $description, $section_id, $price);

                // Loop through num_plot and insert each plot with random price
                for ($i = 1; $i <= $num_plot; $i++) {
                    $description = "Plot $i";
                    // Generate random price between min and max
                    $price = rand($min_price * 100, $max_price * 100) / 100;
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);

                // Clear session variables and redirect
                $_SESSION['name'] = '';
                $_SESSION['num_plot'] = '';
                $_SESSION['min_price'] = '';
                $_SESSION['max_price'] = '';      
                header("Location: /gravekeepercms/section/");
            } else {
                die("Error inserting section: " . mysqli_error($conn));
            }
        }else{
            header("Location: create.php");
        }
    }

    if(isset($_POST['update'])){

        $u_id = $_SESSION['u_id'];

        if(empty($_POST['name'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a name. <br>';
        }else{
            $name = trim($_POST['name']);
            if(!preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $name)){
                $_SESSION['message'] = $_SESSION['message'].'Name must only contain up to 50 letters, numbers, spaces, hyphens, and underscores. <br>';
            }
        }

        if(empty($_POST['num_plot'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter the number of plots. <br>';
        }else{
            $num_plot = $_POST['num_plot'];
            if(!preg_match("/^[1-9]\d*$/", $num_plot)){
                $_SESSION['message'] = $_SESSION['message'].'Number of plots must be a positive whole number. <br>';
            }
        }

        if(empty($_POST['min_price'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter the minimum price. <br>';
        }else{
            $min_price = $_POST['min_price'];
            if(!is_numeric($min_price) || $min_price < 0){
                $_SESSION['message'] = $_SESSION['message'].'Minimum price must be a positive number. <br>';
            }
        }

        if(empty($_POST['max_price'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter the maximum price. <br>';
        }else{
            $max_price = $_POST['max_price'];
            if(!is_numeric($max_price) || $max_price < 0){
                $_SESSION['message'] = $_SESSION['message'].'Maximum price must be a positive number. <br>';
            }
        }

        // Check if max price is greater than or equal to min price
        if(isset($min_price) && isset($max_price) && $max_price < $min_price){
            $_SESSION['message'] = $_SESSION['message'].'Maximum price cannot be lower than minimum price. <br>';
        }

        if((preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $name))
        &&(preg_match("/^[1-9]\d*$/", $num_plot))
        && is_numeric($min_price) && $min_price >= 0
        && is_numeric($max_price) && $max_price >= 0
        && $max_price >= $min_price){
            $ud_sql = "UPDATE section SET sec_name = '$name', num_plot = $num_plot, min_price = $min_price, max_price = $max_price
            WHERE section_id = $u_id";

            $query = "SELECT num_plot FROM section WHERE section_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $u_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $current_num_plot);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if(isset($_FILES['img-path']) && $_FILES['img-path']['error'] === 0){
                $sql = "SELECT sec_img FROM section WHERE section_id = {$u_id}";
                $select_res = mysqli_query($conn, $sql);
                $select_row = mysqli_fetch_assoc($select_res);
                $img_path = $select_row['sec_img'];

                $sql = "SELECT COUNT(*) as num FROM section WHERE sec_img = '$img_path'";
                $count_res = mysqli_query($conn, $sql);
                $count_row = mysqli_fetch_assoc($count_res);
                $num = $count_row['num'];
                

                if($num == 1) {
                    unlink($img_path);
                }

                $source = $_FILES['img-path']['tmp_name'];
                $target = 'images/' . $_FILES['img-path']['name'];
                move_uploaded_file($source, $target) or die("Couldn't copy");

                echo $img_path.'<br>'.$target;

                $ud_sql = "UPDATE section SET sec_name = '$name', sec_img = '$target', num_plot = $num_plot, min_price = $min_price, max_price = $max_price
                WHERE section_id = $u_id";
            }
            echo $ud_sql;

            if ($num_plot > $current_num_plot) {
                // Add new plots
                $sql = "INSERT INTO plot (description, section_id, stat_id, price) VALUES (?, ?, 3, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sid", $description, $u_id, $price);
        
                for ($i = $current_num_plot + 1; $i <= $num_plot; $i++) {
                    $description = "Plot $i";
                    // Generate random price between min and max
                    $price = rand($min_price * 100, $max_price * 100) / 100;
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);
            } elseif ($num_plot < $current_num_plot) {
                // Remove excess plots
                $sql = "DELETE FROM plot WHERE section_id = ? ORDER BY plot_id DESC LIMIT ?";
                $stmt = mysqli_prepare($conn, $sql);
                $diff = $current_num_plot - $num_plot;
                mysqli_stmt_bind_param($stmt, "ii", $u_id, $diff);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            
            $result = mysqli_query($conn, $ud_sql);
            if($result){
                $_SESSION['name'] = '';
                $_SESSION['num_plot'] = '';
                $_SESSION['min_price'] = '';
                $_SESSION['max_price'] = '';      
                
                header("Location: /gravekeepercms/section/");
            }
        }else{
            header("Location: edit.php");
        }
    }

?>