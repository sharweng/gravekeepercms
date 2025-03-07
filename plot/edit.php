<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');
    include('../includes/notAdminRedirect.php');

    $stat_sql = "SELECT * FROM status";
    $stat_res = mysqli_query($conn, $stat_sql);

    if(isset($_POST['plot_id']))
        $plot_id = $_POST['plot_id'];
    else
        $plot_id = $_GET['id'];
    
    $plot_sql = "SELECT * FROM plot WHERE plot_id = $plot_id";
    $plot_res = mysqli_query($conn, $plot_sql);
    $plot_row = mysqli_fetch_assoc($plot_res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Plot</title>
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
                    <h1 class="h1 mb-3 fw-bold text-center">Edit <?php echo $plot_row['description']; ?></h1>
                    <?php include("../includes/alert.php"); ?>
                    <div class="form-floating">
                        <select class="form-select signin-bottom mb-2" name="status">
                            <?php
                                while($row = mysqli_fetch_array($stat_res)){
                                    if($row['stat_id'] == 3 || $row['stat_id'] == 4 || $row['stat_id'] == 5 || $row['stat_id'] == 7)
                                        if($plot_row['stat_id'] == $row['stat_id'])
                                            echo "<option selected value=\"{$row['stat_id']}\">{$row['description']}</option>";
                                        else
                                            echo "<option value=\"{$row['stat_id']}\">{$row['description']}</option>";
                                }
                            ?>
                        </select>
                        <label for="floatingInput">Status</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control signin-bottom" id="floatingInput" name="price" placeholder="price" value="<?php
                                                echo $plot_row['price'];
                                        ?>">
                        <label for="floatingInput">Price</label>
                    </div>
                    <input type="hidden" name="plot_id" value="<?php echo $plot_id ?>"/>
                    <button class="btn btn-darker-grey w-100 py-2 border-darker-grey" name="update" type="submit">Update</button>
                </form>
                <div class=" d-flex justify-content-center mt-2">
                    <a href="/gravekeepercms/section/view_plot.php?id=<?php echo $_SESSION['sec_id']; ?>" class="text-decoration-none a-darker-grey">Back</a>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

<?php
  include("../includes/footer.php");
?>
