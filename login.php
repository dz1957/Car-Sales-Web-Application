<?php
include 'inc/header.php';
include 'lib/Database.php';
Session::CheckLogin();

?>


<?php
# click the login botton, then check the availbility then set the login info 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
  
  $username =  $_POST["username"];
  $password = $_POST["password"];
  $db = new Database;
  $stmt = $db->mysql->query("SELECT Username, User_type FROM USER WHERE Username = '$username' and Userpassword = '$password';");
  // $stmt->bind_param("ss", $username, $password);
  // $stmt->execute();
  if ($stmt->num_rows > 0) {
            // output data of each row
            while($row = $stmt->fetch_assoc()) {
              $usertype = $row["User_type"] ;
              Session::set('userid', $username);
              Session::set('usertype', $usertype);
              Session::set('login', TRUE);
              echo "<script>location.href='index.php';</script>";
            }
          } else {
            if (!isset($_SESSION['login'])) {
                  echo '<center><div class="alert alert-warning">Login Error: Invalid Username or Password</div></center>';
            };
          }
  
    
}


// if (isset($userLog)) {
//   echo $userLog;
//   echo "    ";
// }

// $logout = Session::get('logout');
// if (isset($logout)) {
//   echo $logout;
// }



 ?>

<div class="card ">
  <div class="card-header">
    <h3 class='text-center'><i class="fas fa-sign-in-alt mr-2"></i>User login</h3>
  </div>
  <div class="card-body">
    <div style="width:450px; margin:0px auto">

        <form class="" action="" method="post">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="username" name="username"  class="form-control">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password"  class="form-control">
            </div>
            <div class="form-group">
              <button type="submit" name="login" class="btn btn-success">Login</button>
            </div>
        </form>
    </div>
  </div>
</div>



  <?php
  include 'inc/footer.php';

  ?>
