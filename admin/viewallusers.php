<?php
require'config.php';
?>
<?php
session_start();
$email = $_SESSION['email'];

// Read
$sql = "SELECT * FROM registered_user";
$result = $con->query($sql);
$users = [];

while($row = $result->fetch_assoc()) {
    $users[] = $row;
}
if (isset($_POST['Save-changes'])) {
    $Username =  $_POST['Username'];
    $password = $_POST['password'];
	$mobileNo = $_POST['mobileNo'];
    $email = $_POST['email'];
    $repass = $_POST['repassword'];

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
                window.location.href = "viewallusers.php";
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
       echo '<script>alert("Account deleted !");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "viewallusers.php";
            });
        </script>';
    }
}

if (isset($_POST['Create-account'])) {
    $Username =  $_POST['Username'];
    $password = $_POST['password'];
	$mobileNo = $_POST['mobileNo'];
    $email = $_POST['email'];
    $type = $_POST['type'];
	
	 $quary = "INSERT INTO  registered_user (Username,password,mobileNo,email,type) VALUES ('{$Username}','{$password}','{$mobileNo}','{$email}','{$type}')";
        $result = mysqli_query($con, $quary);

        if ($result) {
            echo '<script>alert("Account created successfully!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "viewallusers.php";
            });
        </script>';
        }
}


$con->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-compatible" content="IE-edge">
<meta name="veiwport" content="width-device-width, initial-scal=1.0">
<title>Coffee Shoot</title>
<link rel="stylesheet" href="style.css">
<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
		
*,
*:before,
*:after {
  box-sizing: border-box;
}
body {
  padding: 1em;
  font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 15px;
  color: #b9b9b9;
  background:Radial-gradient(#964734,#000);
  }
h4 {
  color: #966b9d;
}
input,
input[type="radio"] + label,
input[type="checkbox"] + label:before,
select option,
select {
  width: 100%;
  padding: 1em;
  line-height: 1.4;
  background-color: #f9f9f9;
  border: 1px solid #e5e5e5;
  border-radius: 3px;
  -webkit-transition: 0.35s ease-in-out;
  -moz-transition: 0.35s ease-in-out;
  -o-transition: 0.35s ease-in-out;
  transition: 0.35s ease-in-out;
  transition: all 0.35s ease-in-out;
}
input:focus {
  outline: 0;
  border-color: #bd8200;
}
input:focus + .input-icon i {
  color: #966b9d;
}
input:focus + .input-icon:after {
  border-right-color: #f0a500;
}
input[type="radio"] {
  display: none;
}
input[type="radio"] + label,
select {
  display: inline-block;
  width: 50%;
  text-align: center;
  float: left;
  border-radius: 0;
}
input[type="radio"] + label:first-of-type {
  border-top-left-radius: 3px;
  border-bottom-left-radius: 3px;
}
input[type="radio"] + label:last-of-type {
  border-top-right-radius: 3px;
  border-bottom-right-radius: 3px;
}
input[type="radio"] + label i {
  padding-right: 0.4em;
}
input[type="radio"]:checked + label,
input:checked + label:before,
select:focus,
select:active {
  background-color: #f0a500;
  color: #fff;
  border-color: #bd8200;
}
input[type="checkbox"] {
  display: none;
}
input[type="checkbox"] + label {
  position: relative;
  display: block;
  padding-left: 1.6em;
}
input[type="checkbox"] + label:before {
  position: absolute;
  top: 0.2em;
  left: 0;
  display: block;
  width: 1em;
  height: 1em;
  padding: 0;
  content: "";
}
input[type="checkbox"] + label:after {
  position: absolute;
  top: 0.45em;
  left: 0.2em;
  font-size: 12px;
  color: #fff;
  opacity: 0;
  font-family: FontAwesome;
  content: "\f00c";
}
input:checked + label:after {
  opacity: 1;
}
select {
  height: 3.4em;
  line-height: 2;
}
select:first-of-type {
  border-top-left-radius: 3px;
  border-bottom-left-radius: 3px;
}
select:last-of-type {
  border-top-right-radius: 3px;
  border-bottom-right-radius: 3px;
}
select:focus,
select:active {
  outline: 0;
}
select option {
  background-color: #f0a500;
  color: #fff;
}
.input-group {
  margin-bottom: 1em;
  zoom: 1;
}
.input-group:before,
.input-group:after {
  content: "";
  display: table;
}
.input-group:after {
  clear: both;
}
.input-group-icon {
  position: relative;
  font-size: 18px;
}
.input-group-icon input {
  padding-left: 4.4em;
}
.input-group-icon .input-icon {
  position: absolute;
  top: 30%;
  left: 0;
  width: 10%;
  height: 10%;
  line-height: 3.4em;
  text-align: center;
  pointer-events: none;
}
.input-group-icon .input-icon:after {
  position: absolute;
  top: 0.6em;
  bottom: 0.6em;
  left: 3.4em;
  display: block;
  border-right: 1px solid #e5e5e5;
  content: "";
  -webkit-transition: 0.35s ease-in-out;
  -moz-transition: 0.35s ease-in-out;
  -o-transition: 0.35s ease-in-out;
  transition: 0.35s ease-in-out;
  transition: all 0.35s ease-in-out;
}
.input-group-icon .input-icon i {
  -webkit-transition: 0.35s ease-in-out;
  -moz-transition: 0.35s ease-in-out;
  -o-transition: 0.35s ease-in-out;
  transition: 0.35s ease-in-out;
  transition: all 0.35s ease-in-out;
}
.container {
  max-width: 38em;
  padding: 1em 3em 2em 3em;
  margin: 0em auto;
  background-color: #fff;
  border-radius: 4.2px;
  box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.2);
}
.row {
  zoom: 1;
}
.row:before,
.row:after {
  content: "";
  display: table;
}
.row:after {
  clear: both;
}
.col-half {
  padding-right: 10px;
  float: left;
  width: 50%;
}
.col-half:last-of-type {
  padding-right: 0;
}
.col-third {
  padding-right: 10px;
  float: left;
  width: 33.33333333%;
}
.col-third:last-of-type {
  padding-right: 0;
}
@media only screen and (max-width: 540px) {
  .col-half {
    width: 100%;
    padding-right: 0;
  }
}



:root {
  --colour-black: #AFDDE5;
  --colour-white: #ffffff;
  --font-family: Lato,Roboto,Oxygen,Ubuntu,Cantarell,Open Sans,Helvetica Neue,sans-serif;
  --font-size: 100%;
}


button,
.button{
  --button: hsl(0, 0%, 12%);
  --button-hover: hsl(0, 0%, 20%);
  --button-active:  hsl(0, 0%, 30%);
  --button-visited: hsl(0, 0%, 20%);
  --button-colour: var(--colour-white);
  --button-border: var(--colour-black);
}
button {
  padding: .9rem 1.7rem;
  color: black;
  font-weight: 500;
  font-size: 16px;
  transition: all 0.3s ease-in-out;
  background: #AFDDE5;
  border: solid 1px var(--button-border);
  box-shadow: inset 0 0 0 2px var(--colour-white);
  margin-right: 1em;
}

button:hover {
  text-decoration: underline;
  background: var(--button-hover);
  box-shadow: inset 0 0 0 4px var(--colour-white);
}

button:active {
  background: var(--button-active);
}

button:visited {
  background: var(--button-visited);
}
h1{
	text-align:center;
	color:#fff;
}
h2{
	color:#fff;
}
    </style>
</head>
<body>
<h1>Admin Page</h1>
<h2>User Table</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
				<th>mobileNo</th>
                <th>Email</th>
				<th>Studio name</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo $user["Username"]; ?></td>
                    <td><?php echo $user["password"]; ?></td>
					<td><?php echo $user["mobileNo"]; ?></td>
                    <td><?php echo $user["email"]; ?></td>
					<td><?php echo $user["studio_name"]; ?></td>
                    <td><?php echo $user["type"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table><br><br>
	<div class="container">
<form action="viewallusers.php" method="post" >
    <div class="row">
      <h4>Account</h4>
      <div class="input-group input-group-icon">
        <input type="text" placeholder="Username" name="Username" >
        <div class="input-icon">
          <i  class="fa fa-user"></i>
        </div>
      </div>
      <div class="input-group input-group-icon">
        <input type="email" placeholder="Email Address" name="email" >
        <div class="input-icon">
          <i class="fa fa-envelope"></i>
        </div>
      </div>
	  <div class="input-group input-group-icon">
          <input type="text" placeholder="Mobile no." name="mobileNo" >
        <div class="input-icon">
          <i style="font-size:26px;" class="fa fa-mobile-phone"></i>
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
        <input type="password" placeholder="Re-enter Password" name="repassword">
        <div class="input-icon">
          <i class="fa fa-key"></i>
        </div>
      </div>
	  <div class="input-group input-group-icon">
        <input type="text" placeholder="User type" name="type">
        <div class="input-icon">
          <i class="fa fa-key"></i>
        </div>
      </div>
    </div><br>
    <div class="row">
      <div class="input-group">
			<button type="submit" type="submit" name='Save-changes'>Save changes</button> 	 <button onclick="myFunction()" type="submit" type="submit" name='deleteAccount'>Delete account</button>
			<button type="submit" type="submit" name='Create-account'>Create account</button>
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