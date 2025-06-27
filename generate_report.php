<?php
// generate_report.php
include 'DB_Bifrost.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="detailed_report.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['Tour ID', 'Title', 'Description', 'Price', 'Location', 'Start Date', 'End Date', 'Status', 'Total Bookings']);
$query = "SELECT t.tour_id, t.title, t.description, t.price, t.location, t.start_date, t.end_date, t.status, 
         (SELECT COUNT(*) FROM bookings WHERE tour_id = t.tour_id AND status = 'Confirmed') AS totalBookings
         FROM tours t";
$stmt = $pdo->query($query);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
