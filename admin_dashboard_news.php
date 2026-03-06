<?php
include 'config_database.php';
 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    echo "<script>window.location.href = 'login_user.php';</script>";
    exit;
}
 
// Fetch all articles
$query = "SELECT a.*, c.name as cat_name FROM articles a JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC";
$result = $conn->query($query);
 
// Delete article logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM articles WHERE id = $id");
    echo "<script>window.location.href = 'admin_dashboard_news.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CNN</title>
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
 
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
 
        .top-bar h1 {
            font-size: 28px;
            font-weight: 700;
        }
 
        .add-btn {
            background-color: var(--cnn-red);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 700;
        }
 
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
 
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
 
        table th {
            background-color: #f2f2f2;
            font-weight: 700;
            color: var(--cnn-black);
        }
 
        .action-btns {
            display: flex;
            gap: 10px;
        }
 
        .delete-btn {
            color: var(--cnn-red);
            text-decoration: none;
            font-weight: 700;
        }
 
        .edit-btn {
            color: #007bff;
            text-decoration: none;
            font-weight: 700;
        }
 
        .img-preview {
            width: 50px;
            height: 35px;
            object-fit: cover;
            border-radius: 2px;
        }
    </style>
</head>
<body>
 
    <div class="sidebar">
        <a href="index.php" class="logo"><span>CNN</span> ADMIN</a>
        <nav>
            <ul>
                <li><a href="admin_dashboard_news.php">Dashboard</a></li>
                <li><a href="add_news_article.php">Add News</a></li>
                <li><a href="index.php">View Site</a></li>
                <li><a href="logout_session.php">Logout</a></li>
            </ul>
        </nav>
    </div>
 
    <div class="main-content">
        <div class="top-bar">
            <h1>All News Articles</h1>
            <a href="add_news_article.php" class="add-btn">+ Add New Article</a>
        </div>
 
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Views</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?php echo $row['image_url'] ?: 'https://via.placeholder.com/50'; ?>" class="img-preview"></td>
                        <td><?php echo substr($row['title'], 0, 50) . '...'; ?></td>
                        <td><?php echo $row['cat_name']; ?></td>
                        <td><?php echo $row['views']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                        <td class="action-btns">
                            <a href="article_viewer.php?id=<?php echo $row['id']; ?>" class="edit-btn">View</a>
                            <a href="admin_dashboard_news.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #777;">No articles found. Start by adding one!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
 
</body>
</html>
