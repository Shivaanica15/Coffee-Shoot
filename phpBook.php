<?php
require 'config.php';
session_start();

$email = $_SESSION['email'];
$sql = "SELECT * FROM booking WHERE email = '$email'";
$result = $con->query($sql);
$events = [];

while($row = $result->fetch_assoc()) {
    $events[] = $row;
}

if (isset($_POST['book-now'])) {
    $email =  $_POST['email'];
    $event = $_POST['event'];
	$location = $_POST['location'];
    $date = $_POST['date'];
	$time= $_POST['time'];

    
        $quary = "INSERT INTO  booking (email,event,location,date,time)VALUES ('{$email}','{$event}','{$location}','{$date}','{$time}')";
        $result = mysqli_query($con, $quary);

        if ($result) {
            echo '<script>alert("Booking made successfully!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "Book.html";
            });
        </script>';
        } else {
            $errors[] = 'Faild to add the record';
        }
    }
    $con->close();
	
?>
  <!--Update and delete -->
<?php



if (isset($_POST['update'])) {
    $email = $_POST['email'];
    $event = $_POST['event'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $package_type = $_POST['package_type'];
    $time = $_POST['time'];

  
    $sql = "UPDATE bookings SET event='$event', location='$location', date='$date', package_type='$package_type', time='$time' WHERE email='$email'";

    if (mysqli_query($conn, $sql)) {
        echo "Booking updated successfully";
    } else {
        echo "Error updating booking: " . mysqli_error($con);
    }
}


if (isset($_POST['delete'])) {
    $email = $_POST['email'];

    
    $sql = "DELETE FROM bookings WHERE email='$email'";

    if (mysqli_query($conn, $sql)) {
        echo "Booking deleted successfully";
    } else {
        echo "Error deleting booking: " . mysqli_error($con);
    }
}


mysqli_close($con);
?>







