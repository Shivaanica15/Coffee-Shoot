<?php
require 'config.php';
session_start();

// Admin authorization
if (!isset($_SESSION['email'])) {
    header('Location: ../login_signup.php');
    exit();
}

$sessionEmail = $_SESSION['email'];
$authStmt = $con->prepare("SELECT type FROM registered_user WHERE email = ? LIMIT 1");
$authStmt->bind_param("s", $sessionEmail);
$authStmt->execute();
$authResult = $authStmt->get_result();
$authRow = $authResult ? $authResult->fetch_assoc() : null;
$authStmt->close();

if (!$authRow || ($authRow['type'] ?? null) !== 'admin') {
    header('Location: ../login_signup.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Name = trim($_POST["name"] ?? '');
    $Email = trim($_POST["email"] ?? '');
    $Message = trim($_POST["message"] ?? '');

    $stmt = $con->prepare("INSERT INTO Inquiries (Name, Email, Message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $Name, $Email, $Message);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        echo '<script>alert("Your message has been send successfully!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "admin contact us.php";
            });
        </script>';
    } else {
        echo "Error: " . $con->error;
    }

    $con->close();
}
?>
<html>
<head>
	<title> Contact Us | Coffee Shoot </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div style="margin: 10px 0;">
    <a href="admin home.html">Back to Dashboard</a>
</div>
	<style>
	body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
		}

		header {
			background-image: linear-gradient(to right, #1f0b00 , #581f02);
			background-color: #1f0b00;
			color: #fff;
			padding: 10px;
		}

		nav {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.logo a {
			color: #fff;
			text-decoration: none;
		}

		.nav-links {
			display: flex;
		}

		.nav-link {
			color: #fff;
			text-decoration: none;
			padding: 10px 20px;
			margin-left: 10px;
			border-radius: 5px;
		}

		.nav-link:hover {
			background-color: #5E503F;
		}

		.btn-login {
			background-color: #A9927D;
			color: white;
		}

		.btn-register {
			background-color: #49111C;
			color: white;
		}

		main {
			padding: 20px;
			text-align: center;
		}
		footer {
			background-color: #0A0908;
			color: #fff;
			padding: 10px;
			text-align: center;
		}
		center {
        margin-left: 90px;
        margin-top: 50px;
    }

    .form_contact {
        margin-top: 50px;
        width: 40%;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .form_contact h2 {
        text-align: center;
        color: #4f5d75;
        margin-bottom: 30px;
    }

    .contact_form {
        border-radius: 5px;
        padding: 12px;
        margin-top: 10px;
        margin-right: 8px;
        font-size: 16px;
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #ccc;
        transition: border-color 0.3s ease;
    }

    .contact_form:focus {
        border-color: #4f5d75;
        outline: none;
    }

    textarea.contact_form {
        height: 150px;
        resize: vertical;
    }

    .submit {
        background-color: #4f5d75;
        color: white;
        padding: 14px 30px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .submit:hover {
        background-color: #3e4c5f;
    }
	</style>
	<header>
	    <img src="img/LOGOf.png"> <!--style="border: 5px solid white; border-radius: 50%; "-->
		<center>
		<video  muted autoplay loop>
			<source  src="img/V!.mp4" type="video/mp4">
		</video>
		</center>
        <nav>
            <div class="logo">
                <a href="index.html"><h1>Coffee Shoot</h1></a>
            </div>
            <div class="nav-links">
                <a href="admin home.html" class="nav-link">Home</a>
                <a href="admin explore.html" class="nav-link">Explore</a>
                <a href="About us.html" class="nav-link">About Us</a>
                <a href="admin contact us.php" class="nav-link">Contact Us</a>
                <a href="viewallinquire.php" class="nav-link btn-login">View all inquires</a>

            </div>
        </nav>
    </header>

	<hr>
	<center>
    <fieldset class="form_contact">
        <h2>Contact Us</h2>
        <form method="post" action="admin contact us.php">
            <p>Name</p>
            <input class="contact_form" type="text" placeholder="Your full name..." name="name">

            <p>E-mail</p>
            <input class="contact_form" type="email" placeholder="Your email..." name="email">

            <p>How can we help you?</p>
            <textarea class="contact_form" placeholder="Your message..." name="message"></textarea>

            <br><br>
            <button class="submit" type="submit">Submit</button>
        </form>
    </fieldset>
</center>
	<hr>

	<footer>
        <p>&copy; 2024 Coffee Shoot</p>
    </footer>
