<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carer Dashboard</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="Carer.css">
</head>
<body>
<nav>
   <i class="bi bi-backspace"></i>
   <a class='back_button' href='https://mi-linux.wlv.ac.uk/~2213259/GROUP/LOG.php'>Logout</a>
</nav>
<?php
session_start();

if (!isset($_SESSION['Carer_ID'])) {
    header('Location: LOG.php');
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

// Display carer's own details
$stmt = $conn->prepare("SELECT Name, Email, Phone_Number FROM Carer WHERE Carer_ID = ?");
$stmt->bind_param("i", $carerID);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "<h1>Welcome, " . htmlspecialchars($row['Name']) . "!</h1>";
    echo "<p>Email: " . htmlspecialchars($row['Email']) . "</p>";
    echo "<p>Phone Number: " . htmlspecialchars($row['Phone_Number']) . "</p>";
    echo "<a href='edit.php'><button type='button'>Edit My Details</button></a>";
} else {
    echo "<p>No contact information found.</p>";
}
$stmt->close();

// Display posts made by the elderly
echo "<h2>Posts by Elderly:</h2>";
$stmt = $conn->prepare("SELECT p.title, p.content, e.Name FROM posts p INNER JOIN Elderly e ON p.elderly_id = e.Elderly_ID ORDER BY p.post_id DESC");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($post = $result->fetch_assoc()) {
        echo "<div class='post'>";
        echo "<h3>" . htmlspecialchars($post['title']) . "</h3>";
        echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
        echo "<p>Posted by: " . htmlspecialchars($post['Name']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No posts found.</p>";
}
$stmt->close();

$conn->close();
?>

</body>
</html>
