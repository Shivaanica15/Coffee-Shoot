<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login_signup.php');
    exit();
}

$email = $_SESSION['email'];
require 'config2.php';

// Fetch user details from the registered_user table
$sql = "SELECT username, profile_picture FROM registered_user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Update user details if the form is submitted
if (isset($_POST['update_profile'])) {
    $newUsername = $_POST['username'];

    // Update username
    $sql = "UPDATE registered_user SET username = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newUsername, $email);
    $stmt->execute();
    $stmt->close();

    // Update profile picture
    if (isset($_FILES['profile_picture'])) {
        $file = $_FILES['profile_picture'];
        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Check if the file is an image
        if ($fileType != 'jpg' && $fileType != 'png' && $fileType != 'jpeg') {
            echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
            exit();
        }

        $targetDir = 'uploads/profile_pictures/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Move the uploaded file to the desired location
        $newFileName = uniqid() . '.' . $fileType;
        $targetPath = $targetDir . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo "Failed to move uploaded file.";
            exit();
        }

        // Update the profile picture in the database
        $sql = "UPDATE registered_user SET profile_picture = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $newFileName, $email);
        $stmt->execute();
        $stmt->close();
    }
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the file details
    $file_name = $_FILES["file"]["name"];
    $file_tmp = $_FILES["file"]["tmp_name"];
    $file_type = $_FILES["file"]["type"];

    // Get the file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi');

    // Check if the file type is allowed
    if (in_array($file_ext, $allowed_types)) {
        // Move the uploaded file to a permanent location
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file_name);

        // Create the "uploads" directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Prepare and execute the SQL statement
            $sql = "INSERT INTO media (email, media_name, media_path, media_type) VALUES ('$email', '$file_name', '$target_file', '$file_type')";
            if ($conn->query($sql) === TRUE) {
                echo "File uploaded successfully.";
            } else {
                echo "Error uploading file: " . $conn->error;
            }
        } else {
            echo "Error moving the uploaded file.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, GIF, MP4, MOV, and AVI files are allowed.";
    }
}

// Delete media if the delete_media parameter is set
if (isset($_GET['delete_media'])) {
    $media_id = $_GET['delete_media'];
    $sql = "DELETE FROM media WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $media_id);
    if ($stmt->execute()) {
        echo "Media deleted successfully.";
    } else {
        echo "Error deleting media: " . $conn->error;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Studio User</title>
    <link rel="stylesheet" href="styles/studio.css">
    <style>
         body {
            background-image: url(media/16.jpg);
            background-size: cover;
            background-position: top right;
        }
        </style>
    <script>
        let selectedMedia = null;

        function selectMedia(mediaItem) {
            if (selectedMedia) {
                selectedMedia.classList.remove('selected');
            }

            selectedMedia = mediaItem;
            selectedMedia.classList.add('selected');
        }

        function deleteSelectedMedia() {
            if (selectedMedia) {
                const mediaId = selectedMedia.getAttribute('data-media-id');
                const confirmDelete = confirm("Are you sure you want to delete this media?");
                if (confirmDelete) {
                    window.location.href = `studionew.php?delete_media=${mediaId}`;
                }
            }
        }
    </script>
</head>
<body>	<a href="#">Our logo</a>
		<a class="active-page" href="#">Home</a>
		<a href="user.php">Explore</a>
		<a href="contact us.html">Contact us</a>
		<a href="About us.html">About us</a></div><br><br>
    <center>
        <h1>Studio User</h1>
        <div class="box">
                <div>
                    <br><br>
                <?php
                $profilePicturePath = 'uploads/profile_pictures/' . $user['profile_picture'];
                if (file_exists($profilePicturePath)) {
                    echo '<img src="' . $profilePicturePath . '" alt="Profile Picture" class="profile-picture">';
                } else {
                    echo '<img src="default_profile_picture.jpg" alt="Default Profile Picture" class="profile-picture">';
                }
                ?><br><br>
                <h2 style="color:white;"><?php echo $user['username']; ?></h2>
                <button class="edit-profile-btn" onclick="showEditProfileForm()">Edit Profile <span>&#9998;</span></button>

            </div><br>
            <div id="editProfileForm" style="display: none;">
                <form action="studionew.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="username" placeholder="New Username" value="<?php echo $user['username']; ?>"><br>
                    <input type="file" name="profile_picture" accept="image/*" class="choose-file-btn">
                    <input type="submit" name="update_profile" value="Update Profile" class="upload-btn">
                </form>
            </div><br><br>
            <form action="studionew.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file" required class="choose-file-btn">
                <input type="submit" name="submit" value="Upload Media" class="upload-btn">
            </form> <br><br>
            <button class = "delete-btn"onclick="deleteSelectedMedia()">Delete Selected Media</button><br><br><br>
        </div>

        <div class="box1">
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

            ?>
        </div>
    </center>

    <script>
        function showEditProfileForm() {
            var editProfileForm = document.getElementById('editProfileForm');
            editProfileForm.style.display = editProfileForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
