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
$subject = sanitizeInput($_POST['subject'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// For now, we'll store contact enquiries in bookings table with a special flag
// In a production system, you might want a separate contact_enquiries table
$conn = getDBConnection();
$destination = 'General Enquiry';
$travel_date = date('Y-m-d');
$number_of_persons = 0;
$full_message = "Subject: $subject\n\n$message";

$stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, destination, travel_date, number_of_persons, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
$phone = 'N/A'; // Contact form doesn't require phone
$stmt->bind_param("sssssis", $name, $email, $phone, $destination, $travel_date, $number_of_persons, $full_message);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error sending message. Please try again.']);
}

$stmt->close();
closeDBConnection($conn);
?>

