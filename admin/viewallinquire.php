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
$sql = "SELECT * FROM Inquiries";
$result = $con->query($sql);
$inquiries = [];

while($row = $result->fetch_assoc()) {
    $inquiries [] = $row;
}
$con->close();
?>

<div style="margin: 10px 0;">
    <a href="admin home.html">Back to Dashboard</a>
</div>
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
