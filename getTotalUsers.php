<?php
/**
 * getTotalUsers.php
 *
 * Purpose: Retrieve the total count of all registered users.
 */
include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS totalUsers FROM Users");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to retrieve total users.']);
}
?>
