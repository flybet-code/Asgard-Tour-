<?php
// tours.php
session_start();
include 'DB_Bifrost.php';

// Get filter parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

// Default ranges
$defaultPriceMin = 0;
$defaultPriceMax = 100000;
$defaultRatingMin = 0;
$defaultRatingMax = 5;

// Get price filter values from GET parameters, if available
$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : $defaultPriceMin;
$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : $defaultPriceMax;

// Get rating filter values from GET parameters, if available
$rating_min = isset($_GET['rating_min']) ? floatval($_GET['rating_min']) : $defaultRatingMin;
$rating_max = isset($_GET['rating_max']) ? floatval($_GET['rating_max']) : $defaultRatingMax;

// Get search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build base query parts
$sql_where = "WHERE t.status = 'active' AND t.price BETWEEN :price_min AND :price_max";
if ($search !== '') {
    $sql_where .= " AND (t.title LIKE :search OR t.description LIKE :search)";
}
$sql_group = "GROUP BY t.tour_id";
// Use HAVING for rating range filter
$sql_having = "HAVING COALESCE(AVG(tr.rating),0) BETWEEN :rating_min AND :rating_max";

// Final query: sort by average rating (desc) then creation date desc, and add pagination
$query = "
  SELECT 
    t.tour_id, 
    t.title, 
    t.description, 
    t.price, 
    t.main_image, 
    t.start_date, 
    t.end_date, 
    t.location,
    COALESCE(AVG(tr.rating), 0) AS average_rating
  FROM tours t
  LEFT JOIN tour_ratings tr ON t.tour_id = tr.tour_id
  $sql_where
  $sql_group
  $sql_having
  ORDER BY average_rating DESC, t.created_at DESC
  LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':price_min', $price_min);
$stmt->bindValue(':price_max', $price_max);
if ($search !== '') {
    $stmt->bindValue(':search', '%' . $search . '%');
}
$stmt->bindValue(':rating_min', $rating_min);
$stmt->bindValue(':rating_max', $rating_max);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total filtered tours for pagination (using a subquery)
$count_query = "
  SELECT COUNT(*) as total FROM (
    SELECT t.tour_id
    FROM tours t
    LEFT JOIN tour_ratings tr ON t.tour_id = tr.tour_id
    $sql_where
    $sql_group
    $sql_having
  ) as sub
";
$count_stmt = $pdo->prepare($count_query);
$count_stmt->bindValue(':price_min', $price_min);
$count_stmt->bindValue(':price_max', $price_max);
if ($search !== '') {
    $count_stmt->bindValue(':search', '%' . $search . '%');
}
$count_stmt->bindValue(':rating_min', $rating_min);
$count_stmt->bindValue(':rating_max', $rating_max);
$count_stmt->execute();
$totalTours = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalTours / $limit);

// Check if this is an AJAX request
$isAjax = isset($_GET['ajax']) && $_GET['ajax'] == 1;

if ($isAjax):
  // Output only the tour grid HTML for AJAX requests
?>
  <div class="row row-cols-1 row-cols-md-3 g-4" id="tourGrid">
    <?php if(count($tours) > 0): ?>
      <?php foreach($tours as $tour): 
        $category = isset($tour['category']) ? $tour['category'] : 'Adventure';
      ?>
        <div class="col">
          <div class="card tour-card h-100" data-category="<?php echo strtolower($category); ?>" onclick="window.location.href='tourinfo.php?tour_id=<?php echo $tour['tour_id']; ?>'">
            <img src="<?php echo htmlspecialchars($tour['main_image']); ?>" class="card-img-top tour-img" alt="<?php echo htmlspecialchars($tour['title']); ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($tour['title']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars(substr($tour['description'], 0, 80)) . '...'; ?></p>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Price: ETB <?php echo number_format($tour['price'], 2); ?></li>
              <li class="list-group-item">
                Average Rating: 
                <span class="star-rating">
                  <?php 
                    $avg = $tour['average_rating'];
                    for($i = 0; $i < 5; $i++){
                      echo ($i < round($avg)) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                    }
                  ?>
                </span>
                (<?php echo number_format($tour['average_rating'], 1); ?>)
              </li>
            </ul>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">No tours available for the selected filters.</p>
    <?php endif; ?>
  </div>
  <!-- Pagination for AJAX results -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php for($p = 1; $p <= $totalPages; $p++): ?>
        <li class="page-item <?php echo ($p == $page) ? 'active' : ''; ?>">
          <a class="page-link ajax-page" href="#" data-page="<?php echo $p; ?>"><?php echo $p; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
<?php
  exit;
endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tours - Asgard Tour Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- jQuery UI for Sliders -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <!-- Custom CSS -->
  <style>
    /* Fixed background image with dark blue overlay */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(rgba(0, 0, 139, 0.5), rgba(0,0,139,0.5)), url('image.jpg') fixed center/cover;
    }
    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1050;
      background: linear-gradient(rgba(0, 0, 139, 0.5), rgba(0,0,139,0.5)), url('image.jpg') fixed center/cover;
      
    }
    .navbar-custom {
      position: relative;
      z-index: 1;
    }
    .navbar-custom .nav-link {
      color: #fff !important;
    }
    .main-content {
      margin-top: 100px;
      padding-bottom: 50px;
      position: relative;
      z-index: 1;
    }
    /* Filter Section Styles */
    .filter-container {
      background-color: #fff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .filter-title {
      font-size: 1.2rem;
      margin-bottom: 10px;
      font-weight: 500;
    }
    .filter-group {
      margin-bottom: 15px;
    }
    .tour-card {
      transition: transform 0.3s;
      cursor: pointer;
    }
    .tour-card:hover {
      transform: scale(1.02);
    }
    .tour-img {
      height: 200px;
      object-fit: cover;
    }
    .star-rating i {
      color: #ffc107;
    }
    .ui-slider-range { background: #007bff; }
    .ui-slider-handle { border-color: #007bff; }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- jQuery UI -->
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script>
    $(document).ready(function(){
      var minPrice = <?php echo $defaultPriceMin; ?>;
      var maxPrice = <?php echo $defaultPriceMax; ?>;
      var minRating = <?php echo $defaultRatingMin; ?>;
      var maxRating = <?php echo $defaultRatingMax; ?>;
      
      // Initialize Price Slider
      $("#priceSlider").slider({
        range: true,
        min: minPrice,
        max: maxPrice,
        values: [ <?php echo $price_min; ?>, <?php echo $price_max; ?> ],
        slide: function(event, ui) {
          $("#priceMinDisplay").val(ui.values[0]);
          $("#priceMaxDisplay").val(ui.values[1]);
          applyFilters();
        }
      });
      $("#priceMinDisplay").val($("#priceSlider").slider("values", 0));
      $("#priceMaxDisplay").val($("#priceSlider").slider("values", 1));
      
      // Initialize Rating Slider (0 to 5, step 0.1)
      $("#ratingSlider").slider({
        range: true,
        min: minRating,
        max: maxRating,
        step: 0.1,
        values: [ <?php echo $rating_min; ?>, <?php echo $rating_max; ?> ],
        slide: function(event, ui) {
          $("#ratingMinDisplay").val(ui.values[0]);
          $("#ratingMaxDisplay").val(ui.values[1]);
          applyFilters();
        }
      });
      $("#ratingMinDisplay").val($("#ratingSlider").slider("values", 0));
      $("#ratingMaxDisplay").val($("#ratingSlider").slider("values", 1));
      
      // Dynamic filtering on search bar
      $("#tourSearch").on("keyup", function(){
        applyFilters();
      });
      
      // On Clear Selection button click
      $("#clearFilter").click(function(){
        $("#tourSearch").val('');
        $("#priceSlider").slider("values", 0, minPrice);
        $("#priceSlider").slider("values", 1, maxPrice);
        $("#priceMinDisplay").val(minPrice);
        $("#priceMaxDisplay").val(maxPrice);
        $("#ratingSlider").slider("values", 0, minRating);
        $("#ratingSlider").slider("values", 1, maxRating);
        $("#ratingMinDisplay").val(minRating);
        $("#ratingMaxDisplay").val(maxRating);
        applyFilters();
      });
      
      // Handle pagination clicks (delegated)
      $("#toursContainer").on("click", ".ajax-page", function(e){
        e.preventDefault();
        var page = $(this).data("page");
        applyFilters(page);
      });
      
      // Function to apply filters dynamically
      function applyFilters(page = 1) {
        var price_min = $("#priceMinDisplay").val();
        var price_max = $("#priceMaxDisplay").val();
        var rating_min = $("#ratingMinDisplay").val();
        var rating_max = $("#ratingMaxDisplay").val();
        var search = $("#tourSearch").val();
        $.ajax({
          url: 'tours.php',
          type: 'GET',
          data: {
            ajax: 1,
            page: page,
            price_min: price_min,
            price_max: price_max,
            rating_min: rating_min,
            rating_max: rating_max,
            search: search
          },
          success: function(data){
            $("#toursContainer").html(data);
          }
        });
      }
    });
  </script>
</head>
<body>
  <!-- Header with Navigation -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-globe2"></i> AsgardTour</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto gap-3">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link active" href="tours.php">Tours</a></li>
            <li class="nav-item"><a class="nav-link" href="blogs.php">Blogs</a></li>
            <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            <?php if(!isset($_SESSION['user_id'])): ?>
              <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="container main-content">
    <!-- Filter Options -->
    <div class="filter-container">
      <div class="filter-title">Refine Your Results</div>
      <div class="row mb-3">
        <!-- Search Bar -->
        <div class="col-md-12">
          <input type="text" id="tourSearch" class="form-control" placeholder="Search tours by keyword..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
      </div>
      <div class="row">
        <!-- Price Filter -->
        <div class="col-md-6 filter-group">
          <label><strong>Price Filter (ETB):</strong></label>
          <div id="priceSlider" style="margin:10px 0;"></div>
          <div class="d-flex justify-content-between">
            <input type="number" id="priceMinDisplay" class="form-control w-25" readonly>
            <input type="number" id="priceMaxDisplay" class="form-control w-25" readonly>
          </div>
          <small class="text-muted">Adjust the slider or values to refine by price.</small>
        </div>
        <!-- Rating Filter -->
        <div class="col-md-6 filter-group">
          <label><strong>Rating Filter:</strong></label>
          <div id="ratingSlider" style="margin:10px 0;"></div>
          <div class="d-flex justify-content-between">
            <input type="number" id="ratingMinDisplay" class="form-control w-25" readonly>
            <input type="number" id="ratingMaxDisplay" class="form-control w-25" readonly>
          </div>
          <small class="text-muted">Adjust the slider to select rating range (0 to 5).</small>
        </div>
      </div>
      <div class="d-flex justify-content-end mt-2">
        <button id="clearFilter" class="btn btn-secondary">Clear Selection</button>
      </div>
    </div>

    <!-- Tour Grid & Pagination Container -->
    <div id="toursContainer">
      <div class="row row-cols-1 row-cols-md-3 g-4" id="tourGrid">
        <?php if(count($tours) > 0): ?>
          <?php foreach($tours as $tour): 
            $category = isset($tour['category']) ? $tour['category'] : 'Adventure';
          ?>
            <div class="col">
              <div class="card tour-card h-100" data-category="<?php echo strtolower($category); ?>" onclick="window.location.href='tourinfo.php?tour_id=<?php echo $tour['tour_id']; ?>'">
                <img src="<?php echo htmlspecialchars($tour['main_image']); ?>" class="card-img-top tour-img" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($tour['title']); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars(substr($tour['description'], 0, 80)) . '...'; ?></p>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Price: ETB <?php echo number_format($tour['price'], 2); ?></li>
                  <li class="list-group-item">
                    Average Rating: 
                    <span class="star-rating">
                      <?php 
                        $avg = $tour['average_rating'];
                        for($i = 0; $i < 5; $i++){
                          echo ($i < round($avg)) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                        }
                      ?>
                    </span>
                    (<?php echo number_format($tour['average_rating'], 1); ?>)
                  </li>
                </ul>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center">No tours available at this time.</p>
        <?php endif; ?>
      </div>
      <!-- Pagination -->
      <nav>
        <ul class="pagination justify-content-center">
          <?php for($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?php echo ($p == $page) ? 'active' : ''; ?>">
              <a class="page-link ajax-page" href="#" data-page="<?php echo $p; ?>"><?php echo $p; ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
