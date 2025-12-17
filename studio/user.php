<?php
session_start();
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Studio User Profiles</title>
    <link rel="stylesheet" href="styles/user.css">
</head>
<body>
    <div class="container">
        <h1>Studio User Profiles</h1>
        <input type="text" id="searchInput" placeholder="Search by name or email" onkeyup="searchProfiles()">
        <div id="profileList">
            <?php
            include 'config2.php';

            // Check if the database connection is established
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT email, username, profile_picture FROM registered_user WHERE type = 'studio'";
            $result = $conn->query($sql);

            // Check if the query was successful
            if ($result === false) {
                echo "Error executing the query: " . $conn->error;
            } else {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $email = $row['email'];
                        $username = $row['username'];
                        $profile_pic = $row['profile_picture'];
                             echo "<a href='studioview.php?email=$email' class='profileLink'>
                            <div class='profile'>
                            <img src='uploads/profile_pictures/$profile_pic' alt='Profile Picture'>
                            <h3>$username</h3>
            </div>
                </a>";
                    }
                } else {
                    echo "No studio users found.";
                }
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function searchProfiles() {
            var input, filter, profiles, profileLink, profileName, i;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            profiles = document.getElementById("profileList").getElementsByTagName("div");

            for (i = 0; i < profiles.length; i++) {
                profileLink = profiles[i].getElementsByTagName("a")[0];
                profileName = profileLink.getElementsByTagName("h3")[0].innerText;
                if (profileName.toUpperCase().indexOf(filter) > -1) {
                    profiles[i].style.display = "";
                } else {
                    profiles[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>