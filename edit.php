<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Sets the character set of the webpage -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ensures it displays properly on mobile devices -->
    <title>Edit Details</title> <!-- Title that appears on tab bar of browser, edit details-->
</head>
<body>

<?php
session_start(); // Resumes the session established on the Carer or elderly pages 

if (!isset($_SESSION['Carer_ID'])) { // If the Carer_ID variable isnt set then....
    header('Location: LOG.php'); // Redirect back to the LOG.php page 
    exit(); // Stop execution of the script and stop loading of the page 
}
// Establishes database connection paramters 
$dbServername = "localhost";
$dbUsername = "2105480"; 
$dbPassword = "7lnrib";  
$dbName = "db2105480";   

// Creates a databse connection using the paramters defined 
$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

// If there is an error with the connection to the database...
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); //Display the error message and ends the script
}

$carerID = $_SESSION['Carer_ID']; // Retrieves the Carer_ID from the session variables 

// If a form is submitted..
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newEmail = $conn->real_escape_string($_POST['email']); //Sanitise the input for the email to prevent SQL injection 
    $newPhoneNumber = $conn->real_escape_string($_POST['phone_number']); //Sanitise the input for the phone number to prevent SQL injection 

   // Prepare an UPDATE SQL statement which will update the users email and phone number 
    $updateStmt = $conn->prepare("UPDATE Carer SET Email = ?, Phone_Number = ? WHERE Carer_ID = ?");
    // Binds the form inputs to the statement 
    $updateStmt->bind_param("ssi", $newEmail, $newPhoneNumber, $carerID);
    // Executes the statement 
    $updateStmt->execute();
    // Closes the statement 
    $updateStmt->close();

    
    header("Location: Carer.php"); // Redirects back to the Carer page
    exit(); // Stops execution of the script 
}

// Prepares a SELECT statement to get the users current phone number and email 
$stmt = $conn->prepare("SELECT Email, Phone_Number FROM Carer WHERE Carer_ID = ?");
$stmt->bind_param("i", $carerID); // Binds Carer_ID to the statement 
$stmt->execute(); // Executes the statement 
$result = $stmt->get_result(); // Gets results from the statement 
// If theres data in the database for this user ID then prepopulate the update fields 
if ($row = $result->fetch_assoc()) {
   // Form for editing details 
    echo "<h1>Edit Your Details</h1>";
    echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
    // Email input field 
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='email' name='email' value='" . htmlspecialchars($row['Email']) . "' required><br>";
    // Phone number input field 
    echo "<label for='phone_number'>Phone Number:</label><br>";
    echo "<input type='text' name='phone_number' value='" . htmlspecialchars($row['Phone_Number']) . "' required><br>";
    // Button to submit the updated values
    echo "<input type='submit' value='Update'>";
    echo "</form>";
} else {
    // If no details are found for the current user it would display a conatct support message 
    echo "<p>No contact information found. Please contact support.</p>";
}

$stmt->close(); // Closes statement 
$conn->close(); // Closes database connection 
?>

</body>
</html>
