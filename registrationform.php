<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_management";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// User registration
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "Email already exists";
    } else {
        $sql = "INSERT INTO users (name, email, dob, age, sex, password) VALUES ('$name', '$email', '$dob', '$age', '$sex', '$password')";
        if (mysqli_query($conn, $sql)) {
            echo "User registered successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// User login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $email;
            // Redirect to the content page after successful login
            header("Location: content_page.php");
            exit;
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
}

// Multi-user access
if (isset($_SESSION['email'])) {
    echo "Welcome, " . $_SESSION['email'] . "! You are logged in.";
    // Display content for logged-in users
} else {
    // Display content for non-logged-in users
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up and Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #fdfbfb, #ebedee);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 400px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 20px;
        }

        .container h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
        }

        .container form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .container form input[type="text"],
        .container form input[type="email"],
        .container form input[type="password"],
        .container form input[type="date"],
        .container form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .container form input[type="submit"] {
            background-color: #0078d4;
            color: #fff;
            border: none;
            padding: 12px 0;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        .container form input[type="submit"]:hover {
            background-color: #005a9e;
        }

        .container form .fa {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="post">
            <label for="name"><i class="fas fa-user"></i>Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email"><i class="fas fa-envelope"></i>Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="dob"><i class="fas fa-calendar-alt"></i>Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>
            <label for="age"><i class="fas fa-user"></i>Age:</label>
            <input type="number" id="age" name="age" required>
            <label for="sex"><i class="fas fa-venus-mars"></i>Sex:</label>
            <select id="sex" name="sex" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <label for="password"><i class="fas fa-lock"></i>Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="signup" value="Sign Up">
        </form>

        <h2>Login</h2>
        <form method="post">
            <label for="email"><i class="fas fa-envelope"></i>Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password"><i class="fas fa-lock"></i>Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="login" value="Login">
        </form>

        <form method="post">
            <input type="submit" name="logout" value="Logout">
        </form>
    </div>
</body>
</html>
