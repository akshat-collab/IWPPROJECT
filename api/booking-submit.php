<?php
require_once '../config/config.php';
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$phone = sanitizeInput($_POST['phone'] ?? '');
$destination = sanitizeInput($_POST['destination'] ?? '');
$travel_date = $_POST['travel_date'] ?? '';
$number_of_persons = intval($_POST['number_of_persons'] ?? 0);
$message = sanitizeInput($_POST['message'] ?? '');
$package_id = !empty($_POST['package_id']) ? intval($_POST['package_id']) : null;

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($phone)) {
    $errors[] = 'Phone is required';
}

if (empty($destination)) {
    $errors[] = 'Destination is required';
}

if (empty($travel_date)) {
    $errors[] = 'Travel date is required';
}

if ($number_of_persons < 1) {
    $errors[] = 'Number of persons must be at least 1';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Insert into database
$conn = getDBConnection();
$stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, destination, travel_date, number_of_persons, message, package_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssisi", $name, $email, $phone, $destination, $travel_date, $number_of_persons, $message, $package_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your booking enquiry has been submitted successfully. We will contact you soon.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error submitting booking. Please try again.']);
}

$stmt->close();
closeDBConnection($conn);
?>

