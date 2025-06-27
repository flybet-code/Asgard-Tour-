<?php
// getTours.php
include 'DB_Bifrost.php'; // Ensure $pdo is properly initialized

try {
    // Get parameters
    $page      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page  = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
    $search    = isset($_GET['search']) ? $_GET['search'] : '';
    $location  = isset($_GET['location']) ? $_GET['location'] : '';
    $minPrice  = isset($_GET['minPrice']) ? (float)$_GET['minPrice'] : 0;
    $maxPrice  = isset($_GET['maxPrice']) ? (float)$_GET['maxPrice'] : 0;
    $rating    = isset($_GET['rating']) ? (float)$_GET['rating'] : 0;
    $sort      = isset($_GET['sort']) ? $_GET['sort'] : 'title';
    $order     = isset($_GET['order']) ? $_GET['order'] : 'ASC';
    
    $offset = ($page - 1) * $per_page;
    
    // Build dynamic WHERE clause
    $where = " WHERE 1=1 ";
    $params = [];
    
    // Filter by search (in title or location)
    if (!empty($search)) {
        $where .= " AND (title LIKE ? OR location LIKE ?)";
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }
    
    // Filter by location (exact match)
    if (!empty($location)) {
        $where .= " AND location = ?";
        $params[] = $location;
    }
    
    // Filter by minimum price
    if ($minPrice > 0) {
        $where .= " AND price >= ?";
        $params[] = $minPrice;
    }
    
    // Filter by maximum price
    if ($maxPrice > 0) {
        $where .= " AND price <= ?";
        $params[] = $maxPrice;
    }
    
    // Filter by minimum rating
    if ($rating > 0) {
        $where .= " AND rating >= ?";
        $params[] = $rating;
    }
    
    // Count total tours matching filters
    $countQuery = "SELECT COUNT(*) AS total FROM tours $where";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute($params);
    $totalRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $totalRow ? $totalRow['total'] : 0;
    $totalPages = ceil($total / $per_page);
    
    // Append pagination parameters to query
    $query = "SELECT tour_id, title, description, price, start_date, end_date, location, main_image, video_url, rating, created_at, status 
              FROM tours 
              $where 
              ORDER BY $sort $order 
              LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode(['tours' => $tours, 'totalPages' => $totalPages]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}
?>
