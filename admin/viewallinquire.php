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
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inquiries | Admin</title>
    <link rel="stylesheet" href="admin.css" />
</head>
<body class="admin-page">
    <div class="admin-topbar">
        <div class="admin-topbar-inner">
            <div class="admin-brand">
                <p class="admin-brand-title">Inquiries</p>
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
                <h1>All Inquiries</h1>
                <p>Read-only list of inquiry messages.</p>
            </div>
        </div>

        <div class="admin-surface">
            <table class="admin-table">
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
        </div>
    </div>
</body>
</html>
