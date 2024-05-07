<!DOCTYPE html>
<html>
<head>
    <!-- Sets the title of the HTML page appears in the tab -->
    <title>Elderly Registration Form</title>
</head>
<body>

<?php
// Initalises success and error messages 
$registrationSuccess = '';
$registrationError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $dbServername = "localhost";
    $dbUsername = "2105480"; 
    $dbPassword = "7lnrib"; 
    $dbName = "db2105480"; 

    // Create connection using the paramters 
    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

    // Check connection, if it fails the quit the script and display and error message 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and validate the form inputs in an attempt to stop injection and XSS
    $name = $conn->real_escape_string($_POST['name']);
    $age = $conn->real_escape_string($_POST['age']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $address_postcode = $conn->real_escape_string($_POST['address_postcode']);
    $address_street = $conn->real_escape_string($_POST['address_street']);
    $address_house = $conn->real_escape_string($_POST['address_house']);
    $contact_id = $conn->real_escape_string($_POST['contact_id']); // Assuming you're getting this from the form

    // Hash the password for security before saving to the database
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prepare and bind the paramters to the statement to insert a new elderly record into the database 
    $stmt = $conn->prepare("INSERT INTO Elderly (Name, Password, Age, Email, Phone_Number, Address_Postcode, Address_Street, Address_House, Contact_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssssi", $name, $password, $age, $email, $phone_number, $address_postcode, $address_street, $address_house, $contact_id);

    // Execute and check for success
    if ($stmt->execute()) {
        $registrationSuccess = 'Registration successful! You can now <a href="LOG.php">login</a>.';
    } else {
        // If theres an error while adding the information disaply the message 
        $registrationError = "Error: " . $stmt->error;
    }

    $stmt->close(); // Closes the statement
    $conn->close(); // Closes the connection 
}
?>
<!-- Header for the web page -->
<h2>Elderly Registration Form</h2>

<!--  Displays the success message in green -->
<?php if($registrationSuccess): ?>
    <p style="color: green;"><?php echo $registrationSuccess; ?></p>
<?php endif; ?>
<!-- Displays a red error message if unsuccessful -->
<?php if($registrationError): ?>
    <p style="color: red;"><?php echo $registrationError; ?></p>
<?php endif; ?>


<!-- Registration form for the elderly user -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="container">
        <!-- Input field for the elderly user's name -->
        <label for="name"><b>Name</b></label>
        <input type="text" placeholder="Enter Name" name="name" required><br>
        <!-- Input field for the elderly user's password -->
        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required><br>
        <!-- Input field for the elderly user's age -->
        <label for="age"><b>Age</b></label>
        <input type="number" placeholder="Enter Age" name="age" required><br>
        <!-- Input field for the elderly user's email -->
        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" name="email" required><br>
        <!-- Input field for the elderly user's Phone number -->
        <label for="phone_number"><b>Phone Number</b></label>
        <input type="text" placeholder="Enter Phone Number" name="phone_number" required><br>
        <!-- Input field for the elderly user's Address Postcode -->
        <label for="address_postcode"><b>Address Postcode</b></label>
        <input type="text" placeholder="Enter Postcode" name="address_postcode" required><br>
        <!-- Input field for the elderly user's Address street -->
        <label for="address_street"><b>Address Street</b></label>
        <input type="text" placeholder="Enter Street" name="address_street" required><br>
        <!-- Input field for the elderly user's Address house -->
        <label for="address_house"><b>Address House</b></label>
        <input type="text" placeholder="Enter House" name="address_house" required><br>
        <!-- Input field for the elderly user's Contact ID -->
        <label for="contact_id"><b>Contact ID</b></label>
        <input type="text" placeholder="Enter Contact ID" name="contact_id" required><br>
        <!-- Submit button to complete the registration -->
        <button type="submit">Register</button>
    </div>
</form>

</body>
</html>
