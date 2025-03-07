<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../includes/config.php");
include("../includes/header.php");

    if(isset($_GET['clear'])){
        unset($_GET['search']);
        unset($_GET['date_placed']);
        unset($_GET['date_reserved']);
        unset($_GET['section']);
        unset($_GET['status']);
    }
    
// Initialize variables to prevent undefined errors
$keyword = "";
$date_placed = "";
$date_reserved = "";
$section = "";
$status = "";
$burialTypeFilter = isset($_GET['burial_type']) ? $_GET['burial_type'] : null;

// Base SQL query
$reserv_sql = "SELECT 
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
               LEFT JOIN status s ON r.stat_id = s.stat_id
               JOIN section sec ON r.section_id = sec.section_id
               JOIN plot p ON r.plot_id = p.plot_id
               JOIN user u ON r.user_id = u.user_id
               LEFT JOIN burial b ON p.plot_id = b.plot_id
               LEFT JOIN bur_type bt ON b.type_id = bt.type_id
               LEFT JOIN deceased d ON b.dec_id = d.dec_id";

// Track if WHERE has been added
$whereAdded = false;
$bindParams = [];
$paramTypes = "";

// Apply filters
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $keyword = strtolower(trim($_GET['search']));
    $reserv_sql .= $whereAdded ? " AND" : " WHERE";
    $reserv_sql .= " LOWER(u.email) LIKE ?";
    $whereAdded = true;
    $bindParams[] = "%{$keyword}%";
    $paramTypes .= "s";
}

if (!empty($_GET['date_placed'])) {
    $date_placed = $_GET['date_placed'];
    $reserv_sql .= $whereAdded ? " AND" : " WHERE";
    $reserv_sql .= " r.date_placed = ?";
    $whereAdded = true;
    $bindParams[] = $date_placed;
    $paramTypes .= "s";
}

if (!empty($_GET['date_reserved'])) {
    $date_reserved = $_GET['date_reserved'];
    $reserv_sql .= $whereAdded ? " AND" : " WHERE";
    $reserv_sql .= " r.date_reserved = ?";
    $whereAdded = true;
    $bindParams[] = $date_reserved;
    $paramTypes .= "s";
}

if (!empty($_GET['section'])) {
    $section = $_GET['section'];
    $reserv_sql .= $whereAdded ? " AND" : " WHERE";
    $reserv_sql .= " r.section_id = ?";
    $whereAdded = true;
    $bindParams[] = $section;
    $paramTypes .= "s";
}

if (!empty($_GET['status'])) {
    $status = $_GET['status'];
    $reserv_sql .= $whereAdded ? " AND" : " WHERE";
    
    // Special handling for 'canceled' status
    if ($status === 'canceled') {
        $reserv_sql .= " (s.description = ? OR s.description IS NULL)";
    } else {
        $reserv_sql .= " s.description = ?";
    }
    
    $whereAdded = true;
    $bindParams[] = $status;
    $paramTypes .= "s";
}

if ($burialTypeFilter) {
    $reserv_sql .= $whereAdded ? " AND" : " WHERE";
    $reserv_sql .= " bt.description = ?";
    $whereAdded = true;
    $bindParams[] = $burialTypeFilter;
    $paramTypes .= "s";
}

// Append ORDER BY, ensuring it doesn't cause syntax errors
$reserv_sql .= " ORDER BY 
                   CASE 
                       WHEN COALESCE(s.description, 'canceled') = 'pending' THEN 1 
                       WHEN COALESCE(s.description, 'canceled') = 'confirmed' THEN 2 
                       WHEN COALESCE(s.description, 'canceled') = 'canceled' THEN 3 
                       ELSE 4 
                   END,
                   r.reserv_id DESC";

$stmt = $conn->prepare($reserv_sql);
if ($stmt) {
    // Only bind parameters if we have any
    if (!empty($bindParams)) {
        $stmt->bind_param($paramTypes, ...$bindParams);
    }
    $stmt->execute();
    $reserv_result = $stmt->get_result();
    $stmt->close();
} else {
    // Handle prepare error
    echo "Error preparing statement: " . $conn->error;
    $reserv_result = false;
}
?>

<!-- This is for the admin reservation management page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('../includes/styles/style.css'); ?>
    </style>
</head>
<body>
<div class="container-fluid px-0 mx-0">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0 w-100 py-3" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
<div class="container mt-4 mb-4">

    
    <div class="row">
        <!-- Left side - Search Form -->
        <div class="col-md-3 mb-3">
        <h2 class="text-center fw-bold mb-3">Manage Reservations</h2>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Search Filters</h5>
                </div>
                <div class="card-body">
                    <form method="get" action="">
                        <!-- Search Input -->
                        <div class="mb-1">
                            <label class="form-label small mb-0">Email</label>
                            <input class="form-control form-control-sm" type="search" placeholder="Search by Email" name="search" aria-label="Search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        </div>

                        <!-- Date Placed -->
                        <div class="mb-1">
                            <label class="form-label small mb-0">Date Placed</label>
                            <input class="form-control form-control-sm" type="date" name="date_placed" value="<?= isset($_GET['date_placed']) ? $_GET['date_placed'] : '' ?>">
                        </div>

                        <!-- Date Reserved -->
                        <div class="mb-1">
                            <label class="form-label small mb-0">Date Reserved</label>
                            <input class="form-control form-control-sm" type="date" name="date_reserved" value="<?= isset($_GET['date_reserved']) ? $_GET['date_reserved'] : '' ?>">
                        </div>

                        <!-- Section Dropdown -->
                        <div class="mb-1">
                            <label class="form-label small mb-0">Section</label>
                            <select class="form-control form-control-sm" name="section">
                                <option value="" selected disabled>Select</option>
                                <?php
                                    $selsql = "SELECT section_id, sec_name FROM section ORDER BY sec_name";
                                    $selresult = $conn->query($selsql);
                                ?>
                                <?php while ($row = $selresult->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($row['section_id']) ?>" 
                                        <?= isset($_GET['section']) && $_GET['section'] == $row['section_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['sec_name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-3">
                            <label class="form-label small mb-0">Status</label>
                            <select class="form-control form-control-sm" name="status">
                                <option value="" selected disabled>Select</option>
                                <option value="pending" <?= isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= isset($_GET['status']) && $_GET['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="canceled" <?= isset($_GET['status']) && $_GET['status'] == 'canceled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-sm btn-outline-success" type="submit">Search</button>
                            <button class="btn btn-sm btn-outline-secondary" name="clear" type="submit">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="\gravekeepercms\" class="btn btn-primary w-100">Return</a>
            </div>
        </div>
        
        <!-- Right side - Reservation Cards -->
        <div class="col-md-9">
            <?php include("../includes/alert.php"); ?>
            <div class="row">
                <?php if (mysqli_num_rows($reserv_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($reserv_result)): 
                        $formatted_price = number_format($row['price'], 2);
                        if($row['status']==='confirmed'||$row['status']==='pending')
                            echo "<div class=\"modal fade\" id=\"exampleModal{$row['reserv_id']}\" tabindex=\"-1\" aria-labelledby=\"reservationModalLabel\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-dialog-centered\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <h5 class=\"modal-title\">Reservation Details</h5>
                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                    </div>
                    <div class=\"modal-body text-wrap\">
                        <!-- Reservation Info -->
                        <h5 class=\"fw-bold\">Reservation Info</h5>
                        <div class=\"d-flex\">
                            <div class=\"fw-bold\" style=\"width:140px;\">Reservation ID:</div>
                            <div>{$row['reserv_id']}</div>
                        </div>
                        <div class=\"d-flex\">
                            <div class=\"fw-bold\" style=\"width:140px;\">Date Placed:</div>
                            <div>{$row['date_placed']}</div>
                        </div>
                        <div class=\"d-flex\">
                            <div class=\"fw-bold\" style=\"width:140px;\">Date Reserved:</div>
                            <div>{$row['date_reserved']}</div>
                        </div>
                        <div class=\"d-flex\">
                            <div class=\"fw-bold\" style=\"width:140px;\">Status:</div>
                            <div>".ucfirst($row['status'])."</div>
                        </div>
                        <div class=\"d-flex\">
                            <div class=\"fw-bold\" style=\"width:140px;\">Section-Plot:</div>
                            <div>{$row['sec_name']} - {$row['plot_desc']}</div>
                        </div>
                        <div class=\"d-flex\">
                            <div class=\"fw-bold\" style=\"width:140px;\">Price:</div>
                            <div>₱{$row['price']}</div>
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
                            <div>".ucfirst($row['burial_type'])."</div>
                        </div>

                        <hr>

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
                    </div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Close</button>
                    </div>
                </div>
            </div>
        </div>";
                    ?>
                        <div class="col-md-6 col-lg-6">
                            <div class="card mb-3 shadow-sm <?php if($row['status']==='confirmed'||$row['status']==='pending')
                                                                        echo "enlarge\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal{$row['reserv_id']}\""; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['user_name']); ?></h5>
                                    <p class="card-text mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                    <p class="card-text mb-1"><strong>Section:</strong> <?php echo htmlspecialchars($row['sec_name']); ?></p>
                                    <p class="card-text mb-1"><strong>Plot:</strong> <?php echo htmlspecialchars($row['plot_desc']); ?></p>
                                    <p class="card-text mb-1"><strong>Price:</strong> <span class="text-success fw-bold">₱<?php echo $formatted_price; ?></span></p>
                                    <p class="card-text mb-1"><strong>Date Placed:</strong> <?php echo htmlspecialchars($row['date_placed']); ?></p>
                                    <p class="card-text mb-3"><strong>Date Reserved:</strong> <?php echo htmlspecialchars($row['date_reserved'] ?? 'Not Set'); ?></p>
                                    <p class="card-text mb-2">
                                        <span class="badge w-100
                                            <?php 
                                            echo ($row['status'] === 'pending') ? 'bg-warning' : 
                                                 (($row['status'] === 'confirmed') ? 'bg-success' : 'bg-danger'); 
                                            ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                        </span>
                                    </p>

                                    <?php if ($row['status'] === 'pending'): ?>
                                        <form action="update_reservation.php" method="POST" class="d-flex gap-2">
                                            <input type="hidden" name="reserv_id" value="<?php echo $row['reserv_id']; ?>">
                                            <button type="submit" name="confirm" class="btn btn-success btn-sm w-100">
                                                Confirm
                                            </button>
                                            <button type="submit" name="cancel" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to cancel this reservation?');">
                                                Cancel
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm w-100" disabled>No Action</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 fw-bold text-center">
                        <p>No reservations found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
</body>
<?php include("../includes/footer.php"); ?>
</html>



