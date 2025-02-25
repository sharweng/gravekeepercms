<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');
    $mode = $_GET['mode'];
    if($mode == 'admin')
        include('../includes/notAdminRedirect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <!-- BOOTSTRAP AND CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        <?php include('../includes/styles/style.css') ?>
    </style>
</head>
<body>
<div class="container-fluid px-0 mx-0 h-100 d-flex">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for login form -->
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
        <main class="form-signin m-auto w-100">
                <form method="post" <?php if($mode == 'admin')
                            echo "action=\"store.php?mode=admin\"";
                        else
                            echo "action=\"store.php\"";
                        ?>>
                    <h1 class="h1 mb-3 fw-bold text-center">Register</h1>
                    <?php include("../includes/alert.php"); ?>
                    <div class="form-floating ">
                        <input type="email" class="form-control signin-top" id="floatingInput" name="email" placeholder="name@example.com" value="<?php
                                            if($mode != 'admin')
                                                if(isset($_SESSION['email'])){
                                                    echo $_SESSION['email'];
                                        }?>">
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control signin-middle" id="floatingInput" name="name" placeholder="Name" value="<?php
                                            if(isset($_SESSION['name'])){
                                                echo $_SESSION['name'];
                                        }?>">
                        <label for="floatingInput">Name</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control signin-middle" id="floatingInput" name="phone" placeholder="Phone Number" value="<?php
                                            if(isset($_SESSION['phone'])){
                                                echo $_SESSION['phone'];
                                        }?>">
                        <label for="floatingInput">Phone Number</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control signin-middle" name="password" id="floatingInput" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control signin-bottom" name="confirmPass" id="floatingPassword" placeholder="Confirm Password">
                        <label for="floatingPassword">Confirm Password</label>
                    </div>
                    <button class="btn btn-darker-grey w-100 py-2 border-darker-grey" name="submit-register" type="submit">Register</button>
                </form>
                <div class=" d-flex justify-content-center mt-2">
                    <?php if($mode == 'admin') 
                            echo "<a href=\"/gravekeepercms/user/\" class=\"text-decoration-none a-darker-grey\">Back</a>";
                        else
                            echo "<a href=\"login.php\" class=\"text-decoration-none a-darker-grey\">Login</a>";
                    ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

<?php
  include("../includes/footer.php");
?>
