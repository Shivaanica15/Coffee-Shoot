<?php
require'config.php';
?>
<?php
session_start();
$email = $_SESSION['email'];
?>

<html>

<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>My Profile | Our project name </title>
	<link rel="stylesheet" href="styles/CSS navigation.css">
	<link rel="stylesheet" href="CSS footer.css">
    <link rel="stylesheet" href="styles/client.css">
</head>

<body>
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
                window.location.href = "client.php";
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

    $quary = "DELETE FROM registered_user WHERE User_ID = '{$email}' LIMIT 1";
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
<div class="header">
  <h1>COFFEE SHOOT</h1>
</div>

	<div class="topnav">
		<a href="#">Our logo</a>
		<a class="active-page" href="#">Home</a>
		<a href="user.php">Explore</a>
		<a href="contact us.html">Contact us</a>
		<a href="About us.html">About us</a></div><br><br>
   <div class="container">
<form action="client.php" method="post" >
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

</body>

</html>