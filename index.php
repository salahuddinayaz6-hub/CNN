<?php
include 'config_database.php';
 
// Fetch categories for the navigation
$cat_query = "SELECT * FROM categories";
$cat_result = $conn->query($cat_query);
 
// Fetch articles for the homepage
$articles_query = "SELECT a.*, c.name as cat_name FROM articles a JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC LIMIT 10";
$articles_result = $conn->query($articles_query);
 
// Separate main article and side articles
$main_article = null;
$side_articles = [];
if ($articles_result->num_rows > 0) {
    $main_article = $articles_result->fetch_assoc();
    while ($row = $articles_result->fetch_assoc()) {
        $side_articles[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN - Breaking News, Latest News and Videos</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --cnn-red: #cc0000;
            --cnn-black: #000000;
            --cnn-gray: #262626;
            --cnn-light-gray: #f2f2f2;
            --cnn-dark-gray: #4d4d4d;
            --text-color: #333;
        }
 
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
 
        body {
            background-color: #fff;
            color: var(--text-color);
            line-height: 1.6;
        }
 
        /* Header Styles */
        header {
            background-color: var(--cnn-black);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
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
 
        .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
        }
 
        .nav-links li a {
            color: #ccc;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            transition: color 0.3s;
        }
 
        .nav-links li a:hover {
            color: white;
        }
 
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
 
        .search-bar {
            background: #333;
            border: none;
            padding: 5px 10px;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }
 
        .auth-btn {
            background-color: var(--cnn-dark-gray);
            color: white;
            border: none;
            padding: 5px 15px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }
 
        .auth-btn:hover {
            background-color: var(--cnn-red);
        }
 
        /* Secondary Nav */
        .secondary-nav {
            background-color: white;
            border-bottom: 2px solid var(--cnn-red);
            padding: 5px 20px;
            overflow-x: auto;
            white-space: nowrap;
        }
 
        .secondary-nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }
 
        .secondary-nav ul li a {
            text-decoration: none;
            color: var(--cnn-dark-gray);
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
        }
 
        .secondary-nav ul li a:hover {
            color: var(--cnn-red);
        }
 
        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
 
        .hero-section {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            margin-bottom: 40px;
        }
 
        .main-hero {
            position: relative;
        }
 
        .main-hero img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 4px;
        }
 
        .hero-content {
            padding: 15px 0;
        }
 
        .hero-tag {
            background-color: var(--cnn-red);
            color: white;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 10px;
        }
 
        .hero-title {
            font-size: 36px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 15px;
            color: var(--cnn-black);
            text-decoration: none;
        }
 
        .hero-title:hover {
            text-decoration: underline;
        }
 
        .hero-excerpt {
            font-size: 18px;
            color: var(--cnn-dark-gray);
        }
 
        /* Side List */
        .side-news {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
 
        .side-news-item {
            display: flex;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
 
        .side-news-item img {
            width: 100px;
            height: 65px;
            object-fit: cover;
            border-radius: 2px;
        }
 
        .side-news-info h3 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 5px;
            line-height: 1.3;
        }
 
        .side-news-info a {
            text-decoration: none;
            color: var(--cnn-black);
        }
 
        .side-news-info a:hover {
            color: var(--cnn-red);
        }
 
        /* News Grid */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
 
        .news-card {
            display: flex;
            flex-direction: column;
        }
 
        .news-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }
 
        .news-card h4 {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 10px;
        }
 
        .news-card a {
            text-decoration: none;
            color: var(--cnn-black);
        }
 
        .news-card a:hover {
            text-decoration: underline;
        }
 
        .news-card .cat {
            color: var(--cnn-red);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
 
        /* Footer */
        footer {
            background-color: var(--cnn-black);
            color: #ccc;
            padding: 50px 20px;
            margin-top: 50px;
        }
 
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
 
        .footer-section h4 {
            color: white;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-size: 14px;
        }
 
        .footer-links {
            list-style: none;
        }
 
        .footer-links li {
            margin-bottom: 10px;
        }
 
        .footer-links li a {
            color: #999;
            text-decoration: none;
            font-size: 13px;
        }
 
        .footer-links li a:hover {
            color: white;
        }
 
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #333;
            margin-top: 30px;
            font-size: 12px;
        }
 
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                grid-template-columns: 1fr;
            }
            header {
                flex-direction: column;
                gap: 10px;
            }
            .nav-links {
                display: none;
            }
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
 
    <header>
        <a href="index.php" class="logo"><span>CNN</span></a>
 
        <ul class="nav-links">
            <li><a href="category_explorer.php?slug=politics">Politics</a></li>
            <li><a href="category_explorer.php?slug=business">Business</a></li>
            <li><a href="category_explorer.php?slug=technology">Tech</a></li>
            <li><a href="category_explorer.php?slug=entertainment">Entertainment</a></li>
            <li><a href="category_explorer.php?slug=sports">Sports</a></li>
        </ul>
 
        <div class="header-actions">
            <input type="text" class="search-bar" placeholder="Search News...">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="admin_dashboard_news.php" class="auth-btn">Dashboard</a>
                <a href="logout_session.php" class="auth-btn">Logout</a>
            <?php else: ?>
                <a href="login_user.php" class="auth-btn">Login</a>
            <?php endif; ?>
        </div>
    </header>
 
    <nav class="secondary-nav">
        <ul>
            <?php 
            if ($cat_result->num_rows > 0) {
                while($cat = $cat_result->fetch_assoc()) {
                    echo '<li><a href="category_explorer.php?slug='.$cat['slug'].'">'.$cat['name'].'</a></li>';
                }
            }
            ?>
        </ul>
    </nav>
 
    <div class="container">
 
        <?php if ($main_article): ?>
        <section class="hero-section">
            <div class="main-hero">
                <a href="article_viewer.php?id=<?php echo $main_article['id']; ?>">
                    <img src="<?php echo $main_article['image_url'] ?: 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=80'; ?>" alt="Hero Image">
                </a>
                <div class="hero-content">
                    <span class="hero-tag"><?php echo $main_article['cat_name']; ?></span>
                    <a href="article_viewer.php?id=<?php echo $main_article['id']; ?>" class="hero-title">
                        <h1><?php echo $main_article['title']; ?></h1>
                    </a>
                    <p class="hero-excerpt"><?php echo $main_article['excerpt']; ?></p>
                </div>
            </div>
 
            <div class="side-news">
                <h2 style="font-size: 20px; border-bottom: 2px solid var(--cnn-red); padding-bottom: 5px; margin-bottom: 15px;">Latest Stories</h2>
                <?php foreach(array_slice($side_articles, 0, 5) as $side): ?>
                <div class="side-news-item">
                    <img src="<?php echo $side['image_url'] ?: 'https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?auto=format&fit=crop&w=300&q=80'; ?>" alt="Side News">
                    <div class="side-news-info">
                        <h3><a href="article_viewer.php?id=<?php echo $side['id']; ?>"><?php echo $side['title']; ?></a></h3>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
 
        <section class="news-grid">
            <?php foreach(array_slice($side_articles, 5) as $grid): ?>
            <div class="news-card">
                <a href="article_viewer.php?id=<?php echo $grid['id']; ?>">
                    <img src="<?php echo $grid['image_url'] ?: 'https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=400&q=80'; ?>" alt="Grid News">
                </a>
                <span class="cat"><?php echo $grid['cat_name']; ?></span>
                <h4><a href="article_viewer.php?id=<?php echo $grid['id']; ?>"><?php echo $grid['title']; ?></a></h4>
            </div>
            <?php endforeach; ?>
        </section>
        <?php else: ?>
            <div style="text-align: center; padding: 100px 0;">
                <h2 style="font-size: 32px; color: var(--cnn-dark-gray);">No news articles found.</h2>
                <p>Welcome to CNN Clone. Please add some articles via the admin dashboard.</p>
                <a href="login_user.php" class="auth-btn" style="display: inline-block; margin-top: 20px; padding: 10px 20px;">Get Started</a>
            </div>
        <?php endif; ?>
 
    </div>
 
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <a href="index.php" class="logo" style="margin-bottom: 20px; display: inline-block;"><span>CNN</span></a>
                <p style="font-size: 12px; line-height: 1.6;">&copy; 2026 Cable News Network. A Warner Bros. Discovery Company. All Rights Reserved. CNN Sans &copy; 2016 Cable News Network.</p>
            </div>
            <div class="footer-section">
                <h4>Sections</h4>
                <ul class="footer-links">
                    <li><a href="#">Politics</a></li>
                    <li><a href="#">Business</a></li>
                    <li><a href="#">Health</a></li>
                    <li><a href="#">Entertainment</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>About</h4>
                <ul class="footer-links">
                    <li><a href="#">CNN Business</a></li>
                    <li><a href="#">CNN Health</a></li>
                    <li><a href="#">TV Schedule</a></li>
                    <li><a href="#">CNN Pressroom</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Policy</h4>
                <ul class="footer-links">
                    <li><a href="#">Terms of Use</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">AdChoices</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            CNN Clone - Developed for Educational Purposes
        </div>
    </footer>
 
    <script>
        function redirectTo(url) {
            window.location.href = url;
        }
 
        // Example of search functionality
        document.querySelector('.search-bar').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const query = this.value;
                if (query) {
                    redirectTo('category_explorer.php?search=' + encodeURIComponent(query));
                }
            }
        });
    </script>
</body>
</html>
 
