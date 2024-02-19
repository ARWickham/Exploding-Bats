<!DOCTYPE html>
<html>
<head>
    <title>Elderly Registration Form</title>
</head>
<body>

<?php
$registrationSuccess = '';
$registrationError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $dbServername = "localhost";
    $dbUsername = "2105480"; 
    $dbPassword = "7lnrib"; 
    $dbName = "db2105480"; 

 
    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $name = $conn->real_escape_string($_POST['name']);
    $age = $conn->real_escape_string($_POST['age']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $address_postcode = $conn->real_escape_string($_POST['address_postcode']);
    $address_street = $conn->real_escape_string($_POST['address_street']);
    $address_house = $conn->real_escape_string($_POST['address_house']);
    $contact_id = $conn->real_escape_string($_POST['contact_id']); 

  
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

   
    $stmt = $conn->prepare("INSERT INTO Elderly (Name, Password, Age, Email, Phone_Number, Address_Postcode, Address_Street, Address_House, Contact_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssssi", $name, $password, $age, $email, $phone_number, $address_postcode, $address_street, $address_house, $contact_id);

    
    if ($stmt->execute()) {
        $registrationSuccess = 'Registration successful! You can now <a href="LOG.php">login</a>.';
    } else {
        $registrationError = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<h2>Elderly Registration Form</h2>

<?php if($registrationSuccess): ?>
    <p style="color: green;"><?php echo $registrationSuccess; ?></p>
<?php endif; ?>

<?php if($registrationError): ?>
    <p style="color: red;"><?php echo $registrationError; ?></p>
<?php endif; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="container">
        <label for="name"><b>Name</b></label>
        <input type="text" placeholder="Enter Name" name="name" required><br>

        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required><br>

        <label for="age"><b>Age</b></label>
        <input type="number" placeholder="Enter Age" name="age" required><br>

        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" name="email" required><br>

        <label for="phone_number"><b>Phone Number</b></label>
        <input type="text" placeholder="Enter Phone Number" name="phone_number" required><br>

        <label for="address_postcode"><b>Address Postcode</b></label>
        <input type="text" placeholder="Enter Postcode" name="address_postcode" required><br>

        <label for="address_street"><b>Address Street</b></label>
        <input type="text" placeholder="Enter Street" name="address_street" required><br>

        <label for="address_house"><b>Address House</b></label>
        <input type="text" placeholder="Enter House" name="address_house" required><br>

        <label for="contact_id"><b>Contact ID</b></label>
        <input type="text" placeholder="Enter Contact ID" name="contact_id" required><br>

        <button type="submit">Register</button>
    </div>
</form>

</body>
</html>
