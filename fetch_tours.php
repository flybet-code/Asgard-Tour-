<?php
header('Content-Type: application/json');
$pdo = include 'DB_Bifrost.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM tours");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
