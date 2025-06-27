<?php
// fetchNotifications.php
session_start();
header('Content-Type: application/json');
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}
$admin_id = $_SESSION['user_id'];

$sql = "SELECT notification_id, message, type, created_at 
        FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}
// Since no 'read' flag exists, we'll use total count as unread count.
$unreadCount = count($notifications);
echo json_encode(['notifications' => $notifications, 'unreadCount' => $unreadCount]);
?>
