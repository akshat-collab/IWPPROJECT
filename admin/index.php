<?php
require_once '../config/config.php';
require_once '../config/database.php';
requireAdminLogin();

$conn = getDBConnection();

// Get statistics
$total_packages = $conn->query("SELECT COUNT(*) as count FROM packages")->fetch_assoc()['count'];
$active_packages = $conn->query("SELECT COUNT(*) as count FROM packages WHERE status = 'active'")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")->fetch_assoc()['count'];

// Get recent bookings
$recent_bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-content">
            <h1>Dashboard</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Packages</h3>
                    <p class="stat-number"><?php echo $total_packages; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Active Packages</h3>
                    <p class="stat-number"><?php echo $active_packages; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Total Bookings</h3>
                    <p class="stat-number"><?php echo $total_bookings; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Pending Bookings</h3>
                    <p class="stat-number"><?php echo $pending_bookings; ?></p>
                </div>
            </div>
            
            <div class="dashboard-section">
                <h2>Recent Bookings</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Destination</th>
                            <th>Travel Date</th>
                            <th>Persons</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_bookings)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No bookings yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_bookings as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['destination']); ?></td>
                                    <td><?php echo formatDate($booking['travel_date']); ?></td>
                                    <td><?php echo $booking['number_of_persons']; ?></td>
                                    <td><span class="badge badge-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                                    <td><?php echo formatDate($booking['created_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>

