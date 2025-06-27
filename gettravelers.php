<?php
// getTravelers.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: loginandregister.php");
    exit();
}
include 'DB_Bifrost.php';

// Get parameters
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query for travelers only
$offset = ($page - 1) * $per_page;
$searchSql = "";
$params = [];

if (!empty($search)) {
    $searchSql = " AND (name LIKE ? OR email LIKE ?)";
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

// Count total travelers
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'Traveler' $searchSql");
$stmt->execute($params);
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($total / $per_page);

// Get traveler records
$stmt = $pdo->prepare("SELECT user_id, name, email, created_at FROM users WHERE role = 'Traveler' $searchSql ORDER BY created_at DESC LIMIT ? OFFSET ?");
// Bind additional parameters
if (!empty($search)) {
    $stmt->bindValue(1, '%' . $search . '%', PDO::PARAM_STR);
    $stmt->bindValue(2, '%' . $search . '%', PDO::PARAM_STR);
    $stmt->bindValue(3, $per_page, PDO::PARAM_INT);
    $stmt->bindValue(4, $offset, PDO::PARAM_INT);
} else {
    $stmt->bindValue(1, $per_page, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
}
$stmt->execute();
$travelers = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['travelers' => $travelers, 'totalPages' => $totalPages]);
?>
