<?php
require 'config2.php';
session_start();

$email = $_SESSION['email'];
$sql = "SELECT * FROM event_details WHERE email = '$email'";
$result = $conn->query($sql);
$events = [];

while($row = $result->fetch_assoc()) {
    $events[] = $row;
}

if (isset($_POST['submit'])) {
	$packageNo =  $_POST['packageNo'];
    $title =  $_POST['title'];
    $description = $_POST['description'];
	$price = $_POST['price'];
    
// Function to generate the next package number
function generateNextPackageNo() {
    global $conn;
    $sql = "SELECT COUNT(*) AS count FROM event_details";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row['count'];

    if ($count === 0) {
        return 'P001'; // If no records exist yet, return P001 as default
    } else {
        $sql = "SELECT MAX(packageNo) AS max_packageNo FROM event_details";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $max_packageNo = $row['max_packageNo'];

        // Extract the numeric part of the package number, increment it, and format it back
        $numeric_part = intval(substr($max_packageNo, 1)) + 1;
        return 'P' . sprintf('%03d', $numeric_part);
    }
}


    $packageNo = generateNextPackageNo();
	$quary = "INSERT INTO  event_details (packageNo,title,description,price,email) VALUES ('{$packageNo}','{$title}','{$description}','{$price}','{$email}')";
    $result = mysqli_query($conn, $quary);

        if ($result) {
            echo '<script>alert("Event details are successfully added!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "add_package_details.php";
            });
        </script>';
        }
}

?>

<html>
<head>
	
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title> Add package | Coffee Shoot </title>
	
	<link rel="stylesheet" href="style/el.css"/>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        table {
            border-collapse: collapse;
            width: 100%;
            background-color:#fff;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .form{
            background-image:radial-gradient(#964734,#000);
        }
        #man {
		  background-color: #536F80;
		  width: 40%;
		  border: 10px solid #ffffff;
		  padding: 50px;
		  margin: 8px;
		  height:800px;
		}
		 p {
			color:white;
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
                <a href="index.html"><h1>Coffee Shoot</h1></a>
            </div>
            <div class="nav-links">
                <a href="studio home.html" class="nav-link">Home</a>
                <a href="user.php" class="nav-link">Explore</a>
                <a href="studio about us.html" class="nav-link">About Us</a>
                <a href="studio contact us.php" class="nav-link">Contact Us</a>
                <a href="studionew.php" class="nav-link btn-login">View my profile</a>
                
            </div>
        </nav>
    </header>
	
	<br><br>
<img src="img/photo1.jpg" style="float:right;height:90%;"></>
<form action="add_package_details.php" method ="post" id="man" class="form">
<p>Package No.</p>
<input name="packageNo" type="text" placeholder="Default ID NO Changes can be done" id="got" style="width:65%; color:black;height:50px;"  readonly>

  <br><br><br>
<p>Title Event</p>
  <input name="title" type="text" placeholder="Write the heading......" id="got" style="background-color:#fff;width:65%; color:#051747;" ><br>
  <br><br><br><br>
  <p>Description</p>
  <textarea name="description" style="width:65%;height:250px;background-color:#fff;color:#051747;" placeholder="Description....." id="go"></textarea><br>
  <br><br>
  <p>Price</p>
  <input name="price" type="integer" id="went"style="background-color:#fff;width:65%;color:#051747;"><br><br><br>
  <br>
  <button name="submit" class="fat" style="width:100px;height:50px;background-color:#2a6592; align-items:center;"><b>Submit</b></button>
</form>
<br><br><br><br><br>
<center><a href="change_package_details.php"><button class="fat" style="width:200px;height:50px;background-color:#2a6592;" ><b>Want to make changes!</b></button></a><br>
</form> 
<br>
<table>
        <thead>
            <tr>
				<th>Package no</th>
                <th>Event title</th>
                <th>Description</th>
				<th>Price</th>
                <th>Studio email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($events as $event): ?>
                <tr>
					<td><?php echo $event["packageNo"]; ?></td>
                    <td><?php echo $event["title"]; ?></td>
                    <td><?php echo $event["description"]; ?></td>
					<td><?php echo $event["price"]; ?></td>
                    <td><?php echo $event["email"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table><br><br></center>
  <br><br>
 

<br><br>

<br>

<br>
</body>
</html>
