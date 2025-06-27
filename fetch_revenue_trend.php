<?php
// fetch_revenue_trend.php
include 'DB_Bifrost.php';
header('Content-Type: application/json');
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'daily';
try {
    if ($filter == 'daily') {
        $query = "SELECT DATE(booking_date) AS date, COALESCE(SUM(T.price), 0) AS revenue 
                  FROM bookings B JOIN tours T ON B.tour_id = T.tour_id 
                  WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                    AND B.status = 'Confirmed'
                  GROUP BY DATE(booking_date)
                  ORDER BY DATE(booking_date) ASC";
    } elseif ($filter == 'weekly') {
        $query = "SELECT CONCAT(YEAR(booking_date), '-W', WEEK(booking_date)) AS date, COALESCE(SUM(T.price), 0) AS revenue 
                  FROM bookings B JOIN tours T ON B.tour_id = T.tour_id 
                  WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY) 
                    AND B.status = 'Confirmed'
                  GROUP BY YEAR(booking_date), WEEK(booking_date)
                  ORDER BY YEAR(booking_date), WEEK(booking_date) ASC";
    } elseif ($filter == 'monthly') {
        $query = "SELECT DATE_FORMAT(booking_date, '%Y-%m') AS date, COALESCE(SUM(T.price), 0) AS revenue 
                  FROM bookings B JOIN tours T ON B.tour_id = T.tour_id 
                  WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) 
                    AND B.status = 'Confirmed'
                  GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
                  ORDER BY DATE_FORMAT(booking_date, '%Y-%m') ASC";
    } else {
        $query = "SELECT DATE(booking_date) AS date, COALESCE(SUM(T.price), 0) AS revenue 
                  FROM bookings B JOIN tours T ON B.tour_id = T.tour_id 
                  WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                    AND B.status = 'Confirmed'
                  GROUP BY DATE(booking_date)
                  ORDER BY DATE(booking_date) ASC";
    }
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $labels = [];
    $values = [];
    foreach ($data as $row) {
        $labels[] = $row['date'];
        $values[] = $row['revenue'];
    }
    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (Exception $e) {
    error_log("fetch_revenue_trend error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch revenue trend data']);
}
?>
