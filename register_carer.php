<!DOCTYPE html>
<html>
<head>
    <title>Carer Registration Form</title>
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
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);

    
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("INSERT INTO Carer (Name, Password, Email, Phone_Number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $password, $email, $phone_number);

  
    if ($stmt->execute()) {
        $registrationSuccess = 'Registration successful! You can now <a href="LOG.php">login</a>.';
    } else {
        $registrationError = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<h2>Carer Registration Form</h2>

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

        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" name="email" required><br>

        <label for="phone_number"><b>Phone Number</b></label>
        <input type="text" placeholder="Enter Phone Number" name="phone_number" required><br>

        <button type="submit">Register</button>
    </div>
</form>

</body>
</html>
