<!DOCTYPE html>
<!--Document declaration, stating that is a HTML document -->
<html>
<head>
    <title>Login Form</title> <!--Shown in the tab of the website, gives the page the title Login form -->
</head>
<body>

<h2>Login Form</h2>

<?php
session_start();

$loginError = '';

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


    $stmtCarer = $conn->prepare("SELECT Carer_ID, Password FROM Carer WHERE Name = ?");
    $stmtCarer->bind_param("s", $username);
    $stmtCarer->execute();
    $resultCarer = $stmtCarer->get_result();
    if ($resultCarer->num_rows > 0) {
        $carerRow = $resultCarer->fetch_assoc();
        if (password_verify($password, $carerRow['Password'])) {
            $_SESSION['Carer_ID'] = $carerRow['Carer_ID']; 
            $_SESSION['user'] = $username; 
            $_SESSION['user_type'] = 'carer';
            header("Location: Carer.php");
            exit();
        }
    }
    $stmtCarer->close();


    $stmtCarer = $conn->prepare("SELECT Elderly_ID, Password FROM Elderly WHERE Name = ?");
    $stmtCarer->bind_param("s", $username);
    $stmtCarer->execute();
    $resultCarer = $stmtCarer->get_result();
    if ($resultCarer->num_rows > 0) {
        $carerRow = $resultCarer->fetch_assoc();
        if (password_verify($password, $carerRow['Password'])) {
            $_SESSION['Carer_ID'] = $carerRow['Carer_ID']; 
            $_SESSION['user'] = $username; 
            $_SESSION['user_type'] = 'carer';
            header("Location: ElderPage.html");
            exit();
        }
    }
    $stmtCarer->close();

   

    $loginError = 'Invalid username or password. Please try again.';
    $conn->close();
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="container">
        <label for="username"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required><br>

        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required><br>

        <button type="submit">Login</button>
        <!-- Buttons for registration -->
        <a href="register_elderly.php"><button type="button">Register as Elderly</button></a>
        <a href="register_carer.php"><button type="button">Register as Carer</button></a>
    </div>
</form>

<?php
if ($loginError) {
    echo '<p style="color: red;">'.$loginError.'</p>';
}
?>

</body>
</html>
