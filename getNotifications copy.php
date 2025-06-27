<?php
/**
 * getNotifications.php
 *
 * Purpose: Fetch notifications for the admin panel.
 * Usage: Called via AJAX to dynamically display notifications and alerts.
 *
 * Security: Ensure that notifications are not exposing sensitive information.
 */

include 'DB_Bifrost.php';  // Database connection

header('Content-Type: application/json');

try {
    // Retrieve notifications ordered by creation time
    $stmt = $pdo->prepare("SELECT * FROM Notifications ORDER BY created_at DESC");
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the notifications in JSON format
    echo json_encode($notifications);
} catch (Exception $e) {
    // Log the error details on the server in a production system
    echo json_encode(['error' => 'Failed to load notifications.']);
}
?>
