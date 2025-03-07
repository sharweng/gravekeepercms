<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');
    include('../includes/notAdminRedirect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Section</title>
  <!-- BOOTSTRAP AND CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        <?php include('../includes/styles/style.css') ?>
    </style>
</head>
<body>
<div class="container-fluid px-0 mx-0  d-flex h-100">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for login form -->
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
        <main class="form-signin m-auto w-100">
                <form method="post" action="store.php" enctype="multipart/form-data">
                    <h1 class="h1 mb-3 fw-bold text-center">Create Section</h1>
                    <?php include("../includes/alert.php"); ?>
                    <div class="form-floating">
                        <input type="text" class="form-control signin-top" id="floatingInput" name="name" placeholder="Name" value="<?php
                                            if(isset($_SESSION['name'])){
                                                echo $_SESSION['name'];
                                        }?>">
                        <label for="floatingInput">Name</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" class="form-control signin-middle" id="floatingInput" name="num_plot" placeholder="num_plot" min="1" value="<?php
                                            if(isset($_SESSION['num_plot'])){
                                                echo $_SESSION['num_plot'];
                                        }?>">
                        <label for="floatingInput">Number of Plots</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" class="form-control signin-middle" id="floatingInput" name="min_price" placeholder="min_price" min="0" step="0.01" value="<?php
                                            if(isset($_SESSION['min_price'])){
                                                echo $_SESSION['min_price'];
                                        }?>">
                        <label for="floatingInput">Minimum Price</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" class="form-control signin-middle" id="floatingInput" name="max_price" placeholder="max_price" min="0" step="0.01" value="<?php
                                            if(isset($_SESSION['max_price'])){
                                                echo $_SESSION['max_price'];
                                        }?>">
                        <label for="floatingInput">Maximum Price</label>
                    </div>
                    <input type="file" class="form-control signin-bottom mb-2" name="img-path" placeholder="img-path" accept="image/*">
                    <button class="btn btn-darker-grey w-100 py-2 border-darker-grey" name="create" type="submit">Create</button>
                </form>
                <div class=" d-flex justify-content-center mt-2">
                    <a href="/gravekeepercms/section/" class="text-decoration-none a-darker-grey">Back</a>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

<?php
  include("../includes/footer.php");
?>