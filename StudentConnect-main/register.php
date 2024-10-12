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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $user_type = $_POST['user_type'];

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "<script>alert('An account with this email already exists!');</script>";
    } else {
        // Insert the new user into the users table
        $sql = "INSERT INTO users (fullname, email, password, user_type) VALUES ('$fullname', '$email', '$password', '$user_type')";
        if ($conn->query($sql) === TRUE) {
            // Retrieve the user ID of the newly registered user
            $user_id = $conn->insert_id;
            $_SESSION['user_id'] = $user_id;  // Save user ID in session
            $_SESSION['fullname'] = $fullname;  // Save fullname in session
            $_SESSION['email'] = $email;  // Save email in session
            // Redirect to createprofile.php
            header("Location: createprofile.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            color: #080808;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            color: #110f0f;
        }

        h2 {
            margin-bottom: 30px;
            font-size: 28px;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .input-container {
            position: relative;
            width: 100%;
        }

        .input-container input,
        .input-container select {
            width: 100%;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #0c0a0a;
            font-size: 16px;
            outline: none;
            transition: background-color 0.3s ease;
        }

        .input-container input::placeholder {
            color: #837e7e;
        }

        .input-container input:focus {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #0c45f1;
            border: none;
            color: #fff;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-group button:hover {
            background-color: #2b80ff;
            transform: scale(1.05);
        }

        .form-group p {
            margin-top: 20px;
            font-size: 14px;
        }

        .form-group p a {
            color: #0c45f1;
            text-decoration: none;
            font-weight: 500;
        }

        .form-group p a:hover {
            text-decoration: underline;
        }

        .password-note {
            font-size: 12px;
            color: #757474;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form id="register-form" method="post">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            <div class="form-group">
                <label for="user_type">Are you an Active Student or Alumni?</label>
                <select id="user_type" name="user_type" required>
                    <option value="">Select...</option>
                    <option value="Active Student">Active Student</option>
                    <option value="Alumni">Alumni</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
            <div class="form-group">
                <p>Already have an account? <a href="loginS.html">Login</a></p>
            </div>
        </form>
    </div>
</body>
</html>
