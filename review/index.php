<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    $sql = "SELECT r.rev_id, r.user_id, r.rev_msg, r.rev_num, u.email FROM review r INNER JOIN user u ON u.user_id = r.user_id";
    $result = mysqli_query($conn, $sql);

    function truncateText($text, $maxLength = 46) {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength) . "...";
        }
        return $text;
    }

    $rev_id = $_GET['id'];
    echo $rev_id;

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
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
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
          echo "<div href=\"#\" class=\"text-decoration-none card enlarge p-1\" style=\"width: 230px; height:\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal{$row['rev_id']}\">
            <div class=\"card-body \">
              <h5 class=\"card-title fw-bold text-truncate\">{$row['email']}</h5>
              <p class=\"card-text\"><b>Rating: </b>{$row['rev_num']}</p>
              <p class=\"card-text\">"; echo truncateText($row['rev_msg']); echo "</p>
              <input type=\"hidden\" value=\"{$row['rev_id']}\" name=\"rev_id\">";
              if($_SESSION['roleDesc'] == 'admin'){
                echo "<div class=\"d-flex gap-1\">
                        <form action=\"edit.php\" method=\"post\" class=\"col\">
                          <input type=\"hidden\" name=\"rev_id\" value=\"{$row['rev_id']}\" />
                          <button class=\"col btn btn-warning fw-bold w-100 btn-sm\" name=\"edit\" onclick=\"event.stopPropagation();\">EDIT</button>
                        </form>
                        <form action=\"delete.php\" method=\"post\" class=\"col\">
                          <input type=\"hidden\" name=\"rev_id\" value=\"{$row['rev_id']}\" />
                          <button class=\"col btn btn-danger fw-bold w-100 btn-sm\" name=\"delete\" onclick=\"event.stopPropagation();\">DELETE</button>
                        </form>
                      </div>";
              }
            echo "</div>
            </div>";
            echo "<div class=\"modal fade\" id=\"exampleModal{$row['rev_id']}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog modal-dialog-centered\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <h1 class=\"modal-title fs-5\" id=\"exampleModalLabel\"><b>Sender:</b> {$row['email']}</h1>
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                        </div>
                        <div class=\"modal-body\">
                            <b>Rating:</b> {$row['rev_num']} <br>
                            <b>Message:</b> <br>{$row['rev_msg']}
                        </div>
                        <div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Close</button>
                        </div>
                    </div>
                </div>
            </div>";
        }
      }else{
        echo "<p class=\"text-center mt-2 fw-bold\">No review found.</p>";
      }
      ?>
    </div>
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center mb-4 ">
        <main class="form-signin m-auto w-100">
            <a class="btn btn-darker-grey w-100 py-2 border-darker-grey" href="/gravekeepercms/review/">Back</a>
        </main>
    </div>
</body>
<?php
  include("../includes/footer.php");
?>
</html>

