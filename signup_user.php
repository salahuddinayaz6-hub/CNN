<?php
include 'config_database.php';
 
$error = '';
$success = '';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
 
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
 
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);
 
        if ($stmt->execute()) {
            $success = "Account created successfully! Redirecting to login...";
            echo "<script>setTimeout(() => { window.location.href = 'login_user.php'; }, 1500);</script>";
        } else {
            $error = "Email already exists or registration failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CNN</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --cnn-red: #cc0000;
            --cnn-black: #000000;
            --cnn-gray: #262626;
            --cnn-light-gray: #f2f2f2;
        }
 
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
 
        body {
            background-color: var(--cnn-light-gray);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px 0;
        }
 
        .signup-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
 
        .logo {
            font-size: 32px;
            font-weight: 900;
            color: var(--cnn-black);
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
 
        .logo span {
            background-color: var(--cnn-red);
            color: white;
            padding: 0 5px;
            margin-right: 5px;
        }
 
        h2 {
            margin-bottom: 25px;
            font-weight: 700;
            color: var(--cnn-black);
        }
 
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
 
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 700;
            color: var(--cnn-gray);
        }
 
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
 
        .signup-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--cnn-black);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
 
        .signup-btn:hover {
            background-color: var(--cnn-red);
        }
 
        .error-msg {
            color: var(--cnn-red);
            margin-bottom: 15px;
            font-size: 14px;
        }
 
        .success-msg {
            color: green;
            margin-bottom: 15px;
            font-size: 14px;
        }
 
        .footer-text {
            margin-top: 20px;
            font-size: 14px;
            color: var(--cnn-dark-gray);
        }
 
        .footer-text a {
            color: var(--cnn-red);
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
 
    <div class="signup-container">
        <a href="index.php" class="logo"><span>CNN</span></a>
        <h2>Create Account</h2>
 
        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>
 
        <?php if($success): ?>
            <div class="success-msg"><?php echo $success; ?></div>
        <?php endif; ?>
 
        <form action="signup_user.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" name="signup" class="signup-btn">Create Account</button>
        </form>
 
        <p class="footer-text">Already have an account? <a href="login_user.php">Log In</a></p>
    </div>
 
</body>
</html>
 
Syntax highlighting powered by GeSHi
Help Guide | License
