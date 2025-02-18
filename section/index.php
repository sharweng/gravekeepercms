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
<div class="container-fluid px-0 mx-0 h-100">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0 w-100 py-5" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for login form -->
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
        <main class="form-signin m-auto w-100">
            <a class="btn btn-darker-grey w-100 py-2 border-darker-grey" href="create.php">Create</a>
        </main>
    </div>
    <div class="container d-flex gap-2 justify-content-center">
    <?php 
      if($result->num_rows!=0){
        while($row = mysqli_fetch_array($result)){
          echo "<a href=\"/gravekeepercms/\" class=\"text-decoration-none card enlarge p-1\" style=\"width: 230px; height:\">
            <img src=\"/gravekeepercms/section/{$row['sec_img']}\" class=\"card-img-top\" style\"=width: 220px; height: 220px; object-fit: cover;\">
            <div class=\"card-body \">
              <h5 class=\"card-title fw-bold text-truncate\">{$row['sec_name']}</h5>
              <p class=\"card-text\">{$row['description']}</p>
              <input type=\"hidden\" value=\"{$row['section_id']}\" name=\"section_id\">
              <div class=\"d-flex gap-1\">
                <form action=\"edit.php\" method=\"post\" class=\"col\">
                <input type=\"hidden\" name=\"section_id\" value=\"{$row['section_id']}\" />
                  <button class=\"col btn btn-warning fw-bold w-100 btn-sm\" name=\"edit\">EDIT</button>
                </form>
                <form action=\"delete.php\" method=\"post\" class=\"col\">
                  <input type=\"hidden\" name=\"section_id\" value=\"{$row['section_id']}\" />
                  <button class=\"col btn btn-danger fw-bold w-100 btn-sm\" name=\"delete\">DELETE</button>
                </form>
              </div>
            </div>
          </a>";
        }
      }else{
        echo "<p class=\"text-center mt-2 fw-bold\">No sections found.</p>";
      }
      ?>
    </div>
</body>
</html>

<?php
  include("../includes/footer.php");
?>
