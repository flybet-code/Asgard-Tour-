<?php
/**
 * getTotalTravelers.php
 *
 * Purpose: Retrieve the total number of travelers registered in the system.
 * Query: Count users with role 'Traveler'.
 */

include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS totalTravelers FROM Users WHERE role = 'Traveler'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to retrieve total travelers.']);
}
?>
