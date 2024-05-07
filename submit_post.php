<?php
// Start a new or resume an existing session
session_start();
// Check if the user is logged in and is of the "elderly" type
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'elderly') {
    echo "Unauthorized access."; // Display this message if they are not of the elderly type
    exit(); // Stop script if not logged in or not elderly type
}

$elderly_id = $_SESSION['user_id']; // Get the elderly_id from session

// Database paramter
$dbServername = "localhost";
$dbUsername = "2105480"; 
$dbPassword = "7lnrib";  
$dbName = "db2105480";
// Connect to the database using the paramters
$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
// If the connection fails then kill the execution and output and error message 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Process the POST method form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitise the inputs to try and prevent injection 
    $title = $conn->real_escape_string($_POST['postTitle']);
    $content = $conn->real_escape_string($_POST['postContent']);
    // SQL query to insert a new post into the 'posts' table
    $sql = "INSERT INTO posts (elderly_id, title, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Bind the variables (elderly ID, title, content) to the SQL query parameters
    $stmt->bind_param("iss", $elderly_id, $title, $content);
    // Executee the statement aand check if it is successful
    if ($stmt->execute()) {
        $_SESSION['post_message'] = "New post created successfully"; // If successful, store a success message in the session 
        header("Location: ElderPage.html"); // and redirect to 'ElderPage.html'
        exit();
    } else {
        $_SESSION['post_message'] = "Error creating post: " . $stmt->error; // If an error occured, Store error message in session
    }

    $stmt->close(); // Closes the statemnent
    $conn->close(); // Closes the connection 
}

// If the request is not POST or an error occurred, redirect back to 'ElderPage.html'
header("Location: ElderPage.html");
exit();
?>
