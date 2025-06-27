<?php
/**
 * getReviews.php
 *
 * Purpose: Retrieve tour reviews along with reviewer information.
 * Usage: Called via AJAX from the admin panel to display reviews.
 *
 * Note: Joins the Reviews and Users tables to include reviewer names.
 */

include 'DB_Bifrost.php';  // Database connection

header('Content-Type: application/json');

try {
    // Retrieve reviews with corresponding reviewer names
    $stmt = $pdo->prepare("
        SELECT r.*, u.name AS reviewer 
        FROM Reviews r 
        JOIN Users u ON r.user_id = u.user_id 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the data in JSON format
    echo json_encode($reviews);
} catch (Exception $e) {
    // Handle error appropriately (log error details on the server)
    echo json_encode(['error' => 'Failed to load reviews.']);
}
?>
