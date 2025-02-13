<?php
    session_start();
    include("../includes/config.php");

    $_SESSION['lname'] = trim($_POST['name']);
    $_SESSION['phone'] = trim($_POST['phone']);
    $_SESSION['email'] = trim($_POST['email']);
    $_SESSION['pass'] = trim($_POST['password']);
    $_SESSION['cpass'] = trim($_POST['confirmPass']);

    $_SESSION['nameErr'] = "";
    $_SESSION['phoneErr'] = "";
    $_SESSION['emailErr'] = "";
    $_SESSION['passErr'] = "";

   
    if(isset($_POST['submit'])){
        echo"test";

        if(empty($_POST['name'])){
            $_SESSION['nameErr'] = "Error: please enter a name. ";
            header("Location: register.php");
        }else{
            $name = trim($_POST['name']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $name)){
                $_SESSION['nameErr'] = "Error: please enter a valid name. ";
                header("Location: register.php");
            }
        }

        if(empty($_POST['phone'])){
            $_SESSION['phoneErr'] = "Error: please enter a phone number. ";
            header("Location: register.php");
        }else{
            $phone = trim($_POST['phone']);
            if(!preg_match("/^\d{11}$/", $phone)){
                $_SESSION['phoneErr'] = "Error: please enter a 11 digit long phone number. ";
                header("Location: register.php");
            }
        }
    
        if(empty($_POST['email'])){
            $_SESSION['emailErr'] = "Error: please enter an email. ";
            header("Location: register.php");
        }else{
            $email = trim($_POST['email']);
            if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)){
                $_SESSION['emailErr'] = "Error: please enter a valid email. ";
                header("Location: register.php");
            }
        }
    
        if(empty($_POST['password'])||empty($_POST['confirm'])){
            $_SESSION['passErr'] = "Error: please enter a both passwords. ";
            header("Location: register.php");
        }elseif($_POST['password']!=$_POST['confirm']){
            $_SESSION['passErr'] = "Error: password does not match. ";
            header("Location: register.php");
        }else{
            $pass = trim($_POST['password']);
            if(!preg_match("/^.{12,}$/", $pass)){
                $_SESSION['passErr'] = "Error: password must be atleast 12 characters long. ";
                header("Location: register.php");
            }
        }


        if((preg_match("/^[A-Za-z' -]{2,50}$/", $name))&&(preg_match("/^\d{11}$/", $phone))
        &&(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))
        &&(preg_match("/^.{12,}$/", $pass))){
            $searchsql = "SELECT email FROM user";
            $emailExists = mysqli_query($conn, $searchsql);
            while($row = mysqli_fetch_array($emailExists)){
                if(strtolower($email) == strtolower($row['email'])){
                    $_SESSION['message'] = 'This email is already registered. Please log in or use a different email to sign up.';
                    header("Location: /plantitoshop/user/register.php");
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
        }
    }
?>