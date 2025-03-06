<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../includes/config.php");
include("../includes/header.php");

// Fetch all reservations with user details
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
               LEFT JOIN status s ON r.stat_id = s.stat_id  -- LEFT JOIN to include NULL (canceled) statuses
               JOIN section sec ON r.section_id = sec.section_id
               JOIN plot p ON r.plot_id = p.plot_id
               JOIN user u ON r.user_id = u.user_id  -- Join to get user details
               LEFT JOIN burial b ON p.plot_id = b.plot_id  -- Join burial to get burial details
               LEFT JOIN bur_type bt ON b.type_id = bt.type_id  -- Get burial type description
               LEFT JOIN deceased d ON b.dec_id = d.dec_id  -- Get deceased details
               ORDER BY r.stat_id DESC, r.reserv_id DESC";

$reserv_result = mysqli_query($conn, $reserv_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('../includes/styles/style.css'); ?>
    </style>
</head>
<body>
<div class="container mt-4 mb-4">
    <h2 class="text-center fw-bold">Manage Reservations</h2>
    <?php include("../includes/alert.php"); ?>
    <div class="row">
        <?php if (mysqli_num_rows($reserv_result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($reserv_result)): 
                $formatted_price = number_format($row['price'], 2);
                if($row['status']==='confirmed')
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
                    <div>{$row['status']}</div>
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
                    <div>{$row['burial_type']}</div>
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
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-3 shadow-sm <?php if($row['status']==='confirmed')
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
            <div class="col-12 text-center">
                <p>No reservations found.</p>
            </div>
        <?php endif; ?>
    </div>

    <a href="\gravekeepercms\" class="btn btn-primary mt-1">Return</a>
</div>
</body>
<?php include("../includes/footer.php"); ?>
</html>
