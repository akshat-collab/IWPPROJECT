<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:8000/');
}
require_once 'config/config.php';
require_once 'config/database.php';

$package_id = $_GET['package'] ?? null;
$package = null;

if ($package_id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, title, destination, price, discount_price FROM packages WHERE id = ? AND status = 'active'");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $package = $result->fetch_assoc();
    $stmt->close();
    closeDBConnection($conn);
}

// Get all destinations for dropdown
$conn = getDBConnection();
$destinations = $conn->query("SELECT DISTINCT destination FROM packages WHERE status = 'active' ORDER BY destination")->fetch_all(MYSQLI_ASSOC);
closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="page-header">
        <div class="container">
            <h1>Book Your Travel Package</h1>
            <p>Fill out the form below to submit your booking enquiry</p>
        </div>
    </section>
    
    <section class="booking-section">
        <div class="container">
            <div class="booking-form-container">
                <?php if ($package): ?>
                    <div class="selected-package-info">
                        <h3>Selected Package</h3>
                        <p><strong><?php echo htmlspecialchars($package['title']); ?></strong></p>
                        <p>Destination: <?php echo htmlspecialchars($package['destination']); ?></p>
                        <p>Price: <?php echo formatCurrency($package['discount_price'] ?: $package['price']); ?></p>
                    </div>
                <?php endif; ?>
                
                <form id="bookingForm" class="booking-form">
                    <input type="hidden" name="package_id" value="<?php echo $package_id ?? ''; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required>
                            <span class="error-message" id="name-error"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                            <span class="error-message" id="email-error"></span>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required>
                            <span class="error-message" id="phone-error"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="destination">Destination *</label>
                            <select id="destination" name="destination" required>
                                <option value="">Select Destination</option>
                                <?php if ($package): ?>
                                    <option value="<?php echo htmlspecialchars($package['destination']); ?>" selected><?php echo htmlspecialchars($package['destination']); ?></option>
                                <?php endif; ?>
                                <?php foreach ($destinations as $dest): ?>
                                    <?php if (!$package || $dest['destination'] != $package['destination']): ?>
                                        <option value="<?php echo htmlspecialchars($dest['destination']); ?>"><?php echo htmlspecialchars($dest['destination']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <span class="error-message" id="destination-error"></span>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="travel_date">Travel Date *</label>
                            <input type="date" id="travel_date" name="travel_date" min="<?php echo date('Y-m-d'); ?>" required>
                            <span class="error-message" id="travel_date-error"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="number_of_persons">Number of Persons *</label>
                            <input type="number" id="number_of_persons" name="number_of_persons" min="1" required>
                            <span class="error-message" id="number_of_persons-error"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message / Special Requirements</label>
                        <textarea id="message" name="message" rows="5" placeholder="Any special requirements or questions?"></textarea>
                    </div>
                    
                    <div id="form-message" class="form-message"></div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">Submit Booking Enquiry</button>
                        <button type="reset" class="btn btn-secondary">Clear Form</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/booking.js"></script>
    <script src="assets/js/dynamic.js"></script>
</body>
</html>

