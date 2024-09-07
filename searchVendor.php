<?php
include 'inc/header.php';
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');

if (isset($_POST['vehicle_id']) or isset($_GET['vehicle_id' ])){
    $VIN =  isset($_POST['vehicle_id'] ) ?$_POST['vehicle_id']  :$_GET['vehicle_id']; 
    Session::set('VIN', $VIN);
};

$VIN = $_SESSION['VIN'];
?>

<!DOCTYPE html>
<html lang="en">
<div class="card ">
    <div class="card-header">
        <h3>
            <i class="fas fa-search"></i>Search Vendor
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
            <!-- <div class="search-container">
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
            </div> -->


            <div class="search-bar">
                <input type="text" class="search-input" name="search-input"
                    placeholder="Enter Vendor Name Keyword">
                <!-- <div class="search-container"> -->
                <input type="hidden" name="vehicle_id" value="<?php echo $VIN?> ">
                <button type="submit" name="submit" class="search-button">Search</button>
                <!-- </div> -->
            </div>
        </form>
    </body>


</div>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['submit'])) {
    // $Keyword = $_GET["search-input"];
    $db = new Database;
    $conn = $db->mysql;
    if (isset($_GET['search-input'])) {
        $Keyword = $_GET["search-input"];
       
            $sql =
                "SELECT VENDOR_NAME, PHONE_NUMBER, STREET, CITY, STATE, POSTAL_CODE FROM VENDOR
                WHERE CASE WHEN '$Keyword'='' THEN 1 ELSE (LOWER(VENDOR_NAME)  LIKE CONCAT('%',LOWER('$Keyword'),'%')) END;";
 
    } else {

        $sql = "SELECT VENDOR_NAME, PHONE_NUMBER, STREET, CITY, STATE, POSTAL_CODE FROM VENDOR;";

    }
    ;

    $result = $conn->query($sql);
    echo '<div class="search-result">';

    if ($result->num_rows > 0) {
        // add to page 

        echo '<table class="table table-hover">';
        echo '<tr>';
        echo '  <th>VENDOR_NAME </th>';
        echo '  <th>PHONE_NUMBER </th>';
        echo '  <th>STREET </th>';
        echo '  <th>CITY </th>';
        echo '  <th>STATE </th>';
        echo '  <th>POSTAL_CODE </th>';
        echo '  <th>Select </th>';
        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<form method="POST" action="addpartsorder.php?vehicle_id='.$VIN.'">';
            echo '<input type="hidden" name="vendor_name" value="' . $row['VENDOR_NAME'] . '">';

            echo '<tr><td>' . $row['VENDOR_NAME'] . '</td>';
            echo '<td>' . $row['PHONE_NUMBER'] . '</td>';
            echo '<td>' . $row['STREET'] . '</td>';
            echo '<td>' . $row['CITY'] . '</td>';
            echo '<td>' . $row['STATE'] . '</td>';
            echo '<td>' . $row['POSTAL_CODE'] . '</td>';
            echo '<td> <button type="submit", name="select"> Select </button> </tr>';
            echo '</form>';
        }
        echo '</table>';
    } else {
        echo "<h4> Sorry, no such vendor &nbsp&nbsp&nbsp  </h4>";

        echo '<a href="addvendor.php?vehicle_id='.$VIN.'" class="btn btn-primary">Add New Vendor</a>';
    }

    $conn->close();


    echo '</div>';
}
?>








<?php
include 'inc/footer.php';
?>