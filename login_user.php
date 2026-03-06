<?php
include 'config_database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            $success = "Login successful! Redirecting...";
            echo "<script>setTimeout(() => { window.location.href = 'index.php'; }, 1000);</script>";
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CNN</title>
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
            height: 100vh;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            font-size: 32px;
            font-weight: 900;
            color: var(--cnn-black);
            text-decoration: none;
            display: inline-block;
            margin-bottom: 30px;
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
            margin-bottom: 20px;
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

        .form-group input:focus {
            border-color: var(--cnn-red);
            outline: none;
        }

        .login-btn {
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
        }

        .login-btn:hover {
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

    <div class="login-container">
        <a href="index.php" class="logo"><span>CNN</span></a>
        <h2>Log In</h2>

        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="success-msg"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="login_user.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" name="login" class="login-btn">Continue</button>
        </form>

        <p class="footer-text">Don't have an account? <a href="signup_user.php">Sign Up</a></p>
        <p class="footer-text" style="margin-top: 10px; font-size: 12px;">Admin Test: admin@cnn.com / 123456</p>
    </div>

</body>
</html>
