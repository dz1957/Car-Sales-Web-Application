<?php
include 'inc/header.php';
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');
?>
    
<!DOCTYPE html>
<html lang="en">
<div class="card ">
    <div class="card-header">
        <h3>
            <i class="fas fa-search mr-2"></i>Search Vehicle 
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
            </strong>
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
                flex-wrap: wrap;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 20px;
                background-color: #f2f2f2;
                border-radius: 10px;
            }

            .search-input {
                width: 900px;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            .dropdown-container {
                display: inline-block;
                margin-right: 10px;
            }

            .dropdown-select {
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            .search-button {
                width: 150px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            /* Hide the "Sale Status" dropdown if $User_type is empty */
            .sale-status-dropdown {
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
                display: <?php if (empty($usertype)||($usertype=='Salesperson')): ?>none<?php else: ?>block<?php endif; ?>;
            }

            .VIN-input {
                width: 900px;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
                display: <?php if (empty($usertype)): ?>none<?php else: ?>block<?php endif; ?>;
            }
        </style>
    </head>
    <body>
    <form class="" action="" method="get">
    <div class="search-container">
        <div class="dropdown-container">
            <select class="dropdown-select" name="vehicle_type">
                <option value="">Vehicle Type</option>
                <option value="Sedan">Sedan</option>
                <option value="Coupe">Coupe</option>
                <option value="Convertible">Convertible</option>
                <option value="Truck">Truck</option>
                <option value="Van">Van</option>
                <option value="Minivan">Minivan</option>
                <option value="SUV">SUV</option>
                <option value="Other">Other</option>
            </select>
        </div>
 
        <div class="dropdown-container" >
            <select class="dropdown-select" name="manufacturer">
                <option value="">Manufacturer</option>
                <option value="Acura">Acura</option>
                <option value="Alfa Romeo">Alfa Romeo</option>
                <option value="Aston Martin">Aston Martin</option>
                <option value="Audi">Audi</option>
                <option value="Bentley">Bentley</option>
                <option value="BMW">BMW</option>
                <option value="Buick">Buick</option>
                <option value="Cadillac">Cadillac</option>
                <option value="Chevrolet">Chevrolet</option>
                <option value="Chrysler">Chrysler</option>
                <option value="Dodge">Dodge</option>
                <option value="FIAT">FIAT</option>
                <option value="Ford">Ford</option>
                <option value="Genesis">Genesis</option>
                <option value="GMC">GMC</option>
                <option value="Honda">Honda</option>
                <option value="Hyundai">Hyundai</option>
                <option value="INFINITI">INFINITI</option>
                <option value="Jaguar">Jaguar</option>
                <option value="Jeep">Jeep</option>
                <option value="Karma">Karma</option>
                <option value="Kia">Kia</option>
                <option value="Lamborghini">Lamborghini</option>
                <option value="Land Rover">Land Rover</option>
                <option value="Lexus">Lexus</option>
                <option value="Lincoln">Lincoln</option>
                <option value="Lotus">Lotus</option>
                <option value="Maserati">Maserati</option>
                <option value="MAZDA">MAZDA</option>
                <option value="Mercedes-Benz">Mercedes-Benz</option>
                <option value="MINI">MINI</option>
                <option value="Mitsubishi">Mitsubishi</option>
                <option value="Nissan">Nissan</option>
                <option value="Nio">Nio</option>
                <option value="Porsche">Porsche</option>
                <option value="Ram">Ram</option>
                <option value="Rivian">Rivian</option>
                <option value="Rolls-Royce">Rolls-Royce</option>
                <option value="smart">smart</option>
                <option value="Subaru">Subaru</option>
                <option value="Tesla">Tesla</option>
                <option value="Volkswagen">Volkswagen</option>
                <option value="Volvo">Volvo</option>
                <option value="XPeng">XPeng</option>
            </select>
        </div>

        <div class="dropdown-container">
            <select class="dropdown-select" name="model_year">
                <option value="">Model Year</option>
                <?php
                for ($year = 2024; $year >= 1950; $year--) {
                    echo '<option value="' . $year . '">' . $year . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="dropdown-container">
            <select class="dropdown-select" name="fuel_type">
                <option value="">Fuel Type</option>
                <option value="Gas">Gas</option>
                <option value="Diesel">Diesel</option>
                <option value="Natural Gas">Natural Gas</option>
                <option value="Hybrid">Hybrid</option>
                <option value="Plugin Hybrid">Plugin Hybrid</option>
                <option value="Battery">Battery</option>
                <option value="Fuel Cell">Fuel Cell</option>
            </select>
        </div>

        <div class="dropdown-container">
            <select class="dropdown-select" name="color">
            <option value="">Color</option>
            <option value="Aluminum">Aluminum</option>
            <option value="Beige">Beige</option>
            <option value="Black">Black</option>
            <option value="Blue">Blue</option>
            <option value="Brown">Brown</option>
            <option value="Bronze">Bronze</option>
            <option value="Claret">Claret</option>
            <option value="Copper">Copper</option>
            <option value="Cream">Cream</option>
            <option value="Gold">Gold</option>
            <option value="Gray">Gray</option>
            <option value="Green">Green</option>
            <option value="Maroon">Maroon</option>
            <option value="Metallic">Metallic</option>
            <option value="Navy">Navy</option>
            <option value="Orange">Orange</option>
            <option value="Pink">Pink</option>
            <option value="Purple">Purple</option>
            <option value="Rose">Rose</option>
            <option value="Rust">Rust</option>
            <option value="Silver">Silver</option>
            <option value="Tan">Tan</option>
            <option value="Turquoise">Turquoise</option>
            <option value="White">White</option>
            <option value="Yellow">Yellow</option>
            </select>
        </div>
        
        <div class="dropdown-container">
            <select class="sale-status-dropdown" name="sale_status">
                <option value="">Sale Status</option>
                <option value="Unsold">Unsold</option>
                <option value="Sold">Sold</option>
            </select>
        </div>
    </div>
    <div class="search-container">
        <input type="text" class="search-input" name="search-input" placeholder="Enter Keyword">
    </div>
    <div class="search-container">
        <input type="text" class="VIN-input" name="VIN-input" placeholder="Enter VIN"> 
        <button type = "submit"  name="submit" class="search-button">Search</button>   
    </div>
    </form>
    </body>
</div>
</html>

<?php
 
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['submit'])) {
    $Keyword = $_GET["search-input"];
    $VIN = $_GET["VIN-input"];
    $Vehicle_type = $_GET["vehicle_type"];
    $Manufacturer = $_GET["manufacturer"];
    $Model_year = $_GET["model_year"];
    $Fuel_type = $_GET["fuel_type"];
    $Color = $_GET["color"];
    $Sale_status = $_GET["sale_status"];
    $db = new Database;
    $conn = $db->mysql;
    $sql =
    "SELECT Q1.VIN, Vehicle_type, Model_year, Manufacturer, Model, Fuel_type, Mileage, 
    CASE WHEN Parts_cost IS NOT NULL THEN (Purchase_price*1.25 + Parts_cost*1.1) ELSE Purchase_price*1.25 END AS Sale_price, Color, Vehicle_description, Sale_date
    FROM (SELECT V.VIN, Vehicle_type, Model_year, Manufacturer, Model, Fuel_type, Mileage, Purchase_price, Vehicle_description, Sale_date
    FROM Vehicle AS V LEFT JOIN Buys as B on V.VIN = B.VIN
    ) Q1
    LEFT JOIN
    (SELECT VIN, GROUP_CONCAT(Color) AS Color FROM Vehicle_color GROUP BY VIN) Q2
    ON Q1.VIN = Q2.VIN
    LEFT JOIN
    (SELECT V.VIN, SUM(Unit_price*Parts_quantity) AS Parts_cost FROM
    Vehicle as V
    LEFT OUTER JOIN Parts_Order as O ON V.VIN = O.VIN
    LEFT OUTER JOIN Parts as P ON O.VIN=P.VIN and O.Order_ordinal=P.Order_ordinal
    GROUP BY VIN) Q3
    ON Q1.VIN = Q3.VIN
    WHERE COLOR LIKE concat('%','$Color','%')
    AND CASE WHEN '$Vehicle_type'='' THEN 1 ELSE Vehicle_type = '$Vehicle_type' END
    AND CASE WHEN '$Manufacturer'='' THEN 1 ELSE Manufacturer='$Manufacturer' END
    AND CASE WHEN '$Model_year'='' THEN 1 ELSE Model_year='$Model_year' END
    AND CASE WHEN '$Fuel_type'='' THEN 1 ELSE Fuel_type='$Fuel_type' END
    AND CASE WHEN '$VIN'='' THEN 1 ELSE Q1.VIN ='$VIN' END
    AND CASE WHEN '$Keyword'='' THEN 1 ELSE (Manufacturer LIKE concat('%','$Keyword','%')
    OR Model_year LIKE concat('%','$Keyword','%')
    OR Model LIKE concat('%','$Keyword','%')
    OR Vehicle_description LIKE concat('%','$Keyword','%')) END";
    if($usertype=='Manager' || $usertype=='Owner' || $usertype=='Inventory Clerk'){
        if($Sale_status==''){
            $sql.=" ORDER BY Q1.VIN ASC;";
        } elseif($Sale_status=='Unsold') {
            $sql.=" AND Sale_date IS NULL ORDER BY Q1.VIN ASC;";
        } else{
            $sql.=" AND Sale_date IS NOT NULL ORDER BY Q1.VIN ASC;";
        }
    }
    else{
        $sql.=" AND Q1.VIN in
        (SELECT VIN FROM(SELECT V.VIN, SUM(CASE WHEN P.Parts_status IN ('received', 'ordered') THEN 1 ELSE 0 END) as Pending 
        FROM Vehicle as V LEFT OUTER JOIN Buys as B ON V.VIN = B.VIN 
        LEFT OUTER JOIN Parts_order as O ON V.VIN = O.VIN 
        LEFT OUTER JOIN Parts as P ON O.VIN = P.VIN and O.Order_Ordinal=P.Order_Ordinal 
        WHERE Sale_date IS NULL GROUP BY V.VIN HAVING Pending=0)AS Q4)
        ORDER BY Q1.VIN;";
    }

    $result = $conn->query($sql);
    
    if ($result->num_rows > 0){
    while($row = $result->fetch_assoc() ){
        echo '<div class="vehicle-item">';

        echo '<form method="get" action="viewProfile.php">';        
        echo '<input type="hidden" name="vehicle_id" value="' . $row['VIN'] . '">';
        echo '<table>';
        echo '<h2>' . $row['Manufacturer'] . ' ' . $row['Model'] . '</h2>';
        echo '<tr><td>VIN:</td><td>' . $row['VIN'] . '</td></tr>';
        echo '<tr><td>Type:</td><td>' . $row['Vehicle_type'] . '</td></tr>';
        echo '<tr><td>Year:</td><td>' . $row['Model_year'] . '</td></tr>';
        echo '<tr><td>Color:</td><td>' . $row['Color'] . '</td></tr>';
        echo '<tr><td>Fuel Type:</td><td>' . $row['Fuel_type'] . '</td></tr>';
        echo '<tr><td>Sale Price:</td><td>$' . $row['Sale_price'] . '</td></tr>';
        echo '<tr><td>Mileage:</td><td>' . $row['Mileage'] . ' Miles</td></tr>';
        echo '<tr><td></td><td><button type="submit">View Details</button></td></tr>';
        echo '</table>';
        echo '</form>';
        echo '<br>';
        echo '</div>';

    }
    } else {
        echo "Sorry, it looks like we don't have that in stock!";
    }

    $conn->close();
}
?>

<?php
  include 'inc/footer.php';
?>