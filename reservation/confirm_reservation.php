<?php
session_start();
include("../includes/config.php");
include('../includes/header.php');

if (!isset($_GET['plot']) || !isset($_GET['section'])) {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
    exit();
}

$plot_id = $_GET['plot'];
$section_id = $_GET['section'];

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

// Fetch plot details, including price
$plot_sql = "SELECT * FROM plot WHERE plot_id = '$plot_id'";
$plot_result = mysqli_query($conn, $plot_sql);
$plot = mysqli_fetch_assoc($plot_result);

// Fetch burial types
$burial_types_sql = "SELECT * FROM bur_type";
$burial_types_result = mysqli_query($conn, $burial_types_sql);

// Format price
$plot_price = number_format($plot['price'], 2);
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
    <div class="card mb-2">
        <div class="card-body">
            <h3 class="card-title fw-bold"><?php echo htmlspecialchars($section['sec_name']).' - '.htmlspecialchars($plot['description']); ?></h3>
            <h4>Plot Details</h4>
            <p class="mb-0"><strong>Description:</strong> <?php echo htmlspecialchars($plot['description']); ?></p>
            <p class="mb-0"><strong>Price:</strong> <span class="text-success fw-bold">₱<?php echo $plot_price; ?></span></p>
            <p class="mb-0"><strong>Reserved by:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p class="mb-0"><strong>Date Placed:</strong> <?php echo date("Y-m-d H:i:s"); ?></p>
        </div>
    </div>
    <?php include("../includes/alert.php"); ?>
    <!-- Form to confirm reservation with burial details -->
    <form action="store.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="plot_id" value="<?php echo htmlspecialchars($plot_id); ?>">
        <input type="hidden" name="section_id" value="<?php echo htmlspecialchars($section_id); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="price" value="<?php echo htmlspecialchars($plot['price']); ?>">
        
        <!-- Burial Details Section -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Burial Details</h4>
                
                <!-- Name Fields Row -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" 
                            value="<?php echo isset($_SESSION['lname']) ? htmlspecialchars($_SESSION['lname']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" 
                            value="<?php echo isset($_SESSION['fname']) ? htmlspecialchars($_SESSION['fname']) : ''; ?>">
                    </div>
                </div>
                
                <!-- Birth and Death Date Row -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="date_born" class="form-label">Date Born</label>
                        <input type="date" class="form-control" id="date_born" name="date_born" 
                            value="<?php echo isset($_SESSION['date_born']) ? $_SESSION['date_born'] : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="date_died" class="form-label">Date Died</label>
                        <input type="date" class="form-control" id="date_died" name="date_died" 
                            value="<?php echo isset($_SESSION['date_died']) ? $_SESSION['date_died'] : ''; ?>">
                    </div>
                </div>
                
                <!-- Burial Date and Type Row -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="burial_date" class="form-label">Burial Date</label>
                        <input type="date" class="form-control" id="burial_date" name="burial_date" 
                            value="<?php echo isset($_SESSION['burial_date']) ? $_SESSION['burial_date'] : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="type_id" class="form-label">Burial Type</label>
                        <select class="form-select" id="type_id" name="type_id" required>
                            <option value="" disabled selected>Choose a burial type</option>
                            <?php 
                            mysqli_data_seek($burial_types_result, 0);
                            while ($type = mysqli_fetch_assoc($burial_types_result)) : 
                            ?>
                                <option value="<?php echo htmlspecialchars($type['type_id']); ?>">
                                    <?php echo htmlspecialchars($type['description']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Photo Upload -->
                <div class="mb-3">
                    <label for="img-path" class="form-label">Deceased Photo</label>
                    <input type="file" class="form-control" id="img-path" name="img-path" accept="image/*">
                </div>
            </div>
        </div>

        <div class="mt-3 mb-3 ">
            <button type="submit" class="btn btn-success">Confirm Reservation</button>
            <a href="/gravekeepercms/section/view_plot.php?id=<?php echo $_GET['section'] ?>" class="btn btn-primary">Return</a>
        </div>
    </form>
</div>
</body>
<?php include("../includes/footer.php"); ?>
</html>