<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../config/database.php';
}

$conn = getDBConnection();
$contact = $conn->query("SELECT * FROM contact_info WHERE id=1")->fetch_assoc();
closeDBConnection($conn);
?>
<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?php echo SITE_NAME; ?></h3>
                <p><?php echo SITE_TAGLINE; ?></p>
                <p>Your trusted travel partner for amazing journeys around the world.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>about.php">About Us</a></li>
                    <li><a href="<?php echo BASE_URL; ?>packages.php">Tour Packages</a></li>
                    <li><a href="<?php echo BASE_URL; ?>booking.php">Book Now</a></li>
                    <li><a href="<?php echo BASE_URL; ?>contact.php">Contact Us</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact Information</h3>
                <?php if ($contact['address']): ?>
                    <p>📍 <?php echo nl2br(htmlspecialchars($contact['address'])); ?></p>
                <?php endif; ?>
                <?php if ($contact['phone']): ?>
                    <p>📞 <?php echo htmlspecialchars($contact['phone']); ?></p>
                <?php endif; ?>
                <?php if ($contact['email']): ?>
                    <p>✉️ <?php echo htmlspecialchars($contact['email']); ?></p>
                <?php endif; ?>
                <?php if ($contact['whatsapp']): ?>
                    <p>💬 WhatsApp: <?php echo htmlspecialchars($contact['whatsapp']); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="footer-section">
                <h3>Follow Us</h3>
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
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

