<?php
// deleteTraveler.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: loginandregister.php");
    exit();
}

include 'DB_Bifrost.php'; // Ensure $pdo is initialized

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = (int) $_POST['user_id'];

    // First, ensure the user exists and is a Traveler
    try {
        $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user || $user['role'] !== 'Traveler') {
            echo json_encode(['success' => false, 'message' => 'Traveler not found or cannot be deleted.']);
            exit();
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }

    // Delete the traveler
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ? AND role = 'Traveler'");
        if ($stmt->execute([$user_id])) {
            echo json_encode(['success' => true, 'message' => 'Traveler deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete traveler.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting traveler: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
