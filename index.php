<?php
require_once 'config/config.php';
require_once 'config/database.php';

$conn = getDBConnection();

// Get featured packages
$featured_packages = $conn->query("SELECT * FROM packages WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 6")->fetch_all(MYSQLI_ASSOC);

// Get all active packages for packages section
$all_packages = $conn->query("SELECT * FROM packages WHERE status = 'active' ORDER BY created_at DESC LIMIT 6")->fetch_all(MYSQLI_ASSOC);

// Get site content
$contents = $conn->query("SELECT content_key, content_value FROM site_content")->fetch_all(MYSQLI_ASSOC);
$content_array = [];
foreach ($contents as $content) {
    $content_array[$content['content_key']] = $content['content_value'];
}

// Get contact info
$contact = $conn->query("SELECT * FROM contact_info WHERE id=1")->fetch_assoc();

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php 
    if (!defined('BASE_URL')) {
        define('BASE_URL', 'http://localhost:8000/');
    }
    include 'includes/header.php'; 
    ?>
    
    <!-- Hero Banner -->
    <section class="hero-banner" style="background-image: url('<?php echo isset($content_array['banner_image']) ? BASE_URL . $content_array['banner_image'] : 'assets/images/default-banner.jpg'; ?>');">
        <div class="banner-overlay">
            <div class="container">
                <h1><?php echo htmlspecialchars($content_array['home_banner_title'] ?? 'Discover Amazing Travel Packages'); ?> 🌟</h1>
                <p><?php echo htmlspecialchars($content_array['home_banner_subtitle'] ?? 'Explore the world with Avipro Travels'); ?></p>
                <a href="packages.php" class="btn btn-primary btn-large">Let's Go! 🚀</a>
            </div>
        </div>
    </section>
    
    <!-- Featured Packages -->
    <section class="packages-section">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($content_array['home_featured_text'] ?? 'Featured Travel Packages'); ?> 🔥</h2>
            
            <?php if (empty($featured_packages) && !empty($all_packages)): ?>
                <?php $featured_packages = $all_packages; ?>
            <?php endif; ?>
            
            <?php if (empty($featured_packages)): ?>
                <p class="text-center">No packages available at the moment. Please check back later.</p>
            <?php else: ?>
                <div class="packages-grid">
                    <?php foreach ($featured_packages as $package): ?>
                        <div class="package-card">
                            <?php if ($package['image']): ?>
                                <div class="package-image">
                                    <img src="<?php echo BASE_URL . htmlspecialchars($package['image']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>">
                                    <?php if ($package['discount_price']): ?>
                                        <span class="discount-badge">Save <?php echo round((($package['price'] - $package['discount_price']) / $package['price']) * 100); ?>%</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="package-content">
                                <h3><?php echo htmlspecialchars($package['title']); ?></h3>
                                <p class="package-destination">📍 <?php echo htmlspecialchars($package['destination']); ?></p>
                                <p class="package-duration">⏱️ <?php echo htmlspecialchars($package['duration']); ?></p>
                                <div class="package-price">
                                    <?php if ($package['discount_price']): ?>
                                        <span class="old-price"><?php echo formatCurrency($package['price']); ?></span>
                                        <span class="current-price"><?php echo formatCurrency($package['discount_price']); ?></span>
                                    <?php else: ?>
                                        <span class="current-price"><?php echo formatCurrency($package['price']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="package-description"><?php echo htmlspecialchars(substr($package['description'], 0, 100)) . '...'; ?></p>
                                <div class="package-actions">
                                    <a href="package-details.php?id=<?php echo $package['id']; ?>" class="btn btn-primary">View Details</a>
                                    <a href="booking.php?package=<?php echo $package['id']; ?>" class="btn btn-secondary">Book Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center" style="margin-top: 40px;">
                    <a href="packages.php" class="btn btn-primary">View All Packages</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Why Choose Us -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Why Choose Avipro Travels? ✨</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">🌍</div>
                    <h3>Epic Destinations</h3>
                    <p>Handpicked travel experiences to the world's most Instagram-worthy spots</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💸</div>
                    <h3>Best Deals</h3>
                    <p>Fire prices with exclusive discounts that won't break your bank</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔒</div>
                    <h3>Safe & Secure</h3>
                    <p>Your safety is our vibe - we've got you covered 24/7</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💬</div>
                    <h3>Always Here</h3>
                    <p>Round-the-clock support because your travel dreams don't sleep</p>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
</body>
</html>

