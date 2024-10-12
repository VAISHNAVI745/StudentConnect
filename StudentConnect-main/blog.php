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

// Fetch blog posts from the database
$sql = "SELECT * FROM blogs ORDER BY created_at DESC"; // Ensure your table has a 'created_at' column
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Page - Student Connect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #7bc1ed,  #bbd1f8);
            color: #333;
        }

        header {
            background-color: #0c45f1;
            color: white;
            padding: 1em 0;
            text-align: center;
            position: relative; /* Positioning context for the button */
        }

        header h1 {
            margin: 0;
        }

        .back-arrow {
            position: absolute; /* Absolute positioning */
            left: 20px; /* Aligns the button to the left */
            top: 20px; /* Aligns the button to the top */
            font-size: 24px;
            color: white; /* Arrow color */
            text-decoration: none; /* Remove underline */
        }

        .back-arrow:hover {
            color: #bbd1f8; /* Change color on hover */
        }

        .header-button {
            position: absolute; /* Absolute positioning */
            right: 20px; /* Aligns the button to the right */
            top: 50%; /* Vertically center */
            transform: translateY(-50%); /* Adjusts for the height of the button */
            background-color: #ffffff;
            color: #0c45f1;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .header-button:hover {
            background-color: #e0e0e0;
        }

        #blog {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .blog-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .post {
            background: linear-gradient(135deg, #7bc1ed,  #bbd1f8);
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .post h3 {
            margin-top: 0;
            font-size: 1.5rem;
        }

        .post p {
            margin: 10px 0;
        }

        .read-more {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #0c45f1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .read-more:hover {
            background-color: #2b80ff;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <a href="home.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a> <!-- Back arrow link -->
        <h1>Student Connect Blog</h1>
        <button class="header-button" onclick="window.location.href='writeblog.php'">Write Blog</button> <!-- Button for navigation -->
    </header>

    <section id="blog">
        <h2>Recent Posts</h2>
        <div class="blog-container">
            <?php
            if ($result && $result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo '<article class="post">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>by ' . htmlspecialchars($row['author_name']) . '</p>'; // Adjust based on your column
                    echo '<p>' . htmlspecialchars($row['content']) . '</p>'; // Content field
                    echo '<a href="read_post.php?id=' . htmlspecialchars($row['id']) . '" class="read-more">Read More</a>'; // Adjust the read link
                    echo '</article>';
                }
            } else {
                echo '<p>No blog posts found.</p>'; // Message if no blog posts exist
            }
            ?>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Student Connect. All rights reserved.</p>
    </footer>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
