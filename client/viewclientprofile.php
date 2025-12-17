<?php
require'config.php';
?>
<?php
session_start();
$email = $_SESSION['email'];
?>
<?php
	
if (isset($_POST['Save-changes'])) {
    $Username =  $_POST['Username'];
    $password = $_POST['password'];
	  $mobileNo = $_POST['mobileNo'];
    $email = $_POST['email'];
    $repass = $_POST['repassword'];

    //check username already exists
    $Username = mysqli_real_escape_string($con, $_POST['Username']);
    $quary = "SELECT * FROM registered_user WHERE Username = '{$Username}' LIMIT 1";

    $resultset = mysqli_query($con, $quary);
    if ($resultset) {
        if (mysqli_num_rows($resultset) == 1) {
			$errors[] = 'Username has already taken!';
			echo '<script>alert("Username has already taken!");
            
            
        </script>';
		 
        }
    }

    if ($password != $repass) {
		$errors[] = 'Password does not match';
		echo '<script>alert("Passwords do not match!");
        </script>';
        
    }

    if (empty($errors)) {
        $Username = mysqli_real_escape_string($con, $_POST['Username']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
		$mobileNo = mysqli_real_escape_string($con, $_POST['mobileNo']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
		$type = 'client';

        $quary = "UPDATE registered_user SET Username = '{$Username}', password = '{$password}', mobileNo = '{$mobileNo}', type = '{$type}' 
WHERE email = '{$email}';";
        $result = mysqli_query($con, $quary);

        if ($result) {
            echo '<script>alert("Account successfully updated!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "viewclientprofile.php";
            });
        </script>';
        } else {
            $errors[] = 'Faild to add the record';
        }
    }
}


if (isset($_POST['deleteAccount'])) {
    $Username =  $_POST['Username'];
    $password = $_POST['password'];
	  $mobileNo = $_POST['mobileNo'];
    $email = $_POST['email'];
    $repass = $_POST['repassword'];

    $quary = "DELETE FROM registered_user WHERE email = '{$email}' LIMIT 1";
    $result = mysqli_query($con, $quary);
    if ($result) {
       echo '<script>alert("Account deleted please sign up again!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "login_signup.php";
            });
        </script>';
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="styles/client.css">
    <title> My profile | Coffee Shoot</title>
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
	</style>
</head>
<body>
    <header>
	    <img src="img/LOGOf.png"> <!--style="border: 5px solid white; border-radius: 50%; "-->
		<center>
		<video  muted autoplay loop>
			<source  src="img/V!.mp4" type="video/mp4"> 
		</video>
		</center>
        <nav>
            <div class="logo">
                <a href="home.html"><h1>Coffee Shoot</h1></a>
            </div>
            <div class="nav-links">
                <a href="client home.html" class="nav-link">Home</a>
                <a href="client explore.html" class="nav-link">Explore</a>
                <a href="client about us.html" class="nav-link">About Us</a>
                <a href="client contact us.php" class="nav-link">Contact Us</a>
            </div>
        </nav>
    </header>
<div class="container">
<form action="viewclientprofile.php" method="post" >
    <div class="row">
      <h4>Account</h4>
      <div class="input-group input-group-icon">
        <input type="text" placeholder="Username" name="Username" >
        <div class="input-icon">
          <i class="fa fa-user"></i>
        </div>
      </div>
      <div class="input-group input-group-icon">
        <input type="email" placeholder="Email Address" name="email" readonly value="<?php echo $email; ?>">
        <div class="input-icon">
          <i class="fa fa-envelope"></i>
        </div>
      </div>
	  <div class="input-group input-group-icon">
          <input type="text" placeholder="Mobile no." name="mobileNo" >
        <div class="input-icon">
          <i class="fa fa-mobile-phone"></i>
        </div>
      </div>
      
    </div>
	<div class="row">
      <h4>Change password</h4>
      <div class="input-group input-group-icon">
        <input type="password" placeholder="Old Password">
        <div class="input-icon">
          <i class="fa fa-key"></i>
        </div>
      </div>
      <div class="input-group input-group-icon">
        <input type="password" placeholder="New Password" name="password">
        <div class="input-icon">
          <i class="fa fa-key"></i>
        </div>
      </div>
     <div class="input-group input-group-icon">
        <input type="password" placeholder="Reenter Password" name="repassword">
        <div class="input-icon">
          <i class="fa fa-key"></i>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="input-group">
        <button  type="submit" name='Save-changes'>Save changes</button><button  type="submit"name='deleteAccount'>Delete account</button>
      </div>
    </div>
	
  </form>
</div>
<script>
function myFunction() {
  let text = "Press a button!\nEither OK or Cancel.";
  if (confirm(text) == true) {
    text = "You pressed OK!";
  } else {
    text = "You canceled!";
  }
  document.getElementById("demo").innerHTML = text;
}
</script>