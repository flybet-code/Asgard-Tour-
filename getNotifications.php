<?php
/**
 * getNotifications.php
 *
 * Purpose: Fetch notifications for the admin panel.
 * Usage: Called via AJAX to dynamically display notifications and alerts.
 *
 * Security: Ensure that notifications do not expose sensitive information.
 */

include 'DB_Bifrost.php';  // Database connection

header('Content-Type: application/json');

try {
    // Prepare the query to prevent SQL injection
    $stmt = $pdo->prepare("SELECT id, user_id, message, type, created_at, status FROM notifications ORDER BY created_at DESC");
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all notifications
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if there are any notifications
    if ($notifications) {
        // Output the notifications in JSON format with a success message
        echo json_encode(['success' => true, 'notifications' => $notifications]);
    } else {
        // No notifications found
        echo json_encode(['success' => true, 'notifications' => []]);
    }
} catch (PDOException $e) {
    // Log the error details on the server for debugging (better for production)
    error_log('Database query error: ' . $e->getMessage());

    // Return a generic error message to the client
    echo json_encode(['success' => false, 'message' => 'Failed to load notifications.']);
} catch (Exception $e) {
    // Log general exceptions
    error_log('General error: ' . $e->getMessage());
    
    // Return a generic error message to the client
    echo json_encode(['success' => false, 'message' => 'An error occurred.']);
}

?>