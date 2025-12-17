<?php
require 'config.php';
session_start();
?>
<html>
<head>
	<title> Login | Coffee Shoot </title>
	<link rel="stylesheet" href="styles/loginSignup.css">
	<?php

if (isset($_POST['sign-in'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT Username AS username, email, password, type FROM registered_user WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'] ?? '';

        $isValid = false;
        if (is_string($storedPassword) && $storedPassword !== '' && password_verify($password, $storedPassword)) {
            $isValid = true;
        } elseif ($storedPassword === $password) {
            $isValid = true;

            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $con->prepare("UPDATE registered_user SET password = ? WHERE email = ? LIMIT 1");
            $updateStmt->bind_param("ss", $newHash, $email);
            $updateStmt->execute();
            $updateStmt->close();
        }

        if ($isValid) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            if ($row['type'] == 'client') {
                echo '<script>alert("You have successfully logged in!");
                document.addEventListener("DOMContentLoaded", function() {
                    window.location.href = "client/client home.html";
                });
            </script>';
            }
            elseif ($row['type'] == 'admin') {
                 echo '<script>alert("You have successfully logged in!");
                document.addEventListener("DOMContentLoaded", function() {
                    window.location.href = "admin/admin home.html";
                });
            </script>';

            }
            elseif ($row['type'] == 'studio') {
                 echo '<script>alert("You have successfully logged in!");
                document.addEventListener("DOMContentLoaded", function() {
                    window.location.href = "studio/studio home.html";
                });
            </script>';

            }
        } else {
            $errors[] = "<h1>Username or Password is invalid</h1>";
        }
    }

    else {
        $errors[] = "<h1>Username or Password is invalid</h1>";
    }

    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
}
if (isset($_POST['sign-up'])) {
    $Username = $_POST['Username'];
    $password = $_POST['password'];
	$mobileNo = $_POST['mobileNo'];
    $email = $_POST['email'];
    $repass = $_POST['repassword'];

    //check username already exists
    $checkStmt = $con->prepare("SELECT 1 FROM registered_user WHERE Username = ? LIMIT 1");
    $checkStmt->bind_param("s", $Username);
    $checkStmt->execute();
    $resultset = $checkStmt->get_result();

    if ($resultset && $resultset->num_rows == 1) {
        $errors[] = 'Username has already taken!';
        echo '<script>alert("Username has already taken!");
        </script>';
    }
    $checkStmt->close();

    if ($password != $repass) {
		$errors[] = 'Password does not match';
		echo '<script>alert("Passwords do not match!");
        </script>';

    }

    if (empty($errors)) {
		$type = 'client';

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $profilePicture = 'unkown.png';

        $insertStmt = $con->prepare("INSERT INTO registered_user (Username, password, phone, mobileNo, email, type, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("sssssss", $Username, $hash, $mobileNo, $mobileNo, $email, $type, $profilePicture);
        $result = $insertStmt->execute();
        $insertStmt->close();

        if ($result) {
            echo '<script>alert("Account created successfully!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "login_signup.php";
            });
        </script>';
        } else {
            $errors[] = 'Faild to add the record';
        }
    }
}
?>
</head>
<body>
<b>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="login_signup.php" method="post">
			<h1>Create Account</h1>

			<input type="text" placeholder="Name" name="Username"><br>
			<input type="email" placeholder="Email" name="email" ><br>
			<input type="text" placeholder="Mobile no." name="mobileNo" ><br>
			<input type="password" placeholder="Password" name="password" ><br>
			<input type="password" placeholder="Reenter Password" name="repassword" ><br>

			<button name='sign-up'>Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="login_signup.php" method="post">
			<h1>Sign in</h1>
			<input name="email" type="email" placeholder="Email" ><br>
			<input name="password" type="password" placeholder="Password" ><br>
			<a href="#">Forgot your password?</a>
			<button name='sign-in'>Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Already have an account!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>To create account!</h1>
				<p>Enter your personal details and start journey with us</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div></b>
<video autoplay muted loop id="myVideo">
  <source src="img/Events 1.mp4" type="video/mp4">
  Your browser does not support HTML5 video.
</video>
<script>
	const signUpButton = document.getElementById('signUp');
	const signInButton = document.getElementById('signIn');
	const container = document.getElementById('container');

	signUpButton.addEventListener('click', () => {
		container.classList.add("right-panel-active");
	});

	signInButton.addEventListener('click', () => {
		container.classList.remove("right-panel-active");
	});
</script>
