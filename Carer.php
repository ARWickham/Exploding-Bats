<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carer Dashboard</title>
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


$stmt = $conn->prepare("SELECT Name, Email, Phone_Number FROM Carer WHERE Carer_ID = ?");
$stmt->bind_param("i", $carerID);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "<h1>Welcome, " . htmlspecialchars($row['Name']) . "!</h1>"; 
    echo "<p>Email: " . htmlspecialchars($row['Email']) . "</p>";
    echo "<p>Phone Number: " . htmlspecialchars($row['Phone_Number']) . "</p>";


    echo "<a href='edit.php'><button type='button'>Edit</button></a>";
} else {
    echo "<p>No contact information found.</p>";
}
$stmt->close();


$conn->close();
?>

</body>
</html>
