<?php
include 'config_database.php';
 
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$current_category = "Latest News";
 
if ($slug) {
    $stmt = $conn->prepare("SELECT name FROM categories WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $current_category = $row['name'];
    }
 
    $query = "SELECT a.*, c.name as cat_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE c.slug = ? ORDER BY a.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $slug);
} elseif ($search) {
    $current_category = "Search results for: " . htmlspecialchars($search);
    $search_term = "%$search%";
    $query = "SELECT a.*, c.name as cat_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.title LIKE ? OR a.content LIKE ? ORDER BY a.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search_term, $search_term);
} else {
    $query = "SELECT a.*, c.name as cat_name FROM articles a JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC";
    $stmt = $conn->prepare($query);
}
 
$stmt->execute();
$articles_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $current_category; ?> - CNN</title>
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
            background-color: #fff;
            color: #333;
        }
 
        header {
            background-color: var(--cnn-black);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
 
        .logo {
            font-size: 32px;
            font-weight: 900;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
 
        .logo span {
            background-color: var(--cnn-red);
            padding: 0 5px;
            margin-right: 5px;
        }
 
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }
 
        .page-title {
            font-size: 36px;
            font-weight: 900;
            border-bottom: 3px solid var(--cnn-red);
            padding-bottom: 10px;
            margin-bottom: 40px;
            text-transform: uppercase;
        }
 
        .news-list {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
 
        .news-item {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 25px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }
 
        .news-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
        }
 
        .news-info h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
        }
 
        .news-info h2 a {
            text-decoration: none;
            color: var(--cnn-black);
        }
 
        .news-info h2 a:hover {
            color: var(--cnn-red);
        }
 
        .news-meta {
            font-size: 13px;
            color: var(--cnn-dark-gray);
            margin-bottom: 10px;
        }
 
        .news-excerpt {
            color: #555;
            font-size: 16px;
        }
 
        @media (max-width: 768px) {
            .news-item {
                grid-template-columns: 1fr;
            }
            .news-item img {
                height: 200px;
            }
        }
    </style>
</head>
<body>
 
    <header>
        <a href="index.php" class="logo"><span>CNN</span></a>
        <div>
            <a href="index.php" style="color: white; text-decoration: none; font-weight: 700;">Back to Home</a>
        </div>
    </header>
 
    <div class="container">
        <h1 class="page-title"><?php echo $current_category; ?></h1>
 
        <div class="news-list">
            <?php if ($articles_result->num_rows > 0): ?>
                <?php while($article = $articles_result->fetch_assoc()): ?>
                <div class="news-item">
                    <a href="article_viewer.php?id=<?php echo $article['id']; ?>">
                        <img src="<?php echo $article['image_url'] ?: 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=400&q=80'; ?>" alt="News">
                    </a>
                    <div class="news-info">
                        <h2><a href="article_viewer.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></h2>
                        <div class="news-meta">
                            <strong><?php echo $article['cat_name']; ?></strong> | <?php echo date('M j, Y', strtotime($article['created_at'])); ?>
                        </div>
                        <p class="news-excerpt"><?php echo $article['excerpt']; ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="font-size: 20px; color: #777;">No articles found in this category.</p>
            <?php endif; ?>
        </div>
    </div>
 
</body>
</html>
 
