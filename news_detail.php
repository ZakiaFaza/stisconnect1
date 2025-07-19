<?php 
require_once 'config/database.php';
$news_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT n.*, u.full_name as author_name FROM news n JOIN users u ON n.author_id = u.id WHERE n.id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();
if(!$news) { header("Location: news.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title><?php echo htmlspecialchars($news['title']); ?> - STISConnect</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/news.css">
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main class="page-container article-container">
        <article class="reveal">
            <header class="article-header">
                <span class="category"><?php echo ucwords(str_replace('_', ' ', $news['category'])); ?></span>
                <h1><?php echo htmlspecialchars($news['title']); ?></h1>
                <p class="meta">Oleh <?php echo htmlspecialchars($news['author_name']); ?> • <?php echo date('d F Y', strtotime($news['created_at'])); ?></p>
            </header>
            <img src="<?php echo htmlspecialchars($news['image_url']); ?>" alt="Gambar Utama" class="article-image">
            <div class="article-content">
                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
            </div>
        </article>
    </main>
    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>