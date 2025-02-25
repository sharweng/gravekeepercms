<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');
    $sec_id = $_GET['id'];
    $_SESSION['sec_id'] = $sec_id;

    $sql = "SELECT 
      p.plot_id, 
      p.section_id, 
      p.description AS plot_desc, 
      p.stat_id, 
      s.description AS status_desc 
      FROM plot p 
      INNER JOIN status s ON p.stat_id = s.stat_id 
      WHERE p.section_id = $sec_id
      ORDER BY CAST(SUBSTRING_INDEX(plot_desc, ' ', -1) AS UNSIGNED) ASC"; 
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
<div class="container-fluid px-0 mx-0">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0 w-100 py-5" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for login form -->
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
        <main class="form-signin m-auto w-100 gap-1 d-grid" >
            <div class="gap-1 d-flex">
              <?php 
                if($_SESSION['roleDesc'] == 'admin'){
                  echo "<a class=\"btn btn-darker-grey py-2 border-darker-grey fw-bold\" href=\"/gravekeepercms/section/\" style=\"width: 40px;\"><</a>
                        <a class=\"btn btn-darker-grey w-100 py-2 border-darker-grey\" href=\"/gravekeepercms/plot/create.php\">Create</a>";
                }else{
                  echo "<a class=\"btn btn-darker-grey w-100 py-2 border-darker-grey\" href=\"/gravekeepercms/section/\">Back</a>";
                }
              ?>
            </div>
            <div>
              <?php include("../includes/alert.php"); ?>
            </div>
            <h1 class="fw-bold text-center mt-2">Section <?php echo $_SESSION['sec_id'] ?></h1>
        </main>
        
    </div>
    <div class="container d-flex gap-2 justify-content-center flex-wrap">
    <?php 
      if($result->num_rows!=0){
        while($row = mysqli_fetch_array($result)){
          echo "<div class=\"text-decoration-none card enlarge p-1\" style=\"width: 230px; height:\"  data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal{$row['plot_id']}\">
            <div class=\"card-body \">
              <h5 class=\"card-title fw-bold text-truncate\">{$row['plot_desc']}</h5>
              <input type=\"hidden\" value=\"{$row['p.plot_id']}\" name=\"plot_id\">";
              if($_SESSION['roleDesc'] == 'admin'){
                echo "<div class=\"d-flex gap-1\">
                  <form action=\"/gravekeepercms/plot/edit.php\" method=\"post\" class=\"col\">
                    <input type=\"hidden\" name=\"plot_id\" value=\"{$row['plot_id']}\" />
                    <button class=\"col btn btn-warning fw-bold w-100 btn-sm\" name=\"edit\">EDIT</button>
                  </form>
                  <form action=\"/gravekeepercms/plot/delete.php\" method=\"post\" class=\"col\">
                    <input type=\"hidden\" name=\"plot_id\" value=\"{$row['plot_id']}\" />
                    <button class=\"col btn btn-danger fw-bold w-100 btn-sm\" name=\"delete\">DELETE</button>
                  </form>
                </div>";
              }else{?>
              <p class="mb-1">
                    <span class="badge w-100 
                        <?php echo ($row['status_desc'] == 'occupied') ? 'bg-danger' : 'bg-success'; ?>">
                        <?php echo ucfirst($row['status_desc']); ?>
                    </span>
                </p>
                
                <?php if ($row['status_desc'] !== 'occupied') { ?>
                    <form action="/gravekeepercms/reservation/confirm_reservation.php?section=<?php echo $_SESSION['sec_id'] ?>&plot=<?php echo $row['plot_id'] ?>" method="post">
                        <input type="hidden" name="plot_id" value="<?php echo $row['plot_id']; ?>">
                        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">RESERVE</button>
                    </form>
                <?php } else { ?>
                    <button class="btn btn-secondary btn-sm w-100 fw-bold" disabled>Unavailable</button>
                <?php }
                } ?>
              <?php
              
            echo "</div>
          </div>";
          echo "<div class=\"modal fade\" id=\"exampleModal{$row['plot_id']}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog modal-dialog-centered modal-sm\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <h1 class=\"modal-title fw-bold text-wrap fs-5\" id=\"exampleModalLabel\">{$row['plot_desc']}</h1>
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                        </div>
                        <div class=\"modal-body\">
                            <b>Section:</b> {$sec_id} <br>
                            <b>Status:</b> {$row['status_desc']}
                        </div>
                        <div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Close</button>
                        </div>
                    </div>
                </div>
            </div>";
        }
      }else{
        echo "<p class=\"text-center mt-2 fw-bold\">No sections found.</p>";
      }
      ?>
    </div>
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center mb-4 ">
        <main class="form-signin m-auto w-100">
            <a class="btn btn-darker-grey w-100 py-2 border-darker-grey" href="/gravekeepercms/section/">Back</a>
        </main>
    </div>
</body>
<?php
  include("../includes/footer.php");
?>
</html>

