<?php
include 'inc/header.php';
// Include database connection file
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');

if (isset($_POST['vehicle_id']) or isset($_GET['vehicle_id'])) {
    $VIN = isset($_POST['vehicle_id']) ? $_POST['vehicle_id'] : $_GET['vehicle_id'];
    Session::set('VIN', $VIN);
}
;

$VIN = $_SESSION['VIN'];

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Search</title>
    <style>
        .add-container {
            flex: 1, 100px;
            display: flex;
            flex-wrap: nowrap;
            flex-direction: column;
            align-items: left;
            justify-content: space-around;
            padding: 50px;
            background-color: #f2f2f2;
            border-radius: 10px;
        }
    </style>
</head>

<?php

// Check if the session is active
Session::CheckSession();

// Initialize the database connection
$db = new Database;

// Define variables
$Vendor_name = $Phone_number = $Street = $City = $State = $Postal_code = '';

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_vendor'])) {
    // Validate and process the form data here
    $Vendor_name = $_POST['Vendor_name'];
    $Phone_number = $_POST['Phone_number'];
    $Street = $_POST['Street'];
    $City = $_POST['City'];
    $State = $_POST['State'];
    $Postal_code = $_POST['Postal_code'];

    // Perform data validation (you may need to customize this part)
    if (empty($Vendor_name) || empty($Phone_number) || empty($Street) || empty($City) || empty($State) || empty($Postal_code)) {
        echo "Invalid Data Type";
    } else {
        // Insert into the VENDOR table
        $insertVendorQuery = "INSERT INTO VENDOR (Vendor_name, Phone_number, Street, City, State, Postal_code)
                              VALUES ('$Vendor_name', '$Phone_number', '$Street', '$City', '$State', '$Postal_code')";

        // Execute the query and check for success
        if ($db->mysql->query($insertVendorQuery) === TRUE) {
            // Set the session variable for the added vendor
            $_SESSION['current_vendor'] = array(
                'Vendor_name' => $Vendor_name,
                'Phone_number' => $Phone_number,
                'Street' => $Street,
                'City' => $City,
                'State' => $State,
                'Postal_code' => $Postal_code
            );

            // Redirect to the search Parts Order page
            header("Location: searchvendor.php?vehicle_id=$VIN");
            exit();
        } else {
            echo "Error: " . $insertVendorQuery . "<br>" . $db->mysql->error;
        }
    }
}
;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Add Customer</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <!-- <div class="container"> -->
    <div class="card">
        <div class="card-header">
            <h3>
                <a class="fas fa-add"></a>Add Vendor
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
                    <a href="searchvendor.php?vehicle_id=<?php echo $VIN; ?>" class="btn btn-primary">Back</a>
                </span>
            </h3>
        </div>
        <div class="card-body">

            <div class="add-container">
                <form action="" method="POST">
                    <!-- <form action="" method="POST"> -->
                    <label for="Vendor_name">Vendor Name:</label>
                    <input type="text" name="Vendor_name" required>

                    <div class="form-group">
                        <label for="Phone_number">Phone Number:</label>
                        <input type="tel" name="Phone_number" id="Phone_number" pattern="[0-9]{10}"
                            title="Enter a valid 10-digit phone number" required>
                    </div>

                    <div>
                        <label for="Street">Street:</label>
                        <input type="text" name="Street" required>
                    </div>
                    <div>
                        <label for="City">City:</label>
                        <input type="text" name="City" required>
                        </div>
                        <div>
                            <label for="State">
                                State:
                            </label>
                            <select name="State">
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="DC">District Of Columbia</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="postal_code">
                                Postal Code:
                            </label>
                            <input type="text" name="Postal_code" id="Postal_code" pattern="[0-9]{5}"
                                title="Enter a valid postal code" required>
                        </div>

                        <!-- Save Vendor button -->
                        <button type="submit" name="save_vendor" class="btn btn-primary">Save Vendor</button>
                </form>


            </div>
        </div>
    </div>
    </div>


</body>

</html>



<?php
include 'inc/footer.php';

?>