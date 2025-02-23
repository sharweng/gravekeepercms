<?php
session_start();
include("../includes/config.php");
include('../includes/header.php');

if (!isset($_POST['plot_id']) || !isset($_POST['section_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
    exit();
}

$plot_id = $_POST['plot_id'];
$section_id = $_POST['section_id'];

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<script>alert('You must be logged in to reserve a plot.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch user details
$user_sql = "SELECT name FROM user WHERE user_id = '$user_id'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);
$user_name = $user['name'] ?? 'Unknown';

// Fetch section details
$section_sql = "SELECT * FROM section WHERE section_id = '$section_id'";
$section_result = mysqli_query($conn, $section_sql);
$section = mysqli_fetch_assoc($section_result);

// Fetch plot details
$plot_sql = "SELECT * FROM plot WHERE plot_id = '$plot_id'";
$plot_result = mysqli_query($conn, $plot_sql);
$plot = mysqli_fetch_assoc($plot_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        <?php include('../includes/styles/style.css') ?>
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center fw-bold">Reservation Confirmation</h2>
    <div class="card">
        <img src="/gravekeepercms/section/<?php echo htmlspecialchars($section['sec_img']); ?>" class="card-img-top" alt="Section Image" style="height: 300px; object-fit: cover;">
        <div class="card-body">
            <h3 class="card-title"><?php echo htmlspecialchars($section['sec_name']); ?></h3>
            <p class="card-text"><?php echo htmlspecialchars($section['description']); ?></p>
            <hr>
            <h4>Plot Details</h4>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($plot['description']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($plot['location'] ?? 'Not specified'); ?></p>
            <p><strong>Reserved by:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p><strong>Date Placed:</strong> <?php echo date("Y-m-d H:i:s"); ?></p>
        </div>
    </div>

    <!-- Form to confirm reservation -->
    <form action="store.php" method="POST">
        <input type="hidden" name="plot_id" value="<?php echo htmlspecialchars($plot_id); ?>">
        <input type="hidden" name="section_id" value="<?php echo htmlspecialchars($section_id); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <button type="submit" class="btn btn-success mt-3">Confirm Reservation</button>
    </form>

    <a href="index.php" class="btn btn-primary mt-3">Return</a>
</div>
</body>
<?php include("../includes/footer.php"); ?>
</html>
