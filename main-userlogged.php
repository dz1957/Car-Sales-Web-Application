<!-- <?php
include 'inc/header.php';

# this is a check function. if check it is login, it will keep showing the index.php
# else it will jump to the login.page
// Session::CheckSession();


# could show or not show the current login info. decor pending,
# show here for better debug
$userid = Session::get('userid');
if (isset($userid)) {
  echo $userid;
}

$usertype = Session::get('usertype');
if (isset($usertype)) {
  echo $usertype;
}

?>
<?php




 ?>
      <div class="card ">
        <div class="card-header">
          <h3><i class="fas fa-users mr-2"></i>some text here <span class="float-right">Welcome! <strong>
            <span class="badge badge-lg badge-secondary text-white">
            
<?php
          
          if (isset($userid)) {
            echo $userid;
            echo ' - ';
          }
          if (isset($usertype)) {
            echo $usertype;
          }
          
          
 ?></span>

          </strong></span></h3>
        </div>
        <div class="card-body pr-2 pl-2">

          

          <div>
            <p>this is the page user can see when they have login</p>
            <p>we can add what they can do here</p>
          </div>



        </div>
      </div>



  <?php
  include 'inc/footer.php';

  ?> -->
