<?php
// Include database connection file
include 'inc/header.php';
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');

// Static test VIN 

if (isset($_POST['vehicle_id']) or isset($_GET['vehicle_id'])) {
    $VIN = isset($_POST['vehicle_id']) ? $_POST['vehicle_id'] : $_GET['vehicle_id'];
    Session::set('VIN', $VIN);
}
;

$VIN = $_SESSION['VIN'];

// Initialize the database connection
$db = new Database;

// Get the exited #number for this vehicle
$countOrderQuery = "SELECT CASE WHEN COUNT(ORDER_ORDINAL)<9 THEN CONCAT('00', CAST(COUNT(ORDER_ORDINAL)+1 AS CHAR(1))) 
                    WHEN COUNT(ORDER_ORDINAL)<99 THEN CONCAT('0', CAST(COUNT(ORDER_ORDINAL)+1 AS CHAR(3))) 
                    ELSE CONCAT('0', CAST(COUNT(ORDER_ORDINAL)+1 AS CHAR(3))) END AS ORD_NUM
                    FROM PARTS_ORDER GROUP BY VIN 
                    HAVING VIN = '$VIN';
                    ";
$countOrderQueryResult = $db->mysql->query($countOrderQuery);
if ($countOrderQueryResult->num_rows > 0) {
    while ($row = $countOrderQueryResult->fetch_assoc()) {
        $order_ordinal = $row['ORD_NUM'];
    }
}

$existOrderQuery = "SELECT Order_ordinal FROM parts_order
                    WHERE VIN = '$VIN';";
$existOrderResult = $db->mysql->query($existOrderQuery);
$existOrders = [];
if ($existOrderResult->num_rows > 0) {
    while ($row = $existOrderResult->fetch_assoc()) {
        $existOrders[] = $row['Order_ordinal'];
    }
}

// $VIN = isset($_GET['VIN']) ? $_GET['VIN'] : '';
// Display existing parts orders for the vehicle
$selectPartsOrdersQuery = "SELECT v.VIN, o.Order_ordinal, o.Vendor_name,
    p.Parts_number, p.Parts_quantity, p.Unit_price, p.Parts_status
    FROM VEHICLE as v
    LEFT JOIN PARTS_ORDER AS o ON o.VIN = v.VIN
    LEFT JOIN PARTS AS p ON o.VIN = p.VIN AND p.Order_ordinal = o.Order_ordinal
    WHERE v.VIN = '$VIN'";

$result = $db->mysql->query($selectPartsOrdersQuery);

// Check if the vehicle is sold
$checkVehicleSoldQuery = "SELECT COUNT(VIN) as sold FROM BUYS WHERE VIN = '$VIN'";
$ifVehicleSoldResult = $db->mysql->query($checkVehicleSoldQuery);
$ifVehicleSold = $ifVehicleSoldResult->fetch_assoc()['sold'];
// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit-new"])) {
        // Validate and process the form data here
        $order_ordinal = $_POST['order_ordinal'];
        $vin = $_POST['vin'];
        $clerk_username = $_POST['clerk_name'];
        $vendor_name = $_POST['vendor_name'];

        // Insert into the PARTS_ORDER table
        $insertPartsOrderQuery = "INSERT INTO PARTS_ORDER (Order_ordinal, VIN, Clerker_username, Vendor_name)
                                VALUES ('$order_ordinal', '$vin', '$clerk_username', '$vendor_name')";

        // Execute the query and check for success
        if ($db->mysql->query($insertPartsOrderQuery) === TRUE) {
            // Loop through parts data and insert into the PARTS table
            foreach ($_POST['parts'] as $part) {
                $parts_number = $part['parts_number'];
                $parts_quantity = $part['parts_quantity'];
                $unit_price = $part['unit_price'];
                $parts_status = $part['parts_status'];
                $parts_description = $part['parts_description'];

                // Insert into the PARTS table
                $insertPartsQuery = "INSERT INTO PARTS (VIN, Order_ordinal, Parts_number, Parts_quantity, Unit_price, Parts_status, Parts_description)
                                    VALUES ('$vin', '$order_ordinal', '$parts_number', '$parts_quantity', '$unit_price', '$parts_status', '$parts_description')";

                $db->mysql->query($insertPartsQuery);
            }

            // Redirect or perform additional actions as needed
            header("Location: addPartsOrder.php");
            exit();
        } else {
            echo "Error: " . $insertPartsOrderQuery . "<br>" . $db->mysql->error;
        }
    } elseif (isset($_POST["submit-modify"])) {
        // Process modification of parts order here
        $order_ordinal = $_POST['order_ordinal'];
        $vin = $_POST['vin'];

        foreach ($_POST['parts'] as $part) {
            $parts_number = $part['parts_number'];
            $parts_quantity = $part['parts_quantity'];
            $unit_price = $part['unit_price'];
            $parts_status = $part['parts_status'];
            $parts_description = $part['parts_description'];

            // Insert into the PARTS table
            $insertPartsQuery = "INSERT INTO PARTS (VIN, Order_ordinal, Parts_number, Parts_quantity, Unit_price, Parts_status, Parts_description)
                                VALUES ('$vin', '$order_ordinal', '$parts_number', '$parts_quantity', '$unit_price', '$parts_status', '$parts_description')";

            $db->mysql->query($insertPartsQuery);
        }

        // Redirect or perform additional actions as needed
        header("Location: addPartsOrder.php");
        exit();
    } elseif (isset($_POST["update"])) {
        // Process modification of parts order here
        $order_ordinal = $_POST['Order_ordinal'];
        $parts_number = $_POST['Parts_number'];
        $update_status = $_POST['update-status'];


        $updatePartsQuery = "UPDATE PARTS SET Parts_status = '$update_status' WHERE VIN = '$VIN' AND Parts_number = '$parts_number'
            AND Order_ordinal = '$order_ordinal';";
        // AND Parts_status < '$update_status';";

        $db->mysql->query($updatePartsQuery);


        // Redirect or perform additional actions as needed
        header("Location: addPartsOrder.php");
        exit();
    }

} else {
    echo $db->mysql->error;
    // echo $_POST['order_ordinal'];
    // echo $update_statos = $_POST['update-status'];
    // echo $_POST["update"];
}
;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Add Parts Order</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="card ">
        <div class="card-header">
            <h3>
                <i class="fas fa-plus"></i>Add New Parts Order
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
                    <a href="viewProfile.php?vehicle_id=<?php echo $VIN; ?>" class="btn btn-primary">Back</a>
                </span>
            </h3>
        </div>
        <div class="container">

            <div class="card-body">
                <?php if ($ifVehicleSold == 1): ?>
                    <p>The vehicle is sold. Display only existing parts orders.</p>
                <?php else: ?>
                    <p>The vehicle is unsold. You can modify parts status and add new orders.</p>

                <?php endif; ?>
                <?php if ($result->num_rows > 0): ?>
                    <table class="table">
                        <!-- Display table header -->
                        <thead>
                            <tr>
                                <th>VIN</th>
                                <th>Order Ordinal</th>
                                <th>Vendor Name</th>
                                <th>Parts Number</th>
                                <th>Parts Quantity</th>
                                <th>Unit Price</th>
                                <th>Parts Status</th>
                            </tr>
                        </thead>
                        <!-- Display table rows -->
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td>
                                        <?php echo $row['VIN']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Order_ordinal']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Vendor_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Parts_number']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Parts_quantity']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Unit_price']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Parts_status']; ?>
                                    </td>
                                    <?php if ($ifVehicleSold == 0) { ?>
                                        <td>
                                            <!-- Display dropdown for modifying parts status -->
                                            <form name="update" action="" method="POST">
                                                <?php if ($row['Parts_status'] == 'Ordered') { ?>
                                                    <select name="update-status" id="update-status" required>

                                                        <option value=2>Received</option>
                                                        <option value=3>Installed</option>

                                                    </select>
                                                <?php } elseif ($row['Parts_status'] == 'Received') { ?>
                                                    <select name="update-status" id="update-status" required>

                                                    <option value=3>Installed</option>
                                                    </select>
                                                <?php  }else{ ?>
                                                    <select name="update-status" id="update-status" required>

                                                    <option value=3>N/A</option> 
                                                    </select>
                                                    <?php  }; ?> 

                                                <input type="hidden" name="Order_ordinal"
                                                    value="<?php echo $row['Order_ordinal']; ?>">
                                                <input type="hidden" name="Parts_number"
                                                    value="<?php echo $row['Parts_number']; ?>">
                                        </td>
                                        <td>
                                            <button type="submit" name="update" class="btn btn-primary"> Update </button>
                                        </td>
                                        </form>
                                        </td>
                                    <?php }
                                    ; ?>
                                </tr>
                            <?php }
                            ; ?>

                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No existing parts orders for this vehicle.</p>
                <?php endif; ?>

                <?php if ($ifVehicleSold == 0): ?>
                    <!-- Add New Parts Order and New Parts -->
                    <form action="addPartsOrder.php" method="POST">
                        <!-- Include relevant input fields and buttons as needed for adding new parts order and parts -->
                        <!-- ... -->

                    </form>
                <?php endif; ?>

            </div>
        </div>

        <div>

            <button type="button" onclick="displayNewOrderField()" id="newOrderButton">Add A New Order</button>
            <button type="button" onclick="displayModifyOrderField()" id="modifyOrderButton">Add More in An Existed
                Order</button>
        </div>
        <!-- Form for adding a new parts order -->
        <div id="new-order-container" style="display: none;">
            <div class="order">
                <form action="" name="new_order" method="POST">
                    order_ordinal
                    <label for="order_ordinal">Order Ordinal:</label>
                    <!-- <input type="text" name="order_ordinal" required> -->
                    <input name="order_ordinal" value="<?php echo isset($order_ordinal) ? $order_ordinal : ''; ?>"
                        required>


                    <label for="vin">VIN:</label>
                    <input name="vin" id="vin" value="<?php echo isset($VIN) ? $VIN : ''; ?>" required>

                    <label for="clerk_username">Clerk Username:</label>
                    <input name="clerk_name" id="clerk_name"
                        value="<?php echo Session::get('userid') ? Session::get('userid') : ''; ?>" required>

                    <label for="vendor_name">Vendor Name:</label>
                    <input name="vendor_name" id="vendor_name"
                        value="<?php echo isset($_POST['vendor_name']) ? $_POST['vendor_name'] : ''; ?>" required>
                    <a href="searchvendor.php?vehicle_id=<?php echo $VIN; ?>" class="btn btn-primary">Search Vendor</a>



                    <!-- Parts information -->
                    <div id="parts-container">
                        <h3>Parts Information</h3>
                        <div class="part">
                            <label for="parts_number">Parts Number:</label>
                            <input type="text" name="parts[0][parts_number]" required>

                            <label for="parts_quantity">Quantity:</label>
                            <input type="number" name="parts[0][parts_quantity]" required>

                            <label for="unit_price">Unit Price:</label>
                            <input type="text" name="parts[0][unit_price]" required>

                            <label for="parts_status">Status:</label>
                            <select name="parts[0][parts_status]" required>
                                <option value="Ordered">Ordered</option>
                                <option value="Received">Received</option>
                                <option value="Installed">Installed</option>
                            </select>

                            <label for="parts_description">Description:</label>
                            <textarea name="parts[0][parts_description]"></textarea>
                        </div>
                    </div>
                    <!-- <input type="hidden" name="vehicle_id" value="<?php $VIN ?>" >  -->
                    <button type="button" id="addPartButton">Add Another Part</button>

                    <!-- Save Parts Order button -->
                    <button type="submit" name="submit-new" class="btn btn-primary">Save Parts Order</button>
                </form>
            </div>
        </div>
        <div id="modify-order-container" style="display: none;">
            <div class="order">
                <form action="" name="modify_order" method="POST">

                    <label for="order_ordinal">Order Ordinal:</label>
                    <select name="order_ordinal" id="order_ordinal" required>
                        <?php foreach ($existOrders as $existOrder): ?>
                            <option value="<?php echo $existOrder; ?>">
                                <?php echo $existOrder; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="vin">VIN:</label>
                    <input name="vin" id="vin" value="<?php echo isset($VIN) ? $VIN : ''; ?>" required>

                    <!-- Parts information -->
                    <div id="parts-container">
                        <h3>Parts Information</h3>
                        <div class="part">
                            <label for="parts_number">Parts Number:</label>
                            <input type="text" name="parts[0][parts_number]" required>

                            <label for="parts_quantity">Quantity:</label>
                            <input type="number" name="parts[0][parts_quantity]" required>

                            <label for="unit_price">Unit Price:</label>
                            <input type="text" name="parts[0][unit_price]" required>

                            <label for="parts_status">Status:</label>
                            <select name="parts[0][parts_status]" required>
                                <option value="Ordered">Ordered</option>
                                <option value="Received">Received</option>
                                <option value="Installed">Installed</option>
                            </select>

                            <label for="parts_description">Description:</label>
                            <textarea name="parts[0][parts_description]"></textarea>
                        </div>
                    </div>

                    <button type="button" id="addPartButton">Add Another Part</button>

                    <!-- Save Parts Order button -->
                    <button type="submit" name="submit-modify" class="btn btn-primary">Save Extra Parts
                    </button>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- JavaScript to dynamically add parts fields -->
    <script>
        document.getElementById('addPartButton').addEventListener('click', function () {
            var partsContainer = document.getElementById('parts-container');
            var newPart = document.querySelector('.part').cloneNode(true);
            var partNumber = partsContainer.querySelectorAll('.part').length;

            newPart.querySelectorAll('input, textarea').forEach(function (element) {
                element.name = 'parts[' + partNumber + '][' + element.name + ']';
                element.value = '';
            });

            partsContainer.appendChild(newPart);
        });

        function displayNewOrderField() {
            var modify_field = document.getElementById("modify-order-container");
            var new_field = document.getElementById("new-order-container");
            modify_field.style.display = "none";
            new_field.style.display = "block";
        };

        function displayModifyOrderField() {
            var modify_field = document.getElementById("modify-order-container");
            var new_field = document.getElementById("new-order-container");
            modify_field.style.display = "block";
            new_field.style.display = "none";
        }
        // function updatePartsStatus(select, VIN, PartsNumber, OrderOrdinal) {
        //     var selectedValue = select.value;

        //     // Create mapping hash table
        //     var statusMapping = {
        //         'Ordered': 1,
        //         'Received': 2,
        //         'Installed': 3
        //     };

        //     // Generate variable for Parts_status_update
        //     var PartsStatusUpdate = statusMapping[selectedValue];

        //     // Perform AJAX request or redirect to handle the update
        //     // ...

        //     // For demonstration purposes, alert the selected value
        //     alert('Selected value: ' + selectedValue);
        // }
    </script>
</body>

</html>



<?php
  include 'inc/footer.php';

  ?>