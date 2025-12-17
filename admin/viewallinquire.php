<?php
require'config.php';
?>
<?php
session_start();
$email = $_SESSION['email'];

// Read
$sql = "SELECT * FROM Inquiries"; 
$result = $con->query($sql);
$inquiries = [];

while($row = $result->fetch_assoc()) {
    $inquiries [] = $row;
}
$con->close();
?>
<html>
<head>
<title>Coffee Shoot</title>
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
                <th>Name</th>
                <th>Email</th>
				<th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($inquiries  as $inquirie): ?>
                <tr>
                    <td><?php echo $inquirie["Name"]; ?></td>
                    <td><?php echo $inquirie["Email"]; ?></td>
					<td><?php echo $inquirie["Message"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>