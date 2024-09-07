<?php
include 'inc/header.php';
include 'lib/Database.php';
Session::CheckSession();

 ?>
 
 <div class="card ">
   <div class="card-header">
          <h3>Parts Statistics<span class="float-right"> <a href="viewReports.php" class="btn btn-primary">Back</a> </h3>
        </div>
        <div class="card-body">




          <div style="width:800px; margin:10px auto">
             
<?php
  $db = new Database;
  $stmt = $db->mysql->query("SELECT V.VENDOR_NAME, 
                              SUM(CASE WHEN P.PARTS_QUANTITY IS NULL THEN 0 ELSE P.PARTS_QUANTITY END) AS COUNT_PARTS, 
                              SUM(CASE WHEN P.PARTS_QUANTITY * P.UNIT_PRICE IS NULL THEN 0 ELSE P.PARTS_QUANTITY * P.UNIT_PRICE END) AS TOTAL_SPENT
                            FROM VENDOR AS V
                            LEFT JOIN PARTS_ORDER AS O ON O.VENDOR_NAME = V.VENDOR_NAME
                            LEFT JOIN PARTS AS P ON O.VIN = P.VIN AND P.ORDER_ORDINAL = O.ORDER_ORDINAL
                            GROUP BY V.VENDOR_NAME;

                            ");

  if ($stmt->num_rows > 0) {
        echo '<table class="table table-hover">';
        echo '<tr>';
        echo '  <th>VENDOR_NAME </th>';
        echo '  <th>COUNT_PARTS </th>';
		echo '  <th>TOTAL_SPENT </th>';
        echo '</tr>';  
    while ($row = $stmt->fetch_assoc() ){?>

    <?php 
    
      echo '<tr><td>' . $row['VENDOR_NAME'] . '</td><td>' . $row['COUNT_PARTS']. '</td><td>' . $row['TOTAL_SPENT']. '</td><td>'  . '</td></tr>';
    
    };
    echo '</table>';
          };
        

?>

  
  </div>
        
      </div>
        </div>


  <?php
  include 'inc/footer.php';

  ?>
