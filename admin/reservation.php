<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");

// Fetch all reservations with user details
$reserv_sql = "SELECT r.reserv_id, r.date_placed, r.date_reserved, s.description AS status, 
                      sec.sec_name, p.description AS plot_desc, u.name AS user_name, u.email
               FROM reservation r
               JOIN status s ON r.stat_id = s.stat_id
               JOIN section sec ON r.section_id = sec.section_id
               JOIN plot p ON r.plot_id = p.plot_id
               JOIN user u ON r.user_id = u.user_id
               ORDER BY r.reserv_id DESC";

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
<div class="container mt-4">
    <h2 class="text-center fw-bold">Manage Reservations</h2>

    <div class="row">
        <?php if (mysqli_num_rows($reserv_result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($reserv_result)): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['user_name']); ?></h5>
                            <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                            <p class="card-text"><strong>Section:</strong> <?php echo htmlspecialchars($row['sec_name']); ?></p>
                            <p class="card-text"><strong>Plot:</strong> <?php echo htmlspecialchars($row['plot_desc']); ?></p>
                            <p class="card-text"><strong>Date Placed:</strong> <?php echo htmlspecialchars($row['date_placed']); ?></p>
                            <p class="card-text"><strong>Date Reserved:</strong> <?php echo htmlspecialchars($row['date_reserved'] ?? 'Not Set'); ?></p>
                            <p class="card-text">
                                <span class="badge 
                                    <?php 
                                    echo ($row['status'] === 'pending') ? 'bg-warning' : 
                                         (($row['status'] === 'confirmed') ? 'bg-success' : 'bg-secondary'); 
                                    ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
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
                                <button class="btn btn-secondary btn-sm w-100 mt-2" disabled>No Action</button>
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

    <a href="\gravekeepercms\" class="btn btn-primary mt-3">Return</a>
</div>
</body>
<?php include("../includes/footer.php"); ?>
</html>
