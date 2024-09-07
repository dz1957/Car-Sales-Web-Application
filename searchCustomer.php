<?php
include 'inc/header.php';
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');

$pre_page = Session::get('pre_page');
// temp set a static param
// $pre_page = 'add_vehicle';

if ($pre_page == 'sell_vehicle') {
    $return_page = 'sell.php';
} elseif ($pre_page == 'add_vehicle') {
    $return_page = 'addVehicle.php';
} else {
    $return_page = 'index.php';
}
;

?>

<!DOCTYPE html>
<html lang="en">
<div class="card ">
    <div class="card-header">
        <h3>
            <i class="fas fa-search"></i>Search Customer
            <span class="float-right">
                <!-- <a href="??.php" class="btn btn-primary">Back</a>  -->
                <strong><span class="badge badge-lg badge-secondary text-white">
                        <?php
                        if (isset($userid)) {
                            echo $userid;
                            echo ' - ';
                        }
                        if (isset($usertype)) {
                            echo $usertype;
                        }
                        ?>

                    </span></strong>
                    <a href="<?php echo $return_page ?>" class="btn btn-primary">Back</a>
            </span>
        </h3>
    </div>


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vehicle Search</title>
        <style>
            .search-container {
                display: flex;
                flex-wrap: nowrap;
                flex-direction: column;
                align-items: left;
                justify-content: start;
                padding: 50px;
                background-color: #f2f2f2;
                border-radius: 10px;
            }

            .search-bar {
                display: flex;
                flex-wrap: nowrap;
                flex-direction: row;
                align-items: left;
                justify-content: start;
                padding: 50px;
                background-color: #f2f2f2;
                border-radius: 10px;
            }

            .search-result {
                display: flex;
                flex-wrap: nowrap;
                flex-direction: row;
                align-items: left;
                justify-content: start;
                padding: 50px;
                background-color: #f2f2f2;
                border-radius: 10px;
            }

            .search-input {
                width: 600px;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            .search-button {
                width: 150px;
                padding: 10px 10px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <form class="" action="" method="GET">
            <div class="search-container">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="customertype" id="Person" , value="Person">
                    <label class="form-check-label" for="Person">
                        <h4>Person</h4>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="customertype" id="Business" , value="Business">
                    <label class="form-check-label" for="Business">
                        <h4>Business</h4>
                    </label>
                </div>
            </div>


            <div class="search-bar">
                <input type="text" class="search-input" name="search-input"
                    placeholder="Enter LicenseID/TIN/Name/Email to search">
                <!-- <div class="search-container"> -->
                <button type="submit" name="submit" class="search-button">Search</button>
                <!-- </div> -->
            </div>
        </form>
    </body>


</div>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['submit'])) {
    $Keyword = $_GET["search-input"];
    $db = new Database;
    $conn = $db->mysql;
    if (isset($_GET['customertype'])) {
        $CustomerType = $_GET["customertype"];
        if ($CustomerType == 'Person') {
            $sql =
                "SELECT CUSTOMER_TYPE, C.CUSTOMER_ID, I.LICENSE_ID AS ID, CONCAT(I.FIRSTNAME, ' ', I.LASTNAME) AS 'NAME', EMAIL, PHONE_NUMBER
            FROM CUSTOMER C INNER JOIN
            INDIVIDUAL I ON I.CUSTOMER_ID = C.CUSTOMER_ID
            WHERE CASE WHEN '$Keyword'='' THEN 1 ELSE (I.LICENSE_ID  LIKE concat('%',LOWER('$Keyword'),'%')) 
            OR (LOWER('NAME')  LIKE concat('%',LOWER('$Keyword'),'%') ) 
            OR (LOWER(EMAIL)  LIKE concat('%',LOWER('$Keyword'),'%') ) END;    
            ";
        } else {
            $sql =
                "SELECT CUSTOMER_TYPE, C.CUSTOMER_ID, B.TIN AS ID, BUSINESS_NAME AS 'NAME', EMAIL, PHONE_NUMBER
                FROM CUSTOMER C INNER JOIN
                BUSINESS B ON B.CUSTOMER_ID = C.CUSTOMER_ID
                WHERE CASE WHEN '$Keyword'='' THEN 1 ELSE (B.TIN  LIKE concat('%',LOWER('$Keyword'),'%')) 
            OR (LOWER('NAME')  LIKE concat('%',LOWER('$Keyword'),'%') ) 
            OR (LOWER(EMAIL)  LIKE concat('%',LOWER('$Keyword'),'%') ) END;";
        }
        ;
    } else {

        $sql = "SELECT CUSTOMER_TYPE, CUSTOMER_ID, ID, `NAME`, EMAIL, PHONE_NUMBER
                FROM (
                SELECT CUSTOMER_TYPE, C.CUSTOMER_ID, B.TIN AS ID, BUSINESS_NAME AS 'NAME', EMAIL, PHONE_NUMBER
                        FROM CUSTOMER C INNER JOIN
                        BUSINESS B ON B.CUSTOMER_ID = C.CUSTOMER_ID
                UNION

                SELECT CUSTOMER_TYPE, C.CUSTOMER_ID, I.LICENSE_ID AS ID, CONCAT(I.FIRSTNAME, ' ', I.LASTNAME) AS 'NAME', EMAIL, PHONE_NUMBER
                        FROM CUSTOMER C INNER JOIN
                        INDIVIDUAL I ON I.CUSTOMER_ID = C.CUSTOMER_ID ) T 
                        WHERE CASE WHEN '$Keyword'='' THEN 1 ELSE (ID  LIKE concat('%',LOWER('$Keyword'),'%')) 
            OR (LOWER('NAME')  LIKE concat('%',LOWER('$Keyword'),'%') ) 
            OR (LOWER(EMAIL)  LIKE concat('%',LOWER('$Keyword'),'%') ) END;";

    }
    ;

    $result = $conn->query($sql);
    echo '<div class="search-result">';

    if ($result->num_rows > 0) {
        // add to page 

        echo '<table class="table table-hover">';
        echo '<tr>';
        echo '  <th>CUSTOMER_TYPE </th>';
        echo '  <th>CUSTOMER_ID </th>';
        echo '  <th>ID </th>';
        echo '  <th>NAME </th>';
        echo '  <th>EMAIL </th>';
        echo '  <th>PHONE_NUMBER </th>';
        echo '  <th>Select </th>';
        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<form method="GET" action="' . $return_page . '">';
            echo '<input type="hidden" name="customerid" value="' . $row['CUSTOMER_ID'] . '">';

            echo '<tr><td>' . $row['CUSTOMER_TYPE'] . '</td>';
            echo '<td>' . $row['CUSTOMER_ID'] . '</td>';
            echo '<td>' . $row['ID'] . '</td>';
            echo '<td>' . $row['NAME'] . '</td>';
            echo '<td>' . $row['EMAIL'] . '</td>';
            echo '<td>' . $row['PHONE_NUMBER'] . '</td>';
            echo '<td> <button type="submit", name="select"> Select </button> </tr>';
            echo '</form>';
        }
        echo '</table>';
    } else {
        echo "<h4> Sorry, no such customer &nbsp&nbsp&nbsp </h4>";
        if ($usertype != 'Manager' ){
        echo '<a class="btn btn-primary"  href="addcustomer.php" >Add New Customer</a>';
        };
    }

    $conn->close();

    echo '</div>';



}
?>








<?php
include 'inc/footer.php';
?>