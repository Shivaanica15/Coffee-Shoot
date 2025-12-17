<?php
include 'config2.php';
define('PROFILE_PICS_DIR', 'uploads/profile_pictures/');
define('MEDIA_BASE_DIR', 'C:/xampp/htdocs/Coffeecss/uploads/');
define('DEFAULT_PROFILE_PIC', 'C:/xampp/htdocs/Coffeecss/uploads/profile_pictures/unkown.png');

// Check if the database connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the email parameter is provided
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Fetch studio user details
    $sql = "SELECT username, profile_picture, studio_name FROM registered_user WHERE email = '$email' AND type = 'studio'";
    $result = $conn->query($sql);

    // Check if the query was successful and if a user was found
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $profile_pic = $row['profile_picture'];
        $studio_name = $row['studio_name'];
    } else {
        echo "Studio user not found.";
        exit();
    }

    // Fetch media details
    $sql = "SELECT CONCAT('" . MEDIA_BASE_DIR . "', media_path) AS media_path, media_type FROM media WHERE email = '$email'";
    $media_result = $conn->query($sql);

    // Check if the media query was executed successfully
    if (!$media_result) {
        echo "Error executing the media query: " . $conn->error;
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}


?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($studio_name) ? $studio_name : 'Studio Profile'; ?></title>
    <link rel="stylesheet" href="styles/user.css">
</head>
<body>
<div class="container">
    <?php if (isset($profile_pic) && isset($studio_name) && isset($username)): ?>
        <div class="profile-header">
            <?php if (!isset($profile_pic)) {
                echo "Displaying default profile picture: " . DEFAULT_PROFILE_PIC . "<br>";
            } ?>
            <img src="<?php echo isset($profile_pic) ? PROFILE_PICS_DIR . $profile_pic : DEFAULT_PROFILE_PIC; ?>" alt="Profile Picture">
            <h2><?php echo $studio_name; ?></h2>
            <h3><?php echo $username; ?></h3>
        </div>
    <?php else: ?>
        <p>No studio user found.</p>
    <?php endif; ?>
    <?php
            // Retrieve and display the uploaded media
            $sql = "SELECT id, media_path, media_name, media_type FROM media WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h1>Uploaded Media</h1>";
                $upload_dir = "uploads/"; // Update with the correct server path
                $displayed_media = array(); // Array to store displayed media

                echo '<div class="media-grid">'; // Open the media grid container

                while ($row = $result->fetch_assoc()) {
                    $media_id = $row["id"];
                    $media_path = $row["media_path"];
                    $media_name = $row["media_name"];
                    $media_type = $row["media_type"];
                    $file_ext = strtolower(pathinfo($row["media_name"], PATHINFO_EXTENSION));

                    // Check if the media has been displayed before
                    if (!in_array($media_path, $displayed_media)) {
                        $displayed_media[] = $media_path; // Add the media path to the displayed_media array

                        echo '<div class="media-item" data-media-id="' . $media_id . '" onclick="selectMedia(this)">'; // Open the media item container

                        if ($file_ext == 'jpg' || $file_ext == 'jpeg' || $file_ext == 'png' || $file_ext == 'gif') {
                            echo "<img src='" . $upload_dir . $media_name . "' alt='Media' class='media-thumbnail'>";
                        } elseif ($file_ext == 'mp4' || $file_ext == 'mov' || $file_ext == 'avi') {
                            echo "<video controls class='media-thumbnail'>
                                    <source src='" . $upload_dir . $media_name . "' type='" . $media_type . "'>
                                  </video>";
                        }

                        echo '</div>'; // Close the media item container
                    }
                }

                echo '</div>'; // Close the media grid container
            
            } else {
                echo '<div class="h">';
                echo "No media uploaded yet.";
                echo '</div>';
            }
            $conn->close();
            ?>

            
    </div>
</div>
</body>
</html>