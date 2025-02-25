<?php
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    $mode = $_GET['mode'];
    if($mode != 'user')
        include('../includes/notAdminRedirect.php');
    
    $sql = "SELECT d.dec_id, d.lname, d.fname, d.date_born, d.date_died, d.picture, b.burial_date, 
        b.plot_id, s.sec_name, p.description AS plot_desc, bt.description AS type_desc
        FROM deceased d INNER JOIN burial b ON d.dec_id = b.dec_id
        INNER JOIN plot p ON p.plot_id = b.plot_id 
        INNER JOIN section s ON p.section_id = s.section_id
        INNER JOIN bur_type bt ON bt.type_id = b.type_id";

    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));

    if($keyword){
        $sql = $sql . " WHERE LOWER(CONCAT(d.lname, ', ', d.fname)) LIKE '%{$keyword}%'";
    }
    
    $sql = $sql." ORDER BY dec_id ASC";
    // if($mode == 'user'){
    //     $sql = $sql." WHERE u.user_id = {$_SESSION['user_id']}";
    // }
    $result = mysqli_query($conn, $sql);

    function truncateText($text, $maxLength = 24) {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength) . "...";
        }
        return $text;
    }
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
        <main class="form-signin m-auto w-100 d-grid gap-2" >
            <div class="d-flex w-100 gap-1">
                <a class="btn btn-darker-grey py-2 border-darker-grey fw-bold" href="/gravekeepercms/" style="width: 40px;"><</a>
                <a class="btn btn-darker-grey w-100 py-2 border-darker-grey" href="create.php">Add</a>
            </div>
            <form class="d-flex gap-1" method="get" action="">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" name="search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </main>
        
            
    </div>
    <div class="container d-flex gap-2 justify-content-center flex-wrap">
        <div class="w-100 py-2 align-items-center justify-content-between d-flex border rounded p-1" style="width: 230px; height:">
                <div class="row w-100 ps-3">
                    <div class=" d-grid align-items-center text-wrap fw-bold"  style="width:50px">
                        #
                    </div>
                    <div class="col d-grid align-items-center text-wrap text-center fw-bold">
                        Name
                    </div>
                    <div class="col d-grid align-items-center text-wrap text-center fw-bold">
                        Date
                    </div>
                    <div class="col d-grid align-items-center text-wrap text-center fw-bold">
                        Action 
                    </div>
                </div>
            </div>
    <?php 
      if($result->num_rows!=0){
        while($row = mysqli_fetch_array($result)){ 
            echo "<div href=\"#\" class=\"w-100 py-2 align-items-center justify-content-between d-flex border rounded enlarge p-1\" style=\"width: 230px; height:\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal{$row['dec_id']}\">";?>
                <div class="row w-100 ps-3">
                    <div class=" d-grid align-items-center text-wrap"  style="width:50px">
                        <?php echo $row['dec_id'] ?>
                    </div>
                    <div class="col d-grid align-items-center text-wrap">
                        <?php echo $row['lname'] ?>, <?php echo $row['fname'] ?>
                    </div>
                    <div class="col d-grid align-items-center text-wrap text-center">
                        <?php echo $row['date_born'] ?> - <?php echo $row['date_died'] ?>
                    </div>
                    <?php
                    echo "<div class=\"col px-0 d-flex gap-1 col  align-items-center text-wrap\">
                            <form "; 
                            echo "action=\"edit.php\" "; 
                            echo "method=\"post\" class=\"col\">
                            <input type=\"hidden\" name=\"dec_id\" value=\"{$row['dec_id']}\" />
                            <button class=\"col btn btn-warning fw-bold w-100 btn-sm\" name=\"submit-update\" onclick=\"event.stopPropagation();\">EDIT</button>
                            </form>
                            <form "; 
                            echo "action=\"store.php?mode=admin\" "; 
                            echo "method=\"post\" class=\"col\">
                            <input type=\"hidden\" name=\"dec_id\" value=\"{$row['dec_id']}\" />
                            <button class=\"col btn btn-danger fw-bold w-100 btn-sm\" name=\"submit-delete\" onclick=\"event.stopPropagation();\">DELETE</button>
                            </form>
                        </div>";
                    echo "
                </div>
            </div>";
            echo "<div class=\"modal fade\" id=\"exampleModal{$row['dec_id']}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog modal-dialog-centered\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">"; ?>
                            <img class="object-fit-contain border rounded" src="<?php echo $row['picture'] ?>" alt="" style="width: 100px; height: 100px">

                            <?php echo "
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                        </div>
                        <div class=\"modal-body text-wrap\">
                            <div class=\"d-flex\">
                                <div class=\"fw-bold\" style=\"width:110px;\">Name:</div>
                                <div >{$row['lname']}, {$row['fname']}</div>
                            </div>
                            <div class=\"d-flex\">
                                <div class=\"fw-bold\" style=\"width:110px;\">Date Born:</div>
                                <div >{$row['date_born']}</div>
                            </div>
                            <div class=\"d-flex\">
                                <div class=\"fw-bold\" style=\"width:110px;\">Date Died:</div>
                                <div >{$row['date_died']}</div>
                            </div>
                            <hr>
                            <div class=\"d-flex\">
                                <div class=\"fw-bold\" style=\"width:110px;\">Burial Date:</div>
                                <div >{$row['burial_date']}</div>
                            </div>
                            <div class=\"d-flex\">
                                <div class=\"fw-bold\" style=\"width:110px;\">Burial Type:</div>
                                <div >{$row['type_desc']}</div>
                            </div>
                            <div class=\"d-flex\">
                                <div class=\"fw-bold\" style=\"width:110px;\">Section-Plot:</div>
                                <div >{$row['sec_name']} - {$row['plot_desc']}</div>
                            </div>
                        </div>
                        <div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Close</button>
                        </div>
                    </div>
                </div>
            </div>";
        }
      }else{
        echo "<p class=\"text-center mt-2 fw-bold\">No deceased record found.</p>";
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

