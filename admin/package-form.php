<?php
require_once '../config/config.php';
require_once '../config/database.php';
requireAdminLogin();

$conn = getDBConnection();
$package = null;
$package_id = $_GET['id'] ?? null;

if ($package_id) {
    $stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $package = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $destination = sanitizeInput($_POST['destination'] ?? '');
    $duration = sanitizeInput($_POST['duration'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $discount_price = !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : null;
    $description = $_POST['description'] ?? '';
    $itinerary = $_POST['itinerary'] ?? '';
    $inclusions = $_POST['inclusions'] ?? '';
    $exclusions = $_POST['exclusions'] ?? '';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $status = sanitizeInput($_POST['status'] ?? 'active');
    
    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    
    // Handle image upload
    $image_path = $package['image'] ?? null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = UPLOAD_DIR . 'packages/';
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_exts)) {
            $new_filename = uniqid() . '.' . $file_ext;
            $target_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                if ($image_path && file_exists('../' . $image_path)) {
                    unlink('../' . $image_path);
                }
                $image_path = 'uploads/packages/' . $new_filename;
            }
        }
    }
    
    if ($package_id) {
        // Update existing package
        $stmt = $conn->prepare("UPDATE packages SET title=?, slug=?, destination=?, duration=?, price=?, discount_price=?, description=?, itinerary=?, inclusions=?, exclusions=?, image=?, featured=?, status=? WHERE id=?");
        $stmt->bind_param("ssssddsssssssi", $title, $slug, $destination, $duration, $price, $discount_price, $description, $itinerary, $inclusions, $exclusions, $image_path, $featured, $status, $package_id);
    } else {
        // Insert new package
        $stmt = $conn->prepare("INSERT INTO packages (title, slug, destination, duration, price, discount_price, description, itinerary, inclusions, exclusions, image, featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssddsssssss", $title, $slug, $destination, $duration, $price, $discount_price, $description, $itinerary, $inclusions, $exclusions, $image_path, $featured, $status);
    }
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = $package_id ? 'Package updated successfully!' : 'Package added successfully!';
        header('Location: packages.php');
        exit;
    } else {
        $_SESSION['error_message'] = 'Error saving package: ' . $stmt->error;
    }
    
    $stmt->close();
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $package_id ? 'Edit' : 'Add'; ?> Package - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="page-header">
                <h1><?php echo $package_id ? 'Edit' : 'Add'; ?> Travel Package</h1>
                <a href="packages.php" class="btn btn-secondary">← Back to Packages</a>
            </div>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="form-container">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Package Title *</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($package['title'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="destination">Destination *</label>
                        <input type="text" id="destination" name="destination" value="<?php echo htmlspecialchars($package['destination'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="duration">Duration *</label>
                        <input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($package['duration'] ?? ''); ?>" placeholder="e.g., 5 Days / 4 Nights" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price *</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo $package['price'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="discount_price">Discount Price</label>
                        <input type="number" id="discount_price" name="discount_price" step="0.01" value="<?php echo $package['discount_price'] ?? ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="image">Package Image</label>
                    <?php if ($package && $package['image']): ?>
                        <div class="current-image">
                            <img src="../<?php echo htmlspecialchars($package['image']); ?>" alt="Current Image" style="max-width: 200px; margin-bottom: 10px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($package['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="itinerary">Itinerary</label>
                    <textarea id="itinerary" name="itinerary" rows="8" placeholder="Day 1: ...&#10;Day 2: ..."><?php echo htmlspecialchars($package['itinerary'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="inclusions">Inclusions</label>
                        <textarea id="inclusions" name="inclusions" rows="5" placeholder="• Item 1&#10;• Item 2"><?php echo htmlspecialchars($package['inclusions'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="exclusions">Exclusions</label>
                        <textarea id="exclusions" name="exclusions" rows="5" placeholder="• Item 1&#10;• Item 2"><?php echo htmlspecialchars($package['exclusions'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="active" <?php echo ($package['status'] ?? 'active') == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($package['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="featured" value="1" <?php echo ($package['featured'] ?? 0) ? 'checked' : ''; ?>>
                            Featured Package
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><?php echo $package_id ? 'Update' : 'Add'; ?> Package</button>
                    <a href="packages.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>

