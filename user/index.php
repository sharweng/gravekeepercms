<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    $mode = $_GET['mode'];
    if($mode != 'user')
        include('../includes/notAdminRedirect.php');
    
    $sql = "SELECT u.user_id, u.email, u.name, u.phone, u.role_id, u.stat_id, 
        r.description AS role_desc, s.description AS stat_desc
        FROM user u INNER JOIN role r ON u.role_id = r.role_id
        INNER JOIN status s ON u.stat_id = s.stat_id";

    if(isset($_GET['clear'])){
        unset($_GET['search']);
        unset($_GET['role_desc']);
        unset($_GET['stat_desc']);
    }

    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));

    if (!empty($_GET['role_desc'])) {
        $role_desc = mysqli_real_escape_string($conn, $_GET['role_desc']);
        $sql .= " AND r.description = '$role_desc'";
    }
    
    if (!empty($_GET['stat_desc'])) {
        $stat_desc = mysqli_real_escape_string($conn, $_GET['stat_desc']);
        $sql .= " AND s.description = '$stat_desc'";
    }

    if($keyword){
        $sql = $sql . " WHERE u.email LIKE '%{$keyword}%'";
    }
    
    $sql = $sql." ORDER BY u.user_id ASC";
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

<!-- This is for the user management page -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
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
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0 w-100 py-3" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    
    <div class="container mt-4">
    
        <div class="row">
            <!-- Left side - Search and Actions -->
            <div class="col-md-3 mb-3">
            <h2 class="text-center fw-bold mb-3">Manage Users</h2>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Search Filters</h5>
                    </div>
                    <div class="card-body">
                        <form method="get" action="">
                            <div class="mb-1">
                                <label class="form-label small mb-0">Email</label>
                                <input class="form-control form-control-sm" type="search" placeholder="Search by Email" name="search" aria-label="Search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            </div>
                            <div class="mb-1">
                                <label class="form-label small mb-0">Role</label>
                                <select class="form-control form-control-sm" name="role_desc">
                                    <option value="" selected disabled>Select</option>
                                    <option value="admin" <?= isset($_GET['role_desc']) && $_GET['role_desc'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="user" <?= isset($_GET['role_desc']) && $_GET['role_desc'] == 'user' ? 'selected' : '' ?>>User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small mb-0">Status</label>
                                <select class="form-control form-control-sm" name="stat_desc">
                                    <option value="" selected disabled>Select</option>
                                    <option value="active" <?= isset($_GET['stat_desc']) && $_GET['stat_desc'] == 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="deactivated" <?= isset($_GET['stat_desc']) && $_GET['stat_desc'] == 'deactivated' ? 'selected' : '' ?>>Deactivated</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-sm btn-outline-success" type="submit">Search</button>
                                <button class="btn btn-sm btn-outline-secondary" name="clear" type="submit">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <div class="d-flex w-100 gap-1">
                        <?php 
                            echo "<a class=\"btn btn-darker-grey py-2 border-darker-grey fw-bold\" href=\"/gravekeepercms/\" style=\"width: 40px;\"><</a>
                                <a class=\"btn btn-darker-grey w-100 py-2 border-darker-grey\" "; 
                                    echo "href=\"register.php?mode=admin\">Create</a>";
                        ?>
                    </div>
                    <a class="btn btn-darker-grey w-100 py-2 border-darker-grey" href="/gravekeepercms/">Back</a>
                </div>
            </div>
            
            <!-- Right side - User List -->
            <div class="col-md-9">
                <div class="container d-flex gap-2 justify-content-center flex-wrap">
                    <?php 
                      if($result->num_rows!=0){
                        while($row = mysqli_fetch_array($result)){ 
                          echo "<div href=\"#\" class=\"w-100 px-3 py-2 text-decoration-none form-signin align-items-center justify-content-between d-flex border rounded enlarge p-1\" style=\"width: 230px; height:\" >";?>
                                <div class=" fw-bold">
                                    <?php echo $row['email'] ?>
                                </div>
                                <?php
                                echo "<div class=\"d-flex gap-1\">
                                        <form "; 
                                        echo "action=\"profile.php?mode=admin\" "; 
                                        echo "method=\"post\" class=\"col\">
                                          <div class=\"col btn btn-success fw-bold w-100 btn-sm\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal{$row['user_id']}\">VIEW</div>
                                        </form>
                                        <form "; 
                                        echo "action=\"profile.php?mode=admin\" "; 
                                        echo "method=\"post\" class=\"col\">
                                          <input type=\"hidden\" name=\"user_id\" value=\"{$row['user_id']}\" />
                                          <button class=\"col btn btn-warning fw-bold w-100 btn-sm\" name=\"submit-update\" onclick=\"event.stopPropagation();\">EDIT</button>
                                        </form>
                                        <div class=\"dropdown col d-block\">
                                            <button class=\"btn btn-danger fw-bold btn-sm w-100 dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\" ;/>
                                                DELETE
                                            </button>
                                            <ul class=\"dropdown-menu\">
                                                <form ";
                                                    echo "action=\"store.php?mode=admin\" ";
                                                    echo "method=\"post\" class=\"col\">
                                                    <input type=\"hidden\" name=\"user_id\" value=\"{$row['user_id']}\" />
                                                    <button class=\"dropdown-item btn-sm w-100\" name=\"submit-softdelete\" onclick=\"event.stopPropagation();\">SOFT-DELETE</button>
                                                </form>
                                                <form "; 
                                                    echo "action=\"store.php?mode=admin\" "; 
                                                    echo "method=\"post\" class=\"col\">
                                                    <input type=\"hidden\" name=\"user_id\" value=\"{$row['user_id']}\" />
                                                    <button class=\"dropdown-item btn-sm w-100\" name=\"submit-delete\" onclick=\"event.stopPropagation();\">DELETE</button>
                                                </form>
                                            </ul>
                                        </div>
                                      </div>";
                            echo "
                            </div>";
                            echo "<div class=\"modal fade\" id=\"exampleModal{$row['user_id']}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                <div class=\"modal-dialog modal-dialog-centered modal-sm\">
                                    <div class=\"modal-content\">
                                        <div class=\"modal-header\">
                                            <h1 class=\"modal-title text-wrap fw-bold fs-5\" id=\"exampleModalLabel\">{$row['email']}</h1>
                                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                        </div>
                                        <div class=\"modal-body text-wrap\">
                                            <div class=\"d-flex\">
                                                <div class=\"fw-bold\" style=\"width:65px;\">Name:</div>
                                                <div >{$row['name']}</div>
                                            </div>
                                            <div class=\"d-flex\">
                                                <div class=\"fw-bold\" style=\"width:65px;\">Phone:</div>
                                                <div >{$row['phone']}</div>
                                            </div>
                                            <div class=\"d-flex\">
                                                <div class=\"fw-bold\" style=\"width:65px;\">Role:</div>
                                                <div >".ucfirst($row['role_desc'])."</div>
                                            </div>
                                            <div class=\"d-flex\">
                                                <div class=\"fw-bold\" style=\"width:65px;\">Status:</div>
                                                <div >".ucfirst($row['stat_desc'])."</div>
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
                        echo "<p class=\"text-center mt-2 fw-bold\">No user found.</p>";
                      }
                      ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php include("../includes/footer.php"); ?>
</html>

