<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Sets characetr set for the webpage -->
    <meta charset="UTF-8">
    <!-- Allows for proper rending on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Title which appears in the tab bar, "Carer Dashboard-->
    <title>Carer Dashboard</title>
</head>
<body>

<?php
session_start(); // Resumes the Login session previously stated in LOG.php

// If the Carer_ID session has not already been set 
if (!isset($_SESSION['Carer_ID'])) {
    header('Location: LOG.php'); // Redirect back to the login page, to force the variable to be filled
    exit(); // Stops script execution and loading of the current page 
}

// Defines the database connection parameters 
$dbServername = "localhost";
$dbUsername = "2105480"; 
$dbPassword = "7lnrib";  
$dbName = "db2105480";   

// Starts a connection to the databse with the credentials 
$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

// If the connection failed..
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); //Print the rror message and stops script execution 
}
// Retrieves the Carer_ID from the session variable established on the LOG.php page 
$carerID = $_SESSION['Carer_ID']; 

// Prepares an SQL select statement 
$stmt = $conn->prepare("SELECT Name, Email, Phone_Number FROM Carer WHERE Carer_ID = ?");
$stmt->bind_param("i", $carerID); // Binds the Carer_ID parameter to the statement 
$stmt->execute(); // Executes the statement 
$result = $stmt->get_result(); // Retrieves the results from the statement 

if ($row = $result->fetch_assoc()) { //Places the results in an associative array 
    echo "<h1>Welcome, " . htmlspecialchars($row['Name']) . "!</h1>"; //Displays welcome message with the username escaping special characters 
    echo "<p>Email: " . htmlspecialchars($row['Email']) . "</p>"; // Displays users email
    echo "<p>Phone Number: " . htmlspecialchars($row['Phone_Number']) . "</p>"; // Displays users phone number 

    // Provides a link to an edit information page in the form of a buttno
    echo "<a href='edit.php'><button type='button'>Edit</button></a>";
} else {
    echo "<p>No contact information found.</p>"; // If not details are found this message is displayed 
}
$stmt->close(); //Closes the statement 


$conn->close(); //Closes database connection 
?>
<nav>Help</nav>
</body>
</html>
