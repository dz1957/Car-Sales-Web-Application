<?php
include 'inc/header.php';
include 'lib/Database.php';
Session::CheckSession();
?>
<?php


?>

<div class="card ">
    <div class="card-header">
        <h3>Monthly Sales<span class="float-right"> <a href="viewReports-MonthlySales.php"
                    class="btn btn-primary">Back</a> </h3>
    </div>
    <div class="card-body">




        <div style="width:800px; margin:10px auto">

            <?php
            $curr = $_GET['month'];
            $MONTH = substr($curr, -2);
            $YEAR = substr($curr, 0, 4);

            if (isset($_GET['month'])) {
                $db = new Database;
                $stmt = $db->mysql->query("SELECT  B.YEAR_AND_MONTH,
                U.FIRSTNAME AS SALESPEOPLE_FIRSTNAME,
                U.LASTNAME AS SALESPEOPLE_LASTNAME,
                SUM(T.TOTAL_PARTS_SPENT * 1.1 + V.PURCHASE_PRICE * 1.25) AS SALES_INCOME,
                COUNT(B.VIN) AS NUMBER_OF_VEHICLE
            FROM (SELECT VIN, SALESPEOPLE_USERNAME, DATE_FORMAT(SALE_DATE, '%Y-%m') AS YEAR_AND_MONTH
            FROM BUYS
            WHERE YEAR(SALE_DATE) = '$YEAR' AND MONTH(SALE_DATE) = '$MONTH') B
                LEFT JOIN VEHICLE V ON B.VIN = V.VIN
                LEFT JOIN
            (SELECT   V.VIN, CASE WHEN SUM(P.PARTS_QUANTITY * P.UNIT_PRICE) IS NULL THEN 0
                        ELSE SUM(P.PARTS_QUANTITY * P.UNIT_PRICE) END AS TOTAL_PARTS_SPENT
            FROM VEHICLE AS V
            LEFT JOIN PARTS_ORDER AS O ON O.VIN = V.VIN
            LEFT JOIN PARTS AS P ON O.VIN = P.VIN
                AND P.ORDER_ORDINAL = O.ORDER_ORDINAL
            GROUP BY V.VIN) T ON T.VIN = B.VIN
                LEFT JOIN
            USER U ON U.USERNAME = B.SALESPEOPLE_USERNAME
            GROUP BY B.YEAR_AND_MONTH , U.FIRSTNAME , U.LASTNAME , B.SALESPEOPLE_USERNAME
            ORDER BY SALES_INCOME DESC , NUMBER_OF_VEHICLE DESC;
");
                // $stmt->bind_param("ss", $username, $password);
                // $stmt->execute();
            



                if ($stmt->num_rows > 0) {

                    echo '<table class="table table-hover">';
                    echo '<tr>';
                    echo '  <th>YEAR_AND_MONTH </th>';
                    echo '  <th>SALESPEOPLE_FIRSTNAME </th>';
                    echo '  <th>SALESPEOPLE_LASTNAME </th>';
                    echo '  <th>SALES_INCOME </th>';
                    echo '  <th>NUMBER_OF_VEHICLE </th>';
                    echo '</tr>';
                    while ($row = $stmt->fetch_assoc()) {
                        echo '<tr><td>' . $row['YEAR_AND_MONTH'] . '</td>';
                        echo '<td>' . $row['SALESPEOPLE_FIRSTNAME'] . '</td>';
                        echo '<td>' . $row['SALESPEOPLE_LASTNAME'] . '</td>';
                        echo '<td>' . $row['SALES_INCOME'] . '</td>';
                        echo '<td>' . $row['NUMBER_OF_VEHICLE'] . '</td></td>';


                    }
                    ;
                    echo '</table>';
                    echo '</form>';
                }
                ;
            }



            ?>


        </div>
    </div>


    <?php
    include 'inc/footer.php';

    ?>