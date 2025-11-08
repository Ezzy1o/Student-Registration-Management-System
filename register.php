<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
require 'conn.php';

$message = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    
    if (strlen($password) < 8) {
        $message = "<div class='alert error'>⚠ Password must be at least 8 characters long.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert error'>⚠ Invalid email format.</div>";
    } elseif (empty($fullname) || !preg_match("/^[a-zA-Z ]+$/", $fullname)) {
        $message = "<div class='alert error'>⚠ Full Name must contain only letters and spaces.</div>";
    } elseif (empty($username) || !preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $message = "<div class='alert error'>⚠ Username must contain only letters, numbers, and underscores.</div>";
    } else {
        
        $result = register($fullname, $username, $email, $password);

        if ($result === true) {
            $message = "<div class='alert success'>✅ Registration successful! Redirecting to login...</div>";
            echo "<meta http-equiv='refresh' content='2;url=login.php'>"; // auto redirect lepas 2s
        } else {
            $message = "<div class='alert error'>⚠ Registration failed: " . htmlspecialchars($result) . "</div>";
        }
    }
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Account</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            animation: fadeIn 0.6s ease;
        }

        .header {
            background-color: #1e3c72;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 1.8em;
            margin-bottom: 5px;
        }

        .form-container {
            padding: 35px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #1e3c72;
            outline: none;
        }

        .btn {
            background: #1e3c72;
            color: white;
            padding: 12px 0;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #2a5298;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30,60,114,0.3);
        }

        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #1e3c72;
            font-weight: bold;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Create New Account</h1>
            <p>Register to access the student system</p>
        </div>

        <div class="form-container">
            <?php if (!empty($message)) echo $message; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Register Account</button>
            </form>

            <div class="links">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
