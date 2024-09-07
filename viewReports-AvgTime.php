<?php
include 'inc/header.php';
include 'lib/Database.php';
Session::CheckSession();

?>

<div class="card ">
  <div class="card-header">
    <h3>Average Time In Inventory<span class="float-right"> <a href="viewReports.php" class="btn btn-primary">Back</a>
    </h3>
  </div>
  <div class="card-body">




    <div style="width:800px; margin:10px auto">

      <?php
      $db = new Database;
      $stmt = $db->mysql->query("SELECT VEHICLETYPE.VEHICLE_TYPE, CASE WHEN TT.TIMEININVENTORY IS NULL OR TT.TIMEININVENTORY = 'N/A' THEN 'N/A' ELSE TT.TIMEININVENTORY END TIMEININVENTORY 
                                FROM VEHICLETYPE LEFT JOIN (
                                SELECT VEHICLE_TYPE, CASE WHEN COUNT(IF_NULL)=SUM(IF_NULL) THEN 'N/A' ELSE ROUND(AVG(TIMEININVENTORY),0) END TIMEININVENTORY
                                FROM (
                                SELECT V.VIN,  V.VEHICLE_TYPE, CASE WHEN SALE_DATE IS NOT NULL THEN DATEDIFF(SALE_DATE, PURCHASE_DATE)+1 ELSE 0 END AS TIMEININVENTORY , 
                                CASE WHEN (SALE_DATE - PURCHASE_DATE +1) IS NULL THEN 1 ELSE 0 END AS IF_NULL 
                                FROM VEHICLE AS V 
                                LEFT OUTER JOIN BUYS AS B 
                                ON V.VIN = B.VIN ) T
                                GROUP BY VEHICLE_TYPE )TT
                                ON VEHICLETYPE.VEHICLE_TYPE = TT.VEHICLE_TYPE
                                ORDER BY TIMEININVENTORY ASC;
                                ");

      if ($stmt->num_rows > 0) {
        echo '<table class="table table-hover">';
        echo '<tr>';
        echo '  <th>Vehicle Type </th>';
        echo '  <th>Average Time in Inventory </th>';
        echo '</tr>';
        while ($row = $stmt->fetch_assoc()) { ?>

          <?php

          echo '<tr><td>' . $row['VEHICLE_TYPE'] . '</td><td>' . $row['TIMEININVENTORY'] . '</td></tr>';


        }
        ;
        echo '</table>';
      }
      ;


      ?>


    </div>

  </div>
</div>


<?php
include 'inc/footer.php';

?>