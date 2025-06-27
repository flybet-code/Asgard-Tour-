<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "asgardtour";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$dateRange = isset($_GET['dateRange']) ? $_GET['dateRange'] : 'last12';
$tourType  = isset($_GET['tourType']) ? $_GET['tourType'] : 'all';

$intervalMonths = 12;
if ($dateRange == 'last6') {
    $intervalMonths = 6;
} elseif ($dateRange == 'last3') {
    $intervalMonths = 3;
}

$filter = "";
if ($tourType != 'all') {
    $tourType = $conn->real_escape_string($tourType);
    $filter = " AND t.title LIKE '%$tourType%'";
}

$sql = "SELECT t.location AS label, SUM(t.price) AS revenue 
        FROM bookings b 
        JOIN tours t ON b.tour_id = t.tour_id 
        WHERE b.status='Confirmed'
          AND b.booking_date >= DATE_SUB(CURRENT_DATE(), INTERVAL $intervalMonths MONTH)
          $filter
        GROUP BY t.location
        ORDER BY revenue DESC";

$result = $conn->query($sql);

$labels = [];
$revenues = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['label'];
    $revenues[] = (float)$row['revenue'];
}

echo json_encode(["labels" => $labels, "revenues" => $revenues]);
$conn->close();
?>
