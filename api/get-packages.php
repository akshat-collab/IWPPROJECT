<?php
require_once '../config/config.php';
require_once '../config/database.php';

header('Content-Type: application/json');

$conn = getDBConnection();

// Get parameters
$search = $_GET['search'] ?? '';
$destination = $_GET['destination'] ?? '';
$featured = $_GET['featured'] ?? '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 0;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

// Build query
$query = "SELECT * FROM packages WHERE status = 'active'";
$params = [];
$types = '';

if (!empty($search)) {
    $query .= " AND (title LIKE ? OR description LIKE ? OR destination LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'sss';
}

if (!empty($destination)) {
    $query .= " AND destination LIKE ?";
    $dest_param = "%$destination%";
    $params[] = $dest_param;
    $types .= 's';
}

if ($featured === '1') {
    $query .= " AND featured = 1";
}

$query .= " ORDER BY created_at DESC";

if ($limit > 0) {
    $query .= " LIMIT ?";
    $params[] = $limit;
    $types .= 'i';
    
    if ($offset > 0) {
        $query .= " OFFSET ?";
        $params[] = $offset;
        $types .= 'i';
    }
}

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$packages = $result->fetch_all(MYSQLI_ASSOC);

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM packages WHERE status = 'active'";
if (!empty($search)) {
    $count_query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%' OR destination LIKE '%$search%')";
}
if (!empty($destination)) {
    $count_query .= " AND destination LIKE '%$destination%'";
}
if ($featured === '1') {
    $count_query .= " AND featured = 1";
}

$total_result = $conn->query($count_query);
$total = $total_result->fetch_assoc()['total'];

$stmt->close();
closeDBConnection($conn);

// Format packages
foreach ($packages as &$package) {
    $package['formatted_price'] = formatCurrency($package['price']);
    if ($package['discount_price']) {
        $package['formatted_discount_price'] = formatCurrency($package['discount_price']);
        $package['discount_percent'] = round((($package['price'] - $package['discount_price']) / $package['price']) * 100);
    }
    $package['short_description'] = substr($package['description'], 0, 150) . '...';
}

echo json_encode([
    'success' => true,
    'packages' => $packages,
    'total' => $total,
    'count' => count($packages)
]);
?>

