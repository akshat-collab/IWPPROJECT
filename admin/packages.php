<?php
require_once '../config/config.php';
require_once '../config/database.php';
requireAdminLogin();

$conn = getDBConnection();
$packages = $conn->query("SELECT * FROM packages ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="page-header">
                <h1>Travel Packages</h1>
                <a href="package-form.php" class="btn btn-primary">+ Add New Package</a>
            </div>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Destination</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($packages)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No packages found. <a href="package-form.php">Add your first package</a></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($packages as $package): ?>
                            <tr>
                                <td>
                                    <?php if ($package['image']): ?>
                                        <img src="../<?php echo htmlspecialchars($package['image']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="table-image">
                                    <?php else: ?>
                                        <span class="no-image">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($package['title']); ?></td>
                                <td><?php echo htmlspecialchars($package['destination']); ?></td>
                                <td><?php echo htmlspecialchars($package['duration']); ?></td>
                                <td><?php echo formatCurrency($package['price']); ?></td>
                                <td><span class="badge badge-<?php echo $package['status']; ?>"><?php echo ucfirst($package['status']); ?></span></td>
                                <td><?php echo $package['featured'] ? '⭐' : '-'; ?></td>
                                <td>
                                    <a href="package-form.php?id=<?php echo $package['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="package-delete.php?id=<?php echo $package['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this package?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>

