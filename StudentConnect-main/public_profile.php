<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_connect";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from the URL parameter
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch user profile from the database
$sql = "SELECT * FROM user_profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found."); // Handle case where user doesn't exist
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile of <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Add your styles here (similar to the previous style in your public view profile) */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        .profile-container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .profile-picture {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .profile-name {
            font-size: 24px;
            margin: 10px 0;
        }

        .profile-email {
            font-size: 16px;
            margin: 5px 0;
        }

        .btn-container {
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #0c45f1;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2b80ff;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <img class="profile-picture" src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">
        <h2 class="profile-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
        <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
        
        <div class="btn-container">
            <button class="btn" onclick="window.location.href='home.php'">Back to Homepage</button>
        </div>
        
        <section class="skills-section">
            <h3>Skills</h3>
            <p><?php echo htmlspecialchars($user['skills']); ?></p>
        </section>
        
        <section class="internships-section">
            <h3>Internships</h3>
            <p><?php echo htmlspecialchars($user['internships']); ?></p>
        </section>

        <section class="work-experience-section">
            <h3>Work Experience</h3>
            <p><?php echo htmlspecialchars($user['work_experience']); ?></p>
        </section>
    </div>
</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
