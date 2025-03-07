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
  <title>Sections</title>
  <!-- BOOTSTRAP AND CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        <?php include('../includes/styles/style.css') ?>
        
        /* Add custom styles for consistent image display */
        .section-image-container {
            width: 220px;
            height: 220px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .section-image {
            width: 100%;
            height: 100%;
            object-fit: cover; /* This ensures the image covers the entire container */
        }
        
        .section-card {
            width: 230px;
            height: auto;
            margin-bottom: 15px;
        }

        .price {
          font-size: 15px;
        }
    </style>
</head>
<body>
<div class="container-fluid px-0 mx-0">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0 w-100 py-3" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for login form -->
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
      <img class="img-responsive" src="/gravekeepercms/section/images/heritage-map.png" alt="Heritage-Map" style="height:600px">
        <main class="form-signin m-auto w-100 d-flex gap-1" >
            <?php 
              if($_SESSION['roleDesc'] == 'admin'){
                echo "<a class=\"btn btn-darker-grey py-2 border-darker-grey fw-bold\" href=\"/gravekeepercms/\" style=\"width: 40px;\"><</a>
                      <a class=\"btn btn-darker-grey w-100 py-2 border-darker-grey\" href=\"create.php\">Create</a>";
              }else{
                echo "<a class=\"btn btn-darker-grey w-100 py-2 border-darker-grey\" href=\"/gravekeepercms/\">Back</a>";
              }
            ?>
        </main>
    </div>
    <div class="container d-flex gap-2 justify-content-center flex-wrap">
    <?php 
      if($result->num_rows!=0){
        while($row = mysqli_fetch_array($result)){
          echo "<a href=\"/gravekeepercms/section/view_plot.php?id={$row['section_id']}\" class=\"text-decoration-none card enlarge p-1 section-card\">
            <div class=\"section-image-container\">
              <img src=\"/gravekeepercms/section/{$row['sec_img']}\" class=\"section-image\" alt=\"{$row['sec_name']}\">
            </div>
            <div class=\"card-body \">
              <h5 class=\"card-title fw-bold text-truncate mb-1\">{$row['sec_name']}</h5>
              <h5 class=\"price fw-bold text-success\">₱". number_format($row['min_price'])." - ₱". number_format($row['max_price'])."</h5>
              <input type=\"hidden\" value=\"{$row['section_id']}\" name=\"section_id\">";
              if($_SESSION['roleDesc'] == 'admin'){
                echo "<div class=\"d-flex gap-1\">
                        <form action=\"edit.php\" method=\"post\" class=\"col\">
                          <input type=\"hidden\" name=\"section_id\" value=\"{$row['section_id']}\" />
                          <button class=\"col btn btn-warning fw-bold w-100 btn-sm\" name=\"edit\">EDIT</button>
                        </form>
                        <form action=\"delete.php\" method=\"post\" class=\"col\">
                          <input type=\"hidden\" name=\"section_id\" value=\"{$row['section_id']}\" />
                          <button class=\"col btn btn-danger fw-bold w-100 btn-sm\" name=\"delete\">DELETE</button>
                        </form>
                      </div>";
              }else{
                $check_sql = "SELECT COUNT(*) as num
                              FROM plot WHERE stat_id = 3 AND section_id = {$row['section_id']}";
                $check_res = mysqli_query($conn, $check_sql);
                $check = mysqli_fetch_assoc($check_res);
                
                if($check['num'] == 0)
                  echo "<div class=\"col\">
                          <input type=\"hidden\" name=\"section_id\" value=\"{$row['section_id']}\" />
                          <button class=\"col btn btn-danger fw-bold w-100 btn-sm\" name=\"delete\">FULL</button>
                        </div>";
                else
                  echo "<div class=\"col\">
                          <input type=\"hidden\" name=\"section_id\" value=\"{$row['section_id']}\" />
                          <button class=\"col btn btn-primary fw-bold w-100 btn-sm\" name=\"delete\">RESERVE</button>
                        </div>";
              }
            echo "</div>
          </a>";
        }
      }else{
        echo "<p class=\"text-center mt-2 fw-bold\">No sections found.</p>";
      }
      ?>
    </div>
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center mb-4 ">
        <main class="form-signin m-auto w-100">
            <a class="btn btn-darker-grey w-100 py-2 border-darker-grey" href="/gravekeepercms/">Back</a>
        </main>
    </div>
</body>
<?php
  include("../includes/footer.php");
?>
</html>