<?php
 session_start();
 include("includes/config.php");
 include('includes/header.php');
// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<script>alert('You must be logged in to view reservations.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch reservations for the logged-in user
$reserv_sql = "SELECT r.reserv_id, r.date_placed, r.date_reserved, s.description AS status, sec.sec_name, p.description AS plot_desc
               FROM reservation r
               JOIN status s ON r.stat_id = s.stat_id
               JOIN section sec ON r.section_id = sec.section_id
               JOIN plot p ON r.plot_id = p.plot_id
               WHERE r.user_id = '$user_id' 
               ORDER BY r.date_placed DESC";

$reserv_result = mysqli_query($conn, $reserv_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('../includes/styles/style.css'); ?>
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center fw-bold">My Reservations</h2>
    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Section</th>
                <th>Plot Description</th>
                <th>Date Placed</th>
                <th>Date Reserved</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($reserv_result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($reserv_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['reserv_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['sec_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['plot_desc']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_placed']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_reserved'] ?? 'Not Set'); ?></td>
                        <td>
                            <span class="badge 
                                <?php 
                                echo ($row['status'] === 'pending') ? 'bg-warning' : 
                                     (($row['status'] === 'confirmed') ? 'bg-success' : 'bg-secondary'); 
                                ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'pending'): ?>
                                <form action="cancel_reservation.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="reserv_id" value="<?php echo $row['reserv_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel?');">
                                        Cancel
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>No Action</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No reservations found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-primary">Return Home</a>
</div>
</body>
<?php include("includes/footer.php"); ?>
</html>
