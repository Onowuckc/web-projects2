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

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: registrationform.php");
    exit;
}

// Fetch user information
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Insert new post into the database
if (isset($_POST['post_content'])) {
    $content = $_POST['post_content'];
    $author = $user['email'];

    $sql = "INSERT INTO posts (content, author) VALUES ('$content', '$author')";
    if (mysqli_query($conn, $sql)) {
        header("Location: content_page.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>