<?php
include 'inc/header.php';
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');
# this is a check function. if check it is login, it will keep showing the index.php
# else it will jump to the login.page
// Session::CheckSession();


# could show or not show the current login info. decor pending,
# show here for better debug
// $userid = Session::get('userid');
// if (isset($userid)) {
//   echo $userid;
// }

// $usertype = Session::get('usertype');
// if (isset($usertype)) {
//   echo $usertype;
// }

?>
<?php




?>
<div class="card ">
  <div class="card-header">
    <h3><i class="fas fa-user-plus"></i> Cars For All Budgets.             
            <span class="float-right">
            <?php if (!empty($userid)) { ?>
                <strong><span class="badge badge-lg badge-secondary text-white">
                <?php
                    echo $userid;
                    echo '-' ;
                    echo $usertype;
                ?>
                </span></strong>
                <?php  } ?>
            </span></h3>
  </div>
  <div class="card-body pr-2 pl-2">



    <div>




      <div class="card-deck">
        <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
          <h5 class="card-header">
            <i class="fa fa-car"></i>
            Vehicle For Sale
          </h5>
          <div class="card-body">
            <h5 class="card-title text-white">Total count of vehicles for sale: </h5>

            <p>

              <?php
              $db = new Database;
              $sql = "SELECT COUNT(*) AS NUMBERS
                  FROM
                  (SELECT V.VIN,
                  SUM(CASE WHEN P.PARTS_STATUS IN ('RECEIVED' , 'ORDERED') THEN 1 ELSE 0 END) AS PENDING
                  FROM VEHICLE AS V
                  LEFT OUTER JOIN BUYS AS B ON V.VIN = B.VIN
                  LEFT OUTER JOIN PARTS_ORDER AS O ON V.VIN = O.VIN
                  LEFT OUTER JOIN PARTS AS P ON O.VIN = P.VIN AND O.ORDER_ORDINAL = P.ORDER_ORDINAL
                  WHERE SALE_DATE IS NULL
                  GROUP BY V.VIN
                  HAVING PENDING = 0) AS SUBQUERY";
              $result = $db->mysql->query($sql);


              if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                  $number1 = $row["NUMBERS"] ;
                  echo "<h3>" . $number1 . "</h3>";
                }
              }

              ?>

            </p>

          </div>
        </div>
        <?php if (Session::get('login') == True) { ?>
          <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
            <h5 class="card-header">
              <i class="fa fa-database"></i>
              Vehicle In Stock
            </h5>
            <div class="card-body">
              <h5 class="card-title text-white">Total count of unsold vehicles with pending part orders: </h5>

              <p>

                <?php
                $sql = "SELECT COUNT(*) AS NUMBERS
                FROM
                (SELECT V.VIN,
                SUM(CASE WHEN P.Parts_status IN ('received' , 'ordered') THEN 1 ELSE 0 END) AS Pending
                FROM Vehicle AS V
                LEFT OUTER JOIN Buys AS B ON V.VIN = B.VIN
                LEFT OUTER JOIN Parts_order AS O ON V.VIN = O.VIN
                LEFT OUTER JOIN Parts AS P ON O.VIN = P.VIN AND O.Order_Ordinal = P.Order_Ordinal
                WHERE Sale_date IS NULL
                GROUP BY V.VIN
                HAVING Pending > 0) AS SUBQUERY;";
                $result = $db->mysql->query($sql);


                if ($result->num_rows > 0) {
                  // output data of each row
                  while ($row = $result->fetch_assoc()) {
                    $number2 = $row["NUMBERS"];
                    echo "<h3>" . $number2 . "</h3>";
                  }
                }

                ?>

              </p>

            </div>
          </div>
        </div>
      <?php }
        ; ?>

    </div>
  </div>



  <?php
  include 'inc/footer.php';

  ?>