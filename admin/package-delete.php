<?php
require_once '../config/config.php';
require_once '../config/database.php';
requireAdminLogin();

$package_id = $_GET['id'] ?? null;

if ($package_id) {
    $conn = getDBConnection();
    
    // Get package image path
    $stmt = $conn->prepare("SELECT image FROM packages WHERE id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $package = $result->fetch_assoc();
    $stmt->close();
    
    // Delete package
    $stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->bind_param("i", $package_id);
    
    if ($stmt->execute()) {
        // Delete image file if exists
        if ($package && $package['image'] && file_exists('../' . $package['image'])) {
            unlink('../' . $package['image']);
        }
        $_SESSION['success_message'] = 'Package deleted successfully!';
    } else {
        $_SESSION['error_message'] = 'Error deleting package!';
    }
    
    $stmt->close();
    closeDBConnection($conn);
}

header('Location: packages.php');
exit;
?>

