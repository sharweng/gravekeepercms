<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
session_start();
include("../includes/config.php");

if (!isset($_POST['plot_id']) || !isset($_POST['section_id']) || !isset($_POST['user_id']) || !isset($_POST['type_id'])) {
    echo "<script>alert('Invalid request. Please complete the form.'); window.location.href='/gravekeepercms/section/';</script>";
    exit();
}

$plot_id = $_POST['plot_id'];
$section_id = $_POST['section_id'];
$user_id = $_POST['user_id'];
$type_id = $_POST['type_id']; // Burial type

// Store burial details in session for form persistence if validation fails
$_SESSION['lname'] = trim($_POST['lname'] ?? '');
$_SESSION['fname'] = trim($_POST['fname'] ?? '');
$_SESSION['date_born'] = trim($_POST['date_born'] ?? '');
$_SESSION['date_died'] = trim($_POST['date_died'] ?? '');
$_SESSION['burial_date'] = trim($_POST['burial_date'] ?? '');

// Validation function
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

    // Validate image
    if(empty($_FILES['img-path']['name'])){
        $message .= 'Upload a picture of the deceased. <br>';
    }

    return empty($message);
}

// Initialize error message
$error_message = '';

// Validate inputs
if (!validateInputs($_POST, $error_message)) {
    $_SESSION['message'] = $error_message;
    // Redirect back to the confirmation page with the necessary parameters
    header("Location: confirm_reservation.php?plot=$plot_id&section=$section_id");
    exit();
}

mysqli_begin_transaction($conn); // Start transaction

try {
    // Process the image - Fix the path to upload to deceased/images directory
    $source = $_FILES['img-path']['tmp_name'];
    $filename = $_FILES['img-path']['name'];
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gravekeepercms/deceased/images/';
    $target_path = $target_dir . $filename;
    $db_image_path = 'images/' . $filename; // Path to store in database
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Move the uploaded file
    if (!move_uploaded_file($source, $target_path)) {
        throw new Exception("Failed to upload image. Please try again.");
    }

    // Insert deceased record
    $dec_sql = "INSERT INTO deceased (lname, fname, date_born, date_died, picture) 
                VALUES (?, ?, ?, ?, ?)";
    $dec_stmt = mysqli_prepare($conn, $dec_sql);
    mysqli_stmt_bind_param($dec_stmt, "sssss", $_POST['lname'], $_POST['fname'], 
                         $_POST['date_born'], $_POST['date_died'], $db_image_path);
    mysqli_stmt_execute($dec_stmt);
    $dec_id = mysqli_insert_id($conn);

    // Insert reservation record with burial type
    $reservation_sql = "INSERT INTO reservation (date_placed, date_reserved, stat_id, section_id, plot_id, user_id) 
                        VALUES (NOW(), ?, 5, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $reservation_sql);
    mysqli_stmt_bind_param($stmt, "siii", $_POST['burial_date'], $section_id, $plot_id, $user_id);
    mysqli_stmt_execute($stmt);
    $reserv_id = mysqli_insert_id($conn);

    // Update plot status to "Pending" (stat_id = 5)
    $update_plot_sql = "UPDATE plot SET stat_id = 5 WHERE plot_id = ?";
    $stmt = mysqli_prepare($conn, $update_plot_sql);
    mysqli_stmt_bind_param($stmt, "i", $plot_id);
    mysqli_stmt_execute($stmt);

    // Insert into burial table (will be activated when reservation is confirmed)
    $burial_sql = "INSERT INTO burial (burial_date, dec_id, plot_id, type_id) 
                  VALUES (?, ?, ?, ?)";
    $burial_stmt = mysqli_prepare($conn, $burial_sql);
    mysqli_stmt_bind_param($burial_stmt, "siii", $_POST['burial_date'], $dec_id, 
                         $plot_id, $type_id);
    mysqli_stmt_execute($burial_stmt);

    mysqli_commit($conn); // Commit transaction

    // Clear session variables
    $_SESSION['lname'] = '';
    $_SESSION['fname'] = '';
    $_SESSION['date_born'] = '';
    $_SESSION['date_died'] = '';
    $_SESSION['burial_date'] = '';
    $_SESSION['message'] = '';

    echo "<script>alert('Reservation successful! Status: Pending'); window.location.href='/gravekeepercms/review/create.php?mode=user&after=reserve';</script>";
} catch (Exception $e) {
    mysqli_rollback($conn); // Rollback if something goes wrong
    
    // Delete uploaded image if it exists
    if (isset($target_path) && file_exists($target_path)) {
        unlink($target_path);
    }
    
    $_SESSION['message'] = 'Reservation failed: ' . $e->getMessage();
    header("Location: confirm_reservation.php?plot=$plot_id&section=$section_id");
    exit();
}

mysqli_close($conn);
?>