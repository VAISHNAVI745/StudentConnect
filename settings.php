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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch current user information
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user_profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Store user data in $user array
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Student Connect</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Added Font Awesome -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #0c45f1;
        }

        .section {
            margin-bottom: 40px;
        }

        .section h3 {
            margin-bottom: 10px;
            color: #0c45f1;
        }

        .section label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .section input[type="password"],
        .section textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0c45f1;
            color: #ffffff;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2b80ff;
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        .back-arrow {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 24px;
            color: #0c45f1;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-arrow:hover {
            color: #2b80ff;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="home.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
        <h2>Settings</h2>

        <!-- Account Management Section -->
        <div class="section">
            <h3>Account Management</h3>
            <form method="POST" action="">
                <label for="old-password">Old Password</label>
                <input type="password" id="old-password" name="old_password" placeholder="Enter old password">

                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new_password" placeholder="Enter new password">

                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm new password">

                <div>
                    <button class="btn" name="change_password">Change Password</button>
                </div>
            </form>
        </div>

        <!-- Feedback & Support Section -->
        <div class="section">
            <h3>Feedback & Support</h3>
            <label for="feedback">Your Feedback</label>
            <textarea id="feedback" rows="4" placeholder="Share your thoughts or issues..."></textarea>
            <div>
                <button class="btn" onclick="submitFeedback()">Submit Feedback</button>
            </div>
        </div>

        <!-- Logout Option -->
        <div class="form-actions">
            <button class="btn" onclick="logout()">Logout</button>
        </div>
    </div>

    <script>
        function submitFeedback() {
            // Logic for submitting feedback
            alert("Feedback submitted successfully!");
        }

        function logout() {
            window.location.href = "logout.php"; // Redirect to your logout file
        }
    </script>
</body>

</html>
