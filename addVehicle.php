<?php
include 'inc/header.php';
include 'lib/Database.php';
Session::CheckSession();
$userid = Session::get('userid');
$usertype = Session::get('usertype');

$pre_page = "add_vehicle";
Session::set('pre_page', $pre_page);


// Define arrays for drop-down options
$db = new Database;
$manufacturerQuery = "SELECT Manufacturer FROM MANUFACTURER";
$manufacturerResult = $db->mysql->query($manufacturerQuery);
$manufacturers = [];

if ($manufacturerResult->num_rows > 0) {
    while ($row = $manufacturerResult->fetch_assoc()) {
        $manufacturers[] = $row['Manufacturer'];
    }
}

$vehicleTypeQuery = "SELECT Vehicle_type FROM VEHICLETYPE";
$vehicleTypeResult = $db->mysql->query($vehicleTypeQuery);
$vehicleTypes = [];

if ($vehicleTypeResult->num_rows > 0) {
    while ($row = $vehicleTypeResult->fetch_assoc()) {
        $vehicleTypes[] = $row['Vehicle_type'];
    }
}
$fuelTypes = ["Gas", "Diesel", "Natural Gas", "Hybrid", "Plugin Hybrid", "Battery", "Fuel Cell"];
$colors = ["Aluminum", "Beige", "Copper", "Cream", "Navy", "Orange", "Silver", "Tan", "Turquoise", "Black", "Gold", "Pink", "Blue", "Brown", "Bronze", "Claret", "Gray", "Green", "Maroon", "Metallic", "Purple", "Red", "Rose", "Rust", "White", "Yellow"];
$sellerTypes = ["Individual", "Dealership"];
$vehicleConditions = ["Excellent", "Very Good", "Good", "Fair"];
// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process the form data here
    // Example: Retrieve form data and add a new vehicle to the database
    $VIN = $_POST['vin'];
    $vehicleType = $_POST['vehicle_type'];
    $manufacturer = $_POST['manufacturer'];
    $modelYear = $_POST['model_year'];
    $model = $_POST['model'];
    $fuelType = $_POST['fuel_type'];
    $mileage = $_POST['mileage'];
    $vehicleDescription = $_POST['vehicle_description'];
    $sellerID = $_POST['seller_id'];
    $clerkUsername = $_POST['clerk_username'];
    $vehicleCondition = $_POST['vehicle_condition'];
    $purchaseDate = $_POST['purchase_date'];
    $purchasePrice = $_POST['purchase_price'];
    $colorsSelected = isset($_POST['colors']) ? $_POST['colors'] : [];

    // Perform the database insertion or validation as needed
    // Example: Insert the new vehicle into the VEHICLE table
    $insertVehicleQuery = "INSERT INTO VEHICLE(VIN, Vehicle_type, MANUFACTURER, Model_year, Model,Fuel_type, Mileage, Vehicle_Description, Seller_ID, Clerker_Username, Vehicle_condition, Purchase_date, Purchase_price)
    VALUES ('$VIN',
     '$vehicleType', 
    '$manufacturer', 
    '$modelYear', 
    '$model', 
    '$fuelType', 
    '$mileage', 
    '$vehicleDescription',
    '$sellerID',
     '$clerkUsername', 
    '$vehicleCondition', 
    '$purchaseDate',
     '$purchasePrice');";

    // $db->mysql->query($insertVehicleQuery);


    // Insert colors into the VEHICLE_COLOR table
    if ($db->mysql->query($insertVehicleQuery) === TRUE) {
        // Insert colors into the VEHICLE_COLOR table
        foreach ($colorsSelected as $color) {
            $color = mysqli_real_escape_string($db->mysql, $color); // Assuming you're using MySQLi
            $query = "INSERT INTO VEHICLE_COLOR (VIN, Color) VALUES ('$VIN', '$color')";
            mysqli_query($db->mysql, $query);
        }

        // Redirect to a confirmation page or back to the main page after adding the vehicle

        header("Location: viewProfile.php?vehicle_id=$VIN "); // Adjust the URL accordingly
        exit();
    }
    ;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Add Vehicle</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>


<head>

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
    </style>
</head>

<body>

    <div class="card ">
        <!-- <div class=search-container> -->
            <div class="card-header ">
                <h3><a class="fas fa-add"></a> Add Vehicle</h3>
            </div>
            <div class="card-body">
                <!-- Link to navigate to searchCustomer.php -->


                <form action="addVehicle.php" method="post">
                    <!-- Input fields for vehicle information -->
                    <div class="form-group">
                        <label for="vin">VIN:</label>
                        <input type="text" name="vin" id="vin" pattern=".{17}" title="VIN must be exactly 17 characters"
                            required>
                    </div>

                    <!-- Dropdown for selecting vehicle type -->
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type:</label>
                        <select name="vehicle_type" id="vehicle_type" required>
                            <?php foreach ($vehicleTypes as $vehicleType): ?>
                                <option value="<?php echo $vehicleType; ?>">
                                    <?php echo $vehicleType; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Dropdown for selecting manufacturer -->
                    <div class="form-group">
                        <label for="manufacturer">Manufacturer:</label>
                        <select name="manufacturer" id="manufacturer" required>
                            <?php foreach ($manufacturers as $manufacturer): ?>
                                <option value="<?php echo $manufacturer; ?>">
                                    <?php echo $manufacturer; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- <div class="form-group"> -->
                    <div class="form-group">
                        <label for="model">Model:</label>
                        <input type="text" name="model" class="form-control" required>
                    </div>
                    <div>
                    </div>
                    <div>
                        <label for="model_year">Model Year:</label>
                        <select name="model_year" id="model_year" required>
                            <?php for ($year = date("Y")+1; $year >= 1980; $year--): ?>
                                <option value="<?php echo $year; ?>">
                                    <?php echo $year; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fuel_type">Fuel Type:</label>
                        <select name="fuel_type" id="fuel_type" required>
                            <?php foreach ($fuelTypes as $fuelType): ?>
                                <option value="<?php echo $fuelType; ?>">
                                    <?php echo $fuelType; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Checkbox for selecting colors -->
                    <div class="form-group">
                        <label for="colors">Colors:</label>
                        <div class="form-check form-check-inline">
                            <?php foreach ($colors as $color): ?>
                                <input type="checkbox" class="form-check-input" name="colors[]"
                                    value="<?php echo $color; ?>">
                                <label class="form-check-label" for="colors">
                                    <?php echo $color; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mileage">Mileage:</label>
                        <input type="number" name="mileage" id="mileage" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_description">Vehicle Description:</label>
                        <textarea name="vehicle_description" id="vehicle_description" rows="3"></textarea>
                    </div>
                    <!-- <label for="seller_id">Seller ID:</label>
                <input type="number" name="seller_id" id="seller_id" required> -->
                    <div class="form-group">
                        <label for="seller_id">Seller ID:</label>
                        <input type="number" name="seller_id" id="seller_id"
                            value="<?php echo isset($_GET['customerid']) ? $_GET['customerid'] : ''; ?>" required>
                        <a href="searchCustomer.php" class="btn btn-primary">Search Customer</a>
                    </div>

                    <div class="form-group">
                        <label for="clerk_username">Clerk Username:</label>
                        <input type="text" name="clerk_username" id="clerk_username"
                            value="<?php echo Session::get('userid') ? Session::get('userid') : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_condition">Vehicle Condition:</label>
                        <select name="vehicle_condition" id="vehicle_condition" required>
                            <option value="Excellent">Excellent</option>
                            <option value="Very Good">Very Good</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date:</label>
                        <input type="date" name="purchase_date" id="purchase_date" required>
                    </div>
                    <div class="form-group">
                        <label for="purchase_price">Purchase Price:</label>
                        <input type="number" name="purchase_price" id="purchase_price" min="0" required>
                    </div>
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary">Add Vehicle</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>