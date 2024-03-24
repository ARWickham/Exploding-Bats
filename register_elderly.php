<!DOCTYPE html>
<html>
<head>
    <title>Elderly Registration Form</title> <!-- Defines the title of the HTML page, displayed in the tab Elderly registraition form -->
</head>
<body>

<?php
// Creates two empty variables to store succes and error messages 
$registrationSuccess = '';
$registrationError = '';
// If the form has been submitted 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establishes datbaase connection paramters 
    $dbServername = "localhost";
    $dbUsername = "2105480"; 
    $dbPassword = "7lnrib"; 
    $dbName = "db2105480"; 

    // Creates a connection statement using the paramters
    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

    // If the connection fails...
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Display an error message and terminate the script 
    }

    // Santisies the input fields to stop an special characters, stops SQL injection 
    $name = $conn->real_escape_string($_POST['name']);
    $age = $conn->real_escape_string($_POST['age']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $address_postcode = $conn->real_escape_string($_POST['address_postcode']);
    $address_street = $conn->real_escape_string($_POST['address_street']);
    $address_house = $conn->real_escape_string($_POST['address_house']);
    $contact_id = $conn->real_escape_string($_POST['contact_id']); 

    // Hashes the password for safe storage, doesn't need to be sanitised 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prepares an SQL INSERT statement, to add the data input into the fields to the database 
    $stmt = $conn->prepare("INSERT INTO Elderly (Name, Password, Age, Email, Phone_Number, Address_Postcode, Address_Street, Address_House, Contact_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // Binds the paramters to the INSERT statement 
    $stmt->bind_param("ssisssssi", $name, $password, $age, $email, $phone_number, $address_postcode, $address_street, $address_house, $contact_id);

    // If the statement is successfully executed... 
    if ($stmt->execute()) {
        $registrationSuccess = 'Registration successful! You can now <a href="LOG.php">login</a>.'; // Display the success message and direct back to login
    } else {
        $registrationError = "Error: " . $stmt->error; // Otherwise display an error message 
    }

    $stmt->close();  // Closes the statement 
    $conn->close(); // Closes the connection to the databse 
}
?>

<h2>Elderly Registration Form</h2> <!-- Creates a title for the webpage -->
<!-- If registraition is successful... -->
<?php if($registrationSuccess): ?>
    <p style="color: green;"><?php echo $registrationSuccess; ?></p> <!-- Dsiplay the message in green text -->
<?php endif; ?>
<!-- If registraition is unsuccessful... -->
<?php if($registrationError): ?>
    <p style="color: red;"><?php echo $registrationError; ?></p> <!-- Dsiplay the message in red text -->
<?php endif; ?>
<!-- registraition form using the POST method -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="container">
        <!-- Input field for the user's name -->
        <label for="name"><b>Name</b></label>
        <input type="text" placeholder="Enter Name" name="name" required><br>
         <!-- Input field for the user's password -->
        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required><br>
         <!-- Input field for the user's age -->
        <label for="age"><b>Age</b></label>
        <input type="number" placeholder="Enter Age" name="age" required><br>
         <!-- Input field for the user's email -->
        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" name="email" required><br>
         <!-- Input field for the user's phone number -->
        <label for="phone_number"><b>Phone Number</b></label>
        <input type="text" placeholder="Enter Phone Number" name="phone_number" required><br>
         <!-- Input field for the user's Postcode  -->
        <label for="address_postcode"><b>Address Postcode</b></label>
        <input type="text" placeholder="Enter Postcode" name="address_postcode" required><br>
         <!-- Input field for the user's Street address -->
        <label for="address_street"><b>Address Street</b></label>
        <input type="text" placeholder="Enter Street" name="address_street" required><br>
         <!-- Input field for the user's House address -->
        <label for="address_house"><b>Address House</b></label>
        <input type="text" placeholder="Enter House" name="address_house" required><br>
        <!-- Input field for the contact ID of the user -->
        <label for="contact_id"><b>Contact ID</b></label>
        <input type="text" placeholder="Enter Contact ID" name="contact_id" required><br>
         <!-- Button to submit the fields to the database -->
        <button type="submit">Register</button>
    </div>
</form>

</body>
</html>
