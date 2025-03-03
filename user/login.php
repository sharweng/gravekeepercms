<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- BOOTSTRAP AND CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('../includes/styles/style.css') ?>
    </style>
</head>
<body>
<div class="container-fluid px-0 h-100 mx-0 d-flex">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for login form -->
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
        <main class="form-signin m-auto w-100">
            <form method="post" action="store.php">
                <h1 class="h1 mb-3 fw-bold text-center">Login</h1>
                <?php include("../includes/alert.php"); ?>
                <div class="form-floating">
                    <input type="email" class="form-control signin-top" id="floatingInput" placeholder="name@example.com" name="email">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="password" class="form-control signin-bottom" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                <button class="btn btn-darker-grey w-100 py-2 border-darker-grey" name="submit-login" type="submit">Login</button>
            </form>
            <div class="d-flex justify-content-center mt-2">
                <a href="register.php" class="text-decoration-none a-darker-grey">Register</a>
            </div>
        </main>
    </div>
</div>
</body>
</html>
<?php
    include("../includes/footer.php");
?>
