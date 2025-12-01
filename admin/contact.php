<?php
require_once '../config/config.php';
require_once '../config/database.php';
requireAdminLogin();

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'] ?? '';
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $whatsapp = sanitizeInput($_POST['whatsapp'] ?? '');
    $facebook = sanitizeInput($_POST['facebook'] ?? '');
    $instagram = sanitizeInput($_POST['instagram'] ?? '');
    $twitter = sanitizeInput($_POST['twitter'] ?? '');
    
    $stmt = $conn->prepare("UPDATE contact_info SET address=?, phone=?, email=?, whatsapp=?, facebook=?, instagram=?, twitter=? WHERE id=1");
    $stmt->bind_param("sssssss", $address, $phone, $email, $whatsapp, $facebook, $instagram, $twitter);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Contact information updated successfully!';
    } else {
        $_SESSION['error_message'] = 'Error updating contact information!';
    }
    
    $stmt->close();
}

$contact = $conn->query("SELECT * FROM contact_info WHERE id=1")->fetch_assoc();
if (!$contact) {
    // Insert default if not exists
    $conn->query("INSERT INTO contact_info (id) VALUES (1)");
    $contact = $conn->query("SELECT * FROM contact_info WHERE id=1")->fetch_assoc();
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Information - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-content">
            <h1>Contact Information</h1>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
            
            <form method="POST" class="form-container">
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($contact['address'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($contact['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($contact['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="whatsapp">WhatsApp</label>
                        <input type="text" id="whatsapp" name="whatsapp" value="<?php echo htmlspecialchars($contact['whatsapp'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Social Media Links</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="facebook">Facebook URL</label>
                            <input type="url" id="facebook" name="facebook" value="<?php echo htmlspecialchars($contact['facebook'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="instagram">Instagram URL</label>
                            <input type="url" id="instagram" name="instagram" value="<?php echo htmlspecialchars($contact['instagram'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="twitter">Twitter URL</label>
                            <input type="url" id="twitter" name="twitter" value="<?php echo htmlspecialchars($contact['twitter'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Contact Information</button>
                </div>
            </form>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>

