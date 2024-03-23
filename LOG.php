<!DOCTYPE html>
<!--Document declaration, stating that is a HTML document -->
<html>
<head>
    <title>Login Form</title> <!--Shown in the tab of the website, gives the page the title Login form -->
</head>
<body>

<h2>Login Form</h2> <!--Header for the page called "Login form" -->

<?php
session_start(); //Starts a new session to use session variables throughout the whole website, put at the beginning of all pages that use the variables.

$loginError = ''; //This creates a session variable to store error messages.

// Check if the user submitted the form this makes sure the code only runs when the form is sent using the POST method, which keeps submitted information hidden and secure.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Below is the database connection details/parameters which are used to connect to the database.
    $dbServername = "localhost";
    $dbUsername = "2105480"; 
    $dbPassword = "7lnrib";  
    $dbName = "db2105480";

    // Connects to the database using the defined parameters.
    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
    
    // Check the connection and if unsuccessful display an error message
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Sanitises the Username input, attempts to stop SQL injection through the forms. 
    $username = $conn->real_escape_string($_POST['username']);
    // Retrieves the password directly, doesn't need to be sanitised as the password will be hashed and comapred to the hashed password. 
    $password = $_POST['password']; 

    // Prepares a select statement for the Carer database.
    $stmtCarer = $conn->prepare("SELECT Carer_ID, Password FROM Carer WHERE Name = ?");
    $stmtCarer->bind_param("s", $username); // Binds the username user input to the query 
    $stmtCarer->execute(); // Exectues the query 
    $resultCarer = $stmtCarer->get_result(); // get the results from the query 
    if ($resultCarer->num_rows > 0) { // If their are rows containing the username in the table. 
        $carerRow = $resultCarer->fetch_assoc(); // Fetches the next row in the table, in an associative array format.
        if (password_verify($password, $carerRow['Password'])) { // Verifies the entered password with the table 
            // Binds values to session variables to carry across the website.
            $_SESSION['Carer_ID'] = $carerRow['Carer_ID']; 
            $_SESSION['user'] = $username; 
            $_SESSION['user_type'] = 'carer';
            header("Location: Carer.php"); //If information is entered correcly, redirect to the Carer.php page
            exit(); // Exits the script.
        }
    }
    $stmtCarer->close(); // And closes the statement 


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
