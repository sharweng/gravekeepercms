<?php
  session_start();
  include("includes/config.php");
  include('includes/header.php');
  include('update_plot.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <!-- BOOTSTRAP AND CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        <?php include('includes/styles/style.css') ?>
    </style>
</head>
<body>
  <h1>This is the body.</h1>
  <?php include("includes/alert.php"); ?>
  <a href="/gravekeepercms/section/" class="btn btn-darker-grey">Section</a>
</body>
</html>
<?php
  include("includes/footer.php");
?>

