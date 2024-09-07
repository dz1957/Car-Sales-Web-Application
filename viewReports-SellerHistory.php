<?php
include 'inc/header.php';
include 'lib/Database.php';
Session::CheckSession();

?>

<div class="card ">
  <div class="card-header">
    <h3>Seller History<span class="float-right"> <a href="viewReports.php" class="btn btn-primary">Back</a> </h3>
  </div>
  <div class="card-body">




    <div style="width:800px; margin:10px auto">

      <?php
      $db = new Database;
      $stmt = $db->mysql->query("SELECT Q1.CUSTOMER_NAME, VEHICLE_NUM, ROUND(AVG_PRICE, 0) AVG_PRICE, ROUND(AVG_PARTS_NUM, 0) AVG_PARTS_NUM,ROUND(AVG_PARTS_PRICE, 0) AVG_PARTS_PRICE, HIGHLIGHT_FLAG FROM 
                                (SELECT COUNT(DISTINCT V.VIN) AS VEHICLE_NUM, 
                                SUM(CASE WHEN PARTS_QUANTITY IS NULL THEN 0 ELSE PARTS_QUANTITY END)/COUNT(DISTINCT V.VIN) AS AVG_PARTS_NUM,
                                SUM(CASE WHEN PARTS_QUANTITY * UNIT_PRICE IS NULL THEN 0 ELSE PARTS_QUANTITY * UNIT_PRICE END)/COUNT(DISTINCT V.VIN) AS AVG_PARTS_PRICE,
                                SUM(CASE WHEN PARTS_QUANTITY IS NULL THEN 0 ELSE PARTS_QUANTITY END)/COUNT(DISTINCT V.VIN)>=5
                                  OR SUM(CASE WHEN PARTS_QUANTITY * UNIT_PRICE IS NULL THEN 0 ELSE PARTS_QUANTITY * UNIT_PRICE END)/COUNT(DISTINCT V.VIN)>=500 AS HIGHLIGHT_FLAG,
                                CASE WHEN FIRSTNAME IS NOT NULL THEN CONCAT(FIRSTNAME,' ',LASTNAME) ELSE BUSINESS_NAME END AS CUSTOMER_NAME 
                                FROM VEHICLE AS V INNER JOIN CUSTOMER AS C ON V.SELLER_ID = C.CUSTOMER_ID 
                                  LEFT OUTER JOIN INDIVIDUAL AS I ON C.CUSTOMER_ID = I.CUSTOMER_ID 
                                  LEFT OUTER JOIN BUSINESS AS B ON C.CUSTOMER_ID = B.CUSTOMER_ID 
                                  LEFT OUTER JOIN PARTS_ORDER AS O ON V.VIN = O.VIN 
                                  LEFT OUTER JOIN PARTS AS P ON O.VIN = P.VIN AND O.ORDER_ORDINAL = P.ORDER_ORDINAL 
                                GROUP BY CUSTOMER_NAME) Q1 
                              INNER JOIN
                              (SELECT AVG(PURCHASE_PRICE) AS AVG_PRICE, 
                                CASE WHEN FIRSTNAME IS NOT NULL THEN CONCAT(FIRSTNAME,' ',LASTNAME) ELSE BUSINESS_NAME END AS CUSTOMER_NAME 
                                FROM VEHICLE AS V 
                                  INNER JOIN CUSTOMER AS C ON V.SELLER_ID = C.CUSTOMER_ID 
                                  LEFT OUTER JOIN INDIVIDUAL AS I ON C.CUSTOMER_ID = I.CUSTOMER_ID 
                                  LEFT OUTER JOIN BUSINESS AS B ON C.CUSTOMER_ID = B.CUSTOMER_ID 
                                GROUP BY CUSTOMER_NAME) Q2 
                              ON Q1.CUSTOMER_NAME=Q2.CUSTOMER_NAME 
                              ORDER BY VEHICLE_NUM DESC, AVG_PRICE ASC;

                              ");
      // $stmt->bind_param("ss", $username, $password);
      // $stmt->execute();
      if ($stmt->num_rows > 0) {
        echo '<table class="table table-hover">';
        echo '<tr>';
        echo '  <th>CUSTOMER_NAME </th>';
        echo '  <th>VEHICLE_NUM </th>';
        echo '  <th>AVG_PRICE </th>';
        echo '  <th>AVG_PARTS_NUM </th>';
        echo '  <th>AVG_PARTS_PRICE </th>';
        echo '</tr>';
        while ($row = $stmt->fetch_assoc()) { ?>

          <?php
          if ($row['AVG_PARTS_NUM']<5 && $row['AVG_PARTS_PRICE']<500) {
            echo '<tr><td>' . $row['CUSTOMER_NAME'] . '</td><td>' . $row['VEHICLE_NUM'] . '</td><td>' . $row['AVG_PRICE'] . '</td><td>' . $row['AVG_PARTS_NUM'] . '</td><td>' . $row['AVG_PARTS_PRICE'] . '</td></tr>';
          } else {
            echo '<tr class="table-danger"><td>' . $row['CUSTOMER_NAME'] . '</td><td>' . $row['VEHICLE_NUM'] . '</td><td>' . $row['AVG_PRICE'] . '</td><td>' . $row['AVG_PARTS_NUM'] . '</td><td>' . $row['AVG_PARTS_PRICE'] . '</td></tr>';
          }

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