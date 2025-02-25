<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');
    include('../includes/notAdminRedirect.php');

    // Get deceased record details
    $dec_id = $_POST['dec_id'];
    if(isset($_POST['dec_id'])){
        $_SESSION['dec_id'] = $dec_id;
    }else{
        $dec_id = $_SESSION['dec_id'];
    }
    
    $dec_sql = "SELECT d.*, b.burial_date, b.type_id, b.plot_id, p.section_id 
                FROM deceased d 
                JOIN burial b ON d.dec_id = b.dec_id 
                JOIN plot p ON b.plot_id = p.plot_id 
                WHERE d.dec_id = ?";
    $stmt = mysqli_prepare($conn, $dec_sql);
    mysqli_stmt_bind_param($stmt, "i", $dec_id);
    mysqli_stmt_execute($stmt);
    $dec_res = mysqli_stmt_get_result($stmt);
    $dec_row = mysqli_fetch_assoc($dec_res);

    // Get burial types
    $bur_sql = "SELECT * FROM bur_type";
    $bur_res = mysqli_query($conn, $bur_sql);

    // Get sections
    $sec_sql = "SELECT * FROM section";
    $sec_res = mysqli_query($conn, $sec_sql);

    // Get plots for the current section
    $plot_sql = "SELECT * FROM plot WHERE section_id = ?";
    $stmt = mysqli_prepare($conn, $plot_sql);
    mysqli_stmt_bind_param($stmt, "i", $dec_row['section_id']);
    mysqli_stmt_execute($stmt);
    $plot_res = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Deceased Record</title>
    <!-- BOOTSTRAP AND CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        <?php include('../includes/styles/style.css') ?>
    </style>
</head>
<body>
<div class="container-fluid px-0 mx-0 d-flex h-100">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style="color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for form -->
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center">
        <main class="form-signin m-auto w-100">
            <form method="post" action="store.php" enctype="multipart/form-data">
                <h1 class="h1 mb-3 fw-bold text-center">Edit Deceased Record</h1>
                <?php include("../includes/alert.php"); ?>
                <div class="form-floating">
                    <input type="text" class="form-control signin-top" id="lname" name="lname" placeholder="Last Name" 
                        value="<?php echo $dec_row['lname']; ?>">
                    <label for="lname">Last Name</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control signin-middle" id="fname" name="fname" placeholder="First Name" 
                        value="<?php echo  $dec_row['fname']; ?>">
                    <label for="fname">First Name</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control signin-middle" id="date_born" name="date_born" 
                        value="<?php echo $dec_row['date_born']; ?>">
                    <label for="date_born">Date Born</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control signin-middle" id="date_died" name="date_died" 
                        value="<?php echo $dec_row['date_died']; ?>">
                    <label for="date_died">Date Died</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control signin-middle" id="burial_date" name="burial_date" 
                        value="<?php echo $dec_row['burial_date']; ?>">
                    <label for="burial_date">Burial Date</label>
                </div>
                <div class="form-floating">
                    <select class="form-select signin-middle" name="type" id="type">
                        <?php
                            mysqli_data_seek($bur_res, 0);
                            while($row = mysqli_fetch_array($bur_res)){
                                $selected = ($dec_row['type_id'] == $row['type_id']) ? 'selected' : '';
                                echo "<option value=\"{$row['type_id']}\" {$selected}>{$row['description']}</option>";
                            }
                        ?>
                    </select>
                    <label for="type">Burial Type</label>
                </div>
                <div class="form-floating">
                    <select class="form-select signin-middle" name="section" id="section">
                        <?php
                            mysqli_data_seek($sec_res, 0);
                            while($row = mysqli_fetch_array($sec_res)){
                                $selected = ($dec_row['section_id'] == $row['section_id']) ? 'selected' : '';
                                echo "<option value=\"{$row['section_id']}\" {$selected}>{$row['sec_name']}</option>";
                            }
                        ?>
                    </select>
                    <label for="section">Section</label>
                </div>
                <div class="form-floating">
                    <select class="form-select signin-middle" name="plot" id="plot">
                        <?php
                            while($row = mysqli_fetch_array($plot_res)){
                                $selected = ($dec_row['plot_id'] == $row['plot_id']) ? 'selected' : '';
                                echo "<option value=\"{$row['plot_id']}\" {$selected}>{$row['description']}</option>";
                            }
                        ?>
                    </select>
                    <label for="plot">Plot</label>
                </div>
                <div class="mb-2">
                    <input type="file" class="form-control signin-bottom" name="img-path" accept="image/*">
                    <small class="text-muted">Leave empty to keep current image</small>
                </div>
                <input type="hidden" name="dec_id" value="<?php echo $dec_id; ?>">
                <button class="btn btn-darker-grey w-100 py-2 border-darker-grey" name="update" type="submit">Update</button>
            </form>
            <div class="d-flex justify-content-center mt-2">
                <a href="/gravekeepercms/deceased/" class="text-decoration-none a-darker-grey">Back</a>
            </div>
        </main>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('select[name="section"]').change(function() {
            var sectionId = $(this).val();
            $.ajax({
                url: 'get_plots.php',
                type: 'POST',
                data: { section_id: sectionId },
                success: function(data) {
                    var plotSelect = $('select[name="plot"]');
                    plotSelect.empty();
                    $.each(data, function(index, item) {
                        plotSelect.append(new Option(item.description, item.plot_id));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching plots:', error);
                }
            });
        });
    });
</script>
</body>
</html>
<?php include("../includes/footer.php"); ?>