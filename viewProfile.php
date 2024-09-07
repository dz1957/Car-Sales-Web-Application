<?php
include 'inc/header.php';
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');
// if(!empty($_GET['vehicle_id'])){$VIN=$_GET['vehicle_id'];unset($_GET['vehicle_id']);}
// if(!empty($_SESSION['VIN'])){$VIN=$_SESSION['VIN'];unset($_SESSION['VIN']);};
// $_SESSION['VIN'] = $VIN;

// $VIN =  isset($_POST['vehicle_id'] ) ?$_POST['vehicle_id']  :$_GET['vehicle_id']; 
if (isset($_POST['vehicle_id']) or isset($_GET['vehicle_id'])) {
    $VIN = isset($_POST['vehicle_id']) ? $_POST['vehicle_id'] : $_GET['vehicle_id'];
    Session::set('VIN', $VIN);
}
;

$VIN = $_SESSION['VIN'];

?>


<div class="card ">
    <div class="card-header">
        <h3> <i class="fas fa-user mr-2"></i>Vehicle Profile<span class="float-right"> <a href="search.php"
                    class="btn btn-primary">Back</a> </h3>
    </div>

    <div class="card-body">

        <?php
        $sql = "SELECT Q1.VIN, Vehicle_type, Model_year, Manufacturer, Model, Fuel_type, Mileage, 
    Purchase_price, Purchase_date, Parts_cost, Salespeople_username, Clerker_username,
    C.Phone_number, C.Email, C.Street, C.City, C.State, C.Postal_code, C.Customer_type,
    I.Firstname, I.Lastname, B.Business_name, B.Primary_title, B.Primary_name,
    CONCAT(U1.Firstname, ' ' ,U1.Lastname) AS Clerk_name, CONCAT(U2.Firstname, ' ' ,U2.Lastname) AS Salespeople_name,
    CASE WHEN Parts_cost IS NOT NULL THEN (Purchase_price*1.25 + Parts_cost*1.1) ELSE Purchase_price*1.25 END AS Sale_price, Color, Vehicle_description, Sale_date
    FROM (SELECT V.VIN, Vehicle_type, Model_year, Manufacturer, Model, Fuel_type, Mileage, Purchase_price, Purchase_date, Vehicle_description, 
    Sale_date, Salespeople_username, Clerker_username, Buyer_ID
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
    LEFT JOIN User AS U1 ON Q1.Clerker_username = U1.Username 
    LEFT JOIN User AS U2 ON Q1.Salespeople_username = U2.Username
    LEFT JOIN Customer as C ON Q1.Buyer_ID = C.CUSTOMER_ID 
    LEFT JOIN Individual as I ON Q1.Buyer_ID = I.CUSTOMER_ID 
    LEFT JOIN Business as B ON Q1.Buyer_ID = B.CUSTOMER_ID
    WHERE Q1.VIN='$VIN';";
        $sql_parts = "SELECT O.Order_ordinal, O.Vendor_name, P.Parts_number, P.Parts_description, P.Parts_quantity, P.Unit_price, P.Parts_status 
    FROM Vehicle as V INNER JOIN Parts_Order as O ON V.VIN = O.VIN INNER JOIN Parts as P ON O.VIN = P.VIN and O.Order_ordinal = P.Order_ordinal WHERE V.VIN = '$VIN';";
        $sql_seller = "SELECT V.Seller_ID, C.Phone_number, C.Email, C.Street, C.City, C.State, C.Postal_code,C.Customer_type,
    Firstname, Lastname, Business_name, Primary_title, Primary_name
    FROM Vehicle as V INNER JOIN Customer as C ON V.Seller_ID = C.CUSTOMER_ID 
    LEFT JOIN Individual as I ON C.CUSTOMER_ID = I.CUSTOMER_ID 
    LEFT JOIN Business as B ON C.CUSTOMER_ID = B.CUSTOMER_ID
    WHERE V.VIN = '$VIN';";

        $db = new Database;
        $conn = $db->mysql;
        $result = $conn->query($sql);
        $result_parts = $conn->query($sql_parts);
        $result_seller = $conn->query($sql_seller);
        while($row = $result->fetch_assoc()){
             $salespeople = $row['Salespeople_name'];
            echo '<div class="vehicle-item">';
            echo '<form method="" action="">';
            //echo '<input type="hidden" name="vehicle_id" value="' . $row['VIN'] . '">';
            echo '<h2>' . $row['Manufacturer'] . ' ' . $row['Model'] . '</h2>';
            echo '<table>';
            echo '<tr><td>VIN:</td><td>' . $row['VIN'] . '</td></tr>';
            echo '<tr><td>Type:</td><td>' . $row['Vehicle_type'] . '</td></tr>';
            echo '<tr><td>Year:</td><td>' . $row['Model_year'] . '</td></tr>';
            echo '<tr><td>Color:</td><td>' . $row['Color'] . '</td></tr>';
            echo '<tr><td>Fuel Type:</td><td>' . $row['Fuel_type'] . '</td></tr>';
            echo '<tr><td>Mileage:</td><td>' . $row['Mileage'] . ' Miles</td></tr>';
            echo '<tr><td>Sale Price:</td><td>$' . $row['Sale_price'] . ' </td></tr>';
            echo '<tr><td>Vehicle Description:</td><td>' . $row['Vehicle_description'] . ' </td></tr>';

            if ($usertype == 'Inventory Clerk' || $usertype == 'Manager' || $usertype == 'Owner') {
                if (empty($row['Parts_cost'])) {
                    echo '<tr><td>Parts total cost:</td><td>N/A</td></tr>';
                } else {
                    echo '<tr><td>Parts total cost:</td><td>$' . $row['Parts_cost'] . '</td></tr>';
                }
                echo '<tr><td>Purchase Price: </td><td>$' . $row['Purchase_price'] . '</td></tr>';
                echo '<tr><td>Purchase Date:</td><td>' . $row['Purchase_date'] . '</td></tr>';

                if ($usertype == 'Manager' || $usertype == 'Owner') {
                    echo '<tr><td>Clerk:</td><td>' . $row['Clerk_name'] . '</td></tr>';
                    if ($row['Salespeople_name'] != NULL) {
                        echo '<tr><td>Salespeople:</td><td>' . $row['Salespeople_name'] . '</td></tr>';
                        echo '</table>';
                        echo '<div class="buyer">';
                        echo '<br>';
                        echo '<h4>Buyer</h4>';
                        echo '<table>';
                        if ($row['Customer_type'] == 'Person') {
                            echo '<tr><td>Name:</td><td>&nbsp;&nbsp;' . $row['Firstname'] . '&nbsp;' . $row['Lastname'] . '</td></tr>';
                        } else {
                            echo '<tr><td>Primary Contact Role:</td><td>&nbsp;' . $row['Primary_title'] . '</td></tr>';
                            echo '<tr><td>Primary Contact:</td><td>&nbsp;' . $row['Primary_name'] . '</td></tr>';
                            echo '<tr><td>Business:</td><td>&nbsp;' . $row['Business_name'] . '</td></tr>';
                        }
                        echo '<tr><td>Address:</td><td>&nbsp;' . $row['Street'] . ',&nbsp;' . $row['City'] . ',&nbsp;' .
                            $row['State'] . $row['Postal_code'] . '&nbsp;' . '</td></tr>';
                        echo '<tr><td>Phone:</td><td>&nbsp;' . $row['Phone_number'] . '</td></tr>';
                        echo '<tr><td>Email:</td><td>&nbsp;' . $row['Email'] . '</td></tr>';
                        echo '</table>';
                        echo '</div>';

                    } else {
                        echo '<tr><td>Salespeople:</td><td>N/A</td></tr>';

                    }
                }
                echo '</table>';
                echo '<div class="parts">';
                echo '<table>';

                if ($result_parts->num_rows !== 0) {
                    echo '<br>';
                    echo '<h4>Parts</h4>';
                }
                while ($row_part = $result_parts->fetch_assoc()) {
                    echo '<tr><td>Order:</td><td>' . $row_part['Order_ordinal'] . '&nbsp;</td><td>Vendor:</td><td>' . $row_part['Vendor_name'] . '&nbsp;</td>
                <td>Part:</td><td>' . $row_part['Parts_number'] . '&nbsp;</td>
                <td>Unit Price:</td><td>$' . $row_part['Unit_price'] . '&nbsp;</td>
                <td>Part Quantity:</td><td>' . $row_part['Parts_quantity'] . '&nbsp;</td>
                <td>Part Status:</td><td>' . $row_part['Parts_status'] . '&nbsp;</td>
                <td>Description:</td><td>' . $row_part['Parts_description'] . '</td></tr>';
                }
                echo '</table>';
                echo '</div>';
            }

            if ($usertype == 'Manager' || $usertype == 'Owner') {
                while ($row_seller = $result_seller->fetch_assoc()) {
                    echo '<div class="seller">';
                    echo '<table>';
                    echo '<br>';
                    echo '<h4>Seller</h4>';
                    ;
                    if ($row_seller['Customer_type'] == 'Person') {
                        echo '<tr><td>Name:</td><td>&nbsp;&nbsp;' . $row_seller['Firstname'] . '&nbsp;' . $row_seller['Lastname'] . '</td></tr>';
                    } else {
                        echo '<tr><td>Primary Contact Role:</td><td>&nbsp;' . $row_seller['Primary_title'] . '</td></tr>';
                        echo '<tr><td>Primary Contact:</td><td>&nbsp;' . $row_seller['Primary_name'] . '</td></tr>';
                        echo '<tr><td>Business:</td><td>&nbsp;' . $row_seller['Business_name'] . '</td></tr>';

                    }
                    echo '<tr><td>Address:</td><td>&nbsp;' . $row_seller['Street'] . ',&nbsp;' . $row_seller['City'] . ',&nbsp;' .
                        $row_seller['State'] . $row_seller['Postal_code'] . '&nbsp;' . '</td></tr>';
                    echo '<tr><td>Phone:</td><td>&nbsp;' . $row_seller['Phone_number'] . '</td></tr>';
                    echo '<tr><td>Email:</td><td>&nbsp;' . $row_seller['Email'] . '</td></tr>';
                    echo '</table>';
                    echo '</div>';
                }
            }
            echo '</form>';
            echo '</table>';
            echo '</div>';
            echo '<br>';
        }
        

        if ($usertype == 'Inventory Clerk' || $usertype == 'Owner') {
            if (!isset($salespeople)) {
                echo '<form method="post" action="addPartsOrder.php?vehicle_id=' . $VIN . '"">';
                echo '<table>';
                echo '<input type="hidden" name="vehicle_id" value="' . $VIN . '">';
                echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td><td><button type="submit">Add/Edit Part Order</button></td></tr>';
                echo '</form>';
                echo '</table>';
                echo '<br>';
            }
            ;
        }

        if ($usertype == 'Salesperson' || $usertype == 'Owner') {
            $sql_parts_status = "SELECT V.VIN, SUM(CASE WHEN P.Parts_status IN ('received', 'ordered') THEN 1 ELSE 0 END) as Pending FROM Vehicle as V LEFT OUTER JOIN Buys as B ON V.VIN = B.VIN LEFT OUTER JOIN Parts_order as O ON V.VIN = O.VIN LEFT OUTER JOIN Parts as P 
        ON O.VIN = P.VIN and O.Order_Ordinal=P.Order_Ordinal WHERE Sale_date IS NULL and V.VIN ='$VIN' GROUP BY V.VIN HAVING Pending=0";
            $result_parts_status = $conn->query($sql_parts_status);
            $row_parts_status = $result_parts_status->fetch_assoc();
            if (!isset($salespeople) && !empty($row_parts_status['VIN'])) {
                echo '<form method="post" action="sell.php">';
                echo '<table>';
                echo '<input type="hidden" name="vehicle_id" value="' . $VIN . '">';
                echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td><td><button type="submit">Add Sales Order</button></td></tr>';
                echo '</table>';
                echo '</form>';
            }
        }

        ?>

    </div>
</div>
</div>
