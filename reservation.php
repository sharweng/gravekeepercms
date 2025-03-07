<?php
session_start();
include("includes/config.php");
include("includes/header.php");

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<script>alert('You must be logged in to view reservations.'); window.location.href='login.php';</script>";
    exit();
}

// Initialize search variables
$keyword = "";
$date_placed = "";
$date_reserved = "";
$section = "";
$status = "";

// Handle clear button
if(isset($_GET['clear'])){
    unset($_GET['date_placed']);
    unset($_GET['date_reserved']);
    unset($_GET['section']);
    unset($_GET['status']);
}

// Base SQL query
$reserv_sql = "SELECT 
                   r.reserv_id, 
                   r.date_placed, 
                   r.date_reserved, 
                   COALESCE(s.description, 'canceled') AS status, 
                   sec.sec_name, 
                   sec.section_id,
                   p.description AS plot_desc, 
                   p.price, 
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
               LEFT JOIN burial b ON p.plot_id = b.plot_id
               LEFT JOIN bur_type bt ON b.type_id = bt.type_id
               LEFT JOIN deceased d ON b.dec_id = d.dec_id
               WHERE r.user_id = ?";

// Track if additional WHERE conditions have been added
$whereAdded = true; // Already have WHERE r.user_id = ?
$bindParams = [$user_id];
$paramTypes = "i"; // Assuming user_id is integer

// Apply filters

if (!empty($_GET['date_placed'])) {
    $date_placed = $_GET['date_placed'];
    $reserv_sql .= " AND r.date_placed = ?";
    $bindParams[] = $date_placed;
    $paramTypes .= "s";
}

if (!empty($_GET['date_reserved'])) {
    $date_reserved = $_GET['date_reserved'];
    $reserv_sql .= " AND r.date_reserved = ?";
    $bindParams[] = $date_reserved;
    $paramTypes .= "s";
}

if (!empty($_GET['section'])) {
    $section = $_GET['section'];
    $reserv_sql .= " AND r.section_id = ?";
    $bindParams[] = $section;
    $paramTypes .= "s";
}

if (!empty($_GET['status'])) {
    $status = $_GET['status'];
    
    // Special handling for 'canceled' status
    if ($status === 'canceled') {
        $reserv_sql .= " AND (s.description = ? OR s.description IS NULL)";
    } else {
        $reserv_sql .= " AND s.description = ?";
    }
    
    $bindParams[] = $status;
    $paramTypes .= "s";
}

// Append ORDER BY
$reserv_sql .= " ORDER BY 
                   CASE 
                       WHEN COALESCE(s.description, 'canceled') = 'pending' THEN 1 
                       WHEN COALESCE(s.description, 'canceled') = 'confirmed' THEN 2 
                       WHEN COALESCE(s.description, 'canceled') = 'canceled' THEN 3 
                       ELSE 4 
                   END,
                   r.reserv_id DESC";

// Use prepared statement
$stmt = $conn->prepare($reserv_sql);
if ($stmt) {
    $stmt->bind_param($paramTypes, ...$bindParams);
    $stmt->execute();
    $reserv_result = $stmt->get_result();
    $stmt->close();
} else {
    // Handle prepare error
    echo "Error preparing statement: " . $conn->error;
    $reserv_result = false;
}
?>

<!-- This is for the user-facing reservation page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('includes/styles/style.css'); ?>
    </style>
</head>
<body>
<div class="container mt-4 mb-4">
   
    
    <div class="row">
        <!-- Left side - Search Form -->
        <div class="col-md-3 mb-3">
        <h2 class="text-center fw-bold mb-3">My Reservations</h2>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Search Filters</h5>
                </div>
                <div class="card-body">
                    <form method="get" action="">
                        <!-- Date Placed -->
                        <div class="mb-3">
                            <label class="form-label small mb-0">Date Placed</label>
                            <input class="form-control form-control-sm" type="date" name="date_placed" value="<?= isset($_GET['date_placed']) ? $_GET['date_placed'] : '' ?>">
                        </div>

                        <!-- Date Reserved -->
                        <div class="mb-3">
                            <label class="form-label small mb-0">Date Reserved</label>
                            <input class="form-control form-control-sm" type="date" name="date_reserved" value="<?= isset($_GET['date_reserved']) ? $_GET['date_reserved'] : '' ?>">
                        </div>

                        <!-- Section Dropdown -->
                        <div class="mb-3">
                            <label class="form-label small mb-0">Section</label>
                            <select class="form-control form-control-sm" name="section">
                                <option value="" selected disabled>Select</option>
                                <?php
                                    $selsql = "SELECT DISTINCT sec.section_id, sec.sec_name 
                                              FROM reservation r 
                                              JOIN section sec ON r.section_id = sec.section_id 
                                              WHERE r.user_id = ? 
                                              ORDER BY sec.sec_name";
                                    $selstmt = $conn->prepare($selsql);
                                    $selstmt->bind_param("i", $user_id);
                                    $selstmt->execute();
                                    $selresult = $selstmt->get_result();
                                ?>
                                <?php while ($row = $selresult->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($row['section_id']) ?>" 
                                        <?= isset($_GET['section']) && $_GET['section'] == $row['section_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['sec_name']) ?>
                                    </option>
                                <?php endwhile; ?>
                                <?php $selstmt->close(); ?>
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
                <a href="/gravekeepercms/" class="btn btn-primary w-100">Return Home</a>
            </div>
        </div>
        
        <!-- Right side - Reservation Cards -->
        <div class="col-md-9">
            <div class="row">
                <?php include("includes/alert.php"); ?>
                <?php if ($reserv_result && mysqli_num_rows($reserv_result) > 0): ?>
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
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['sec_name']); ?></h5>
                                    <p class="card-text mb-1"><strong>Plot:</strong> <?php echo htmlspecialchars($row['plot_desc']); ?></p>
                                    <p class="card-text mb-1"><strong>Price:</strong> <span class="text-success fw-bold">₱<?php echo $formatted_price; ?></span></p>
                                    <p class="card-text mb-1"><strong>Date Placed:</strong> <?php echo htmlspecialchars($row['date_placed']); ?></p>
                                    <p class="card-text mb-3"><strong>Date Reserved:</strong> <?php echo htmlspecialchars($row['date_reserved'] ?? 'Not Set'); ?></p>
                                    <p class="card-text mb-2">
                                        <span class="badge w-100 
                                            <?php 
                                            echo ($row['status'] === 'pending') ? 'bg-warning' : 
                                                 (($row['status'] === 'confirmed') ? 'bg-success' : 
                                                 (($row['status'] === 'canceled') ? 'bg-danger' : 'bg-secondary')); 
                                            ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                        </span>
                                    </p>

                                    <?php if ($row['status'] === 'pending'): ?>
                                        <form action="cancel_reservation.php" method="POST">
                                            <input type="hidden" name="reserv_id" value="<?php echo $row['reserv_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to cancel?');">
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
</body>
<?php include("includes/footer.php"); ?>
</html>
