<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentConnect - FAQs</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #7bc1ed, #bbd1f8);
            color: #333;
            margin: 0;
            padding: 0;
        }
        .faq-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 30px;
            color: #0c45f1;
        }
        .faq-section {
            margin-bottom: 20px;
        }
        .faq-question {
            background-color: #0c45f1;
            color: #ffffff;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
            transition: background-color 0.3s;
        }
        .faq-question:hover {
            background-color: #2b80ff;
        }
        .faq-answer {
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin-top: 5px;
            display: none;
            font-size: 16px;
            color: #555;
        }
        .faq-question.active + .faq-answer {
            display: block;
        }
    </style>
</head>
<body>

<div class="faq-container">
    <h1>Frequently Asked Questions</h1>

    <div class="faq-section">
        <button class="faq-question">Who can join StudentConnect?
            ?</button>
        <div class="faq-answer">
            <p>Any student currently enrolled in a college or university can join StudentConnect. Some features may also be available to recent graduates or alumni.
            </p>
        </div>
    </div>

    <div class="faq-section">
        <button class="faq-question">Can I delete my account?
        </button>
        <div class="faq-answer">
            <p>No, you can't delete your account.
            </p>
        </div>
    </div>
    <div class="faq-section">
        <button class="faq-question">How do I send a connection request to another student?

        </button>
        <div class="faq-answer">
            <p>When viewing another student’s profile, click on the “Follow” button to send a connection request. Once accepted, you’ll be able to chat and collaborate.

            </p>
        </div>
    </div>
    <div class="faq-section">
        <button class="faq-question">Is there a messaging system for communication?

        </button>
        <div class="faq-answer">
            <p>Yes, once you are connected with another student, you can use our built-in messaging feature to communicate directly.

            </p>
        </div>
    </div>

    <div class="faq-section">
        <button class="faq-question">Is StudentConnect free to use?</button>
        <div class="faq-answer">
            <p>Yes, StudentConnect is free to use for students. Some premium features might require a fee.</p>
        </div>
    </div>

    <div class="faq-section">
        <button class="faq-question">
            Can I filter students based on specific criteria (e.g., same course, university)?</button>
        <div class="faq-answer">
            <p>Yes, StudentConnect offers search filters to find students based on courses, projects, skills, or interests</p>
        </div>
    </div>

    <div class="faq-section">
        <button class="faq-question">How is my personal information protected?
        </button>
        <div class="faq-answer">
            <p>StudentConnect values your privacy and uses encryption and other security measures to protect your personal information. We do not share your details without your consent.

            </p>
        </div>
    </div>

    <div class="faq-section">
        <button class="faq-question">I’m having trouble logging in. What should I do?</button>
        <div class="faq-answer">
            <p>Ensure that your email and password are correct. If you are still having trouble, try resetting your password or contact support for further assistance.
            </p>
        </div>
    </div>
</div>

<script>
    // Get all FAQ questions
    const faqQuestions = document.querySelectorAll('.faq-question');

    // Loop through the FAQ questions and add click event listener
    faqQuestions.forEach((question) => {
        question.addEventListener('click', () => {
            // Toggle the active class to open/close the answer
            question.classList.toggle('active');

            // Get the answer related to the clicked question
            const answer = question.nextElementSibling;

            // Toggle the display of the answer
            if (answer.style.display === "block") {
                answer.style.display = "none";
            } else {
                answer.style.display = "block";
            }
        });
    });
</script>

</body>
</html>