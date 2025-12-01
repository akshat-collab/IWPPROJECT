<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:8000/');
}
require_once 'config/config.php';
require_once 'config/database.php';

$package_id = $_GET['id'] ?? null;

if (!$package_id) {
    header('Location: packages.php');
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ? AND status = 'active'");
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

if (!$package) {
    header('Location: packages.php');
    exit;
}

// Get related packages
$related = $conn->query("SELECT * FROM packages WHERE status = 'active' AND id != $package_id AND destination = '{$package['destination']}' LIMIT 3")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($package['title']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="package-details-section">
        <div class="container">
            <div class="package-details-header">
                <div class="package-image-large">
                    <?php if ($package['image']): ?>
                        <img src="<?php echo BASE_URL . htmlspecialchars($package['image']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>">
                    <?php endif; ?>
                </div>
                <div class="package-info-box">
                    <h1><?php echo htmlspecialchars($package['title']); ?></h1>
                    <p class="package-meta">
                        <span>📍 <?php echo htmlspecialchars($package['destination']); ?></span>
                        <span>⏱️ <?php echo htmlspecialchars($package['duration']); ?></span>
                    </p>
                    <div class="package-price-large">
                        <?php if ($package['discount_price']): ?>
                            <span class="old-price"><?php echo formatCurrency($package['price']); ?></span>
                            <span class="current-price"><?php echo formatCurrency($package['discount_price']); ?></span>
                            <span class="discount-text">Save <?php echo round((($package['price'] - $package['discount_price']) / $package['price']) * 100); ?>%</span>
                        <?php else: ?>
                            <span class="current-price"><?php echo formatCurrency($package['price']); ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="booking.php?package=<?php echo $package['id']; ?>" class="btn btn-primary btn-large">Book Now</a>
                </div>
            </div>
            
            <div class="package-details-content">
                <div class="details-tabs">
                    <button class="tab-btn active" onclick="showTab('description')">Description</button>
                    <?php if ($package['itinerary']): ?>
                        <button class="tab-btn" onclick="showTab('itinerary')">Itinerary</button>
                    <?php endif; ?>
                    <?php if ($package['inclusions'] || $package['exclusions']): ?>
                        <button class="tab-btn" onclick="showTab('inclusions')">Inclusions & Exclusions</button>
                    <?php endif; ?>
                </div>
                
                <div id="description" class="tab-content active">
                    <h2>Package Description</h2>
                    <p><?php echo nl2br(htmlspecialchars($package['description'])); ?></p>
                </div>
                
                <?php if ($package['itinerary']): ?>
                    <div id="itinerary" class="tab-content">
                        <h2>Itinerary</h2>
                        <div class="itinerary-content">
                            <?php echo nl2br(htmlspecialchars($package['itinerary'])); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($package['inclusions'] || $package['exclusions']): ?>
                    <div id="inclusions" class="tab-content">
                        <div class="inclusions-grid">
                            <?php if ($package['inclusions']): ?>
                                <div class="inclusions-box">
                                    <h3>✅ Inclusions</h3>
                                    <div class="inclusions-list">
                                        <?php echo nl2br(htmlspecialchars($package['inclusions'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($package['exclusions']): ?>
                                <div class="exclusions-box">
                                    <h3>❌ Exclusions</h3>
                                    <div class="exclusions-list">
                                        <?php echo nl2br(htmlspecialchars($package['exclusions'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($related)): ?>
                <div class="related-packages">
                    <h2>Related Packages</h2>
                    <div class="packages-grid">
                        <?php foreach ($related as $rel_package): ?>
                            <div class="package-card">
                                <?php if ($rel_package['image']): ?>
                                    <div class="package-image">
                                        <img src="<?php echo BASE_URL . htmlspecialchars($rel_package['image']); ?>" alt="<?php echo htmlspecialchars($rel_package['title']); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="package-content">
                                    <h3><?php echo htmlspecialchars($rel_package['title']); ?></h3>
                                    <p class="package-destination">📍 <?php echo htmlspecialchars($rel_package['destination']); ?></p>
                                    <p class="package-duration">⏱️ <?php echo htmlspecialchars($rel_package['duration']); ?></p>
                                    <div class="package-price">
                                        <?php if ($rel_package['discount_price']): ?>
                                            <span class="old-price"><?php echo formatCurrency($rel_package['price']); ?></span>
                                            <span class="current-price"><?php echo formatCurrency($rel_package['discount_price']); ?></span>
                                        <?php else: ?>
                                            <span class="current-price"><?php echo formatCurrency($rel_package['price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="package-actions">
                                        <a href="package-details.php?id=<?php echo $rel_package['id']; ?>" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>

