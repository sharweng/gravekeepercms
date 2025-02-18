<?php
    session_start();
    include("../includes/config.php");

    if(isset($_POST['delete'])){
        $d_id = $_POST['section_id'];

        $sql = "SELECT sec_img FROM section WHERE section_id = {$d_id}";
        $select_res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($select_res);
        $img_path = $row['sec_img'];

        $sql = "SELECT COUNT(*) as num FROM section WHERE sec_img = '$img_path'";
        $count_res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($count_res);
        $num = $row['num'];

        echo is_writable($img_path);
       
        if($num == 1){
           unlink($img_path);
        }

        $sql = "DELETE FROM section WHERE section_id = {$d_id}";
        $d_result = mysqli_query($conn, $sql);

        if($d_result){
            header("Location: index.php");
        }

        // $d_id = $_POST['section_id'];
        // $num = 1;

        // $sql = "SELECT COUNT(sec_img) as num FROM section WHERE sec_img = ''";
        // $count_res = mysqli_query($conn, $sql);
        // while($row = mysqli_fetch_array($count_res)){
        //     $num = $row['num'];
        // }

        // $sql = "SELECT sec_img FROM section WHERE section_id = {$d_id}";
        // $select_res = mysqli_query($conn, $sql);
        // while($row = mysqli_fetch_array($select_res)){
        //     if($num == 1)
        //         unlink($row['sec_img']);
        // }

        // $sql = "DELETE FROM section WHERE section_id = {$d_id}";
        // $d_result = mysqli_query($conn, $sql);

        // if($d_result){
        //     header("Location: index.php");
        // }
    }

?>