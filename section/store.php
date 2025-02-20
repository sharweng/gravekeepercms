<?php
    session_start();
    include("../includes/config.php");

    $_SESSION['name'] = trim($_POST['name']);
    $_SESSION['desc'] = trim($_POST['desc']);
    $_SESSION['num_plot'] = $_POST['num_plot'];

    if(isset($_POST['create'])){

        if(empty($_POST['name'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a name. <br>';
        }else{
            $name = trim($_POST['name']);
            if(!preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $name)){
                $_SESSION['message'] = $_SESSION['message'].'Name must only contain up to 50 letters, numbers, spaces, hyphens, and underscores. <br>';
            }
        }

        if(empty($_POST['desc'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a descripstion. <br>';
        }else{
            $desc = trim($_POST['desc']);
            if(!preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $desc)){
                $_SESSION['message'] = $_SESSION['message'].'Description must only contain up to 50 letters, numbers, spaces, hyphens, and underscores. <br>';
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

        if(empty($_FILES['img-path']['name'][0])){
            $_SESSION['message'] = $_SESSION['message'].'Upload a picture. <br>';
            header("Location: create.php");
        }

        if((preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $name))&&(preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $desc))&&
        (preg_match("/^[1-9]\d*$/", $num_plot))){
            $source = $_FILES['img-path']['tmp_name'];
            $target = 'images/' . $_FILES['img-path']['name'];
            move_uploaded_file($source, $target) or die("Couldn't copy");

            // Insert section using prepared statement
            $sql = "INSERT INTO section (sec_name, description, sec_img, num_plot) 
                    VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $name, $desc, $target, $num_plot);
            
            if (mysqli_stmt_execute($stmt)) {
                // Get last inserted section_id
                $section_id = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt);

                // Insert plots using prepared statement
                $sql = "INSERT INTO plot (description, section_id, type_id, stat_id) 
                        VALUES (?, ?, 1, 3)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "si", $description, $section_id);

                // Loop through num_plot and insert each plot
                for ($i = 1; $i <= $num_plot; $i++) {
                    $description = "Plot $i";
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);

                // Clear session variables and redirect
                $_SESSION['name'] = '';
                $_SESSION['desc'] = '';
                $_SESSION['num_plot'] = '';      
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

        if(empty($_POST['desc'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a descripstion. <br>';
        }else{
            $desc = trim($_POST['desc']);
            if(!preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $desc)){
                $_SESSION['message'] = $_SESSION['message'].'Description must only contain up to 50 letters, numbers, spaces, hyphens, and underscores. <br>';
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

        if((preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $name))&&(preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $desc))
        &&(preg_match("/^[1-9]\d*$/", $num_plot))){
            $ud_sql = "UPDATE section SET sec_name = '$name', description = '$desc', num_plot = $num_plot
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

                $ud_sql = "UPDATE section SET sec_name = '$name', description = '$desc', sec_img = '$target', num_plot = $num_plot
                WHERE section_id = $u_id";
            }
            echo $ud_sql;

            if ($num_plot > $current_num_plot) {
                // Add new plots
                $sql = "INSERT INTO plot (description, section_id, type_id, stat_id) VALUES (?, ?, 1, 3)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "si", $description, $u_id);
        
                for ($i = $current_num_plot + 1; $i <= $num_plot; $i++) {
                    $description = "Plot $i";
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
                $_SESSION['desc'] = '';
                $_SESSION['num_plot'] = '';      
                
                header("Location: /gravekeepercms/section/");
            }
        }else{
            header("Location: edit.php");
        }
    }

?>