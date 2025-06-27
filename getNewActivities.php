<?php
/**
 * getNewActivities.php
 *
 * Purpose: Retrieve recent activities including new user registrations and bookings.
 * Note: This example returns two lists (registrations and bookings) as a JSON object.
 */

include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    // Get recent user registrations (limit to 5)
    $stmt1 = $pdo->prepare("SELECT user_id, name, email, created_at FROM Users ORDER BY created_at DESC LIMIT 5");
    $stmt1->execute();
    $recentRegistrations = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Get recent bookings (limit to 5)
    $stmt2 = $pdo->prepare("SELECT booking_id, user_id, tour_id, booking_date, status FROM Bookings ORDER BY booking_date DESC LIMIT 5");
    $stmt2->execute();
    $recentBookings = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Merge results into one JSON response
    $activities = [
        'registrations' => $recentRegistrations,
        'bookings'      => $recentBookings
    ];

    echo json_encode($activities);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to retrieve new activities.']);
}
?>
