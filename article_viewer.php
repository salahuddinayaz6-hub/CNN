<?php
include 'config_database.php';
 
if (!isset($_GET['id'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
 
$id = $_GET['id'];
 
// Increment views
$conn->query("UPDATE articles SET views = views + 1 WHERE id = $id");
 
// Fetch article details
$stmt = $conn->prepare("SELECT a.*, c.name as cat_name, u.full_name as author_name FROM articles a JOIN categories c ON a.category_id = c.id LEFT JOIN users u ON a.author_id = u.id WHERE a.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
 
if ($result->num_rows == 0) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
 
$article = $result->fetch_assoc();
 
// Fetch related news
$related_query = "SELECT id, title, image_url FROM articles WHERE category_id = " . $article['category_id'] . " AND id != $id LIMIT 4";
$related_result = $conn->query($related_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['title']; ?> - CNN</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --cnn-red: #cc0000;
            --cnn-black: #000000;
            --cnn-gray: #262626;
            --cnn-light-gray: #f2f2f2;
            --cnn-dark-gray: #4d4d4d;
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
            line-height: 1.8;
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
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
 
        .article-header {
            margin-bottom: 30px;
        }
 
        .category-tag {
            color: var(--cnn-red);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
        }
 
        h1 {
            font-size: 48px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 20px;
            color: var(--cnn-black);
        }
 
        .article-meta {
            font-size: 14px;
            color: var(--cnn-dark-gray);
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            margin-bottom: 30px;
        }
 
        .featured-image {
            width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 30px;
        }
 
        .article-content {
            font-size: 18px;
            color: #333;
        }
 
        .article-content p {
            margin-bottom: 20px;
        }
 
        /* Sidebar Like Related Section */
        .related-section {
            margin-top: 60px;
            border-top: 2px solid var(--cnn-red);
            padding-top: 30px;
        }
 
        .related-title {
            font-size: 24px;
            font-weight: 900;
            margin-bottom: 25px;
        }
 
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
 
        .related-card {
            text-decoration: none;
            color: var(--cnn-black);
        }
 
        .related-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }
 
        .related-card h4 {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
        }
 
        .related-card:hover h4 {
            color: var(--cnn-red);
        }
 
        footer {
            background-color: var(--cnn-black);
            color: #ccc;
            padding: 30px 20px;
            text-align: center;
            margin-top: 100px;
        }
 
        @media (max-width: 768px) {
            h1 {
                font-size: 32px;
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
        <article>
            <div class="article-header">
                <span class="category-tag"><?php echo $article['cat_name']; ?></span>
                <h1><?php echo $article['title']; ?></h1>
                <div class="article-meta">
                    By <strong><?php echo $article['author_name'] ?: 'CNN Reporter'; ?></strong> | Published <?php echo date('F j, Y, g:i a', strtotime($article['created_at'])); ?> | Views: <?php echo $article['views']; ?>
                </div>
            </div>
 
            <img src="<?php echo $article['image_url'] ?: 'https://images.unsplash.com/photo-1585829365294-4c11f8ca0121?auto=format&fit=crop&w=1200&q=80'; ?>" alt="Article Image" class="featured-image">
 
            <div class="article-content">
                <?php echo nl2br($article['content']); ?>
            </div>
        </article>
 
        <section class="related-section">
            <h2 class="related-title">Related Stories</h2>
            <div class="related-grid">
                <?php while($related = $related_result->fetch_assoc()): ?>
                <a href="article_viewer.php?id=<?php echo $related['id']; ?>" class="related-card">
                    <img src="<?php echo $related['image_url'] ?: 'https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=400&q=80'; ?>" alt="Related">
                    <h4><?php echo $related['title']; ?></h4>
                </a>
                <?php endwhile; ?>
            </div>
        </section>
    </div>
 
    <footer>
        &copy; 2026 CNN Clone - All rights reserved.
    </footer>
 
</body>
</html>
 
