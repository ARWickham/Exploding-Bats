<?php
// Start or resume a session to use session variables
session_start();
// Check if the session variable 'Carer_ID' is set, indicating the user is logged in and if not redirect to LOG.php
if (!isset($_SESSION['Carer_ID'])) {
    header('Location: LOG.php');
    exit();
}
 // Database connection parameters
$dbServername = "localhost";
$dbUsername = "2105480"; 
$dbPassword = "7lnrib";  
$dbName = "db2105480";   
// Database connection query using the paramters 
$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
// If theres an issue with the database connection then kill the script and display and error message 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check that both the session variable 'Carer_ID' and POST variable 'post_id' are set
if (!isset($_SESSION['Carer_ID']) || !isset($_POST['post_id'])) {
// If not set then display the following message
    echo 'Session or post ID not set';
    exit();
}
 // Retrieve the carer's ID from the session and the post's ID from the POST request
$carerID = $_SESSION['Carer_ID'];
$postID = $_POST['post_id'];
// Prepares an SQL statement to delete the specified post based on the ID's
$stmt = $conn->prepare("DELETE FROM CarerFavorites WHERE Carer_ID = ? AND Post_ID = ?");
$stmt->bind_param("ii", $carerID, $postID);
if($stmt->execute()) { // Execute the statement 
    // If successful display the follwoing message 
    echo "Removed from favorites";
} else {
    // If unsuccessful display an error
    echo "Error: " . $conn->error;
}
$stmt->close(); // Closes the statment 
$conn->close(); // Ends database connection 
?>
