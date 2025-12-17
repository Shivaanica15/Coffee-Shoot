<?php
require'config.php';
?>
<?php
session_start();
$email = $_SESSION['email'];

// Read
$sql = "SELECT * FROM booking";
$result = $con->query($sql);
$bookings = [];

while($row = $result->fetch_assoc()) {
    $bookings [] = $row;
}
$con->close();
?>
<html>
<head>
<title>Bookings</title>
<head>
<body><style>
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
        }</style>
 <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Event</th>
				<th>Location</th>
                <th>Date</th>
                <th>Studio Name</th>
				<th>Time</th>
				<th>Package Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($bookings  as $booking): ?>
                <tr>
                    <td><?php echo $booking["email"]; ?></td>
                    <td><?php echo $booking["event"]; ?></td>
					<td><?php echo $booking["location"]; ?></td>
                    <td><?php echo $booking["date"]; ?></td>
					<td><?php echo $booking["time"]; ?></td>
                    <td><?php echo $booking["studioName"]; ?></td>
					<td><?php echo $booking["package_type"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>