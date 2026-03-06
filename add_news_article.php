<?php
include 'config_database.php';
 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    echo "<script>window.location.href = 'login_user.php';</script>";
    exit;
}
 
$success = '';
$error = '';
 
// Fetch categories for the dropdown
$cat_result = $conn->query("SELECT * FROM categories");
 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $excerpt = $_POST['excerpt'];
    $content = $_POST['content'];
    $image_url = $_POST['image_url'];
    $author_id = $_SESSION['user_id'];
    $is_breaking = isset($_POST['is_breaking']) ? 1 : 0;
 
    $stmt = $conn->prepare("INSERT INTO articles (category_id, title, excerpt, content, image_url, author_id, is_breaking) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssisi", $category_id, $title, $excerpt, $content, $image_url, $author_id, $is_breaking);
 
    if ($stmt->execute()) {
        $success = "Article published successfully!";
        echo "<script>setTimeout(() => { window.location.href = 'admin_dashboard_news.php'; }, 1500);</script>";
    } else {
        $error = "Failed to publish article. " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post New Article - CNN Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --cnn-red: #cc0000;
            --cnn-black: #000000;
            --cnn-gray: #262626;
            --cnn-light-gray: #f8f9fa;
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
        }
 
        .sidebar {
            width: 260px;
            background-color: var(--cnn-black);
            height: 100vh;
            color: white;
            padding: 30px 20px;
            position: fixed;
        }
 
        .sidebar .logo {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 40px;
            display: block;
            text-decoration: none;
            color: white;
        }
 
        .sidebar .logo span {
            background-color: var(--cnn-red);
            padding: 0 5px;
        }
 
        .sidebar nav ul {
            list-style: none;
        }
 
        .sidebar nav ul li {
            margin-bottom: 15px;
        }
 
        .sidebar nav ul li a {
            color: #ccc;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 4px;
            transition: 0.3s;
        }
 
        .sidebar nav ul li a:hover {
            background-color: var(--cnn-red);
            color: white;
        }
 
        .main-content {
            margin-left: 260px;
            padding: 40px;
            width: 100%;
        }
 
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            max-width: 800px;
            margin: 0 auto;
        }
 
        h1 {
            font-size: 32px;
            margin-bottom: 30px;
            font-weight: 900;
            border-bottom: 3px solid var(--cnn-red);
            padding-bottom: 10px;
        }
 
        .form-group {
            margin-bottom: 25px;
        }
 
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--cnn-black);
        }
 
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
 
        .form-group textarea {
            height: 150px;
            resize: vertical;
        }
 
        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
 
        .submit-btn {
            background-color: var(--cnn-black);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }
 
        .submit-btn:hover {
            background-color: var(--cnn-red);
        }
 
        .success-msg { color: green; margin-bottom: 20px; }
        .error-msg { color: var(--cnn-red); margin-bottom: 20px; }
    </style>
</head>
<body>
 
    <div class="sidebar">
        <a href="index.php" class="logo"><span>CNN</span> ADMIN</a>
        <nav>
            <ul>
                <li><a href="admin_dashboard_news.php">Dashboard</a></li>
                <li><a href="add_news_article.php" style="background-color: var(--cnn-red); color: white;">Add News</a></li>
                <li><a href="index.php">View Site</a></li>
                <li><a href="logout_session.php">Logout</a></li>
            </ul>
        </nav>
    </div>
 
    <div class="main-content">
        <div class="form-container">
            <h1>Publish New Article</h1>
 
            <?php if($success): ?>
                <div class="success-msg"><?php echo $success; ?></div>
            <?php endif; ?>
 
            <?php if($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>
 
            <form action="add_news_article.php" method="POST">
                <div class="form-group">
                    <label>Article Title</label>
                    <input type="text" name="title" placeholder="Enter a catchy headline" required>
                </div>
 
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" required>
                        <option value="">Select Category</option>
                        <?php while($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
 
                <div class="form-group">
                    <label>Short Excerpt</label>
                    <textarea name="excerpt" placeholder="Short summary for the homepage" style="height: 80px;" required></textarea>
                </div>
 
                <div class="form-group">
                    <label>Full Content</label>
                    <textarea name="content" placeholder="Write your full news story here..." required></textarea>
                </div>
 
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="url" name="image_url" placeholder="https://example.com/image.jpg">
                </div>
 
                <div class="form-group">
                    <label><input type="checkbox" name="is_breaking"> Mark as Breaking News</label>
                </div>
 
                <button type="submit" name="submit" class="submit-btn">Publish Article</button>
            </form>
        </div>
    </div>
 
</body>
</html>
