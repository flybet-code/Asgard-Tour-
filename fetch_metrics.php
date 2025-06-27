<?php
// fetch_metrics.php
include 'DB_Bifrost.php';
header('Content-Type: application/json');
try {
    $stmt1 = $pdo->query("SELECT COUNT(*) AS totalUsers FROM users");
    $totalUsers = $stmt1->fetch(PDO::FETCH_ASSOC)['totalUsers'];

    $stmt2 = $pdo->query("SELECT COUNT(*) AS totalTours FROM tours");
    $totalTours = $stmt2->fetch(PDO::FETCH_ASSOC)['totalTours'];

    $stmt3 = $pdo->prepare("SELECT COALESCE(SUM(T.price), 0) AS totalRevenue FROM bookings B JOIN tours T ON B.tour_id = T.tour_id WHERE B.status = 'Confirmed'");
    $stmt3->execute();
    $totalRevenue = $stmt3->fetch(PDO::FETCH_ASSOC)['totalRevenue'];

    $stmt4 = $pdo->query("SELECT COUNT(*) AS totalBookings FROM bookings");
    $totalBookings = $stmt4->fetch(PDO::FETCH_ASSOC)['totalBookings'];

    echo json_encode([
        'totalUsers' => $totalUsers,
        'totalTours' => $totalTours,
        'totalRevenue' => $totalRevenue,
        'totalBookings' => $totalBookings
    ]);
} catch (Exception $e) {
    error_log("fetch_metrics error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch metrics']);
}
?>
