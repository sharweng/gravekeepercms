<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');

    $mode = $_GET['mode'];
    if($mode != 'user')
        include('../includes/notAdminRedirect.php');

    $sql = "SELECT * FROM user";
    $result = mysqli_query($conn, $sql);
    
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
        <?php include('../includes/styles/style.css') ?>
    </style>
</head>
<body>
<div class="container-fluid px-0 mx-0 h-100 d-flex">
    <!-- Right half with GraveKeeper and text -->
    <div class="col-6 d-flex flex-column align-items-center justify-content-center px-0" style="background-color: #4b4a4d;">
        <p class="fw-bold mb-0 h1" style=" color: #d1d1d3;">GraveKeeper</p>
        <p class="fw-bold h3 text-center mx-3" style="color: #a8a8a9;">Cemetery Management System</p>
    </div>
    <!-- Left half for login form -->
    <div class="col-6 container px-0 d-flex flex-column justify-content-center align-items-center ">
        <main class="form-signin m-auto w-100">
                <form method="post" <?php if($mode == 'user')
                            echo "action=\"store.php?mode=user\"";
                        else
                            echo "action=\"store.php\"";
                        ?>>
                    <?php if($mode == 'user')
                            echo "<h1 class=\"h1 mb-3 fw-bold text-center\">Make a Review</h1>";
                        else
                            echo "<h1 class=\"h1 mb-3 fw-bold text-center\">Create Review</h1>";
                    ?>
                    <?php include("../includes/alert.php"); 
                    if(($_SESSION['roleDesc'] == 'admin')&&($mode != 'user')){
                        echo "<div class=\"form-floating\">
                            <select class=\"form-select signin-top\" name=\"user_id\">";
                            while($row = mysqli_fetch_array($result)){
                                    echo "<option value=\"{$row['user_id']}\">{$row['email']}</option>";
                            };
                            echo "</select>
                            <label for=\"floatingInput\">Email</label>
                        </div>";
                    
                        echo "<div class=\"form-floating\">
                        <select class=\"form-select signin-middle\" name=\"rev_num\">";
                    }else{
                        echo "  <input type=\"hidden\" value=\"{$_SESSION['user_id']}?>\" name=\"user_id\">
                        <div class=\"form-floating\">
                        <select class=\"form-select signin-top\" name=\"rev_num\">";
                    }
                    ?>
                            <?php
                                for($i = 1; $i <= 5; $i++){
                                    echo "<option value=\"$i\">$i</option>";
                                }
                            ?>
                        </select>
                        <label for="floatingInput">Rating</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control signin-bottom" id="floatingInput" name="rev_msg" placeholder="Review Message">
                        <label for="floatingInput">Message</label>
                    </div>
                    <button class="btn btn-darker-grey w-100 py-2 border-darker-grey" name="submit-review" type="submit">Save Review</button>
                </form>
                <div class=" d-flex justify-content-center mt-2">
                    <a <?php if($mode == 'user' && $_GET['after'] == 'reserve')
                            echo "href=\"/gravekeepercms/\"";
                        elseif($mode == 'user')
                            echo "href=\"/gravekeepercms/review/index.php?mode=user\"";
                        else
                            echo "href=\"/gravekeepercms/review/\"";
                        ?> class="text-decoration-none a-darker-grey">Back</a>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

<?php
  include("../includes/footer.php");
?>
