<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    if (!isset($_POST['section_id'])) {
        echo "<script>alert('No section selected.'); window.location.href='index.php';</script>";
        exit();
    }

    $section_id = $_POST['section_id'];

    // Fetch section details
    $sql = "SELECT * FROM section WHERE section_id = '$section_id'";
    $result = mysqli_query($conn, $sql);
    $section = mysqli_fetch_assoc($result);

    // Fetch plots with their status
    $plot_sql = "SELECT p.*, s.description AS status_desc 
                 FROM plot p
                 JOIN status s ON p.stat_id = s.stat_id
                 WHERE p.section_id = '$section_id'";
    $plot_result = mysqli_query($conn, $plot_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reserve a Plot</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
      <?php include('../includes/styles/style.css') ?>
  </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center fw-bold">Reserve a Plot</h2>
    <div class="card">
        <img src="/gravekeepercms/section/<?php echo $section['sec_img']; ?>" class="card-img-top" alt="Section Image" style="height: 300px; object-fit: cover;">
        <div class="card-body">
            <h3 class="card-title"> <?php echo $section['sec_name']; ?> </h3>
            <p class="card-text"> <?php echo $section['description']; ?> </p>
        </div>
    </div>

    <h4 class="mt-4">Available Plots</h4>
    <div class="d-flex flex-wrap gap-3">
        <?php while ($plot = mysqli_fetch_assoc($plot_result)) { ?>
            <div class="p-3 border rounded shadow-sm">
                <strong><?php echo htmlspecialchars($plot['description']); ?></strong>
                <p class="mb-2">
                    <span class="badge 
                        <?php echo ($plot['status_desc'] == 'occupied') ? 'bg-danger' : 'bg-success'; ?>">
                        <?php echo ucfirst($plot['status_desc']); ?>
                    </span>
                </p>
                
                <?php if ($plot['status_desc'] !== 'occupied') { ?>
                    <form action="confirm_reservation.php" method="post">
                        <input type="hidden" name="plot_id" value="<?php echo $plot['plot_id']; ?>">
                        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Reserve</button>
                    </form>
                <?php } else { ?>
                    <button class="btn btn-secondary btn-sm" disabled>Unavailable</button>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <a href="/gravekeepercms/reservation/" class="btn btn-secondary mt-3">Back</a>
</div>
</body>
<?php include("../includes/footer.php"); ?>
</html>
