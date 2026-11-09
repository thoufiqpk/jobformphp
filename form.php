<?php
$conn = new mysqli("localhost", "root", "", "job_application");

if ($conn->connect_error) {
    die("Database connection failed");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Name validation
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $message = "Invalid name!";
    }

    // Email validation
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address!";
    }

    // Mobile validation
    elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $message = "Mobile number must contain 10 digits!";
    }

    // Username validation
    elseif (strlen($username) < 4) {
        $message = "Username must contain at least 4 characters!";
    }

    // Password validation
    elseif (
        strlen($password) < 8 ||
        !preg_match("/[0-9]/", $password) ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password)
    ) {
        $message = "Password must have 8 characters, one number,
                    one uppercase and one lowercase letter!";
    }

    else {
        // Hash password
        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Insert into database
        $stmt = $conn->prepare(
            "INSERT INTO applicants
            (name, email, mobile, username, password)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssss",
            $name,
            $email,
            $mobile,
            $username,
            $hashed_password
        );

        if ($stmt->execute()) {
            $message = "Application submitted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Application Form</title>

    <style>
        body {
            font-family: Arial;
            background: #e8f0fe;
        }

        .container {
            width: 350px;
            margin: 50px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 15px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Job Application Form</h2>

    <form method="POST" action="">

        <input type="text" name="name"
        placeholder="Full Name" required>

        <input type="email" name="email"
        placeholder="Email Address" required>

        <input type="text" name="mobile"
        placeholder="Mobile Number" required>

        <input type="text" name="username"
        placeholder="Username" required>

        <input type="password" name="password"
        placeholder="Password" required>

        <button type="submit">Submit Application</button>

    </form>

    <div class="message">
        <?php echo htmlspecialchars($message); ?>
    </div>

</div>

</body>
</html>