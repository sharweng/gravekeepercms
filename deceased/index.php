<?php
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    $mode = $_GET['mode'];
    if($mode != 'user')
        include('../includes/notAdminRedirect.php');
    
    $sql = "SELECT 
                   d.dec_id,
                   r.reserv_id, 
                   r.date_placed, 
                   r.date_reserved, 
                   COALESCE(s.description, 'canceled') AS status, 
                   sec.sec_name, 
                   p.description AS plot_desc, 
                   p.price, 
                   u.name AS user_name, 
                   u.email, 
                   b.burial_id, 
                   b.burial_date, 
                   bt.description AS burial_type, 
                   d.lname AS deceased_lname, 
                   d.fname AS deceased_fname, 
                   d.date_born, 
                   d.date_died, 
                   d.picture AS deceased_picture
               FROM reservation r
               INNER JOIN status s ON r.stat_id = s.stat_id  
               INNER JOIN section sec ON r.section_id = sec.section_id
               INNER JOIN plot p ON r.plot_id = p.plot_id
               INNER JOIN user u ON r.user_id = u.user_id  -- Join to get user details
               INNER JOIN burial b ON p.plot_id = b.plot_id  -- Join burial to get burial details
               INNER JOIN bur_type bt ON b.type_id = bt.type_id  -- Get burial type description
               INNER JOIN deceased d ON b.dec_id = d.dec_id  -- Get deceased details
               ";
    if(isset($_GET['clear'])){
        unset($_GET['search']);
        unset($_GET['burial_type']);
        unset($_GET['date_born']);
        unset($_GET['date_died']);
        unset($_GET['burial_date']);
    }
    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));

    if($keyword){
        $sql = $sql . " WHERE LOWER(CONCAT(d.lname, ', ', d.fname)) LIKE '%{$keyword}%'";
    }

    if (!empty($_GET['burial_type'])) {
        $burial_type = mysqli_real_escape_string($conn, $_GET['burial_type']);
        $sql .= " AND bt.description = '$burial_type'";
    }
    
    if (!empty($_GET['date_born'])) {
        $date_born = mysqli_real_escape_string($conn, $_GET['date_born']);
        $sql .= " AND d.date_born = '$date_born'";
    }
    
    if (!empty($_GET['date_died'])) {
        $date_died = mysqli_real_escape_string($conn, $_GET['date_died']);
        $sql .= " AND d.date_died = '$date_died'";
    }
    
    if (!empty($_GET['burial_date'])) {
        $burial_date = mysqli_real_escape_string($conn, $_GET['burial_date']);
        $sql .= " AND b.burial_date = '$burial_date'";
    }
    
    
    $sql = $sql." ORDER BY d.dec_id DESC";
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
        </main>
        <form class="row g-2 align-items-center justify-content-center mb-3" method="get" action="">
    <!-- Search Input -->
    <div class="col-12 col-sm-6 col-md-4">
        <label class="form-label small mb-0">Name</label>
        <input class="form-control" type="search" placeholder="Search by Name" name="search" aria-label="Search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    </div>

    <!-- Burial Type Dropdown -->
    <div class="col-12 col-sm-6 col-md-3">
        <label class="form-label small mb-0">Burial Type</label>
        <select class="form-control" name="burial_type">
            <option value="" selected disabled>Select</option>
            <option value="unassigned" <?= isset($_GET['burial_type']) && $_GET['burial_type'] == 'unassigned' ? 'selected' : '' ?>>Unassigned</option>
            <option value="buried" <?= isset($_GET['burial_type']) && $_GET['burial_type'] == 'buried' ? 'selected' : '' ?>>Buried</option>
            <option value="cremated" <?= isset($_GET['burial_type']) && $_GET['burial_type'] == 'cremated' ? 'selected' : '' ?>>Cremated</option>
        </select>
    </div>

    <!-- Date Born -->
    <div class="col-6 col-sm-4 col-md-3">
        <label class="form-label small mb-0">Born</label>
        <input class="form-control" type="date" name="date_born" value="<?= isset($_GET['date_born']) ? $_GET['date_born'] : '' ?>">
    </div>

    <!-- Date Died -->
    <div class="col-6 col-sm-4 col-md-3">
        <label class="form-label small mb-0">Died</label>
        <input class="form-control" type="date" name="date_died" value="<?= isset($_GET['date_died']) ? $_GET['date_died'] : '' ?>">
    </div>

    <!-- Burial Date -->
    <div class="col-6 col-sm-4 col-md-3">
        <label class="form-label small mb-0">Burial Date</label>
        <input class="form-control" type="date" name="burial_date" value="<?= isset($_GET['burial_date']) ? $_GET['burial_date'] : '' ?>">
    </div>

    <!-- Buttons -->
    <div class="col-12 col-md-auto">
        <label class="form-label small mb-0">&nbsp</label>
        <button class="btn btn-outline-success w-100" type="submit">Search</button>
    </div>
    <div class="col-12 col-md-auto">
        <label class="form-label small mb-0">&nbsp</label>
        <button class="btn btn-outline-secondary w-100" name="clear" type="submit">Clear</button>
    </div>
</form>

            
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
                        Burial Type
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
                        <?php echo $row['deceased_lname'] ?>, <?php echo $row['deceased_fname'] ?>
                    </div>
                    <div class="col d-grid align-items-center text-wrap text-center">
                        <?php echo $row['date_born'] ?> - <?php echo $row['date_died'] ?>
                    </div>
                    <div class="col d-grid align-items-center text-wrap text-center">
                        <?php echo $row['burial_type'] ?>
                    </div>
                    <?php
                    echo "<div class=\"col d-flex align-items-center text-wrap text-center gap-1\">
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
            echo "<div class=\"modal fade\" id=\"exampleModal{$row['dec_id']}\" tabindex=\"-1\" aria-labelledby=\"reservationModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-dialog-centered\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h5 class=\"modal-title\">Deceased Details</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body text-wrap\">
            <!-- Deceased Info -->
                <h5 class=\"fw-bold\">Deceased Info</h5>
                <div class=\"text-center mb-3\">
                    <img class=\"object-fit-contain border rounded\" src=\"/gravekeepercms/deceased/{$row['deceased_picture']}\" alt=\"\" style=\"width: 100px; height: 100px\">
                </div>
                <div class=\"d-flex\">
                    <div class=\"fw-bold\" style=\"width:140px;\">Name:</div>
                    <div>{$row['deceased_lname']}, {$row['deceased_fname']}</div>
                </div>
                <div class=\"d-flex\">
                    <div class=\"fw-bold\" style=\"width:140px;\">Date Born:</div>
                    <div>{$row['date_born']}</div>
                </div>
                <div class=\"d-flex\">
                    <div class=\"fw-bold\" style=\"width:140px;\">Date Died:</div>
                    <div>{$row['date_died']}</div>
                </div>
                <hr>
                <!-- Burial Details -->
                <h5 class=\"fw-bold\">Burial Details</h5>
                <div class=\"d-flex\">
                    <div class=\"fw-bold\" style=\"width:140px;\">Burial Date:</div>
                    <div>{$row['burial_date']}</div>
                </div>
                <div class=\"d-flex\">
                    <div class=\"fw-bold\" style=\"width:140px;\">Burial Type:</div>
                    <div>{$row['burial_type']}</div>
                </div>
                <div class=\"d-flex\">
                    <div class=\"fw-bold\" style=\"width:140px;\">Section-Plot:</div>
                    <div>{$row['sec_name']} - {$row['plot_desc']}</div>
                </div>
                <div class=\"d-flex\">
                    <div class=\"fw-bold\" style=\"width:140px;\">Price:</div>
                    <div>₱{$row['price']}</div>
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

