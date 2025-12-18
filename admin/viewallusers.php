<?php
require'config.php';
?>
<?php
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

// Read
$sql = "SELECT Username AS username, password, phone, email, studio_name, type FROM registered_user";
$result = $con->query($sql);
$users = [];

while($row = $result->fetch_assoc()) {
    $users[] = $row;
}
if (isset($_POST['Save-changes'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $repass = $_POST['repassword'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $studioName = trim($_POST['studio_name'] ?? '');

    if ($email === '') {
        echo '<script>alert("Email is required.");</script>';
    } elseif ($password !== '' && $password !== $repass) {
        echo '<script>alert("Passwords do not match!");</script>';
    } else {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $con->prepare("UPDATE registered_user SET Username = ?, password = ?, phone = ?, mobileNo = ?, type = ?, studio_name = ? WHERE email = ? LIMIT 1");
            $stmt->bind_param("sssssss", $username, $hash, $phone, $phone, $type, $studioName, $email);
        } else {
            $stmt = $con->prepare("UPDATE registered_user SET Username = ?, phone = ?, mobileNo = ?, type = ?, studio_name = ? WHERE email = ? LIMIT 1");
            $stmt->bind_param("ssssss", $username, $phone, $phone, $type, $studioName, $email);
        }

        $ok = $stmt->execute();
        $stmt->close();

        if ($ok) {
            echo '<script>alert("Account successfully updated!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "viewallusers.php";
            });
        </script>';
        } else {
            echo '<script>alert("Failed to update account.");</script>';
        }
    }
}


if (isset($_POST['deleteAccount'])) {
    $email = trim($_POST['email'] ?? '');
    if ($email === '') {
        echo '<script>alert("Email is required.");</script>';
    } else {
        $stmt = $con->prepare("DELETE FROM registered_user WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $ok = $stmt->execute();
        $stmt->close();

        if ($ok) {
           echo '<script>alert("Account deleted !");
                document.addEventListener("DOMContentLoaded", function() {
                    window.location.href = "viewallusers.php";
                });
            </script>';
        } else {
            echo '<script>alert("Failed to delete account.");</script>';
        }
    }
}

if (isset($_POST['Create-account'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $repass = $_POST['repassword'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $studioName = trim($_POST['studio_name'] ?? '');
    $profilePicture = 'unkown.png';

    if ($email === '' || $username === '' || $password === '') {
        echo '<script>alert("Username, email, and password are required.");</script>';
    } elseif ($repass !== '' && $password !== $repass) {
        echo '<script>alert("Passwords do not match!");</script>';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $con->prepare("INSERT INTO registered_user (Username, password, phone, mobileNo, email, studio_name, type, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $hash, $phone, $phone, $email, $studioName, $type, $profilePicture);
        $ok = $stmt->execute();
        $stmt->close();

        if ($ok) {
            echo '<script>alert("Account created successfully!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "viewallusers.php";
            });
        </script>';
        } else {
            echo '<script>alert("Failed to create account. Email may already exist.");</script>';
        }
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
<link rel="stylesheet" href="admin.css">
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
body.admin-page {
  padding: 0;
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  font-size: 15px;
  color: var(--admin-text);
  background: var(--admin-bg);
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
<body class="admin-page">
    <div class="admin-topbar">
        <div class="admin-topbar-inner">
            <div class="admin-brand">
                <p class="admin-brand-title">Users</p>
                <p class="admin-brand-subtitle">Admin Panel</p>
            </div>
            <div class="admin-nav">
                <a href="admin home.html">Dashboard</a>
                <a href="viewallusers.php">Users</a>
                <a href="viewallbooking.php">Bookings</a>
                <a href="viewallinquire.php">Inquiries</a>
                <a href="admin contact us.php">Messages</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="admin-container">
        <div class="admin-back">
            <a href="admin home.html">Back to Dashboard</a>
        </div>
        <div class="admin-page-title">
            <div>
                <h1>Manage Users</h1>
                <p>View all registered users and manage accounts.</p>
            </div>
        </div>

        <div class="admin-surface">
            <table class="admin-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
				<th>Phone</th>
                <th>Email</th>
				<th>Studio name</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo $user["username"]; ?></td>
                    <td><?php echo $user["password"]; ?></td>
					<td><?php echo $user["phone"]; ?></td>
                    <td><?php echo $user["email"]; ?></td>
					<td><?php echo $user["studio_name"]; ?></td>
                    <td><?php echo $user["type"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
            </table>
        </div>

        <div style="height: 14px"></div>
	<div class="container">
<form action="viewallusers.php" method="post" >
    <div class="row">
      <h4>Account</h4>
      <div class="input-group input-group-icon">
        <input type="text" placeholder="Username" name="username" >
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
          <input type="text" placeholder="Phone" name="phone" >
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
	  <div class="input-group input-group-icon">
        <input type="text" placeholder="Studio name" name="studio_name">
        <div class="input-icon">
          <i class="fa fa-key"></i>
        </div>
      </div>
    </div><br>
    <div class="row">
      <div class="input-group">
			<button class="btn btn-secondary" type="submit" name='Save-changes'>Save changes</button>
			<button class="btn btn-danger" onclick="myFunction()" type="submit" name='deleteAccount'>Delete account</button>
			<button class="btn btn-primary" type="submit" name='Create-account'>Create account</button>
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


</div>
</body>
</html>
