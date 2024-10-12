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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Back arrow button */
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            text-decoration: none;
            font-size: 24px;
            color: #0c45f1;
            background: none;
            border: none;
            cursor: pointer;
        }

        h1 {
            text-align: center;
            color: #0c45f1;
        }

        fieldset {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        legend {
            font-weight: bold;
            color: #0c45f1;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="url"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .custom-file-upload {
            display: block;
            margin-bottom: 10px;
        }

        input[type="file"] {
            display: none;
        }

        input[type="file"] + label {
            background: #010101;
            color: white;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="file"] + label:hover {
            background: #1f6390;
        }

        .image-container {
            position: relative;
            display: inline-block;
            margin-top: 10px;
        }

        img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
        }

        .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: rgb(10, 7, 7);
            text-align: center;
            pointer-events: none; 
        }

        button {
            background-color: #0c45f1;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color:  #2b80ff;
        }

        #message {
            text-align: center;
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back arrow button -->
        <a href="home.php" class="back-button">&#8592;</a>
        
        <h1>Edit Profile</h1>
        <form id="profile-form">
            <fieldset>
                <legend>Profile Picture</legend>
                <label for="profile-pic" class="custom-file-upload">Upload Profile Picture:</label>
                <input type="file" id="profile-pic" accept="image/*" onchange="previewImage(event)" />
                <div class="image-container">
                    <img id="profile-pic-preview" src="<?php echo htmlspecialchars($user['profile_pic']); ?>" />
                    <div class="overlay-text" id="overlay-text">Profile Picture</div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Personal Information</legend>
                
                <label for="user-type">Are you an Active Student or Alumni?</label>
                <select id="user-type" required>
                    <option value="" <?php if ($user['user_type'] == "") echo "selected"; ?>>Select...</option>
                    <option value="Active Student" <?php if ($user['user_type'] == "Active Student") echo "selected"; ?>>Active Student</option>
                    <option value="Alumni" <?php if ($user['user_type'] == "Alumni") echo "selected"; ?>>Alumni</option>
                </select>

                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>" readonly />

                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required />

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required />

                <label for="email">Email:</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" />

                <label for="gender">Gender:</label>
                <select id="gender">
                    <option value="" <?php if ($user['gender'] == "") echo "selected"; ?>>Select...</option>
                    <option value="Male" <?php if ($user['gender'] == "Male") echo "selected"; ?>>Male</option>
                    <option value="Female" <?php if ($user['gender'] == "Female") echo "selected"; ?>>Female</option>
                    <option value="Other" <?php if ($user['gender'] == "Other") echo "selected"; ?>>Other</option>
                </select>

                <label for="bio">Bio:</label>
                <textarea id="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </fieldset>

            <!-- Academic Details Section -->
            <fieldset>
                <legend>Academic Details</legend>

                <h3>Graduation</h3>
                <label for="graduation-college">College Name:</label>
                <input type="text" id="graduation-college" value="<?php echo htmlspecialchars($user['graduation_college']); ?>" placeholder="Enter your graduation college name" />

                <label for="graduation-course">Course Name:</label>
                <input type="text" id="graduation-course" value="<?php echo htmlspecialchars($user['graduation_course']); ?>" placeholder="Enter your graduation course name" />

                <label for="graduation-year">Graduation Year:</label>
                <input type="text" id="graduation-year" value="<?php echo htmlspecialchars($user['graduation_year']); ?>" placeholder="Enter your graduation year" />

                <h3>Post-Graduation</h3>
                <label for="post-graduation-college">College Name:</label>
                <input type="text" id="post-graduation-college" value="<?php echo htmlspecialchars($user['post_graduation_college']); ?>" placeholder="Enter your post-graduation college name" />

                <label for="post-graduation-course">Course Name:</label>
                <input type="text" id="post-graduation-course" value="<?php echo htmlspecialchars($user['post_graduation_course']); ?>" placeholder="Enter your post-graduation course name" />

                <h3>PhD</h3>
                <label for="phd-college">College Name:</label>
                <input type="text" id="phd-college" value="<?php echo htmlspecialchars($user['phd_college']); ?>" placeholder="Enter your PhD college name" />

                <label for="phd-course">Course Name:</label>
                <input type="text" id="phd-course" value="<?php echo htmlspecialchars($user['phd_course']); ?>" placeholder="Enter your PhD course name" />
            </fieldset>

            <fieldset>
                <legend>Skills</legend>
                <input type="text" id="skill" placeholder="Enter a skill" />
                <button type="button" onclick="addSkill()">Add Skill</button>
                <ul id="skills-list">
                    <?php 
                    // Assuming $user['skills'] is a comma-separated string of skills
                    $skills = explode(",", $user['skills']);
                    foreach ($skills as $skill) {
                        echo "<li>" . htmlspecialchars(trim($skill)) . "</li>";
                    }
                    ?>
                </ul>
            </fieldset>

            <fieldset>
                <legend>Courses</legend>
                <input type="text" id="course" placeholder="Enter a course" />
                <button type="button" onclick="addCourse()">Add Course</button>
                <ul id="courses-list">
                    <?php 
                    // Assuming $user['courses'] is a comma-separated string of courses
                    $courses = explode(",", $user['courses']);
                    foreach ($courses as $course) {
                        echo "<li>" . htmlspecialchars(trim($course)) . "</li>";
                    }
                    ?>
                </ul>
            </fieldset>

            <fieldset>
                <legend>Internships</legend>
                <input type="text" id="internship" placeholder="Enter an internship" />
                <button type="button" onclick="addInternship()">Add Internship</button>
                <ul id="internships-list">
                    <?php 
                    // Assuming $user['internships'] is a comma-separated string of internships
                    $internships = explode(",", $user['internships']);
                    foreach ($internships as $internship) {
                        echo "<li>" . htmlspecialchars(trim($internship)) . "</li>";
                    }
                    ?>
                </ul>
            </fieldset>

            <fieldset>
                <legend>Work Experiences</legend>
                <input type="text" id="job" placeholder="Enter a job experience" />
                <button type="button" onclick="addJob()">Add Job</button>
                <ul id="jobs-list">
                    <?php 
                    // Assuming $user['work_experience'] is a comma-separated string of work experiences
                    $jobs = explode(",", $user['work_experience']);
                    foreach ($jobs as $job) {
                        echo "<li>" . htmlspecialchars(trim($job)) . "</li>";
                    }
                    ?>
                </ul>
            </fieldset>

            <fieldset>
                <legend>Social Media</legend>
                <label for="linkedin">LinkedIn:</label>
                <input type="url" id="linkedin" value="<?php echo htmlspecialchars($user['linkedin_url']); ?>" placeholder="https://www.linkedin.com/in/your-full-profile-link-here" />

                <label for="github">GitHub:</label>
                <input type="url" id="github" value="<?php echo htmlspecialchars($user['github_url']); ?>" placeholder="https://www.github.com/your-username-here" />

                <label for="instagram">Instagram:</label>
                <input type="url" id="instagram" value="<?php echo htmlspecialchars($user['instagram_url']); ?>" placeholder="https://www.instagram.com/your-username-here" />

                <label for="facebook">Facebook:</label>
                <input type="url" id="facebook" value="<?php echo htmlspecialchars($user['facebook_url']); ?>" placeholder="https://www.facebook.com/your-profile-link-here" />

                <label for="twitter">Twitter:</label>
                <input type="url" id="twitter" value="<?php echo htmlspecialchars($user['twitter_url']); ?>" placeholder="https://www.twitter.com/your-username-here" />
            </fieldset>

            <button type="submit">Save Profile</button>
            <p id="message"></p>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('profile-pic-preview');
            const overlayText = document.getElementById('overlay-text');
            preview.src = URL.createObjectURL(event.target.files[0]);
            overlayText.textContent = '';
        }

        function addSkill() {
            const skillInput = document.getElementById('skill');
            const skillsList = document.getElementById('skills-list');
            if (skillInput.value) {
                const li = document.createElement('li');
                li.textContent = skillInput.value;
                skillsList.appendChild(li);
                skillInput.value = '';
            }
        }

        function addCourse() {
            const courseInput = document.getElementById('course');
            const coursesList = document.getElementById('courses-list');
            if (courseInput.value) {
                const li = document.createElement('li');
                li.textContent = courseInput.value;
                coursesList.appendChild(li);
                courseInput.value = '';
            }
        }

        function addInternship() {
            const internshipInput = document.getElementById('internship');
            const internshipsList = document.getElementById('internships-list');
            if (internshipInput.value) {
                const li = document.createElement('li');
                li.textContent = internshipInput.value;
                internshipsList.appendChild(li);
                internshipInput.value = '';
            }
        }

        function addJob() {
            const jobInput = document.getElementById('job');
            const jobsList = document.getElementById('jobs-list');
            if (jobInput.value) {
                const li = document.createElement('li');
                li.textContent = jobInput.value;
                jobsList.appendChild(li);
                jobInput.value = '';
            }
        }

        document.getElementById('profile-form').addEventListener('submit', function (event) {
            event.preventDefault();
            document.getElementById('message').textContent = 'Profile saved successfully!';
        });
    </script>
</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
