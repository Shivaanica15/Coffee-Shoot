
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as studio | Coffee Shoot</title>

    <style>
        .register-container {
            max-width: 450px;
            margin: 50px auto;
            padding: 20px;
            background-image:radial-gradient(#964734,#000);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border:5px solid #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 90%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
        body{
            background-image:url(media/4.jpg);
            background-size: cover;
            background-position: top right;
        }
        header {
   
    padding: 5px;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 36px;
    color: #fff;
}
    </style>
    <?php
// Establish database connection
$con = mysqli_connect("localhost", "root", "", "mydb");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Initialize errors array
$errors = array();

if (isset($_POST['registerForm'])) {
    // Check if form fields are set
    if (isset($_POST['username'], $_POST['password'], $_POST['phone'], $_POST['email'], $_POST['repassword'], $_POST['studio_name'])) {
        // Retrieve form data
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $phone = mysqli_real_escape_string($con, $_POST['phone']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $repassword = mysqli_real_escape_string($con, $_POST['repassword']);
        $studio_name = mysqli_real_escape_string($con, $_POST['studio_name']);

        // Check if username already exists
        $query = "SELECT * FROM registered_user WHERE username = '{$username}' LIMIT 1";
        $resultset = mysqli_query($con, $query);
        if ($resultset) {
            if (mysqli_num_rows($resultset) == 1) {
                $errors[] = 'Username has already been taken!';
            }
        }

        // Check if passwords match
        if ($password != $repassword) {
            $errors[] = 'Passwords do not match!';
        }

        // If no errors, insert data into database
        if (empty($errors)) {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $type ='studio';

            $query = "INSERT INTO registered_user (Username,password,phone,email,studio_name,type) VALUES ('$username', '$password', '$phone', '$email', '$studio_name','$type')";
            $result = mysqli_query($con, $query);

            if ($result) {
                echo '<script>alert("Account created successfully!"); window.location.href = "login_signup.php";</script>';
            } else {
                $errors[] = 'Failed to add the record!';
            }
        }
    } else {
        $errors[] = 'All fields are required!';
    }
}

// Close the database connection
mysqli_close($con);
?>

</head>
<body>
<header>
<img src="media/P.png" style="width:150px; height:150px;">
<h1> COFFEE SHOOT <h1> 
</header>

<div class="register-container">
    <h2 style="color:white;">Create Account</h2>
    <form id="registerForm" action="studioaccountcreate.php" method="post">
        <div class="form-group">
            <label for="email" style="color:white;">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone" style="color:white;">Phone Number</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="username" style="color:white;">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password" style="color:white;">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="repassword" style="color:white;">Confirm Password</label>
            <input type="password" id="repassword" name="repassword" required>
        </div>
        <div class="form-group">
            <label for="studio_name" style="color:white;">Studio Name</label>
            <input type="text" id="studio_name" name="studio_name" required>
        </div>
        <button name="registerForm">Create Account</button>
    </form>
</div>
<script>
    document.getElementById("registerForm").addEventListener("submit", function (event) {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("repassword").value;

        if (password !== confirmPassword) {
            alert("Passwords do not match");
            event.preventDefault();
        }
    });
</script>
</body>
</html>
