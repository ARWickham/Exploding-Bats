<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'elderly') {
    echo "Unauthorized access.";
    exit(); // Stop script if not logged in or not elderly type
}

$elderly_id = $_SESSION['user_id']; // Get the elderly_id from session

$dbServername = "localhost";
$dbUsername = "2105480"; 
$dbPassword = "7lnrib";  
$dbName = "db2105480";

$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['postTitle']);
    $content = $conn->real_escape_string($_POST['postContent']);

    $sql = "INSERT INTO posts (elderly_id, title, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $elderly_id, $title, $content);

    if ($stmt->execute()) {
        $_SESSION['post_message'] = "New post created successfully"; // Store success message in session
        header("Location: ElderPage.html"); // Redirect back to the originating page
        exit();
    } else {
        $_SESSION['post_message'] = "Error creating post: " . $stmt->error; // Store error message in session
    }

    $stmt->close();
    $conn->close();
}

// Redirect back if not a POST request or if there's an error
header("Location: ElderPage.html");
exit();
?>
