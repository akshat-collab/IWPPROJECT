<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../config/database.php';
}

$conn = getDBConnection();
$contact = $conn->query("SELECT * FROM contact_info WHERE id=1")->fetch_assoc();
closeDBConnection($conn);
?>
<header class="main-header">
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="contact-info">
                    <?php if ($contact['phone']): ?>
                        <span>📞 <?php echo htmlspecialchars($contact['phone']); ?></span>
                    <?php endif; ?>
                    <?php if ($contact['email']): ?>
                        <span>✉️ <?php echo htmlspecialchars($contact['email']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="social-links">
                    <?php if ($contact['facebook']): ?>
                        <a href="<?php echo htmlspecialchars($contact['facebook']); ?>" target="_blank">Facebook</a>
                    <?php endif; ?>
                    <?php if ($contact['instagram']): ?>
                        <a href="<?php echo htmlspecialchars($contact['instagram']); ?>" target="_blank">Instagram</a>
                    <?php endif; ?>
                    <?php if ($contact['twitter']): ?>
                        <a href="<?php echo htmlspecialchars($contact['twitter']); ?>" target="_blank">Twitter</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <nav class="main-nav">
        <div class="container">
            <div class="nav-content">
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>">
                        <h1><?php echo SITE_NAME; ?></h1>
                        <p><?php echo SITE_TAGLINE; ?></p>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li><a href="<?php echo BASE_URL; ?>" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About Us</a></li>
                    <li><a href="<?php echo BASE_URL; ?>packages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'packages.php' ? 'active' : ''; ?>">Tour Packages</a></li>
                    <li><a href="<?php echo BASE_URL; ?>booking.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'booking.php' ? 'active' : ''; ?>">Book Now</a></li>
                    <li><a href="<?php echo BASE_URL; ?>contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact Us</a></li>
                </ul>
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>
            </div>
        </div>
    </nav>
</header>

