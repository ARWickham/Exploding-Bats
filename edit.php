<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Details</title>
</head>
<body>

<?php
session_start(); 

if (!isset($_SESSION['Carer_ID'])) {
    header('Location: login.php'); 
    exit();
}

$dbServername = "localhost";
$dbUsername = "2105480"; 
$dbPassword = "7lnrib";  
$dbName = "db2105480";   


$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$carerID = $_SESSION['Carer_ID']; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newEmail = $conn->real_escape_string($_POST['email']);
    $newPhoneNumber = $conn->real_escape_string($_POST['phone_number']);

   
    $updateStmt = $conn->prepare("UPDATE Carer SET Email = ?, Phone_Number = ? WHERE Carer_ID = ?");
    $updateStmt->bind_param("ssi", $newEmail, $newPhoneNumber, $carerID);
    $updateStmt->execute();
    $updateStmt->close();

    
    header("Location: Carer.php");
    exit();
}


$stmt = $conn->prepare("SELECT Email, Phone_Number FROM Carer WHERE Carer_ID = ?");
$stmt->bind_param("i", $carerID);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
   
    echo "<h1>Edit Your Details</h1>";
    echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='email' name='email' value='" . htmlspecialchars($row['Email']) . "' required><br>";
    echo "<label for='phone_number'>Phone Number:</label><br>";
    echo "<input type='text' name='phone_number' value='" . htmlspecialchars($row['Phone_Number']) . "' required><br>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
} else {
    echo "<p>No contact information found. Please contact support.</p>";
}

$stmt->close();
$conn->close();
?>

</body>
</html>
