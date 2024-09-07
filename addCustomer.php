<?php
include 'inc/header.php';
// Include database connection file
include 'lib/Database.php';
$userid = Session::get('userid');
$usertype = Session::get('usertype');
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

// Initialize the database connection
$db = new Database;

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process the form data here

    $customerType = $_POST['customer_type'];

    // Common fields for both individual and business
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];

    // Additional fields for individual
    $license_id = $_POST['license_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Additional fields for business
    $tin = $_POST['tin'];
    $business_name = $_POST['business_name'];
    $primary_title = $_POST['primary_title'];
    $primary_name = $_POST['primary_name'];

    // Perform data validation (you need to implement this)

    // Insert into the CUSTOMER table
    $insertCustomerQuery = "INSERT INTO CUSTOMER (Email, Phone_number, Street, City, State, Postal_code, Customer_type)
                        VALUES ('$email', '$phone_number', '$street', '$city', '$state', '$postal_code', '$customerType')";

    // Execute the query and check for success
    if ($db->mysql->query($insertCustomerQuery) === TRUE) {
        $customerID = $db->mysql->insert_id; // Get the last inserted ID

        // Insert into the corresponding entity based on customer type
        if ($customerType == "Person") {
            $insertIndividualQuery = "INSERT INTO INDIVIDUAL (Customer_ID, License_ID, Firstname, Lastname)
                                      VALUES ('$customerID', '$license_id', '$first_name', '$last_name')";

            $db->mysql->query($insertIndividualQuery);
        } elseif ($customerType == "Business") {
            $insertBusinessQuery = "INSERT INTO BUSINESS (Customer_ID, TIN, Business_name, Primary_title, Primary_name)
                                    VALUES ('$customerID', '$tin', '$business_name', '$primary_title', '$primary_name')";

            $db->mysql->query($insertBusinessQuery);
        }

        // Redirect or perform additional actions as needed
        header("Location: searchcustomer.php"); // Adjust the URL accordingly
        exit();
    } else {
        echo "Error: " . $insertCustomerQuery . "<br>" . $db->mysql->error;
    }
}
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
                <i class="fas fa-add"></i>Add Customer
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
                        <a href="searchcustomer.php" class="btn btn-primary">Back</a>
                </span>
            </h3>
        </div>
        <div class="card-body">
            <div class="add-container">
                <!-- Form for adding a new customer -->
                <form action="addCustomer.php" method="post">
                    <!-- Dropdown for selecting customer type -->
                    <label for="customer_type">
                        <h4>Customer Type:</h4>
                    </label>
                    <select name="customer_type" id="customer_type" required>
                        <option selected = "selected" value="Person">Person</option>
                        <option value="Business">Business</option>
                    </select>
                    <!-- <div class="form-check">
                        <input class="form-check-input" type="radio" name="customer_type" id="Person" , value="Person">
                        <label class="form-check-label" for="Person">
                            <h4>Person</h4>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="customer_type" id="Business" ,value="Business">
                        <label class="form-check-label" for="Business">
                            <h4>Business</h4>
                        </label>
                    </div> -->


                    <!-- Fields for Individual -->
                    <div id="person-fields">
                        <div>
                            <label for="license_id">
                                <h4> License ID: </h4>
                            </label>
                            <input type="text" name="license_id">
                        </div>
                        <div>
                            <label for="first_name">
                                <h4> First Name: </h4>
                            </label>
                            <input type="text" name="first_name">
                        </div>
                        <div>
                            <label for="last_name">
                                <h4> Last Name: </h4>
                            </label>
                            <input type="text" name="last_name">
                        </div>
                    </div>

                    <!-- Fields for Business -->
                    <div id="business-fields">
                        <div>
                            <label for="tin">
                                <h4> TIN: </h4>
                            </label>
                            <input type="text" name="tin">
                        </div>
                        <div>
                            <label for="business_name">
                                <h4> Business Name: </h4>
                            </label>
                            <input type="text" name="business_name">
                        </div>
                        <div>
                            <label for="primary_title">
                                <h4> Primary Title: </h4>
                            </label>
                            <input type="text" name="primary_title">
                        </div>
                        <div>
                            <label for="primary_name">
                                <h4> Primary Name: </h4>
                            </label>
                            <input type="text" name="primary_name">
                        </div>
                    </div>
                    <!-- Common fields -->
                    <div class="form-group">
                        <label for="email">
                            <h4>Email:</h4>
                        </label>
                        <input type="email" name="email" id="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Enter a valid email address" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">
                            <h4>Phone Number:</h4>
                        </label>
                        <input type="tel" name="phone_number" id="phone_number" pattern="[0-9]{10,}" title="Enter a valid phone number" required>
                    </div>

                    <div>
                        <label for="street">
                            <h4> Street: </h4>
                        </label>
                        <input type="text" name="street" required>
                    </div>
                    <div>
                        <label for="city">
                            <h4> City: </h4>
                        </label>
                        <input type="text" name="city" required>
                    </div>
                    <div>
                        <label for="state">
                            <h4> State: </h4>
                        </label>
                        <select name="state">
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
                            <h4>Postal Code:</h4>
                        </label>
                        <input type="text" name="postal_code" id="postal_code" pattern="[0-9]{5}" title="Enter a valid postal code" required>
                    </div>

                    <!-- Save Customer button -->
                    <button type="submit" href="searchcustomer.php" class="btn btn-primary">Save Customer</button>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript to show/hide fields based on customer type -->
    <script>
        document.getElementById('customer_type').addEventListener('change', function () {

            var customerType = this.value;
            document.getElementById('business-fields').style.display = customerType === 'Business' ? 'block' : 'none';
            document.getElementById('person-fields').style.display = customerType === 'Person' ? 'block' : 'none';

        });
    </script>

</body>

</html>



<?php
  include 'inc/footer.php';

  ?>