<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_connect";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search input
$searchTerm = "";
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

// SQL query to fetch matching profiles
$sql = "SELECT * FROM user_profiles 
        WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?";
$stmt = $conn->prepare($sql);
$searchTermWildcard = '%' . $searchTerm . '%';
$stmt->bind_param('sss', $searchTermWildcard, $searchTermWildcard, $searchTermWildcard);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search Form with Back Button</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            color: #fff;
            height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            position: sticky;
            top: 0;
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            z-index: 100;
        }

        .back-button {
            background: #fff;
            color: #333;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-button:hover {
            background: #f0f0f0;
            transform: scale(1.05);
        }

        .search-container {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .filter-button {
            background: #0c45f1;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            padding: 12px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        .filter-button:hover {
            background: #2b80ff;
        }

        .search-container input,
        .search-container button {
            padding: 12px;
            border: none;
            border-radius: 8px;
            margin-right: 10px;
        }

        .search-container input {
            flex: 1;
            background: #fff;
            color: #333;
            outline: none;
            font-size: 14px;
        }

        .search-container button {
            background: #0c45f1;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-container button:hover {
            background: #2b80ff;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            color: #000;
        }

        .profile-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-name {
            font-weight: bold;
            margin-top: 10px;
        }

        .profile-email {
            color: #333;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Top Bar Container -->
    <div class="top-bar">
        <!-- Back Button -->
        <button class="back-button" onclick="window.location.href='homepage.html';">&#8592;</button>

        <!-- Filter Button -->
        <button class="filter-button" onclick="window.location.href='filter.php';">Filter</button>

        <!-- Search Form -->
        <form class="search-container" method="POST" action="search.php">
            <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Display Profiles -->
    <div class="profile-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="profile-card">
                    <img src="<?php echo htmlspecialchars($row['profile_pic']); ?>" alt="Profile Picture">
                    <div class="profile-name"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                    <div class="profile-email"><?php echo htmlspecialchars($row['email']); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No profiles found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
