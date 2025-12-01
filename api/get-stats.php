<?php
require_once '../config/config.php';
require_once '../config/database.php';

header('Content-Type: application/json');

$conn = getDBConnection();

$stats = [
    'total_packages' => $conn->query("SELECT COUNT(*) as count FROM packages")->fetch_assoc()['count'],
    'active_packages' => $conn->query("SELECT COUNT(*) as count FROM packages WHERE status = 'active'")->fetch_assoc()['count'],
    'total_bookings' => $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'],
    'pending_bookings' => $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")->fetch_assoc()['count']
];

closeDBConnection($conn);

echo json_encode([
    'success' => true,
    'stats' => $stats
]);
?>

