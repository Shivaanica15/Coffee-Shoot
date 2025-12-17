<?php
require'config.php';
?>
<?php
session_start();
$email = $_SESSION['email'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update/Delete Event details</title>
	<link rel="stylesheet" type="text/css" href="styles/al.css"></>
	<?php
	
if (isset($_POST['Save-changes'])) {
	$packageNo =  $_POST['packageNo'];
	$title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
  
        
$quary = "UPDATE event_details 
          SET title = '{$title}', description = '{$description}', price = '{$price}' 
          WHERE email = '{$email}' AND packageNo = '{$packageNo}'";

        $result = mysqli_query($con, $quary);

        if ($result) {
            echo '<script>alert("Event details updated!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "add_package_details.php";
            });
        </script>';
        } else {
            $errors[] = 'Faild to add the record';
        }
    }



if (isset($_POST['deleteAccount'])) {
	$packageNo =$_POST['packageNo'];
	$title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $quary = "DELETE FROM event_details WHERE email = '{$email}' and packageNo='{$packageNo}'";
	
    $result = mysqli_query($con, $quary);
    if ($result) {
       echo '<script>alert("Event details deleted!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "add_package_details.php";
            });
        </script>';
    
	} else {
            $errors[] = 'Faild to Delete the record';
        }
}

?>
</head>
<body>
<div class="topnav">
<img style="width:100px;height:100px;"src="video_and_images/photo7.png"></>
<a href="studio home.html" class="nav-link">Home</a>
                <a href="user.php" class="nav-link">Explore</a>
                <a href="studio about us.html" class="nav-link">About Us</a>
                <a href="studio contact us.php" class="nav-link">Contact Us</a>
                <a href="studionew.php" class="nav-link btn-login">View my profile</a>
    
</div>
		

    <!-- Form for updating booking -->
   <center> <form id="man" action="change_package_details.php" method="Post" >
	<h2 style="color:white;">Update/Delete Booking</h2>
<p style="color:white;">Pakage no.</p>
  <input name ="packageNo" type="text" placeholder="Write the heading......" id="got" style="width:65%; color:#051747;height:50px;" >
  <br><br><br>
  <p style="color:white;">Title Event</p>
  <input name ="title" type="text" placeholder="Write the heading......" id="got" style="width:65%; color:#051747;height:50px;" ><br>
  <br><br>
  <p style="color:white;">Description</p>
  <textarea name ="description" style="width:65%;height:250px;color:#051747;" placeholder="Description....." id="go"></textarea><br>
  <br><br>
  <p style="color:white;">Price</p>
  <input name ="price" type="text" id="went"style="width:65%;color:#051747;height:50px;"><br><br>
  
  <button name="Save-changes" class="fat" style="width:100px;height:50px;background-color:white;"><b>Save changes</b></button>
  <button name="deleteAccount" class="fat" style="width:100px;height:50px;background-color:white;"><b>Delete</b></button>
</form></center>
<br><br>

</body>
</html>