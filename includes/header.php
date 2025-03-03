<?php 
  if(!isset($_SESSION['roleDesc'])){
    $_SESSION['roleDesc'] = "";
  }
?>
<nav class="site-header border-bottom navbar navbar-expand-lg bg-body-tertiary px-0 mx-0">
  <div class="container-fluid container-lg header-dropdown px-0">
    <a class="navbar-brand fw-bold ms-5" href="/gravekeepercms/">GraveKeeper</a>
    <button class="navbar-toggler me-5" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/gravekeepercms/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Reviews</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu me-5">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <div class="d-flex align-items-center">
        <?php
          if($_SESSION['roleDesc'] == "admin"){
            echo "<div class=\"nav-item dropdown me-3\">
                <a class=\"nav-link dropdown-toggle \" href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                    Admin
                </a>
                <ul class=\"dropdown-menu\">
                    <li><a class=\"dropdown-item\" href=\"/gravekeepercms/user/\">User</a></li>
                    <li><a class=\"dropdown-item\" href=\"/gravekeepercms/deceased/\">Deceased</a></li>
                    <li><a class=\"dropdown-item\" href=\"/gravekeepercms/section/\">Section</a></li>
                    <li><a class=\"dropdown-item\" href=\"/gravekeepercms/admin/reservation.php\">Reservation</a></li>
                    <li><a class=\"dropdown-item\" href=\"/gravekeepercms/review/\">Review</a></li>
                </ul>
            </div>";
          }
          if(!isset($_SESSION['roleDesc'])){
              $_SESSION['roleDesc'] = "";
          }
          
          if($_SESSION['roleDesc'] == "user" || $_SESSION['roleDesc'] == "admin"){
            $sql = "SELECT email, name FROM user WHERE user_id = {$_SESSION['user_id']}";
              $DBpath = mysqli_query($conn, $sql);
              while($row = mysqli_fetch_array($DBpath)){
                  $settingName = $row['name'];
                  $settingEmail = $row['email'];
              }
              echo "<div class=\"nav-item dropdown\">
                      <a class=\"nav-link dropdown-toggle \" href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                          Settings
                      </a>
                      <ul class=\"dropdown-menu dropdown-menu-end dropdown-menu-sm-start\">
                          <li class=\"text-center p-2\">
                              <div class=\"fw-bold text-wrap\">$settingEmail</div>
                          </li>
                          <li><hr class=\"dropdown-divider\"></li>";
                          echo "
                          <li><a class=\"dropdown-item\" href=\"/gravekeepercms/user/profile.php\">Profile</a></li>
                          <li><a class=\"dropdown-item\" href=\"/gravekeepercms/reservation.php\">Reservations</a></li>
                          <li><a class=\"dropdown-item\" href=\"/gravekeepercms/review/index.php?mode=user\">Reviews</a></li>
                          <li><hr class=\"dropdown-divider\"></li>
                          <li><a class=\"dropdown-item\" href=\"/gravekeepercms/user/logout.php\">Logout</a></li>
                      </ul>
                  </div>";
              }else{
                echo'<a id="login-link" href="/gravekeepercms/user/login.php" class="nav-link disabled a-darker-grey me-4 text-decoration-none me-0" aria-disabled="true">Login</a>
                    <a id="register-link" href="/gravekeepercms/user/register.php" class="disabled btn btn-darker-grey" aria-disabled="true">Register</a>';
              }
        ?>
        
      </div>
    </div>
  </div>
</nav>

<script>
  // Check if the current page is NOT the login page
  if (!window.location.pathname.includes("login.php")) {
    let loginLink = document.getElementById("login-link");
    loginLink.classList.remove("disabled");
    loginLink.removeAttribute("aria-disabled");
  }
  if (!window.location.pathname.includes("register.php")) {
    let regisLink = document.getElementById("register-link");
    regisLink.classList.remove("disabled");
    regisLink.removeAttribute("aria-disabled");
  }
</script>