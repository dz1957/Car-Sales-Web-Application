<?php
include 'inc/header.php';
include 'lib/Database.php';
Session::CheckSession();

?>

<div class="card ">
  <div class="card-header">
    <h3>Price Per Condition<span class="float-right"> <a href="viewReports.php" class="btn btn-primary">Back</a> </h3>
  </div>
  <div class="card-body">




    <div style="width:800px; margin:10px auto">

      <?php
      $db = new Database;
      $stmt = $db->mysql->query("SELECT VEHICLE_TYPE,
                                      ROUND(AVG(CASE WHEN VEHICLE_CONDITION = 'EXCELLENT' THEN PURCHASE_PRICE ELSE 0 END),0) AS EXCELLENT,
                                      ROUND(AVG(CASE WHEN VEHICLE_CONDITION = 'VERY GOOD' THEN PURCHASE_PRICE ELSE 0 END),0) AS VERY_GOOD,
                                      ROUND(AVG(CASE WHEN VEHICLE_CONDITION = 'GOOD' THEN PURCHASE_PRICE ELSE 0 END),0) AS GOOD,
                                      ROUND(AVG(CASE WHEN VEHICLE_CONDITION = 'FAIR' THEN PURCHASE_PRICE ELSE 0 END),0) AS FAIR
                                  FROM (SELECT V.VEHICLE_CONDITION, V.PURCHASE_PRICE, T.VEHICLE_TYPE 
                                  FROM VEHICLETYPE T LEFT JOIN VEHICLE V ON T.VEHICLE_TYPE = V.VEHICLE_TYPE) P
                                  GROUP BY VEHICLE_TYPE;

                                  ");
      // $stmt->bind_param("ss", $username, $password);
      // $stmt->execute();
      if ($stmt->num_rows > 0) {
        echo '<table class="table table-hover">';
        echo '<tr>';
        echo '  <th>VEHICLE_TYPE </th>';
        echo '  <th>EXCELLENT </th>';
        echo '  <th>VERY_GOOD </th>';
        echo '  <th>GOOD </th>';
        echo '  <th>FAIR </th>';
        echo '</tr>';
        while ($row = $stmt->fetch_assoc()) { ?>

          <?php

          echo '<tr><td>' . $row['VEHICLE_TYPE'] . '</td><td>' . $row['EXCELLENT'] . '</td><td>' . $row['VERY_GOOD'] . '</td><td>' . $row['GOOD'] . '</td><td>' . $row['FAIR'] . '</td></tr>';


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