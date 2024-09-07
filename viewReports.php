<?php
include 'inc/header.php';
Session::CheckSession();

 ?>
 
 <div class="card ">
   <div class="card-header">
   <h3><i class="fas fa-clock"></i>View Reports <span class="float-right"> <a href="search.php" class="btn btn-primary">Back</a> </h3>
        </div>
        <div class="card-body">
<p class="card-text"></p>

<div class="card">
  <div class="card-header">
    <h3 class='float-center'><i class="fas fa-history"></i>&emsp; Seller History</h3>
  </div>
  <div class="card-body">
    <p class="card-text">This report will show detail about all vehicles purchased by BuzzCars and their sellers. </p>
    <a href="viewReports-SellerHistory.php" class="btn btn-info"> View Report </a>
  </div>
</div>
<p class="card-text"></p>

<div class="card">
  <div class="card-header">
    <h3 class='float-center'><i class="fas fa-history"></i>&emsp; Average Time In Inventory</h3>
  </div>
  <div class="card-body">
    <p class="card-text">This report,will show the average amount of time a vehicle remains in inventory, in days.</p>
    <a href="viewReports-AvgTime.php" class="btn btn-info"> View Report </a>
  </div>
</div>
<p class="card-text"></p>

<div class="card">
  <div class="card-header">
    <h3 class='float-center'><i class="fas fa-history"></i>&emsp; Price Per Condition</h3>
  </div>
  <div class="card-body">
    <p class="card-text">This report will display, by vehicle type, and for each condition, the average price paid for cars that BuzzCars has purchased. </p>
    <a href="viewReports-PricePerCondition.php" class="btn btn-info"> View Report </a>
  </div>
</div>
<p class="card-text"></p>

<div class="card">
  <div class="card-header">
    <h3 class='float-center'><i class="fas fa-history"></i>&emsp; Parts Statistics</h3>
  </div>
  <div class="card-body">
    <p class="card-text">This report will be used to negotiate better prices with parts vendors.</p>
    <a href="viewReports-PartsStatistic.php" class="btn btn-info"> View Report </a>
  </div>
</div>
<p class="card-text"></p>

<div class="card">
  <div class="card-header">
    <h3 class='float-center'><i class="fas fa-history"></i>&emsp; Monthly Sales</h3>
  </div>
  <div class="card-body">
    <p class="card-text">This report will show the overall sales in each month and monthly report to track salesperson performance. </p>
    <a href="viewReports-MonthlySales.php" class="btn btn-info"> View Report </a>
  </div>
</div>



</div>


          <div style="width:600px; margin:0px auto">
             <?php
        //  echo 'here we can see reports'

     ?>
          
        </div>


      </div>
    </div>


  <?php
  include 'inc/footer.php';

  ?>
