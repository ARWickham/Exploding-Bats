<!DOCTYPE html>
<html>
<head>
    <title>Carer Registration Form</title> <!-- Displayed in the browser tab, ' Registraition Form -->
    <link rel="stylesheet" href="Register.css">

</head>
<body>

<?php
// Creates variable to hold error and success messages 
$registrationSuccess = '';
$registrationError = '';

// If the form has been submitted...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Defines connection paramters to be used in the connection statement 
    $dbServername = "localhost";
    $dbUsername = "2105480";
    $dbPassword = "7lnrib";
    $dbName = "db2105480";

    // Creates a datbase connection using the paramters defined 
    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

    // If failed to connect to the database 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Dsiplay the error message and stop script execution 
    }

    // Sanitises the enterys to the form to stop SQL inejction 
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);

    // Hashes the password and therefore doesn't need to be sanitised, used for secure storage 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prepares a registraition statement to insert the data into the datatbase
    $stmt = $conn->prepare("INSERT INTO Carer (Name, Password, Email, Phone_Number) VALUES (?, ?, ?, ?)");
    // Binds the variables to the prepared statement 
    $stmt->bind_param("ssss", $name, $password, $email, $phone_number);

    // If the statement is successfully executed...
    if ($stmt->execute()) {
        $registrationSuccess = 'Registration successful! You can now <a href="LOG.php">login</a>.'; // Display the followin g message or....
    } else {
        $registrationError = "Error: " . $stmt->error; // Show an error message if unsucessful
    }

    $stmt->close(); // Closes statement 
    $conn->close(); // Closes connection 
}
?>

<!-- Display success message in green text if the registraition is successful -->
<?php if($registrationSuccess): ?>
    <p style="color: green;"><?php echo $registrationSuccess; ?></p>
<?php endif; ?>
<!-- Display error message in red text if the registraition is unsuccessful -->
<?php if($registrationError): ?>
    <p style="color: red;"><?php echo $registrationError; ?></p>
<?php endif; ?>
<!--  Registraition form for entering details -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="container">
        <h2>Carer Registration Form</h2> <!-- Creates a header for the registraition page -->
        <!-- Input field for the name of the user-->
        <label for="name"><b>Name</b></label>
        <input type="text" placeholder="Enter Name" name="name" required><br>
        <!-- Input field for the users password -->
        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required><br>
        <!-- Input field for the users email -->
        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" name="email" required><br>
        <!-- Input field for the users phone number -->
        <label for="phone_number"><b>Phone Number</b></label>
        <input type="text" placeholder="Enter Phone Number" name="phone_number" required><br>
        <!-- Submission button for the form -->
        <button type="submit">Register</button>
    </div>
</form>

</body>
</html>
