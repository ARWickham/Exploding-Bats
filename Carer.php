<!DOCTYPE html>
<html lang="en">
    <!-- The document is declared as HTML document, and language is set to English. -->
<head>
     <!-- Character encoding is set to UTF-8, and the viewport meta tag makes the page mobile-responsive. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Appears in the tab -->
    <title>Carer Dashboard</title>
    <!-- Boostrap css and a custom Carer css file linked -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="Carer.css">
    <!-- jQuery for AJAX requests and client-side scripting -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<nav>
    <!-- Navigation bar with logout button and boostrap -->
    <i class="bi bi-backspace"></i>
    <a class='back_button' href='LOG.php'>Logout</a>
</nav>

<div class='container-fluid'>
    <div class='row'>
        <!-- First column displays the carer's information and posts by elderly people -->
        <div class='col-md-6'>
            <?php
            // Starts or continues a session 
            session_start();
            // If not signed in the user is redirected to the LOG.php page 
            if (!isset($_SESSION['Carer_ID'])) {
                header('Location: LOG.php');
                exit();
            }
            // Database connection details 
            $dbServername = "localhost";
            $dbUsername = "2105480";
            $dbPassword = "7lnrib";
            $dbName = "db2105480";
            // Database connection query using these paramters 
            $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
            // If the connection has failed then display an error message and stop script execution 
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // Gets the current Carer_ID from the session
            $carerID = $_SESSION['Carer_ID'];
            // Fetches the users details and information using the session Carer_ID
            $stmt = $conn->prepare("SELECT Name, Email, Phone_Number FROM Carer WHERE Carer_ID = ?");
            $stmt->bind_param("i", $carerID);
            $stmt->execute();
            $result = $stmt->get_result();
            // If the users ID exists then display their information in the following format
            if ($row = $result->fetch_assoc()) {
                echo "<h1>Welcome, " . htmlspecialchars($row['Name']) . "!</h1>";
                echo "<p>Email: " . htmlspecialchars($row['Email']) . "</p>";
                echo "<p>Phone Number: " . htmlspecialchars($row['Phone_Number']) . "</p>";
                // Button established to link to edit your details page
                echo "<a href='edit.php'><button type='button'>Edit My Details</button></a>";
            } else {
                // If no information about the user is found then this message is displayed 
                echo "<p>No contact information found.</p>";
            }
            // Close the first statement 
            $stmt->close();

            echo "<h2>Posts by Elderly:</h2>";
            // Display all the posts by the elderly that the carer hasnt favourited 
            $stmt = $conn->prepare("
                SELECT p.post_id, p.title, p.content, e.Name, e.Phone_Number, e.Address_Postcode, e.Address_Street, e.Address_House
                FROM posts p
                JOIN Elderly e ON p.elderly_id = e.Elderly_ID
                LEFT JOIN (SELECT Post_ID FROM CarerFavorites WHERE Carer_ID = ?) cf ON p.post_id = cf.Post_ID
                WHERE cf.Post_ID IS NULL
                ORDER BY p.post_id DESC
            ");
            $stmt->bind_param("i", $carerID);
            $stmt->execute();
            $result = $stmt->get_result();
            // If the posts are available Display them one by one in the following format
            if ($result->num_rows > 0) {
                while ($post = $result->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<h3>" . htmlspecialchars($post['title']) . "</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
                    echo "<p>Posted by: " . htmlspecialchars($post['Name']) . "</p>";
                    echo "<p>Phone: " . htmlspecialchars($post['Phone_Number']) . "</p>";
                    echo "<p>Address: " . htmlspecialchars($post['Address_House']) . ", " . htmlspecialchars($post['Address_Street']) . ", " . htmlspecialchars($post['Address_Postcode']) . "</p>";
                    echo "<button onclick='addToFavorites(" . $post['post_id'] . ")'>Add to Favorites</button>";
                    echo "</div>";
                }
            } else {
                // If there have been no posts found on the database then display this message 
                echo "<p>No posts found or all posts are in favorites.</p>";
            }
            // Close the perpared statement 
            $stmt->close();
            ?>
        </div>
        <!-- The right column will display the carers favoruite posts -->
        <div class='col-md-6'>
            <?php
            // SImilar to the first column the second column fetches all the posts that the carer has favourited and displays them
            echo "<h2>My Favorite Posts:</h2>";
            $stmt = $conn->prepare("
                SELECT p.post_id, p.title, p.content, e.Name, e.Phone_Number, e.Address_Postcode, e.Address_Street, e.Address_House
                FROM posts p
                JOIN Elderly e ON p.elderly_id = e.Elderly_ID
                JOIN CarerFavorites cf ON p.post_id = cf.Post_ID
                WHERE cf.Carer_ID = ?
                ORDER BY p.post_id DESC
            ");
            $stmt->bind_param("i", $carerID);
            $stmt->execute();
            $result = $stmt->get_result();
            // If posts exist then display them one by one in the following formats
            if ($result->num_rows > 0) {
                while ($post = $result->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<h3>" . htmlspecialchars($post['title']) . "</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
                    echo "<p>Posted by: " . htmlspecialchars($post['Name']) . "</p>";
                    echo "<p>Phone: " . htmlspecialchars($post['Phone_Number']) . "</p>";
                    echo "<p>Address: " . htmlspecialchars($post['Address_House']) . ", " . htmlspecialchars($post['Address_Street']) . ", " . htmlspecialchars($post['Address_Postcode']) . "</p>";
                    echo "<button onclick='removeFromFavorites(" . $post['post_id'] . ")'>Remove from Favorites</button>";
                    echo "</div>";
                }
            } else {
                // If the carer hasnt favorutied any posts then this message will be displayed in the column
                echo "<p>You have no favorite posts.</p>";
            }
            // CLoses the statement
            $stmt->close();
            // Closes the datbase connection 
            $conn->close();
            ?>
        </div>
    </div>
</div>
<!--The following is Javascript code to alert the users of removal or addition to/from favourites -->
<script>
function addToFavorites(postId) {
    $.post('add_to_favorites.php', { post_id: postId }, function(response) {
        // Dipslay an alert to the user that the post has been added to faovurites and refreshes the page
        alert('Added to favorites');
        location.reload();
    }).fail(function() {
         // Display an alert if there's an error adding to favorites
        alert('Error adding to favorites.');
    });
}

function removeFromFavorites(postId) {
    $.post('remove_from_favorites.php', { post_id: postId }, function(response) {
        // Dipslay an alert to the user that the post has been removed from faovurites and refreshes the page
        alert('Removed from favorites');
        location.reload();
    }).fail(function() {
        // Display an alert if there's an error removing from favorites
        alert('Error removing from favorites.');
    });
}
</script>

</body>
</html>
