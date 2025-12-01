<header class="admin-header">
    <div class="header-content">
        <h1><?php echo SITE_NAME; ?> - Admin Panel</h1>
        <div class="header-actions">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
            <a href="<?php echo BASE_URL; ?>" target="_blank" class="btn btn-sm">View Site</a>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</header>

