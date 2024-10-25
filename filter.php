<?php
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

// Initialize filter variables
$searchTerm = "";
$department = "";
$graduationYear = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchTerm = $_POST['search'] ?? '';
    $department = $_POST['department'] ?? '';
    $graduationYear = $_POST['year'] ?? '';

    // Prepare SQL query with filters
    $sql = "SELECT * FROM user_profiles WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($searchTerm)) {
        $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
        $likeTerm = '%' . $searchTerm . '%';
        $params[] = $likeTerm;
        $params[] = $likeTerm;
        $params[] = $likeTerm;
        $types .= "sss";
    }
    if (!empty($department)) {
        $sql .= " AND department = ?";
        $params[] = $department;
        $types .= "s";
    }
    if (!empty($graduationYear)) {
        $sql .= " AND graduation_year = ?";
        $params[] = $graduationYear;
        $types .= "i";
    }

    // Execute query with parameters
    $stmt = $conn->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query to show all profiles
    $sql = "SELECT * FROM user_profiles";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Connect - Search and Filter</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            color: #fff;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 350px;
            margin-top: 50px;
            text-align: center;
            color: #0c0b0b;
        }
        h1 {
            margin-bottom: 30px;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .input-container {
            position: relative;
            width: 100%;
        }
        .input-container input,
        .input-container select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #060606;
            font-size: 16px;
            outline: none;
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
        }
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            width: 80%;
            margin: 20px auto;
        }
        .profile-card {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            color: #000;
        }
        .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Search Profiles</h1>
    <form method="POST">
        <div class="form-group">
            <label for="search">Skills</label>
            <div class="input-container">
                <input type="text" name="search" id="search" placeholder="Enter skills, projects, etc." value="<?= htmlspecialchars($searchTerm) ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="department">Graduation Course</label>
            <div class="input-container">
                <select name="department" id="department">
                    <option value="">Select Department</option>
                    <option value="cs" <?= $department == 'cs' ? 'selected' : '' ?>>Computer Science</option>
                    <option value="business" <?= $department == 'business' ? 'selected' : '' ?>>Information Technology</option>
                    <option value="arts" <?= $department == 'arts' ? 'selected' : '' ?>>AIDS</option>
                    <option value="engineering" <?= $department == 'engineering' ? 'selected' : '' ?>>EXTC</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="year">Graduation Year:</label>
            <div class="input-container">
                <select name="year" id="year">
                    <option value="">Select Year</option>
                    <option value="2024" <?= $graduationYear == '2024' ? 'selected' : '' ?>>2024</option>
                    <option value="2025" <?= $graduationYear == '2025' ? 'selected' : '' ?>>2025</option>
                    <option value="2026" <?= $graduationYear == '2026' ? 'selected' : '' ?>>2026</option>
                    <option value="2027" <?= $graduationYear == '2027' ? 'selected' : '' ?>>2027</option>
                    <option value="2028" <?= $graduationYear == '2028' ? 'selected' : '' ?>>2028</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <button type="submit">Search</button>
        </div>
    </form>
</div>

<div class="profile-grid">
    <?php if (isset($result) && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="profile-card">
                <img src="<?= htmlspecialchars($row['profile_pic']) ?>" alt="Profile Photo">
                <h2><?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) ?></h2>
                <p><?= htmlspecialchars($row['email']) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No profiles found.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
