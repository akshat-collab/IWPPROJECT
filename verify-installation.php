<?php
/**
 * Avipro Travels - Installation Verification Script
 * Run this file after installation to verify everything is set up correctly
 * Access: http://localhost/iwpproject/verify-installation.php
 */

echo "<!DOCTYPE html><html><head><title>Installation Verification</title>";
echo "<style>body{font-family:Arial;max-width:800px;margin:50px auto;padding:20px;}";
echo ".success{color:green;}.error{color:red;}.info{color:blue;}";
echo "h1{color:#2c5aa0;}h2{border-bottom:2px solid #ddd;padding-bottom:10px;}";
echo "ul{list-style:none;padding:0;}li{padding:5px 0;}</style></head><body>";
echo "<h1>Avipro Travels - Installation Verification</h1>";

$errors = [];
$warnings = [];
$success = [];

// Check PHP Version
echo "<h2>1. PHP Version Check</h2>";
$php_version = phpversion();
if (version_compare($php_version, '7.4.0', '>=')) {
    echo "<p class='success'>✓ PHP Version: $php_version (OK)</p>";
    $success[] = "PHP version";
} else {
    echo "<p class='error'>✗ PHP Version: $php_version (Requires 7.4+)</p>";
    $errors[] = "PHP version";
}

// Check Required PHP Extensions
echo "<h2>2. PHP Extensions Check</h2>";
$required_extensions = ['mysqli', 'session', 'mbstring', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='success'>✓ $ext extension loaded</p>";
        $success[] = "$ext extension";
    } else {
        echo "<p class='error'>✗ $ext extension not loaded</p>";
        $errors[] = "$ext extension";
    }
}

// Check Configuration Files
echo "<h2>3. Configuration Files Check</h2>";
$config_files = [
    'config/config.php',
    'config/database.php'
];
foreach ($config_files as $file) {
    if (file_exists($file)) {
        echo "<p class='success'>✓ $file exists</p>";
        $success[] = $file;
    } else {
        echo "<p class='error'>✗ $file missing</p>";
        $errors[] = $file;
    }
}

// Check Database Connection
echo "<h2>4. Database Connection Check</h2>";
if (file_exists('config/database.php')) {
    require_once 'config/database.php';
    try {
        $conn = getDBConnection();
        if ($conn) {
            echo "<p class='success'>✓ Database connection successful</p>";
            $success[] = "Database connection";
            
            // Check if tables exist
            $tables = ['admin_users', 'packages', 'bookings', 'site_content', 'contact_info'];
            echo "<h3>Database Tables:</h3><ul>";
            foreach ($tables as $table) {
                $result = $conn->query("SHOW TABLES LIKE '$table'");
                if ($result && $result->num_rows > 0) {
                    echo "<li class='success'>✓ Table '$table' exists</li>";
                    $success[] = "Table: $table";
                } else {
                    echo "<li class='error'>✗ Table '$table' missing</li>";
                    $errors[] = "Table: $table";
                }
            }
            echo "</ul>";
            
            // Check admin user
            $admin_check = $conn->query("SELECT COUNT(*) as count FROM admin_users WHERE username = 'admin'");
            if ($admin_check && $admin_check->fetch_assoc()['count'] > 0) {
                echo "<p class='success'>✓ Admin user exists</p>";
                $success[] = "Admin user";
            } else {
                echo "<p class='warning'>⚠ Admin user not found. Import database.sql</p>";
                $warnings[] = "Admin user";
            }
            
            closeDBConnection($conn);
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Database connection failed: " . $e->getMessage() . "</p>";
        $errors[] = "Database connection";
    }
} else {
    echo "<p class='error'>✗ Cannot check database - config file missing</p>";
}

// Check Directory Permissions
echo "<h2>5. Directory Permissions Check</h2>";
$directories = [
    'uploads',
    'uploads/packages',
    'uploads/banners'
];
foreach ($directories as $dir) {
    if (file_exists($dir)) {
        if (is_writable($dir)) {
            echo "<p class='success'>✓ $dir is writable</p>";
            $success[] = "$dir permissions";
        } else {
            echo "<p class='error'>✗ $dir is not writable</p>";
            $errors[] = "$dir permissions";
        }
    } else {
        echo "<p class='warning'>⚠ $dir does not exist (will be created automatically)</p>";
        $warnings[] = "$dir directory";
    }
}

// Check Frontend Pages
echo "<h2>6. Frontend Pages Check</h2>";
$frontend_pages = [
    'index.php' => 'Home Page',
    'about.php' => 'About Us',
    'packages.php' => 'Tour Packages',
    'package-details.php' => 'Package Details',
    'booking.php' => 'Booking/Enquiry',
    'contact.php' => 'Contact Us'
];
foreach ($frontend_pages as $file => $name) {
    if (file_exists($file)) {
        echo "<p class='success'>✓ $name ($file)</p>";
        $success[] = $file;
    } else {
        echo "<p class='error'>✗ $name ($file) missing</p>";
        $errors[] = $file;
    }
}

// Check Admin Pages
echo "<h2>7. Admin Panel Pages Check</h2>";
$admin_pages = [
    'admin/login.php' => 'Admin Login',
    'admin/index.php' => 'Admin Dashboard',
    'admin/packages.php' => 'Package Management',
    'admin/bookings.php' => 'Booking Management',
    'admin/content.php' => 'Content Management',
    'admin/contact.php' => 'Contact Management'
];
foreach ($admin_pages as $file => $name) {
    if (file_exists($file)) {
        echo "<p class='success'>✓ $name</p>";
        $success[] = $file;
    } else {
        echo "<p class='error'>✗ $name missing</p>";
        $errors[] = $file;
    }
}

// Check API Endpoints
echo "<h2>8. API Endpoints Check</h2>";
$api_files = [
    'api/booking-submit.php' => 'Booking Submission',
    'api/contact-submit.php' => 'Contact Submission'
];
foreach ($api_files as $file => $name) {
    if (file_exists($file)) {
        echo "<p class='success'>✓ $name</p>";
        $success[] = $file;
    } else {
        echo "<p class='error'>✗ $name missing</p>";
        $errors[] = $file;
    }
}

// Check Assets
echo "<h2>9. Assets Check</h2>";
$assets = [
    'assets/css/style.css' => 'Frontend CSS',
    'assets/css/admin.css' => 'Admin CSS',
    'assets/js/main.js' => 'Main JavaScript',
    'assets/js/booking.js' => 'Booking JavaScript',
    'assets/js/admin.js' => 'Admin JavaScript'
];
foreach ($assets as $file => $name) {
    if (file_exists($file)) {
        echo "<p class='success'>✓ $name</p>";
        $success[] = $file;
    } else {
        echo "<p class='error'>✗ $name missing</p>";
        $errors[] = $file;
    }
}

// Summary
echo "<h2>10. Verification Summary</h2>";
echo "<p><strong>Successfully Verified:</strong> " . count($success) . " items</p>";
if (count($warnings) > 0) {
    echo "<p class='info'><strong>Warnings:</strong> " . count($warnings) . " items</p>";
}
if (count($errors) > 0) {
    echo "<p class='error'><strong>Errors:</strong> " . count($errors) . " items</p>";
    echo "<p><strong>Please fix the errors before using the system.</strong></p>";
} else {
    echo "<p class='success'><strong>✓ Installation verified successfully!</strong></p>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li>Access frontend: <a href='index.php'>Homepage</a></li>";
    echo "<li>Access admin: <a href='admin/login.php'>Admin Login</a></li>";
    echo "<li>Default credentials: admin / admin123</li>";
    echo "</ul>";
}

echo "</body></html>";
?>

