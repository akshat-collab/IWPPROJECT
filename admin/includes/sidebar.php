<nav class="admin-sidebar">
    <ul class="sidebar-menu">
        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <span>📊</span> Dashboard
        </a></li>
        <li><a href="packages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'packages.php' ? 'active' : ''; ?>">
            <span>✈️</span> Travel Packages
        </a></li>
        <li><a href="bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
            <span>📋</span> Bookings & Enquiries
        </a></li>
        <li><a href="content.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'content.php' ? 'active' : ''; ?>">
            <span>📝</span> Site Content
        </a></li>
        <li><a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
            <span>📞</span> Contact Info
        </a></li>
    </ul>
</nav>

