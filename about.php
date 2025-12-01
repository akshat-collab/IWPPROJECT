<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:8000/');
}
require_once 'config/config.php';
require_once 'config/database.php';

$conn = getDBConnection();
$contents = $conn->query("SELECT content_key, content_value FROM site_content WHERE content_key = 'about_us_content'")->fetch_assoc();
$about_content = $contents['content_value'] ?? '<p>Avipro Travels is a leading travel agency dedicated to providing exceptional travel experiences. We offer carefully curated travel packages to stunning destinations around the world.</p>';
closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="page-header">
        <div class="container">
            <h1>About Us</h1>
            <p>Learn more about Avipro Travels</p>
        </div>
    </section>
    
    <section class="content-section">
        <div class="container">
            <div class="about-content">
                <?php echo $about_content; ?>
            </div>
            
            <div class="about-features">
                <h2>Our Mission</h2>
                <p>To provide exceptional travel experiences that create lasting memories for our customers while promoting sustainable and responsible tourism.</p>
                
                <h2>Our Vision</h2>
                <p>To become the most trusted and preferred travel partner, known for our commitment to quality, customer satisfaction, and innovative travel solutions.</p>
                
                <h2>Why Choose Us?</h2>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">✈️</div>
                        <h3>Expert Team</h3>
                        <p>Our experienced travel consultants are here to help you plan the perfect trip</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🌍</div>
                        <h3>Global Reach</h3>
                        <p>We offer travel packages to destinations worldwide</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">💎</div>
                        <h3>Quality Service</h3>
                        <p>We ensure the highest standards of service and customer care</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🎯</div>
                        <h3>Best Value</h3>
                        <p>Competitive prices with no hidden charges</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
</body>
</html>

