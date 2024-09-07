<?php
include 'inc/header.php';
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');
$pre_page = "sell_vehicle";
Session::set('pre_page', $pre_page);


// $VIN =  isset($_POST['vehicle_id'] ) ?$_POST['vehicle_id']  :$_GET['vehicle_id']; 
if (isset($_POST['vehicle_id']) or isset($_GET['vehicle_id'])) {
    $VIN = isset($_POST['vehicle_id']) ? $_POST['vehicle_id'] : $_GET['vehicle_id'];
    Session::set('VIN', $VIN);
}
;

$VIN = $_SESSION['VIN'];

//TODO: edit below
$db = new Database;
$conn = $db->mysql;
$result = $conn->query("SELECT * FROM VEHICLE WHERE VIN='$VIN';");
$row = $result->fetch_assoc();
$purchase_date = $row['Purchase_date'];

$customer_type = 'Person';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $VIN = $_POST['vehicle_id'];
    $_SESSION['VIN'] = $VIN;
    if ($_POST['year'] == null || $_POST['month'] == null || $_POST['date'] == null) {
        echo '<center><div class="alert alert-warning">Error: Invalid Sales Date</div></center>';
    } else {
        $sale_date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['date'];
        if ($sale_date < $purchase_date) {
            echo '<center><div class="alert alert-warning">Error: Invalid Sales Date</div></center>';
        } else {
            $sales_username = $_POST['username-input'];
            // if($sales_username = Null){
            //     echo '<center><div class="alert alert-warning">Error: Invalid Salesperson</div></center>';
            // } else{
            //     $salesperson = $conn->query("SELECT `username` FROM `USER` WHERE `Username` = '.$sales_username.' AND User_type='Salesperson';");
            //     if ( $salesperon == null ){
            //         echo '<center><div class="alert alert-warning">Error: Invalid Salesperson</div></center>';
            //     } else{
            $customer_id = $_POST['buyer_id'];
                    $customer = $conn->query("SELECT * FROM Customer WHERE Customer_id = $customer_id;");
                    if($customer->num_rows == null){
                        echo '<center><div class="alert alert-warning">Error: Invalid Customer</div></center>';
                    }
                    else{
            $conn->query("INSERT INTO Buys( VIN, Buyer_ID, Salespeople_username, Sale_date) VALUES('$VIN','$customer_id','$sales_username', '$sale_date');");
            echo "<script>location.href='viewProfile.php';</script>";
                    }
        }

    }
}
//         }
//     }
// } 

?>

<!DOCTYPE html>
<html lang="en">
<div class="card ">
    <div class="card-header">
        <h3> <i class="fas fa-user mr-2"></i>Add Sales Order<span class="float-right"> <a href="viewProfile.php"
                    class="btn btn-primary">Back</a> </h3>
    </div>


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Sales Order</title>
        <style>
            .sales-container {
                display: flex;
                flex-wrap: wrap;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 20px;
                background-color: #f2f2f2;
                border-radius: 10px;
            }

            .sales-input {
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

            .add-button {
                width: 150px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <form class="" action="" method="post">
            <div class="sales-container">
                <h4>Select Sales Date</h4>
                <div class="dropdown-container">
                    <select class="dropdown-select" name="year">
                        <option value="">Year</option>
                        <?php
                        for ($year = 2024; $year >= 1950; $year--) {
                            echo '<option value="' . $year . '">' . $year . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="dropdown-container">
                    <select class="dropdown-select" name="month">
                        <option value="">Month</option>
                        <?php
                        for ($month = 1; $month <= 12; $month++) {
                            if ($month < 10) {
                                $formattedMonth = "0" . $month;
                            } else {
                                $formattedMonth = $month;
                            }
                            echo '<option value="' . $formattedMonth . '">' . $formattedMonth . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="dropdown-container">
                    <select class="dropdown-select" name="date">
                        <option value="">Date</option>
                        <?php
                        for ($date = 1; $date <= 30; $date++) {
                            if ($date < 10) {
                                $formattedDate = "0" . $date;
                            } else {
                                $formattedDate = $date;
                            }
                            echo '<option value="' . $formattedDate . '">' . $formattedDate . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="sales-container">
                <input type="text" class="sales-input" name="username-input"
                    value="<?php echo Session::get('userid') ? Session::get('userid') : ''; ?>" required>

            </div>
            <div class="sales-container">
                <h4>CustomerID: </h4>
                <input type="number" name="buyer_id" id="buyer_id"
                    value="<?php echo isset($_GET['customerid']) ? $_GET['customerid'] : ''; ?>" required>
                <a href="searchCustomer.php" class="btn btn-primary">Search Customer</a>
            </div>
            <div class="sales-container">
                <input type="hidden" name="vehicle_id" value="<?php echo $VIN ?>">
                <button type="submit" class="add-button" name="submit">Sell</button>
            </div>
        </form>

    </body>
</div>

</html>

<?php

include 'inc/footer.php';
?>