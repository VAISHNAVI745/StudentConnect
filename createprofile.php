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
    header("Location: registration.php");
    exit();
}

// Retrieve user data from the session
$fullname = $_SESSION['fullname'];
$email = $_SESSION['email'];
$first_name = explode(' ', $fullname)[0]; 
$last_name = explode(' ', $fullname)[1] ?? ''; 

// If the form is submitted, process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $user_type = $_POST['user_type'];
    $bio = $_POST['bio'];
    $graduation_college = $_POST['graduation_college'];
    $graduation_course = $_POST['graduation_course'];
    $graduation_year = $_POST['graduation_year'];
    $post_graduation_college = $_POST['post_graduation_college'] ?? '';
    $post_graduation_course = $_POST['post_graduation_course'] ?? '';
    $phd_college = $_POST['phd_college'] ?? '';
    $phd_course = $_POST['phd_course'] ?? '';
    $linkedin_url = $_POST['linkedin'] ?? '';
    $github_url = $_POST['github'] ?? '';
    $instagram_url = $_POST['instagram'] ?? '';
    $facebook_url = $_POST['facebook'] ?? '';
    $twitter_url = $_POST['twitter'] ?? '';

    // Handling skills, courses, internships, and jobs
    $skills = isset($_POST['skills']) ? json_decode($_POST['skills'], true) : [];
    $courses = isset($_POST['courses']) ? json_decode($_POST['courses'], true) : [];
    $internships = isset($_POST['internships']) ? json_decode($_POST['internships'], true) : [];
    $jobs = isset($_POST['jobs']) ? json_decode($_POST['jobs'], true) : [];

    // Profile picture upload
    $profile_pic = "default-profile.jpg"; // Default profile picture
    if (isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != '') {
        $profile_pic = 'uploads/' . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic);
    }

    // Insert profile data into the user_profiles table
    $sql = "INSERT INTO user_profiles (
                user_id, first_name, last_name, email, user_type, bio, graduation_college, graduation_course, graduation_year, 
                post_graduation_college, post_graduation_course, phd_college, phd_course, 
                linkedin_url, github_url, instagram_url, facebook_url, twitter_url, 
                profile_pic
            ) VALUES (
                '$user_id', '$first_name', '$last_name', '$email', '$user_type', '$bio', 
                '$graduation_college', '$graduation_course', '$graduation_year', 
                '$post_graduation_college', '$post_graduation_course', '$phd_college', '$phd_course', 
                '$linkedin_url', '$github_url', '$instagram_url', '$facebook_url', '$twitter_url', 
                '$profile_pic'
            )";

    if ($conn->query($sql) === TRUE) {
        $profile_id = $conn->insert_id;

        handleListSubmission($conn, $profile_id, 'skills', 'skill_name', $skills);
        handleListSubmission($conn, $profile_id, 'internships', 'internship_name', $internships);
        handleListSubmission($conn, $profile_id, 'work_experiences', 'work_details', $jobs);
        handleListSubmission($conn, $profile_id, 'courses', 'course_name', $courses);

        header("Location: home.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

function handleListSubmission($conn, $profile_id, $table, $column, $items) {
    if (is_array($items) && !empty($items)) {
        foreach ($items as $item) {
            $sql = "INSERT INTO $table (profile_id, $column) VALUES ('$profile_id', '$item')";
            $conn->query($sql);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        input, textarea, select, button {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #0c45f1;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #2b80ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Profile</h1>
        <form method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Personal Information</legend>
                <select name="user_type" required>
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

                <textarea name="bio" placeholder="Write a brief bio..."></textarea>
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
                <legend>Profile Picture</legend>
                <input type="file" name="profile_pic" accept="image/*">
            </fieldset>

            <fieldset>
                <legend>Social Media</legend>
                <input type="url" name="linkedin" placeholder="LinkedIn URL">
                <input type="url" name="github" placeholder="GitHub URL">
                <input type="url" name="instagram" placeholder="Instagram URL">
            </fieldset>

            <fieldset id="skills-section">
                <legend>Skills</legend>
                <input type="text" id="skill" placeholder="Enter skill">
                <button type="button" onclick="addItem('skills-list', 'skills-input', 'skill')">Add Skill</button>
                <ul id="skills-list"></ul>
                <input type="hidden" id="skills-input" name="skills">
            </fieldset>

            <fieldset id="internships-section">
                <legend>Internships</legend>
                <input type="text" id="internship" placeholder="Enter internship">
                <button type="button" onclick="addItem('internships-list', 'internships-input', 'internship')">Add Internship</button>
                <ul id="internships-list"></ul>
                <input type="hidden" id="internships-input" name="internships">
            </fieldset>

            <fieldset id="work-section">
                <legend>Work Experiences</legend>
                <input type="text" id="work" placeholder="Enter work experience">
                <button type="button" onclick="addItem('work-list', 'work-input', 'work')">Add Experience</button>
                <ul id="work-list"></ul>
                <input type="hidden" id="work-input" name="work_experiences">
            </fieldset>

            <fieldset id="courses-section">
                <legend>Courses</legend>
                <input type="text" id="course" placeholder="Enter course">
                <button type="button" onclick="addItem('courses-list', 'courses-input', 'course')">Add Course</button>
                <ul id="courses-list"></ul>
                <input type="hidden" id="courses-input" name="courses">
            </fieldset>

            <button type="submit">Save Profile</button>
        </form>
    </div>

    <script>
        function addItem(listId, inputId, fieldId) {
            const field = document.getElementById(fieldId);
            const list = document.getElementById(listId);
            const input = document.getElementById(inputId);

            if (field.value) {
                const li = document.createElement('li');
                li.textContent = field.value;
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'X';
                deleteButton.onclick = () => {
                    list.removeChild(li);
                    updateInput(input, list);
                };
                li.appendChild(deleteButton);
                list.appendChild(li);
                updateInput(input, list);
                field.value = '';
            }
        }

        function updateInput(input, list) {
            const items = Array.from(list.children).map(li => li.textContent.replace('X', '').trim());
            input.value = JSON.stringify(items);
        }
    </script>
     <script>
            function addItem(listId, inputId, fieldId) {
            const field = document.getElementById(fieldId);
            const list = document.getElementById(listId);
            const input = document.getElementById(inputId);

            if (field.value) {
                const li = document.createElement('li');
                li.textContent = field.value;
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'X';
                deleteButton.onclick = () => {
                    list.removeChild(li);
                    updateInput(input, list);
                };
                li.appendChild(deleteButton);
                list.appendChild(li);
                updateInput(input, list);
                field.value = '';
            }
        }

        function updateInput(input, list) {
            const items = Array.from(list.children).map(li => li.textContent.replace('X', '').trim());
            input.value = JSON.stringify(items);
        }
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
</body>
</html>
