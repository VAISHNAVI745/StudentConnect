<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_connect";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle logout
if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: home.php"); // Redirect to homepage after logout
    exit();
}

// Initialize search term
$searchTerm = "";
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

// Fetch user profiles from the database with filtering
$sql = "SELECT * FROM user_profiles WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?";
$stmt = $conn->prepare($sql);
$searchTermWithWildcard = '%' . $searchTerm . '%'; // Add wildcards for SQL LIKE
$stmt->bind_param('sss', $searchTermWithWildcard, $searchTermWithWildcard, $searchTermWithWildcard);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Connect</title>

    <!-- Add Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            color: #fff;
            overflow: hidden; /* Prevent scrolling when modal is open */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-sizing: border-box;
        }

        .header-left,
        .header-right {
            display: flex;
            align-items: center;
        }

        .header button {
            padding: 10px;
            border: none;
            background-color: transparent;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .header button:hover {
            color: #2b80ff;
        }

        .profile-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 80%; /* Adjust as needed */
            text-align: center;
            color: #0c0b0b;
            margin-top: 20px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            letter-spacing: 1px;
        }

        p {
            font-size: 14px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            width: 80%; /* Increase width of the search bar */
            max-width: 800px; /* Maximum width for larger screens */
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            padding-right: 40px; /* Add padding to make space for the icon */
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #060606;
            font-size: 14px;
            outline: none;
            transition: background-color 0.3s ease;
        }

        .search-bar input::placeholder {
            color: #837e7e;
        }

        .search-bar input:focus {
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Magnifying glass icon */
        .search-bar i {
            position: absolute;
            right: 10px;
            color: #837e7e;
        }

        /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: rgba(255, 255, 255, 0.9);
            min-width: 160px;
            z-index: 1;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 columns */
            gap: 20px;
            width: 80%; /* Adjust width of the grid */
            margin: 20px auto;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            color: #000;
            cursor: pointer; /* Indicate clickable */
        }

        .profile-card img {
            width: 100px; /* Adjust profile image size */
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 18px;
            font-weight: bold;
        }

        .profile-email {
            font-size: 14px;
            color: #333;
        }

        /* Floating blog button */
        .floating-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #0c45f1;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .floating-btn i {
            margin-right: 10px;
        }

        .floating-btn:hover {
            background-color: #2b80ff;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-left">
            <button onclick="toggleModal()">
                <img src="https://via.placeholder.com/50" alt="Profile Photo" class="profile-photo">
            </button>
        </div>

        <div class="header-middle">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <i class="fas fa-search"></i> <!-- Magnifying glass icon -->
            </div>
        </div>

        <div class="header-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <button>Menu</button>
                    <div class="dropdown-content">
                        <a href="public_profile.php?id=<?php echo $_SESSION['user_id']; ?>">My Profile</a>
                        <a href="settings.php">Settings</a> <!-- Added Settings link -->
                        <a href="home.php?logout=true">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <button onclick="window.location.href='login.php'">Login</button>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-container">
        <h1>Profiles</h1>

        <div class="profile-grid">
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="profile-card" onclick="window.location.href=\'public_profile.php?id=' . htmlspecialchars($row['user_id']) . '\'">'; // Link to public profile
                    echo '<img src="' . htmlspecialchars($row['profile_pic']) . '" alt="Profile Photo">'; // Display profile picture
                    echo '<div class="profile-name">' . htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) . '</div>';
                    echo '<div class="profile-email">' . htmlspecialchars($row['email']) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No profiles found.</p>'; // Message if no profiles exist
            }
            ?>
        </div>
    </div>

    <!-- Floating blog button -->
    <a href="blog.php" class="floating-btn">
        <i class="fas fa-edit"></i>Blog
    </a>

    <script>
        // Function to toggle the modal
        function toggleModal() {
            const modal = document.getElementById('profileModal');
            modal.classList.toggle('open');
        }
    </script>
</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
