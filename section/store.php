<?php
    session_start();
    include("../includes/config.php");

    $_SESSION['name'] = trim($_POST['name']);
    $_SESSION['desc'] = trim($_POST['desc']);
    $_SESSION['row'] = $_POST['row'];
    $_SESSION['col'] = $_POST['col'];

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

        if(empty($_POST['row'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter the number of rows. <br>';
        }else{
            $row = $_POST['row'];
            if(!preg_match("/^[1-9]\d*$/", $row)){
                $_SESSION['message'] = $_SESSION['message'].'Number of rows must be a positive whole number. <br>';
            }
        }

        if(empty($_POST['col'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter the number of rows. <br>';
        }else{
            $col = $_POST['col'];
            if(!preg_match("/^[1-9]\d*$/", $col)){
                $_SESSION['message'] = $_SESSION['message'].'Number of rows must be a positive whole number. <br>';
            }
        }

        if(empty($_FILES['img-path']['name'][0])){
            $_SESSION['message'] = $_SESSION['message'].'Upload a picture. <br>';
            header("Location: create.php");
        }

        if((preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $name))&&(preg_match("/^[a-zA-Z0-9\s.,'\"\ -_]{1,50}$/", $desc))&&(preg_match("/^[1-9]\d*$/", $row))
        &&(preg_match("/^[1-9]\d*$/", $col))){
            $source = $_FILES['img-path']['tmp_name'];
            $target = 'images/' . $_FILES['img-path']['name'];
            echo $source.'<br>'.$target.'<br>';
            move_uploaded_file($source, $target) or die("Couldn't copy");

            $sql = "INSERT INTO section (sec_name, description, sec_img, sec_row, sec_col)VALUES
            ('$name', '$desc', '$target', $row, $col)";
            $result = mysqli_query($conn, $sql);
            if($result){
                $_SESSION['name'] = '';
                $_SESSION['desc'] = '';
                $_SESSION['row'] = '';
                $_SESSION['col'] = '';          
                
                header("Location: /gravekeepercms/section/");
            }
        }else{
            header("Location: create.php");
        }
    }

?>