<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    $mode = $_GET['mode'] ?? '';
    $from = $_GET['from'] ?? '';
    if($mode != 'user'&&$from != 'header')
        include('../includes/notAdminRedirect.php');
    
    // Clear filters if requested
    if(isset($_GET['clear'])){
        unset($_GET['email']);
        unset($_GET['name']);
        unset($_GET['rating']);
        unset($_GET['message']);
    }
    
    // Initialize filter variables
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $name = isset($_GET['name']) ? $_GET['name'] : '';
    $rating = isset($_GET['rating']) ? $_GET['rating'] : '';
    $message = isset($_GET['message']) ? $_GET['message'] : '';
    
    // Base SQL query
    $sql = "SELECT r.rev_id, r.user_id, r.rev_msg, r.rev_num, u.email, u.name FROM review r INNER JOIN user u ON u.user_id = r.user_id";
    
    // Track if WHERE has been added
    $whereAdded = false;
    $conditions = [];
    
    // Apply filters
    if (!empty($email)) {
        $conditions[] = "u.email LIKE '%$email%'";
        $whereAdded = true;
    }
    
    if (!empty($name)) {
        $conditions[] = "u.name LIKE '%$name%'";
        $whereAdded = true;
    }
    
    if (!empty($rating)) {
        $conditions[] = "r.rev_num = $rating";
        $whereAdded = true;
    }
    
    if (!empty($message)) {
        $conditions[] = "r.rev_msg LIKE '%$message%'";
        $whereAdded = true;
    }
    
    // Add WHERE clause if any filters are applied
    if ($whereAdded) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    // Add mode condition
    if($mode == 'user'){
        if($whereAdded) {
            $sql .= " AND u.user_id = {$_SESSION['user_id']}";
        } else {
            $sql .= " WHERE u.user_id = {$_SESSION['user_id']}";
        }
    }
    
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
  <title>Manage reviews</title>
  <!-- BOOTSTRAP AND CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        <?php include('../includes/styles/style.css') ?>
        
        /* Add custom styles for sticky sidebar */
        @media (min-width: 768px) {
            .sticky-sidebar {
                position: sticky;
                top: 20px;
                height: calc(100vh - 40px);
                overflow-y: auto;
            }
            
            .scrollable-content {
                height: calc(100vh - 40px);
                overflow-y: auto;
                padding-bottom: 20px;
            }
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
    
    <div class="container mt-4">
        <div class="row">
            <!-- Left side - Search Filters with sticky positioning -->
            <div class="col-md-3 mb-3">
                <div class="sticky-sidebar">
                    <?php if($mode == 'user'){ ?>
                        <h2 class="text-center fw-bold mb-3">My Reviews</h2>
                    <?php }elseif($from != 'header'){ ?>
                        <h2 class="text-center fw-bold mb-3">Manage Reviews</h2>
                    <?php }else{ ?>
                        <h2 class="text-center fw-bold mb-3">Reviews</h2>
                    <?php }?>
                    
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Search Filters</h5>
                        </div>
                        <div class="card-body">
                            <form method="get" action="">
                                <!-- Preserve mode and from parameters -->
                                <?php if($mode): ?>
                                    <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode); ?>">
                                <?php endif; ?>
                                <?php if($from): ?>
                                    <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                                <?php endif; ?>
                                
                                <!-- Email Filter -->
                                <div class="mb-1">
                                    <label class="form-label small mb-0">Email</label>
                                    <input class="form-control form-control-sm" type="text" placeholder="Search by Email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                
                                <!-- Name Filter -->
                                <div class="mb-1">
                                    <label class="form-label small mb-0">User Name</label>
                                    <input class="form-control form-control-sm" type="text" placeholder="Search by Name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                </div>
                                
                                <!-- Rating Filter -->
                                <div class="mb-1">
                                    <label class="form-label small mb-0">Rating</label>
                                    <select class="form-control form-control-sm" name="rating">
                                        <option value="" <?php echo empty($rating) ? 'selected' : ''; ?>>All Ratings</option>
                                        <option value="1" <?php echo $rating == '1' ? 'selected' : ''; ?>>1 Star</option>
                                        <option value="2" <?php echo $rating == '2' ? 'selected' : ''; ?>>2 Stars</option>
                                        <option value="3" <?php echo $rating == '3' ? 'selected' : ''; ?>>3 Stars</option>
                                        <option value="4" <?php echo $rating == '4' ? 'selected' : ''; ?>>4 Stars</option>
                                        <option value="5" <?php echo $rating == '5' ? 'selected' : ''; ?>>5 Stars</option>
                                    </select>
                                </div>
                                
                                <!-- Message Filter -->
                                <div class="mb-3">
                                    <label class="form-label small mb-0">Message Contains</label>
                                    <input class="form-control form-control-sm" type="text" placeholder="Search in Message" name="message" value="<?php echo htmlspecialchars($message); ?>">
                                </div>
                                
                                <!-- Buttons -->
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
                                if($from != 'header'){
                                    echo "<a class=\"btn btn-darker-grey py-2 border-darker-grey fw-bold\" href=\"/gravekeepercms/\" style=\"width: 40px;\"><</a>
                                      <a class=\"btn btn-darker-grey w-100 py-2 border-darker-grey\" "; 
                                        if($mode == 'user')
                                            echo "href=\"create.php?mode=user\">Create</a>";
                                        else
                                            echo "href=\"create.php\">Create</a>";
                                }else{
                                    echo "<a class=\"btn btn-darker-grey w-100 py-2 border-darker-grey\" href=\"/gravekeepercms/\">Back</a>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right side - Review Cards with scrollable content -->
            <div class="col-md-9">
                <div class="scrollable-content">
                    <div class="container d-flex gap-2 justify-content-center flex-wrap">
                    <?php 
                      if($result->num_rows!=0){
                        while($row = mysqli_fetch_array($result)){ 
                          echo "<div href=\"#\" class=\"text-decoration-none card enlarge p-1\" style=\"width: 230px; height:\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal{$row['rev_id']}\">
                            <div class=\"card-body \">
                              <h5 class=\"card-title fw-bold text-truncate\">{$row['email']}</h5>
                              <p class=\"card-text mb-0\"><b>Name: </b>{$row['name']}</p>
                              <p class=\"card-text mb-0\"><b>Rating: </b>{$row['rev_num']}</p>
                              <p class=\"card-text mb-2\">"; echo truncateText($row['rev_msg']); echo "</p>
                              <input type=\"hidden\" value=\"{$row['rev_id']}\" name=\"rev_id\">";
                              if(($_SESSION['roleDesc'] == 'admin')||($mode == 'user')){
                                echo "<div class=\"d-flex gap-1\">
                                        <form "; 
                                            if($mode == 'user')
                                                echo "action=\"edit.php?mode=user\" "; 
                                            else
                                                echo "action=\"edit.php\" "; 
                                        echo "method=\"post\" class=\"col\">
                                          <input type=\"hidden\" name=\"rev_id\" value=\"{$row['rev_id']}\" />
                                          <button class=\"col btn btn-warning fw-bold w-100 btn-sm\" name=\"edit\" onclick=\"event.stopPropagation();\">EDIT</button>
                                        </form>
                                        <form "; 
                                            if($mode == 'user')
                                                echo "action=\"delete.php?mode=user\" "; 
                                            else
                                                echo "action=\"delete.php\" "; 
                                        echo "method=\"post\" class=\"col\">
                                          <input type=\"hidden\" name=\"rev_id\" value=\"{$row['rev_id']}\" />
                                          <button class=\"col btn btn-danger fw-bold w-100 btn-sm\" name=\"delete\" onclick=\"event.stopPropagation();\">DELETE</button>
                                        </form>
                                      </div>";
                              }
                            echo "</div>
                            </div>";
                            echo "<div class=\"modal fade\" id=\"exampleModal{$row['rev_id']}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                <div class=\"modal-dialog modal-dialog-centered modal-sm\">
                                    <div class=\"modal-content\">
                                        <div class=\"modal-header\">
                                            <h1 class=\"modal-title text-wrap fs-5\" id=\"exampleModalLabel\"><b>Sender:</b><br> {$row['email']}</h1>
                                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                        </div>
                                        <div class=\"modal-body text-wrap\">
                                            <b>Name:</b> {$row['name']} <br>
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
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php
  include("../includes/footer.php");
?>
</html>