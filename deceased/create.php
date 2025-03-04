<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');
    include('../includes/notAdminRedirect.php');

    $bur_sql = "SELECT * FROM bur_type";
    $bur_res = mysqli_query($conn, $bur_sql);
    $sec_sql = "SELECT * FROM section";
    $sec_res = mysqli_query($conn, $sec_sql);

    // Get the section ID from session or default to first section
    $section_id = isset($_SESSION['section']) ? $_SESSION['section'] : null;
    if (!$section_id) {
        $first_section = mysqli_fetch_array($sec_res);
        $section_id = $first_section['section_id'];
        mysqli_data_seek($sec_res, 0);
    }

    $plot_sql = "SELECT * FROM plot WHERE section_id = '$section_id'";
    $plot_res = mysqli_query($conn, $plot_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Deceased</title>
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
                <h1 class="h1 mb-3 fw-bold text-center">Add Deceased</h1>
                <?php include("../includes/alert.php"); ?>
                <div class="form-floating">
                    <input type="text" class="form-control signin-top" id="lname" name="lname" placeholder="Last Name" 
                        value="<?php echo isset($_SESSION['lname']) ? htmlspecialchars($_SESSION['lname']) : ''; ?>">
                    <label for="lname">Last Name</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control signin-middle" id="fname" name="fname" placeholder="First Name" 
                        value="<?php echo isset($_SESSION['fname']) ? htmlspecialchars($_SESSION['fname']) : ''; ?>">
                    <label for="fname">First Name</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control signin-middle" id="date_born" name="date_born" 
                        value="<?php echo isset($_SESSION['date_born']) ? $_SESSION['date_born'] : ''; ?>">
                    <label for="date_born">Date Born</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control signin-middle" id="date_died" name="date_died" 
                        value="<?php echo isset($_SESSION['date_died']) ? $_SESSION['date_died'] : ''; ?>">
                    <label for="date_died">Date Died</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control signin-middle" id="burial_date" name="burial_date" 
                        value="<?php echo isset($_SESSION['burial_date']) ? $_SESSION['burial_date'] : ''; ?>">
                    <label for="burial_date">Burial Date</label>
                </div>
                <div class="form-floating">
                    <select class="form-select signin-middle" name="type" id="type">
                        <?php
                            mysqli_data_seek($bur_res, 0);
                            while($row = mysqli_fetch_array($bur_res)){
                                $selected = (isset($_SESSION['type']) && $_SESSION['type'] == $row['type_id']) ? 'selected' : '';
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
                                $selected = (isset($_SESSION['section']) && $_SESSION['section'] == $row['section_id']) ? 'selected' : '';
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
                                $selected = (isset($_SESSION['plot']) && $_SESSION['plot'] == $row['plot_id']) ? 'selected' : '';
                                echo "<option value=\"{$row['plot_id']}\" {$selected}>{$row['description']}</option>";
                            }
                        ?>
                    </select>
                    <label for="plot">Plot</label>
                </div>
                <input type="file" class="form-control signin-bottom mb-2" name="img-path" accept="image/*">
                <button class="btn btn-darker-grey w-100 py-2 border-darker-grey" name="create" type="submit">Create</button>
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
                        var selected = <?php echo isset($_SESSION['plot']) ? $_SESSION['plot'] : 'null' ?> == item.plot_id ? 'selected' : '';
                        plotSelect.append(new Option(item.description, item.plot_id, false, selected));
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