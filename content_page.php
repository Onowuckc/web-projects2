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

// Fetch user's posts
$sql = "SELECT * FROM posts WHERE author='$email' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook-like Content Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        .user-info {
            margin-bottom: 20px;
        }

        .user-info h2 {
            margin-top: 0;
        }

        .post-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .post-form button {
            padding: 10px 20px;
            background-color: #4267B2;
            color: white;
            border: none;
            cursor: pointer;
        }

        .post-form button:hover {
            background-color: #385898;
        }

        .posts {
            margin-top: 20px;
        }

        .post {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .post p {
            margin: 0;
        }

        .post-author, .post-date {
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info">
            <h2>User Information</h2>
            <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Date of Birth:</strong> <?php echo $user['dob']; ?></p>
            <p><strong>Age:</strong> <?php echo $user['age']; ?></p>
        </div>
        
        <div class="post-form">
            <h2>Create a Post</h2>
            <form method="post" action="create_post.php">
                <textarea name="post_content" placeholder="What's on your mind?" required></textarea>
                <button type="submit">Post</button>
            </form>
        </div>

        <div class="posts">
            <h2>Recent Posts</h2>
            <?php foreach ($posts as $post) : ?>
                <div class="post">
                    <p><?php echo $post['content']; ?></p>
                    <p class="post-author">Posted by: <?php echo $user['name']; ?></p>
                    <p class="post-date">Posted on: <?php echo $post['created_at']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
