<?php
// deleteTour.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: loginandregister.php");
    exit();
}
include 'DB_Bifrost.php'; // Ensure $pdo is initialized

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tour_id'])) {
    $tour_id = (int) $_POST['tour_id'];

    try {
        // Delete the tour record from the tours table
        $stmt = $pdo->prepare("DELETE FROM tours WHERE tour_id = ?");
        if ($stmt->execute([$tour_id])) {
            echo json_encode(['success' => true, 'message' => 'Tour deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete tour.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting tour: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
