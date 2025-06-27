<?php
require 'DB_Bifrost.php'; // Ensure database connection

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["error" => "Tour ID is required"]);
    exit;
}

$tour_id = intval($_GET['id']);

// Fetch tour details
$stmt = $pdo->prepare("SELECT * FROM Tours WHERE tour_id = ?");
$stmt->execute([$tour_id]);
$tour = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tour) {
    echo json_encode(["error" => "Tour not found"]);
    exit;
}

// Fetch images
$imgStmt = $pdo->prepare("SELECT image_url FROM tour_images WHERE tour_id = ?");
$imgStmt->execute([$tour_id]);
$images = $imgStmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch videos
$vidStmt = $pdo->prepare("SELECT video_url FROM tour_videos WHERE tour_id = ?");
$vidStmt->execute([$tour_id]);
$videos = $vidStmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch bookings
$bookStmt = $pdo->prepare("SELECT users.name, users.email, bookings.date, bookings.status FROM bookings 
                           JOIN users ON bookings.user_id = users.id WHERE bookings.tour_id = ?");
$bookStmt->execute([$tour_id]);
$bookings = $bookStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch reviews
$reviewStmt = $pdo->prepare("SELECT users.name AS user, reviews.rating, reviews.comment FROM reviews 
                             JOIN users ON reviews.user_id = users.id WHERE reviews.tour_id = ?");
$reviewStmt->execute([$tour_id]);
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate rating
$ratingStmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM reviews WHERE tour_id = ?");
$ratingStmt->execute([$tour_id]);
$ratingData = $ratingStmt->fetch(PDO::FETCH_ASSOC);

$tourData = [
    "id" => $tour['tour_id'],
    "title" => $tour['title'],
    "status" => $tour['status'] == 1 ? "Active" : "Inactive",
    "description" => $tour['description'],
    "price" => number_format($tour['price'], 2),
    "location" => $tour['location'],
    "start_date" => $tour['start_date'],
    "end_date" => $tour['end_date'],
    "rating" => round($ratingData['avg_rating'], 1),
    "review_count" => $ratingData['review_count'],
    "images" => $images,
    "videos" => $videos,
    "bookings" => $bookings,
    "reviews" => $reviews
];

echo json_encode($tourData);
?>
