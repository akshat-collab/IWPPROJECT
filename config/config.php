<?php
// Site Configuration
session_start();

// Base URL
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:8000/');
}
define('ADMIN_URL', BASE_URL . 'admin/');

// Site Information
define('SITE_NAME', 'Avipro Travels');
define('SITE_TAGLINE', 'Your Journey, Our Passion');

// File Upload Settings
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Session timeout (30 minutes)
define('SESSION_TIMEOUT', 1800);

// Check if session is expired
if (isset($_SESSION['admin_logged_in']) && isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: ' . ADMIN_URL . 'login.php');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
    mkdir(UPLOAD_DIR . 'packages/', 0777, true);
    mkdir(UPLOAD_DIR . 'banners/', 0777, true);
}

// Helper Functions
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . ADMIN_URL . 'login.php');
        exit;
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}
?>

