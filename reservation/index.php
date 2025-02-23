<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    $sql = "SELECT * FROM section ";
    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reserve a Plot</title>
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
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0 w-100 py-5" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style="color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
      <img class="img-responsive" src="/gravekeepercms/section/images/heritage-map.png" alt="Heritage-Map" style="height:600px">
        <main class="form-signin m-auto w-100 d-flex gap-1" >
           
            
        </main>
    </div>
    <div class="container d-flex gap-2 justify-content-center flex-wrap">
    <?php 
      if($result->num_rows!=0){
        while($row = mysqli_fetch_array($result)){
          echo "<a href='/gravekeepercms/section/view_plot.php?id={$row['section_id']}' class='text-decoration-none card enlarge p-1' style='width: 230px;'>
            <img src='/gravekeepercms/section/{$row['sec_img']}' class='card-img-top' style='width: 220px; height: 220px; object-fit: cover;'>
            <div class='card-body'>
              <h5 class='card-title fw-bold text-truncate'>{$row['sec_name']}</h5>
              <p class='card-text'>{$row['description']}</p>
              <input type='hidden' value='{$row['section_id']}' name='section_id'>
              <div class='d-flex gap-1'>
                <form action='request.php' method='post' class='col'>
                  <input type='hidden' name='section_id' value='{$row['section_id']}' />
                  <button class='col btn btn-primary fw-bold w-100 btn-sm' name='reserve'>RESERVE</button>
                </form>
              </div>
            </div>
          </a>";
        }
      }else{
        echo "<p class='text-center mt-2 fw-bold'>No sections found.</p>";
      }
      ?>
    </div>
</body>
<?php
  include("../includes/footer.php");
?>
</html>
