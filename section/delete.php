<?php
session_start();
include("../includes/config.php");

if(isset($_POST['delete'])){
    $d_id = $_POST['section_id'];

    // Get section image path
    $sql = "SELECT sec_img FROM section WHERE section_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $d_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row) {
        $img_path = $row['sec_img'];

        // Check if image is used by other sections
        $sql = "SELECT COUNT(*) as num FROM section WHERE sec_img = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $img_path);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($row['num'] == 1 && is_writable($img_path)) {
            unlink($img_path); // Delete the image if no other section uses it
        }
    }

    // Delete section (automatically deletes plots due to ON DELETE CASCADE)
    $sql = "DELETE FROM section WHERE section_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $d_id);
    $delete_result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($delete_result) {
        header("Location: index.php");
    } else {
        die("Error deleting section: " . mysqli_error($conn));
    }
}
?>
