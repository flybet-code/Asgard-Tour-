<?php
include 'DB_Bifrost.php';

/* --- Featured Tours --- */
// Select tours marked as featured and compute average rating from tour_ratings.
$featuredToursQuery = "
    SELECT t.tour_id, t.title, t.main_image, t.price,
           (SELECT IFNULL(AVG(tr.rating), 0) FROM tour_ratings tr WHERE tr.tour_id = t.tour_id) AS rating,
           t.created_at
    FROM tours t
    WHERE t.status = 'active' AND t.featured = 1
    ORDER BY t.created_at DESC
    LIMIT 5
";
$stmt = $pdo->prepare($featuredToursQuery);
$stmt->execute();
$featuredTours = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* --- Recommended Tours --- */
// Select tours marked as recommended and compute average rating.
$recommendedToursQuery = "
    SELECT t.tour_id, t.title, t.main_image, t.price, t.location,
           (SELECT IFNULL(AVG(tr.rating), 0) FROM tour_ratings tr WHERE tr.tour_id = t.tour_id) AS rating,
           t.created_at 
    FROM tours t 
    WHERE t.status = 'active' AND t.recommended = 1 
    ORDER BY t.created_at DESC 
    LIMIT 5
";
$stmt = $pdo->prepare($recommendedToursQuery);
$stmt->execute();
$recommendedTours = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* --- Popular Destinations --- */
// Get distinct active tour locations with their average ratings from tour_ratings.
$destinationsQuery = "
    SELECT t.location, ROUND(AVG(tr.rating),1) AS average_rating 
    FROM tours t 
    LEFT JOIN tour_ratings tr ON t.tour_id = tr.tour_id 
    WHERE t.status = 'active'
    GROUP BY t.location 
    ORDER BY average_rating DESC 
    LIMIT 9
";
$stmt = $pdo->prepare($destinationsQuery);
$stmt->execute();
$destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* --- Featured Blogs --- */
// Latest 3 blog posts
$blogsQuery = "SELECT blog_id, title, content, created_at, image FROM blogs ORDER BY created_at DESC LIMIT 3";
$stmt = $pdo->prepare($blogsQuery);
$stmt->execute();
$featuredBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Asgard Tour Guide - Your Ultimate Travel Companion</title>
  <meta name="description" content="Plan your trips with Asgard Tour Guide. Discover tours, read insightful blogs, and explore popular destinations.">
  <meta name="keywords" content="Tours, Blogs, Destinations, Travel, Trip Planning">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    /* ---------- Global Styles ---------- */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
    }
    /* ---------- Navigation Bar ---------- */
    .navbar-custom {
      background: url('piclumen-1743427077839.png'); /* Use header.jpg as background */
      background-size: cover;
      position: relative;
    }
    .navbar-custom::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(3,36,85,0.8);
      z-index: 0;
    }
    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link {
      position: relative;
      z-index: 1;
      color: #fff;
    }
    .navbar-custom .nav-link:hover {
      color: #ffc107;
    }
    /* ---------- Hero Section ---------- */
    .hero {
      background: url('alexander-slattery-LI748t0BK8w-unsplash.jpg'); /* herosection.jpg as background */
      background-size: cover;
      background-position: center;
      position: relative;
      text-align: center;
      padding: 120px 0;
      color: #fff;
    }
    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.8);
      z-index: 0;
    }
    .hero .container {
      position: relative;
      z-index: 1;
    }
    /* ---------- Section Overlays ---------- */
    section {
      position: relative;
      background-size: cover;
      background-position: center;
    }
    section::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.8);
      z-index: 0;
    }
    .section-content {
      position: relative;
      z-index: 1;
      padding: 60px 0;
      color: #fff;
    }
    /* ---------- Specific Section Backgrounds ---------- */
    #featured-tours { background: url('jms-kFHz9Xh3PPU-unsplash.jpg'); }
    #popular-destinations { background: url('stefan-stefancik--g7axSVst6Y-unsplash.jpg'); }
    #featured-blogs { background: url('greg-rakozy-0LU4vO5iFpM-unsplash.jpg'); }
    #recommended-tours { background: url('greg-rakozy-0LU4vO5iFpM-unsplash.jpg'); }
    #newsletter { background: url('greg-rakozy-0LU4vO5iFpM-unsplash.jpg'); }
    /* ---------- Card Styles ---------- */
    .carousel-caption {
      background: rgba(0,0,0,0.5);
      border-radius: 5px;
      padding: 10px;
    }
    .card, .blog-card, .destination-card, .tour-card {
      transition: transform 0.2s;
      cursor: pointer;
    }
    .card:hover, .blog-card:hover, .destination-card:hover, .tour-card:hover {
      transform: scale(1.03);
    }
    /* Force card image size to 400x400 for multi-item carousels */
    .tour-card img {
      width: 400px;
      height: 400px;
      object-fit: cover;
    }
    /* ---------- Star Rating ---------- */
    .stars {
      color: #ffc107;
    }
    /* ---------- Footer ---------- */
    footer {
      background-color: #343a40;
      color: #fff;
      padding: 20px 0;
      text-align: center;
    }
    /* ---------- Multi-Item Carousel Styles ---------- */
    .carousel-item .row > div {
      padding: 15px;
    }
  </style>
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="bi bi-globe2"></i> AsgardTour</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="tours.php">Tours</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="blogs.php" id="blogsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Blogs
            </a>
            <ul class="dropdown-menu" aria-labelledby="blogsDropdown">
              <li><a class="dropdown-item" href="blogs.php?cat=travel-tips">Travel Tips</a></li>
              <li><a class="dropdown-item" href="blogs.php?cat=destination-guides">Destination Guides</a></li>
              <li><a class="dropdown-item" href="blogs.php?cat=experiences">Experiences</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <header class="hero">
    <div class="container">
      <h1>Discover Your Next Adventure</h1>
      <p>Explore our curated tours, read engaging travel blogs, and plan your dream trip with ease.</p>
      <a href="tours.php" class="btn btn-primary btn-lg"><i class="bi bi-compass"></i> Explore Tours</a>
    </div>
  </header>

  <!-- Section 1: Featured Tours (Multi-Item Carousel) -->
  <section id="featured-tours">
    <div class="section-content container">
      <h2 class="mb-4">Featured Tours</h2>
      <div id="featuredToursCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
          // Group featured tours into chunks of 3
          $featuredChunks = array_chunk($featuredTours, 3);
          foreach($featuredChunks as $index => $chunk): ?>
            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
              <div class="row">
                <?php foreach($chunk as $tour): ?>
                  <div class="col-md-4">
                    <div class="card tour-card">
                      <img src="<?php echo htmlspecialchars($tour['main_image']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                      <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($tour['title']); ?></h5>
                        <p class="card-text">
                          <?php echo htmlspecialchars($tour['location']); ?><br>
                          $<?php echo number_format($tour['price'], 2); ?><br>
                          Rating: <?php echo htmlspecialchars($tour['rating']); ?>
                        </p>
                        <a href="tourviews.php?tour_id=<?php echo $tour['tour_id']; ?>" class="btn btn-primary">Book Now</a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#featuredToursCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#featuredToursCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </section>

  <!-- Section 2: Popular Destinations -->
  <section id="popular-destinations">
    <div class="section-content container">
      <h2 class="mb-4">Popular Destinations</h2>
      <div class="row">
        <?php if(count($destinations) > 0): ?>
          <?php foreach($destinations as $dest): ?>
            <div class="col-md-4 mb-3">
              <div class="card destination-card h-100 shadow-sm">
                <img src="assets/destination-default.jpg" class="card-img-top" alt="<?php echo htmlspecialchars($dest['location']); ?>">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($dest['location']); ?></h5>
                  <p class="card-text">
                    Average Rating: 
                    <span class="stars">
                      <?php
                      $stars = round($dest['average_rating']);
                      for ($i = 0; $i < 5; $i++) {
                          echo ($i < $stars) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                      }
                      ?>
                    </span>
                    (<?php echo htmlspecialchars($dest['average_rating']); ?>)
                  </p>
                  <a href="destinationDetails.php?location=<?php echo urlencode($dest['location']); ?>" class="btn btn-outline-primary">Discover More</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <p class="text-center">No popular destinations available.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Section 3: Travel Tips & Guides (Featured Blogs) -->
  <section id="featured-blogs">
    <div class="section-content container">
      <h2 class="mb-4">Travel Tips &amp; Guides</h2>
      <div class="row">
        <?php if(count($featuredBlogs) > 0): ?>
          <?php foreach($featuredBlogs as $blog): ?>
            <div class="col-md-4">
              <div class="card blog-card mb-4 shadow-sm">
                <img src="<?php echo !empty($blog['image']) ? htmlspecialchars($blog['image']) : 'assets/default-blog.jpg'; ?>" class="card-img-top blog-img" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="blogDetails.php?blog_id=<?php echo $blog['blog_id']; ?>" class="text-decoration-none text-dark">
                      <?php echo htmlspecialchars($blog['title']); ?>
                    </a>
                  </h5>
                  <p class="card-text"><?php echo htmlspecialchars(substr(strip_tags($blog['content']), 0, 120)) . '...'; ?></p>
                  <a href="blogDetails.php?blog_id=<?php echo $blog['blog_id']; ?>" class="btn btn-outline-primary">Read More</a>
                </div>
                <div class="card-footer text-muted">
                  <?php echo date("F j, Y", strtotime($blog['created_at'])); ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <p class="text-center">No blog posts available at this time.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Section 4: Recommended Tours (Multi-Item Carousel) -->
  <section id="recommended-tours">
    <div class="section-content container">
      <h2 class="mb-4">Recommended Tours</h2>
      <div id="recommendedToursCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
          // Group recommended tours into chunks of 3
          $recommendedChunks = array_chunk($recommendedTours, 3);
          foreach($recommendedChunks as $index => $chunk): ?>
            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
              <div class="row justify-content-center">
                <?php foreach($chunk as $tour): ?>
                  <div class="col-md-4">
                    <div class="card tour-card recommended-card shadow-sm">
                      <img src="<?php echo htmlspecialchars($tour['main_image']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                      <div class="card-img-overlay p-0" style="background: rgba(0, 0, 0, 0.5);">
                        <div class="p-2" style="position: absolute; bottom: 0; width: 100%;">
                          <h6 class="text-white mb-1"><?php echo htmlspecialchars($tour['title']); ?></h6>
                          <p class="text-white mb-0" style="font-size: 0.9rem;">
                            $<?php echo number_format($tour['price'], 2); ?><br>
                            <?php echo htmlspecialchars($tour['location']); ?><br>
                            <span class="stars">
                              <?php
                              $stars = round($tour['rating']);
                              for ($i = 0; $i < 5; $i++) {
                                echo ($i < $stars) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                              }
                              ?>
                            </span>
                          </p>
                          <a href="tourviews.php?tour_id=<?php echo $tour['tour_id']; ?>" class="btn btn-outline-light btn-sm mt-1">Book Now</a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#recommendedToursCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#recommendedToursCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </section>

  <!-- Section 5: Newsletter Sign-Up -->
  <section id="newsletter">
    <div class="section-content container text-center">
      <h2>Stay Updated!</h2>
      <p>Subscribe to our newsletter for the latest travel tips, exclusive offers, and inspiring stories.</p>
      <form id="newsletterForm" action="newsletter_subscribe.php" method="POST" class="d-flex justify-content-center">
        <input type="email" name="email" class="form-control w-50 me-2" placeholder="Enter your email" required>
        <button type="submit" class="btn btn-primary"><i class="bi bi-envelope"></i> Subscribe</button>
      </form>
    </div>
  </section>

  <!-- Footer Section -->
  <footer>
    <div class="container">
      <p>&copy; <?php echo date("Y"); ?> AsgardTour. All rights reserved.</p>
      <ul class="list-inline">
        <li class="list-inline-item"><a href="privacy.php" class="text-decoration-none text-white">Privacy Policy</a></li>
        <li class="list-inline-item"><a href="terms.php" class="text-decoration-none text-white">Terms &amp; Conditions</a></li>
        <li class="list-inline-item"><a href="contact.php" class="text-decoration-none text-white">Contact Us</a></li>
      </ul>
      <div class="mt-2">
        <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
        <a href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
        <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
        <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
      </div>
    </div>
  </footer>

  <!-- JavaScript Dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
