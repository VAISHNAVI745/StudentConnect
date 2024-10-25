<?php
// Database connection
$host = 'localhost';
$dbname = 'student_connect';
$username = 'root';
$password = ''; // Add your password if needed

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author_name = $_POST['author_name'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = 1; // Replace with dynamic user ID if available

    // Prepare and execute the SQL query
    $sql = "INSERT INTO blogs (user_id, title, content, author_name) VALUES (:user_id, :title, :content, :author_name)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':author_name', $author_name, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "<script>alert('Blog post successfully submitted!');</script>";
    } else {
        echo "<script>alert('Failed to submit the blog post.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student & Alumni Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
        }
        header {
            background-color: #0c45f1;
            color: white;
            padding: 1em;
            text-align: center;
            position: relative;
        }
        h1 {
            margin: 0;
        }
        .back-arrow {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 24px;
            color: white;
            text-decoration: none;
        }
        .back-arrow:hover {
            color: #bbd1f8;
        }
        main {
            width: 90%;
            max-width: 900px;
            margin: 2em auto;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        .form-section {
            margin-bottom: 6em;
        }
        input, textarea {
            width: calc(100% - 40px);
            padding: 1em;
            margin: 0.5em 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #0c45f1;
            color: white;
            padding: 0.7em 2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 10px auto;
        }
        button:hover {
            background-color: #2b80ff;
        }
        .floating-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #0c45f1;
            color: white;
            padding: 0.7em 1.5em;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }
        .floating-btn:hover {
            background-color: #2b80ff;
        }
    </style>
</head>
<body>

<header>
    <a href="home.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
    <h1>Student & Alumni Blog</h1>
    <p>Share your college experiences and projects!</p>
</header>

<main>
    <section class="form-section">
        <h2>Post Your Experience</h2>
        <form action="writeblog.php" method="POST">
            <input type="text" name="author_name" placeholder="Your Name" required>
            <input type="text" name="title" placeholder="Blog Title" required>
            <textarea name="content" rows="5" placeholder="Share your experience..." required></textarea>
            <button type="submit">Submit</button>
        </form>
    </section>
</main>

<a href="blog.php" class="floating-btn">Read Blog</a>

</body>
</html>
