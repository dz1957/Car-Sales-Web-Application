<?php
include 'inc/header.php';
include 'lib/Database.php';
Session::CheckSession();
?>
<?php


?>

<div class="card ">
  <div class="card-header">
    <h3>Monthly Sales<span class="float-right"> <a href="viewReports.php" class="btn btn-primary">Back</a> </h3>
  </div>
  <div class="card-body">




    <div style="width:800px; margin:10px auto">

      <?php
      $db = new Database;
      $stmt = $db->mysql->query("SELECT 
                                DATE_FORMAT(B.SALE_DATE, '%Y-%m') AS YEAR_AND_MONTH,
                                COUNT(B.VIN) AS NUMBER_OF_VEHICLE,
                                SUM(T.TOTAL_PARTS_SPENT * 1.1 + V.PURCHASE_PRICE * 1.25) AS SALES_INCOME,
                                SUM(T.TOTAL_PARTS_SPENT * 0.1 + V.PURCHASE_PRICE * 0.25) AS NET_INCOME
                            FROM BUYS B
                            LEFT JOIN VEHICLE V ON B.VIN = V.VIN
                            LEFT JOIN
                                (SELECT  V.VIN,
                                        CASE WHEN SUM(P.PARTS_QUANTITY * P.UNIT_PRICE) IS NULL THEN 0
                                            ELSE SUM(P.PARTS_QUANTITY * P.UNIT_PRICE) END AS TOTAL_PARTS_SPENT
                                FROM VEHICLE AS V
                                LEFT JOIN PARTS_ORDER AS O ON O.VIN = V.VIN
                                LEFT JOIN PARTS AS P ON O.VIN = P.VIN
                                    AND P.ORDER_ORDINAL = O.ORDER_ORDINAL
                                GROUP BY V.VIN) T ON T.VIN = B.VIN
                            GROUP BY YEAR_AND_MONTH
                            ORDER BY YEAR_AND_MONTH DESC;

                            ");
     



      if ($stmt->num_rows > 0) {
        echo '<form method="POST" action="viewReports-MonthlySalesDetail.php">';
        echo '<table class="table table-hover">';
        echo '<tr>';
        echo '  <th>YEAR_AND_MONTH </th>';
        echo '  <th>NUMBER_OF_VEHICLE </th>';
        echo '  <th>SALES_INCOME </th>';
        echo '  <th>NET_INCOME </th>';
        echo '<th> Monthly Report </th>';
        // echo '  <th>Monthly Report  </th> ';
        echo '</tr>';
        while ($row = $stmt->fetch_assoc()) { ?>

          <?php
          // echo '<input type="hidden" name="select-month" value="' . $row['YEAR_AND_MONTsH'] . '">';
      
          echo '<tr><td>' . $row['YEAR_AND_MONTH'] . '</td><td>' . $row['NUMBER_OF_VEHICLE'] . '</td><td>' . $row['SALES_INCOME'] . '</td><td>' . $row['NET_INCOME'] . '</td></td>';
          // echo '<td> <button type="input", name="select-month" value="' . $row['YEAR_AND_MONTH'] . '"> View Detail </button> </tr>';
          echo '<td><a class="btn btn-info btn-sm"  href="viewReports-MonthlySalesDetail.php?month=' . $row['YEAR_AND_MONTH'] . '"> View Report</a></td>';
        }
        ;
        echo '</table>';
        echo '</form>';
      }
      ;




      ?>


    </div>
  </div>


  <?php
  include 'inc/footer.php';

  ?>