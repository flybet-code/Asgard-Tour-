<?php
session_start();
header('Content-Type: application/json');
require 'db_bifrost.php';  // Ensure this matches the actual filename

$registrations = [];
$bookings = [];

// Prepare and execute query for recent registrations
try {
    $stmt1 = $pdo->prepare("SELECT name, email, created_at FROM users ORDER BY created_at DESC LIMIT 10");
    $stmt1->execute();
    $registrations = $stmt1->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch registrations: ' . $e->getMessage()]);
    exit;
}

// Prepare and execute query for recent bookings
try {
    $stmt2 = $pdo->prepare("SELECT b.booking_date, t.title AS tour_name 
                             FROM bookings b 
                             JOIN tours t ON b.tour_id = t.tour_id 
                             ORDER BY b.booking_date DESC LIMIT 10");
    $stmt2->execute();
    $bookings = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch bookings: ' . $e->getMessage()]);
    exit;
}

// Return JSON response
echo json_encode(['registrations' => $registrations, 'bookings' => $bookings]);
?>