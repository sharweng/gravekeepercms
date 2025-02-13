<?php
    session_start();
    include("../includes/config.php");
    include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <!-- BOOTSTRAP AND CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/gravekeepercms/includes/styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <?php include("../includes/alert.php"); ?>
                    <form class="form-horizontal d-grid gap-2" method="post" action="store.php" >
                        <div class="form-group">
                            <label for="name" class="cols-sm-2 control-label">Your Name</label>
                            <?php
                                if(isset($_SESSION['nameErr'])){
                                    echo"<br><label class=\"form-text text-danger\" >";
                                        echo $_SESSION['nameErr'];
                                        unset($_SESSION['nameErr']);
                                    echo "</label>";
                                }
                            ?>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter your Name"  value="<?php
                                        if(isset($_SESSION['name'])){
                                            echo $_SESSION['name'];
                                    }?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            
                            <label for="phone" class="cols-sm-2 control-label">Your Phone#</label>
                            <?php
                                if(isset($_SESSION['phoneErr'])){
                                    echo"<br><label class=\"form-text text-danger\" >";
                                        echo $_SESSION['phoneErr'];
                                        unset($_SESSION['phoneErr']);
                                    echo "</label>";
                                }
                            ?>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter your Phone#"  value="<?php
                                        if(isset($_SESSION['phone'])){
                                            echo $_SESSION['phone'];
                                    }?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                           
                            <label for="email" class="cols-sm-2 control-label">Your Email</label>
                            <?php
                                if(isset($_SESSION['emailErr'])){
                                    echo"<br><label class=\"form-text text-danger\" >";
                                        echo $_SESSION['emailErr'];
                                        unset($_SESSION['emailErr']);
                                    echo "</label>";
                                }
                            ?>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Enter your Email" value="<?php
                                        if(isset($_SESSION['email'])){
                                            echo $_SESSION['email'];
                                    }?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="cols-sm-2 control-label">Password</label>
                            <?php
                                if(isset($_SESSION['passErr'])){
                                    echo"<br><label class=\"form-text text-danger\" >";
                                        echo $_SESSION['passErr'];
                                        unset($_SESSION['passErr']);
                                    echo "</label>";
                                }
                            ?>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your Password" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm" class="cols-sm-2 control-label">Confirm Password</label>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control" name="confirm" id="confirm" placeholder="Confirm your Password" />
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block login-button">Register</button>
                        <div class="login-register">
                            <a href="login.php">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
  include("../includes/footer.php");
?>
