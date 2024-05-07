<!DOCTYPE html>
<html lang="en">
    <!-- Declares the document type as HTML document and sets the language to English -->
<head>
    <!--  Adds resposnib#ve layout for mobile devices -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title displayed in the tab -->
    <title>Edit Details</title>
</head>
<body>

<?php
session_start(); // Starts the session or continues a rpeviously running session

// Check if the user is logged in
if (!isset($_SESSION['Carer_ID'])) {
    header('Location: login.php'); // Redirect to login page if the user is not logged in
    exit();
}

// Database connection details
$dbServername = "localhost";
$dbUsername = "2105480"; 
$dbPassword = "7lnrib";  
$dbName = "db2105480";   

// Create a new database connection using the parameters
$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

// Check connection and if the connection has failed display and error message 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$carerID = $_SESSION['Carer_ID']; // Retrieve the carer's ID from the session variable

// Check if the form has been submitted and if it has update the email and phone number 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newEmail = $conn->real_escape_string($_POST['email']);
    $newPhoneNumber = $conn->real_escape_string($_POST['phone_number']);

    // Prepare an SQL statement to update the carer's email and phone number
    $updateStmt = $conn->prepare("UPDATE Carer SET Email = ?, Phone_Number = ? WHERE Carer_ID = ?");
    $updateStmt->bind_param("ssi", $newEmail, $newPhoneNumber, $carerID);
    $updateStmt->execute();  // Execute the statement
    $updateStmt->close(); // Close the statement 

    // After the update the users should be redirected back to the Carer.php page
    header("Location: Carer.php");
    exit();
}

// Prior to the form being sumbitted this query will fetch the preexiting data in the database
$stmt = $conn->prepare("SELECT Email, Phone_Number FROM Carer WHERE Carer_ID = ?");
$stmt->bind_param("i", $carerID);
$stmt->execute(); // Execute the statement
$result = $stmt->get_result(); // retrives the resulting data

if ($row = $result->fetch_assoc()) {
    // If prior details have been found in the database then display them in the HTML form 
    echo "<h1>Edit Your Details</h1>";
    echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='email' name='email' value='" . htmlspecialchars($row['Email']) . "' required><br>";
    echo "<label for='phone_number'>Phone Number:</label><br>";
    echo "<input type='text' name='phone_number' value='" . htmlspecialchars($row['Phone_Number']) . "' required><br>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
} else {
    // If there is no prior data then it suggests theres an issue an support should be contacted 
    echo "<p>No contact information found. Please contact support.</p>";
}

$stmt->close(); // Closes the statement
$conn->close(); // CLoses the connection 
?>

</body>
</html>
