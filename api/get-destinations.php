<?php
require_once '../config/config.php';
require_once '../config/database.php';

header('Content-Type: application/json');

$conn = getDBConnection();
$destinations = $conn->query("SELECT DISTINCT destination, COUNT(*) as count FROM packages WHERE status = 'active' GROUP BY destination ORDER BY destination")->fetch_all(MYSQLI_ASSOC);
closeDBConnection($conn);

echo json_encode([
    'success' => true,
    'destinations' => $destinations
]);
?>

