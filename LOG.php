<!DOCTYPE html>
<!--Document declaration, stating that is a HTML document -->
<html>
<head>
    <title>Login Form</title> <!--Shown in the tab of the website, gives the page the title Login form -->
    <!--Adds a stylesheet for the login page-->
    <link rel="stylesheet" href="LOG_style.css">
</head>
<body>

<?php
session_start(); // Starts a new session to use session variables throughout the whole website.

$loginError = ''; // This creates a session variable to store error messages.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dbServername = "localhost";
    $dbUsername = "2105480"; 
    $dbPassword = "7lnrib";  
    $dbName = "db2105480";

    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

// Handling login for Carer
$stmt = $conn->prepare("SELECT Carer_ID, Password FROM Carer WHERE Name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['Password'])) {
        $_SESSION['Carer_ID'] = $row['Carer_ID'];  // Use 'Carer_ID' consistently
        $_SESSION['user'] = $username;
        $_SESSION['user_type'] = 'carer';
        header("Location: Carer.php"); // Redirect to a .php page
        exit();
    }
}
$stmt->close();

// Handling login for Elderly
$stmt = $conn->prepare("SELECT Elderly_ID, Password FROM Elderly WHERE Name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['Password'])) {
        $_SESSION['user_id'] = $row['Elderly_ID'];
        $_SESSION['user'] = $username;
        $_SESSION['user_type'] = 'elderly';
        header("Location: ElderPage.html"); // Redirect to the Elderly's dashboard
        exit();
    }
}
$stmt->close();


    $loginError = 'Invalid username or password. Please try again.';
    $conn->close();
}
?>

<!--HTML form for information entry-->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="container">
        <h2>Login</h2> <!--Header for the page called "Login form" -->
        <!-- Username entry field -->
        <label for="username"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" class="details" required><br>
        <!-- Password entry field-->
        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" class="details" required><br>
        <!--Login button to submit the details entered in the fields-->
        <button class="login_buttons" type="submit">Login</button>
        <!-- Button links to register as either a Carer or Elderly person -->
        <a href="register_elderly.php"><button class="login_buttons" type="button">Register as Elderly</button></a>
        <a href="register_carer.php"><button class="login_buttons" type="button">Register as Carer</button></a>
    </div>
</form>

<?php
// Checks if there is a login error message stored in the loginError variable 
if ($loginError) {
    echo '<p style="color: red;">'.$loginError.'</p>'; //If so print the statement in red text
}
?>

</body>
</html>
