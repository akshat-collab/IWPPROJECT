<?php
require_once '../config/config.php';
require_once '../config/database.php';
requireAdminLogin();

$conn = getDBConnection();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $status = sanitizeInput($_POST['status']);
    
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $booking_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Booking status updated successfully!';
    } else {
        $_SESSION['error_message'] = 'Error updating status!';
    }
    
    $stmt->close();
}

// Get all bookings
$bookings = $conn->query("SELECT b.*, p.title as package_title FROM bookings b LEFT JOIN packages p ON b.package_id = p.id ORDER BY b.created_at DESC")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings & Enquiries - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-content">
            <h1>Bookings & Enquiries</h1>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Destination</th>
                        <th>Travel Date</th>
                        <th>Persons</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="11" class="text-center">No bookings found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>#<?php echo $booking['id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                                <td><?php echo htmlspecialchars($booking['destination']); ?></td>
                                <td><?php echo formatDate($booking['travel_date']); ?></td>
                                <td><?php echo $booking['number_of_persons']; ?></td>
                                <td><?php echo $booking['package_title'] ? htmlspecialchars($booking['package_title']) : '-'; ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td><?php echo formatDate($booking['created_at']); ?></td>
                                <td>
                                    <button onclick="viewBooking(<?php echo htmlspecialchars(json_encode($booking)); ?>)" class="btn btn-sm btn-primary">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
    
    <!-- Booking Details Modal -->
    <div id="bookingModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Booking Details</h2>
            <div id="bookingDetails"></div>
        </div>
    </div>
    
    <script src="<?php echo BASE_URL; ?>assets/js/admin.js"></script>
    <script>
        function viewBooking(booking) {
            document.getElementById('bookingDetails').innerHTML = `
                <p><strong>Name:</strong> ${booking.name}</p>
                <p><strong>Email:</strong> ${booking.email}</p>
                <p><strong>Phone:</strong> ${booking.phone}</p>
                <p><strong>Destination:</strong> ${booking.destination}</p>
                <p><strong>Travel Date:</strong> ${booking.travel_date}</p>
                <p><strong>Number of Persons:</strong> ${booking.number_of_persons}</p>
                <p><strong>Message:</strong> ${booking.message || 'N/A'}</p>
                <p><strong>Status:</strong> ${booking.status}</p>
                <p><strong>Submitted:</strong> ${booking.created_at}</p>
            `;
            document.getElementById('bookingModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('bookingModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

