<?php
require 'config2.php';
session_start();

// Check if the search form is submitted
if(isset($_POST['search'])) {
    // Get the search term
    $searchTerm = $_POST['search'];

    // Prepare and execute the query
    $query = "SELECT * FROM registered_user WHERE username LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $query);

    // Check if query execution was successful
    if($result === false) {
        // If query execution failed, show an error message
        echo "Error executing the query: " . mysqli_error($conn);
    } else {
        // Check if any results found
        if(mysqli_num_rows($result) > 0) {
            // Display each user's profile picture and profile name
            while($row = mysqli_fetch_assoc($result)) {
                $profilePicture = $row['profile_picture'];
                $profileName = $row['username'];

                // Display the profile picture and profile name
                echo "<div>";
                echo "<img src='$profilePicture' alt='Profile Picture'>";
                echo "<p>$profileName</p>";
                echo "</div>";
            }
        } else {
            // If no results found
            echo "<br>";
            echo "<div style='text-align: center; color: #fff; font-size:50px;'>No user found with the given username.</div>";
        }
    }
}
?>

<html>
    <head></head>
    <body>
        <style>
              body{
            background-image: url(media/9.jpg);
            background-size: cover;
            background-position: top right;
        }
            </style>
</body>
</html>
