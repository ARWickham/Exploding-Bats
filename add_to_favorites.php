<?php
// Starts or resumes session
session_start();
// Checks if the Carer_ID if set and if no redirect to the LOG.php file
if (!isset($_SESSION['Carer_ID'])) {
    header('Location: LOG.php');
    // Stops further execution
    exit();
}
// Connection paramters
$dbServername = "localhost";
$dbUsername = "2105480"; 
$dbPassword = "7lnrib";  
$dbName = "db2105480";   

//Connection query to the SQL database
$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
// Check if the connection filed and if it did print an error message and kill the script
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Ensure both the Carer_ID and post_id have been set
if (!isset($_SESSION['Carer_ID']) || !isset($_POST['post_id'])) {
    // If not inform the user that one paramter is not set
    echo 'Session or post ID not set';
    exit();
}
// Retrieve 'Carer_ID' from session and 'post_id' from the POST request
$carerID = $_SESSION['Carer_ID'];
$postID = $_POST['post_id'];
// Prepares a statement to add the favourite to the Carerfavourites table
$stmt = $conn->prepare("INSERT INTO CarerFavorites (Carer_ID, Post_ID) VALUES (?, ?)");
// Bind 'carerID' and 'postID' to the SQL statement's placeholders as integers
$stmt->bind_param("ii", $carerID, $postID);
//Execute the query
if($stmt->execute()) {
    // Inform if successful
    echo "Added to favorites";
} else {
    // Inform if error
    echo "Error: " . $conn->error;
}
// Close the query
$stmt->close();
// Close the connection
$conn->close();
?>
