<?php
require_once '../config/config.php';
require_once '../config/database.php';
requireAdminLogin();

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'content_') === 0) {
            $content_key = str_replace('content_', '', $key);
            $content_value = $value;
            
            $stmt = $conn->prepare("UPDATE site_content SET content_value = ? WHERE content_key = ?");
            $stmt->bind_param("ss", $content_value, $content_key);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Handle banner image upload
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $upload_dir = UPLOAD_DIR . 'banners/';
        $file_ext = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_exts)) {
            $new_filename = 'banner.' . $file_ext;
            $target_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $target_path)) {
                $banner_path = 'uploads/banners/' . $new_filename;
                $stmt = $conn->prepare("UPDATE site_content SET content_value = ? WHERE content_key = 'banner_image'");
                $stmt->bind_param("s", $banner_path);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    
    $_SESSION['success_message'] = 'Content updated successfully!';
    header('Location: content.php');
    exit;
}

$contents = $conn->query("SELECT * FROM site_content ORDER BY content_key")->fetch_all(MYSQLI_ASSOC);
$content_array = [];
foreach ($contents as $content) {
    $content_array[$content['content_key']] = $content['content_value'];
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Content - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-content">
            <h1>Site Content Management</h1>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="form-container">
                <div class="form-section">
                    <h2>Homepage Banner</h2>
                    
                    <div class="form-group">
                        <label for="content_home_banner_title">Banner Title</label>
                        <input type="text" id="content_home_banner_title" name="content_home_banner_title" value="<?php echo htmlspecialchars($content_array['home_banner_title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="content_home_banner_subtitle">Banner Subtitle</label>
                        <input type="text" id="content_home_banner_subtitle" name="content_home_banner_subtitle" value="<?php echo htmlspecialchars($content_array['home_banner_subtitle'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="banner_image">Banner Image</label>
                        <?php if (isset($content_array['banner_image']) && $content_array['banner_image']): ?>
                            <div class="current-image">
                                <img src="../<?php echo htmlspecialchars($content_array['banner_image']); ?>" alt="Banner" style="max-width: 300px; margin-bottom: 10px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="banner_image" name="banner_image" accept="image/*">
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>About Us Page</h2>
                    
                    <div class="form-group">
                        <label for="content_about_us_content">About Us Content</label>
                        <textarea id="content_about_us_content" name="content_about_us_content" rows="10"><?php echo htmlspecialchars($content_array['about_us_content'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Homepage Content</h2>
                    
                    <div class="form-group">
                        <label for="content_home_featured_text">Featured Packages Section Title</label>
                        <input type="text" id="content_home_featured_text" name="content_home_featured_text" value="<?php echo htmlspecialchars($content_array['home_featured_text'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Content</button>
                </div>
            </form>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>

