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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php"); // Redirect to registration if not logged in
    exit();
}

// Retrieve saved user data from the session
$fullname = $_SESSION['fullname'];
$email = $_SESSION['email'];

$first_name = explode(' ', $fullname)[0]; // Extract first name
$last_name = explode(' ', $fullname)[1] ?? ''; // Extract last name

// If the form is submitted, process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $user_type = $_POST['user_type'];
    $bio = $_POST['bio'];
    $graduation_college = $_POST['graduation_college'];
    $graduation_course = $_POST['graduation_course'];
    $graduation_year = $_POST['graduation_year'];
    $post_graduation_college = $_POST['post_graduation_college'];
    $post_graduation_course = $_POST['post_graduation_course'];
    $phd_college = $_POST['phd_college'];
    $phd_course = $_POST['phd_course'];
    $linkedin_url = $_POST['linkedin'];
    $github_url = $_POST['github'];
    $instagram_url = $_POST['instagram'];
    $facebook_url = $_POST['facebook'];
    $twitter_url = $_POST['twitter'];

    // Handling skills, courses, internships, and jobs
    $skills = isset($_POST['skills']) ? implode(", ", $_POST['skills']) : '';
    $courses = isset($_POST['courses']) ? implode(", ", $_POST['courses']) : '';
    $internships = isset($_POST['internships']) ? implode(", ", $_POST['internships']) : '';
    $jobs = isset($_POST['jobs']) ? implode(", ", $_POST['jobs']) : '';

    // Profile picture upload
    $profile_pic = "default-profile.jpg"; // Default profile picture
    if (isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != '') {
        $profile_pic = 'uploads/' . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic);
    }

    // Insert profile data into the user_profiles table
    $sql = "INSERT INTO user_profiles (user_id, first_name, last_name, email, user_type, bio, graduation_college, graduation_course, graduation_year, 
            post_graduation_college, post_graduation_course, phd_college, phd_course, linkedin_url, github_url, instagram_url, facebook_url, twitter_url, 
            skills, courses, internships, work_experience, profile_pic)
            VALUES ('$user_id', '$first_name', '$last_name', '$email', '$user_type', '$bio', '$graduation_college', '$graduation_course', '$graduation_year', 
            '$post_graduation_college', '$post_graduation_course', '$phd_college', '$phd_course', '$linkedin_url', '$github_url', '$instagram_url', '$facebook_url', 
            '$twitter_url', '$skills', '$courses', '$internships', '$jobs', '$profile_pic')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to homepage after saving the profile
        header("Location: home.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Print SQL error for debugging
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Profile</title>
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
        input[type="text"], input[type="email"], input[type="file"], select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
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
            background-color: #2b80ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Profile</h1>
        <form id="profile-form" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Profile Picture</legend>
                <label for="profile-pic">Upload Profile Picture:</label>
                <input type="file" id="profile-pic" name="profile_pic" accept="image/*" />
            </fieldset>

            <fieldset>
                <legend>Personal Information</legend>
                <label for="user_type">Are you an Active Student or Alumni?</label>
                <select id="user_type" name="user_type" required>
                    <option value="">Select...</option>
                    <option value="Active Student">Active Student</option>
                    <option value="Alumni">Alumni</option>
                </select>

                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first-name" value="<?php echo $first_name; ?>" required />

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name" value="<?php echo $last_name; ?>" required />

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly />

                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" rows="4"></textarea>
            </fieldset>

            <fieldset>
                <legend>Academic Details</legend>
                <label for="graduation-college">Graduation College:</label>
                <input type="text" id="graduation-college" name="graduation_college" />

                <label for="graduation-course">Graduation Course:</label>
                <input type="text" id="graduation-course" name="graduation_course" />

                <label for="graduation-year">Graduation Year:</label>
                <input type="text" id="graduation-year" name="graduation_year" />

                <label for="post-graduation-college">Post-Graduation College:</label>
                <input type="text" id="post-graduation-college" name="post_graduation_college" />

                <label for="post-graduation-course">Post-Graduation Course:</label>
                <input type="text" id="post-graduation-course" name="post_graduation_course" />

                <label for="phd-college">PhD College:</label>
                <input type="text" id="phd-college" name="phd_college" />

                <label for="phd-course">PhD Course:</label>
                <input type="text" id="phd-course" name="phd_course" />
            </fieldset>

            <fieldset>
                <legend>Skills</legend>
                <input type="text" id="skill" name="skills[]" placeholder="Enter a skill" />
                <button type="button" onclick="addSkill()">Add Skill</button>
                <ul id="skills-list"></ul>
            </fieldset>

            <fieldset>
                <legend>Courses</legend>
                <input type="text" id="course" name="courses[]" placeholder="Enter a course" />
                <button type="button" onclick="addCourse()">Add Course</button>
                <ul id="courses-list"></ul>
            </fieldset>
            <!-- Internships Section -->
            <fieldset>
                <legend>Internships</legend>
                <input type="text" id="internship" placeholder="Enter an internship" />
                <button type="button" onclick="addInternship()">Add Internship</button>
                <ul id="internships-list"></ul>
            </fieldset>

            <!-- Work Experience Section -->
            <fieldset>
                <legend>Work Experiences</legend>
                <input type="text" id="job" placeholder="Enter a job experience" />
                <button type="button" onclick="addJob()">Add Job</button>
                <ul id="jobs-list"></ul>
            </fieldset>

            <!-- Social Media Links Section -->
            <fieldset>
                <legend>Social Media</legend>
                <label for="linkedin">LinkedIn:</label>
                <input type="url" id="linkedin" placeholder="https://www.linkedin.com/in/your-full-profile-link-here" />

                <label for="github">GitHub:</label>
                <input type="url" id="github" placeholder="https://www.github.com/your-username-here" />

                <label for="instagram">Instagram:</label>
                <input type="url" id="instagram" placeholder="https://www.instagram.com/your-username-here" />

                <label for="facebook">Facebook:</label>
                <input type="url" id="facebook" placeholder="https://www.facebook.com/your-profile-link-here" />

                <label for="twitter">Twitter:</label>
                <input type="url" id="twitter" placeholder="https://www.twitter.com/your-username-here" />
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
            document.getElementById('message').textContent = 'Prof<script>
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
        // Remove event.preventDefault() to allow form submission
        event.preventDefault(); // Comment this out if you want the form to submit

        // Optionally: Show success message
        document.getElementById('message').textContent = 'Profile saved successfully!';

        // Uncomment below to actually submit the form after your validations
        this.submit(); // Submit the form after processing, if validation is successful
    });
</script>

    </script>
</body>
</html>
