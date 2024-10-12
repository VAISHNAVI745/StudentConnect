<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_connect";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an ID is set in the URL
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']); // Convert to integer to prevent SQL injection
    $sql = "SELECT * FROM blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc(); // Fetch the post
    } else {
        die("Post not found.");
    }
} else {
    die("No post ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Student Connect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            color: #333;
        }

        header {
            background-color: #0c45f1;
            color: white;
            padding: 1em;
            text-align: center;
        }

        h1 {
            margin: 0;
        }

        main {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 20px;
        }

        a {
            color: #0c45f1;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p>by <?php echo htmlspecialchars($post['author_name']); ?></p>
</header>

<main>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
</main>

<footer>
    <a href="blog.php">Back to Blog</a>
</footer>

</body>
</html>

<?php
$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>
