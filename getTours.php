<?php
/**
 * getTours.php
 *
 * Purpose: Retrieve all tour records from the database and return them in JSON format.
 * Usage: Called via AJAX from the admin panel to dynamically load tour data.
 *
 * Security: Use prepared statements to avoid SQL injection.
 */

include 'DB_Bifrost.php';  // Database connection

header('Content-Type: application/json');

try {
    // Retrieve all tours from the Tours table
    $stmt = $pdo->prepare("SELECT * FROM Tours ORDER BY created_at DESC");
    $stmt->execute();
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the data in JSON format
    echo json_encode($tours);
} catch (Exception $e) {
    // Log error details in a production system instead of exposing them
    echo json_encode(['error' => 'Failed to load tours.']);
}
?>
