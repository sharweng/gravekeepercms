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
    <div class="row h-100" style="background-color: #d1d1d3">
        <div class="col container d-grid align-items-center px-0">
            <main class="form-signin w-100 m-auto ">
            <form>
                <h1 class="h1 mb-3 fw-bold text-center">Login</h1>
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                <button class="btn btn-darker-grey w-100 py-2 border-darker-grey" type="submit">Log in</button>
                </form>
                <div class=" d-flex justify-content-center mt-2">
                    <a href="register.php" class="text-decoration-none a-darker-grey">Register</a>
                </div>
            </main>
        </div>
        <div class="col container d-flex flex-column align-items-center justify-content-center px-0" style="background-color: #4b4a4d;">
            <p class="fw-bold mb-0" style="font-size: 64px; color: #d1d1d3">GraveKeeper</p>
            <p class="fw-bold" style="font-size: 32px; color: #a8a8a9">Cemetery Management System</p>
        </div>
    </div>

</body>
</html>
<?php
    include("../includes/footer.php");
?>
