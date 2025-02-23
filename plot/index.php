<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    $plot_id = $_GET['id'];
    $_SESSION['plot_id'] = $plot_id;

    $plot_sql = "SELECT * FROM plot WHERE plot_id = $plot_id";
    $plot_sql = "SELECT 
      p.plot_id, 
      sec.sec_name,
      p.description AS plot_desc, 
      b.description AS type_desc,  
      s.description AS status_desc 
      FROM plot p 
      INNER JOIN status s ON p.stat_id = s.stat_id 
      INNER JOIN bur_type b ON b.type_id = p.type_id 
      INNER JOIN section sec ON sec.section_id = p.section_id
      WHERE p.plot_id = $plot_id";
    $plot_res = mysqli_query($conn, $plot_sql);
    $row = mysqli_fetch_assoc($plot_res);
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
<div class="container-fluid px-0 mx-0">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0 w-100 py-5" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for login form -->
    <div class="container d-flex gap-1 justify-content-center flex-wrap">
      <div class="card w-25 mt-3">
        <div class="card-body">
        <h5 class="card-title"><?php echo $row['plot_desc']; ?></h5>
        <b>Section : </b><?php echo $row['sec_name']; ?> <br>
        <b>Burial Type: </b><?php echo $row['type_desc']; ?> <br>
        <b>Status: </b><?php echo $row['status_desc']; ?> <br>
        </div>
        <main class="form-signin m-auto w-100">
              <a class="btn btn-darker-grey w-100 py-2 border-darker-grey" href="/gravekeepercms/section/view_plot.php?id=<?php echo $_SESSION['sec_id']; ?>">Back</a>
        </main>
      </div>
    </div>
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center mb-4 ">
    </div>
</body>
<?php
  include("../includes/footer.php");
?>
</html>

