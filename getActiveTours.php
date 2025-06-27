<?php
/**
 * getActiveTours.php
 *
 * Purpose: Retrieve the count of active tours.
 * Assumes the Tours table has a 'status' field with active tours marked as 'active'.
 */
include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS activeTours FROM Tours WHERE status = 'active'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $result = ['activeTours' => 0];
    }
    echo json_encode($result);
} catch (Exception $e) {
    error_log("getActiveTours error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to retrieve active tours.']);
}
?>
