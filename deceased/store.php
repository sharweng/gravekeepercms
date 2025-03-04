<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    include("../includes/config.php");
    include('../includes/notAdminRedirect.php');
    
    function validateInputs($post, &$message) {
        // Validate Last Name
        if(empty($post['lname'])){
            $message .= 'Enter a last name. <br>';
        }else{
            $lname = trim($post['lname']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $lname)){
                $message .= 'Enter a valid last name. <br>';
            }
        }

        // Validate First Name
        if(empty($post['fname'])){
            $message .= 'Enter a first name. <br>';
        }else{
            $fname = trim($post['fname']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $fname)){
                $message .= 'Enter a valid first name. <br>';
            }
        }

        // Validate Dates
        if(empty($post['date_born'])) $message .= 'Enter date of birth. <br>';
        if(empty($post['date_died'])) $message .= 'Enter date of death. <br>';
        if(empty($post['burial_date'])) $message .= 'Enter burial date. <br>';

        // Validate date sequence if all dates are provided
        if(!empty($post['date_born']) && !empty($post['date_died']) && !empty($post['burial_date'])) {
            $date_born = strtotime($post['date_born']);
            $date_died = strtotime($post['date_died']);
            $burial_date = strtotime($post['burial_date']);
            $current_date = strtotime(date('Y-m-d'));

            if($date_died < $date_born) {
                $message .= 'Date of death cannot be earlier than date of birth. <br>';
            }
            if($burial_date < $date_died) {
                $message .= 'Burial date cannot be earlier than date of death. <br>';
            }
            if($date_born > $current_date || $date_died > $current_date) {
                $message .= 'Dates cannot be in the future. <br>';
            }
        }

        // Validate burial type and plot
        if(empty($post['type'])) $message .= 'Select a burial type. <br>';
        if(empty($post['plot'])) $message .= 'Select a plot. <br>';

        if(isset($_POST['create'])){
            if(empty($_FILES['img-path']['name'][0])){
                $message .= 'Upload a picture. <br>';
                header("Location: create.php");
            }
        }

        return empty($message);
    }

    $_SESSION['lname'] = trim($_POST['lname']);
    $_SESSION['fname'] = trim($_POST['fname']);
    $_SESSION['date_born'] = trim($_POST['date_born']);
    $_SESSION['date_died'] = trim($_POST['date_died']);
    $_SESSION['burial_date'] = trim($_POST['burial_date']);
    $_SESSION['type'] = $_POST['type'];
    $_SESSION['section'] = $_POST['section'];
    $_SESSION['plot'] = $_POST['plot'];
    
    if(isset($_POST['create']) || isset($_POST['update'])){
        // Save form data to session

        // Validate inputs
        if(!validateInputs($_POST, $_SESSION['message'])) {
            header("Location: " . (isset($_POST['update']) ? "edit.php" : "create.php"));
            exit();
        }

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            if(isset($_POST['create'])) {
                $source = $_FILES['img-path']['tmp_name'];
                $target = 'images/' . $_FILES['img-path']['name'];
                move_uploaded_file($source, $target) or die("Couldn't copy");

                // Insert into deceased table
                $dec_sql = "INSERT INTO deceased (lname, fname, date_born, date_died, picture) 
                           VALUES (?, ?, ?, ?, ?)";
                $dec_stmt = mysqli_prepare($conn, $dec_sql);
                mysqli_stmt_bind_param($dec_stmt, "sssss", $_POST['lname'], $_POST['fname'], 
                                     $_POST['date_born'], $_POST['date_died'], $target);
                mysqli_stmt_execute($dec_stmt);
                $dec_id = mysqli_insert_id($conn);

                // Insert into burial table
                $burial_sql = "INSERT INTO burial (burial_date, dec_id, plot_id, type_id) 
                              VALUES (?, ?, ?, ?)";
                $burial_stmt = mysqli_prepare($conn, $burial_sql);
                mysqli_stmt_bind_param($burial_stmt, "siii", $_POST['burial_date'], $dec_id, 
                                     $_POST['plot'], $_POST['type']);
                mysqli_stmt_execute($burial_stmt);

            } else { // Update
                $dec_id = $_POST['dec_id'];
                
                $sql = "SELECT plot_id FROM burial WHERE dec_id = {$dec_id}";
                $select_res = mysqli_query($conn, $sql);
                $select_row = mysqli_fetch_assoc($select_res);
                $plot_before = $select_row['plot_id'];

                // Update deceased table

                if(isset($_FILES['img-path']) && $_FILES['img-path']['error'] === 0){
                    $sql = "SELECT picture FROM deceased WHERE dec_id = {$dec_id}";
                    $select_res = mysqli_query($conn, $sql);
                    $select_row = mysqli_fetch_assoc($select_res);
                    $img_path = $select_row['picture'];
    
                    $sql = "SELECT COUNT(*) as num FROM deceased WHERE picture = '$img_path'";
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

                    $dec_sql = "UPDATE deceased SET lname=?, fname=?, date_born=?, date_died=?, picture=? 
                           WHERE dec_id=?";
                    $dec_stmt = mysqli_prepare($conn, $dec_sql);
                    mysqli_stmt_bind_param($dec_stmt, "sssssi", $_POST['lname'], $_POST['fname'], 
                                        $_POST['date_born'], $_POST['date_died'], $target, $dec_id);
                    mysqli_stmt_execute($dec_stmt);
                }else{
                    $dec_sql = "UPDATE deceased SET lname=?, fname=?, date_born=?, date_died=?
                           WHERE dec_id=?";
                    $dec_stmt = mysqli_prepare($conn, $dec_sql);
                    mysqli_stmt_bind_param($dec_stmt, "ssssi", $_POST['lname'], $_POST['fname'], 
                                        $_POST['date_born'], $_POST['date_died'], $dec_id);
                    mysqli_stmt_execute($dec_stmt);
                }
                
                // Update plot table
                $plot_sql = "UPDATE plot SET stat_id = 3
                              WHERE plot_id=?";
                $plot_stmt = mysqli_prepare($conn, $plot_sql);
                mysqli_stmt_bind_param($plot_stmt, "i", $plot_before);
                mysqli_stmt_execute($plot_stmt);

                // Update burial table
                $burial_sql = "UPDATE burial SET burial_date=?, plot_id=?, type_id=? 
                              WHERE dec_id=?";
                $burial_stmt = mysqli_prepare($conn, $burial_sql);
                mysqli_stmt_bind_param($burial_stmt, "siii", $_POST['burial_date'], 
                                     $_POST['plot'], $_POST['type'], $dec_id);
                mysqli_stmt_execute($burial_stmt);
            }

            // Update plot status
            $plot_sql = "UPDATE plot SET stat_id = 4 WHERE plot_id = ?";
            $plot_stmt = mysqli_prepare($conn, $plot_sql);
            mysqli_stmt_bind_param($plot_stmt, "i", $_POST['plot']);
            mysqli_stmt_execute($plot_stmt);

            // Commit transaction
            mysqli_commit($conn);

            // Clear session variables
            $_SESSION['lname'] = '';
            $_SESSION['fname'] = '';
            $_SESSION['date_born'] = '';
            $_SESSION['date_died'] = '';
            $_SESSION['burial_date'] = '';
            $_SESSION['type'] = '';
            $_SESSION['section'] = '';
            $_SESSION['plot'] = '';

            header("Location: index.php");
            exit();

        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            
            // Delete uploaded image if exists and new
            if(isset($picture) && $picture !== $current_image && file_exists('../' . $picture)) {
                unlink('../' . $picture);
            }

            $_SESSION['message'] = "Error " . (isset($_POST['update']) ? "updating" : "adding") . 
                                " deceased record. Please try again.";
            header("Location: " . (isset($_POST['update']) ? "edit.php" : "create.php"));
            exit();
        }
    } else {
        header("Location: index.php");
        exit();
    }
?>