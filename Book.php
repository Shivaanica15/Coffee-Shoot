<?php
require 'config.php';
session_start();

$email = $_SESSION['email'];
$sql = "SELECT * FROM booking WHERE email = '$email'";
$result = $con->query($sql);
$bookings = [];

while($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

if (isset($_POST['book-now'])) {
	$studioName = $_POST['studioName'];
    $event = $_POST['event'];
	$location = $_POST['location'];
    $date = $_POST['date'];
	$time= $_POST['time'];
	$package_type = $_POST['package_type'];

    
        $quary = "INSERT INTO  booking (email,event,location,date,time,studioName,package_type) VALUES ('{$email}','{$event}','{$location}','{$date}','{$time}','{$studioName}','{$package_type}')";
        $result = mysqli_query($con, $quary);

        if ($result) {
            echo '<script>alert("Booking made successfully!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "Book.php";
            });</script>';
        
        } else {
            $errors[] = 'Faild to add the record';
        }
    }
    
	
?>
  <!--Update and delete -->
<?php
 
if (isset($_POST['update'])) {
	$studioName = $_POST['studioName'];
    $event = $_POST['event'];
	$location = $_POST['location'];
    $date = $_POST['date'];
	$time= $_POST['time'];
	$package_type = $_POST['package_type'];

  
    $quary = "UPDATE booking SET event='$event', location='$location', date='$date', studioName='$studioName', package_type='$package_type', time='$time' WHERE email='$email'";
	$result = mysqli_query($con, $quary);
    if ($result) {
        echo '<script>alert("Booking updated successfully!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "Book.php";
            });</script>';
    } else {
        echo "Error updating booking: " . mysqli_error($con);
    }
}


if (isset($_POST['delete'])) {
    $email = $_POST['email'];

    
    $sql = "DELETE FROM booking WHERE email='$email'";

    if (mysqli_query($con, $sql)) {
        echo '<script>alert("Booking deleted successfully!");
            document.addEventListener("DOMContentLoaded", function() {
                window.location.href = "Book.php";
            });</script>';
    } else {
        echo "Error deleting booking: " . mysqli_error($con);
    }
}

$con->close();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Home Page | Our project name </title>
    <link rel="stylesheet" href="styles/CSS navigation.css">
    <link rel="stylesheet" href="CSS footer.css">
    <link rel="stylesheet" href="styles/Css book.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
	</style>

</head>
<body>

    <div class="header">
        <h1>Coffee Shoot</h1>
    </div>

    <div class="topnav">
        <a href="#">Our logo</a>
        <a class="active-page" href="#">Home</a>
        <a href="#">Explore</a>
        <a href="Book.html">Book</a>
        <a href="contact us.html">Contact us</a>
        <a href="About us.html">About us</a>

        <div class="search-container">
            <form action="action_page.php">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit" placeholder="Submit">Search</button>
            </form>
        </div>
    </div>

    <!-- booking-->
    <section class="Book" id="book">
        <h1 class="heading">
            <span>B</span>
            <span>O</span>
            <span>O</span>
            <span>K</span>
            <span class="space"></span>
            <span>N</span>
            <span>O</span>
            <span>W</span>
        </h1><br><br>
		<center><table>
        <thead>
            <tr>
				<th>Studio Name</th>
                <th>Your email</th>
                <th>Event</th>
				<th>Location</th>
                <th>Package type</th>
				<th>Time</th>
				<th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($bookings as $booking): ?>
                <tr>
					<td><?php echo $booking["studioName"]; ?></td>
                    <td><?php echo $booking["email"]; ?></td>
                    <td><?php echo $booking["event"]; ?></td>
					<td><?php echo $booking["location"]; ?></td>
                    <td><?php echo $booking["package_type"]; ?></td>
					<td><?php echo $booking["time"]; ?></td>
					<td><?php echo $booking["date"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table><br><br></center><br><br>

        <div class="row">


            <form action="Book.php" method="post">
                <div class="inputBox">
                    <h3>Email</h3>
                    <input type="text" placeholder="Enter your email" name="email" readonly value="<?php echo $email; ?>">
                </div>
				 <div class="inputBox">
                    <h3>Studio name</h3>
                    <input type="text" placeholder="Enter studio name" name="studioName" required>
                </div>
                <div class="inputBox">
                    <h3>Event</h3>
                    <input type="text" placeholder="category" name="event" required>
                </div>
                <div class="inputBox">
                    <h3>Location</h3>
                    <input type="text" placeholder="place name" name="location" required>
                </div>
                <div class="inputbox">
                    <h3>Date</h3>
                    <input type="Date" name="date" required>
                </div>
                <div class="inputbox">
                    <h3>package</h3>

                </div>
                <div class="inputbox">
                    <select  name="package_type" id="package type">
                        <option value="Wedding">Wedding</option>
                        <option value="Birthday">Birthday</option>
                        <option value="Party">Party</option>
                        <option value="Baby shoot">Baby shoot</option>
                        <option value="Outdoor">Outdoor </option>
                    </select>
                </div>
                <div class="inputBox">
                    <h3>Time</h3>
                    <input type="time" name ="time" required>
                </div>
                <input type="submit" class="btn" name="book-now" value='Book-Now'>
				<input type="submit" class="btn" name="update" value='Save Changes'>
				<input type="submit" class="btn" name="delete" value='Delete Booking'>
            </form>

        </div>
    </section>

    <!--packages section-->
    <section class="packages" id="packages">
        <h1 class="heading">
            <span>P</span>
            <span>a</span>
            <span>c</span>
            <span>k</span>
            <span>a</span>
            <span>g</span>
            <span>e</span>
            <span>s</span>
        </h1>


        <div class="row">
            <div class="column">
                <img src="video_and_images/Wedding.jpg" alt="wedding" onclick="myFunction(this);">
                <h3>Wedding</h3>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <h4>Price:RS.00.00</h4>
                <button class="button">More Details</button>
            </div>
            <div class="column">
                <img src="video_and_images/birthday.jpg" alt="party" onclick="myFunction(this);">
                <h3>Party</h3>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <h4>Price:RS.00.00</h4>
                <button class="button">More Details</button>
            </div>

            <div class="column">
                <img src="video_and_images/babyz.jpg" alt="baby shoot" onclick="myFunction(this);">
                <h3>Newborn baby shoot</h3>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <h4>Price:RS.00.00</h4>
                <button class="button">More Details</button>


            </div>
            <div class="column">
                <img src="video_and_images/normalz.jpg" alt="Outdoor" onclick="myFunction(this);">
                <h3>Outdoor shoot</h3>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <h4>Price:RS.00.00</h4>
                <button class="button">More Details</button>
            </div>
        </div>
        

        <!--

            <div class="card">
                <img src="Photos/birthday.jpg" alt="">
                <h3>Party</h3>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>

            </div>
            <div class="card">
                <img src="Photos/babyz.jpg" alt="">
                <h3>Newborn baby shoot</h3>
                <p>gh hjwkd ndbjkfj</p>
            </div>
            <div class="card">
                <img src="Photos/normalz.jpg" alt="">
                <h3>Outdoor shoot</h3>
            </div>
        </div>
    </div> -->

    </section>
    

</body>




