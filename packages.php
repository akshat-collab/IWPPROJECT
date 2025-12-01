<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:8000/');
}
require_once 'config/config.php';
require_once 'config/database.php';

$conn = getDBConnection();

// Get search/filter parameters
$search = $_GET['search'] ?? '';
$destination = $_GET['destination'] ?? '';

$query = "SELECT * FROM packages WHERE status = 'active'";
$params = [];

if (!empty($search)) {
    $query .= " AND (title LIKE ? OR description LIKE ? OR destination LIKE ?)";
    $search_param = "%$search%";
}

if (!empty($destination)) {
    $query .= " AND destination LIKE ?";
    $dest_param = "%$destination%";
}

$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);

if (!empty($search) && !empty($destination)) {
    $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $dest_param);
} elseif (!empty($search)) {
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
} elseif (!empty($destination)) {
    $stmt->bind_param("s", $dest_param);
}

$stmt->execute();
$packages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get unique destinations for filter
$destinations = $conn->query("SELECT DISTINCT destination FROM packages WHERE status = 'active' ORDER BY destination")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Packages - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="page-header">
        <div class="container">
            <h1>Tour Packages</h1>
            <p>Explore our amazing travel packages</p>
        </div>
    </section>
    
    <section class="packages-section">
        <div class="container">
            <!-- Search and Filter - Dynamic -->
            <div class="search-filter-bar">
                <form id="search-form" class="search-form" onsubmit="return false;">
                    <input type="text" id="package-search" name="search" placeholder="🔍 Search packages..." value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                    <select id="destination-filter" name="destination" class="filter-select">
                        <option value="">All Destinations</option>
                        <?php foreach ($destinations as $dest): ?>
                            <option value="<?php echo htmlspecialchars($dest['destination']); ?>" <?php echo $destination == $dest['destination'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dest['destination']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" onclick="clearFilters()" class="btn btn-secondary">Clear</button>
                </form>
                <div id="results-count" class="results-count" style="margin-top: 15px; text-align: center; color: var(--text-light); font-weight: 500;">
                    <?php echo count($packages); ?> package<?php echo count($packages) !== 1 ? 's' : ''; ?> found
                </div>
            </div>
            
            <!-- Packages Grid - Dynamic Loading -->
            <div id="packages-grid" class="packages-grid">
                <?php foreach ($packages as $package): ?>
                    <div class="package-card">
                        <?php if ($package['image']): ?>
                            <div class="package-image">
                                <img src="<?php echo BASE_URL . htmlspecialchars($package['image']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" loading="lazy">
                                <?php if ($package['discount_price']): ?>
                                    <span class="discount-badge">Save <?php echo round((($package['price'] - $package['discount_price']) / $package['price']) * 100); ?>%</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="package-content">
                            <h3><?php echo htmlspecialchars($package['title']); ?></h3>
                            <p class="package-destination">📍 <?php echo htmlspecialchars($package['destination']); ?></p>
                            <p class="package-duration">⏱️ <?php echo htmlspecialchars($package['duration']); ?></p>
                            <div class="package-price">
                                <?php if ($package['discount_price']): ?>
                                    <span class="old-price"><?php echo formatCurrency($package['price']); ?></span>
                                    <span class="current-price"><?php echo formatCurrency($package['discount_price']); ?></span>
                                <?php else: ?>
                                    <span class="current-price"><?php echo formatCurrency($package['price']); ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="package-description"><?php echo htmlspecialchars(substr($package['description'], 0, 150)) . '...'; ?></p>
                            <div class="package-actions">
                                <a href="package-details.php?id=<?php echo $package['id']; ?>" class="btn btn-primary">View Details</a>
                                <a href="booking.php?package=<?php echo $package['id']; ?>" class="btn btn-secondary">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/dynamic.js"></script>
    <script>
        function clearFilters() {
            document.getElementById('package-search').value = '';
            document.getElementById('destination-filter').value = '';
            if (window.dynamicPackages) {
                window.dynamicPackages.searchPackages('');
            }
        }
    </script>
</body>
</html>

