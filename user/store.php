<?php
    session_start();
    include("../includes/config.php");

    $_SESSION['name'] = trim($_POST['name']);
    $_SESSION['phone'] = trim($_POST['phone']);
    $_SESSION['email'] = trim($_POST['email']);
    $_SESSION['pass'] = trim($_POST['password']);
    $_SESSION['cpass'] = trim($_POST['confirmPass']);

    if(isset($_POST['submit-register'])){

        if(empty($_POST['email'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter an email. <br>';
        }else{
            $email = trim($_POST['email']);
            if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)){
                $_SESSION['message'] = $_SESSION['message'].'Enter a valid email. <br>';
            }
        }

        if(empty($_POST['name'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a name. <br>';
        }else{
            $name = trim($_POST['name']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $name)){
                $_SESSION['message'] = $_SESSION['message'].'Enter a valid name. <br>';
            }
        }

        if(empty($_POST['phone'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a phone number. <br>';
        }else{
            $phone = trim($_POST['phone']);
            if(!preg_match("/^\d{11}$/", $phone)){
                $_SESSION['message'] = $_SESSION['message'].'Enter an 11 digit phone number. <br>';
            }
        }    
    
        if(empty($_POST['password'])||empty($_POST['confirmPass'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter both passwords. <br>';
        }elseif($_POST['password']!=$_POST['confirmPass']){
            $_SESSION['message'] = $_SESSION['message'].'Passwords do not match. <br>';
        }else{
            $pass = trim($_POST['password']);
            if(!preg_match("/^.{12,}$/", $pass)){
                $_SESSION['message'] = $_SESSION['message'].'Password must be atleast 12 characters. <br>';
            }
        }

        if((preg_match("/^[A-Za-z' -]{2,50}$/", $name))&&(preg_match("/^\d{11}$/", $phone))
        &&(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))
        &&(preg_match("/^.{12,}$/", $pass))){
            $searchsql = "SELECT email FROM user";
            $emailExists = mysqli_query($conn, $searchsql);
            while($row = mysqli_fetch_array($emailExists)){
                if(strtolower($email) == strtolower($row['email'])){
                    $_SESSION['message'] = $_SESSION['message'].'Email is already registered. Please log in or use a different email to register. <br>';
                    header("Location: register.php");
                    exit();
                }
            }

            $password = sha1($pass);
            $role = 2;
            $status = 1;

            $sql = "INSERT INTO user (email, password, name, phone, role_id, stat_id)VALUES
            ('$email', '$password', '$name', '$phone', $role, $status)";
            $result = mysqli_query($conn, $sql);
            if($result){
                echo'test';
                $_SESSION['lname'] = '';
                $_SESSION['fname'] = '';
                $_SESSION['email'] = '';
                $_SESSION['pass'] = '';
                $_SESSION['cpass'] = '';
                $_SESSION['add'] = '';
                $_SESSION['phone'] = '';
                

                $last_id = $conn->insert_id;
                $_SESSION['user_id'] = $last_id;
                $_SESSION['email'] = $email;
                if($role == 1)
                    $_SESSION['roleDesc'] = "admin";
                else
                    $_SESSION['roleDesc'] = "user";
                
                header("Location: /gravekeepercms/");
                
            }
        }else{
            header("Location: register.php");
        }
    }

    if (isset($_POST['submit-login'])) {
        if(empty($_POST['email'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter an email. <br>';
        }
        if(empty($_POST['password'])){
            $_SESSION['message'] = $_SESSION['message'].'Enter a password. <br>';
        }
        
        if(!empty($_POST['email']) && !empty($_POST['password'])){
            $email = trim($_POST['email']);
            $pass = sha1(trim($_POST['password']));
            $sql = "SELECT u.user_id, u.email, u.role_id FROM user u WHERE u.email=? AND u.password=? LIMIT 1";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $email, $pass);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $user_id, $email, $role);
            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_fetch($stmt);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $_SESSION['pass'] = '';

                if($role == 1)
                    $_SESSION['roleDesc'] = "admin";
                else
                    $_SESSION['roleDesc'] = "user";
                header("Location: /gravekeepercms/"); 
            } else {
                $_SESSION['message'] = 'Invalid email or password. Please try again.';
                header("Location: login.php");
            }
        }else{
            $_SESSION['email'] = '';
            $_SESSION['pass'] = '';
            header("Location: login.php");
        }
        
    }
?>