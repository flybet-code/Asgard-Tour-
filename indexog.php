<?php
// index.php
include 'DB_Bifrost.php'; // Database connection

// Fetch 3 featured blog posts (modify LIMIT as desired)
$query = "SELECT blog_id, title, content, created_at FROM blogs ORDER BY created_at DESC LIMIT 3";
$stmt = $pdo->prepare($query);
$stmt->execute();
$featuredBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Asgard Tour Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    /* Navigation Bar */
    .navbar-custom {
      background: linear-gradient(135deg, #007bff, #00d4ff);
    }
    .navbar-custom .nav-link, .navbar-custom .navbar-brand {
      color: #fff;
    }
    .navbar-custom .nav-link:hover {
      color: #ffc107;
    }
    /* Featured Blogs Section */
    .blog-card {
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .blog-card:hover {
      transform: scale(1.02);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .blog-img {
      height: 200px;
      object-fit: cover;
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
            <a class="nav-link dropdown-toggle" href="blogs.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Blogs
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
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

  <!-- Hero Section (Optional) -->
  <header class="py-5 text-center bg-light">
    <div class="container">
      <h1 class="display-4">Welcome to AsgardTour</h1>
      <p class="lead">Discover incredible destinations, plan your perfect trip, and read inspiring travel blogs.</p>
      <a href="tours.php" class="btn btn-primary btn-lg">Explore Tours</a>
    </div>
  </header>

  <!-- Featured Blogs Section -->
  <section id="featured-blogs" class="py-5">
    <div class="container">
      <h2 class="mb-4">Featured Blogs</h2>
      <div class="row">
        <?php if(count($featuredBlogs) > 0): ?>
          <?php foreach($featuredBlogs as $blog): ?>
            <div class="col-md-4">
              <div class="card blog-card mb-4">
                <!-- Blog Image: If your blogs table has no image field, use a default placeholder -->
                <img src="<?php echo isset($blog['image']) ? $blog['image'] : 'default-blog.jpg'; ?>" class="card-img-top blog-img" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="blogDetails.php?blog_id=<?php echo $blog['blog_id']; ?>" class="text-decoration-none">
                      <?php echo htmlspecialchars($blog['title']); ?>
                    </a>
                  </h5>
                  <p class="card-text">
                    <?php echo htmlspecialchars(substr(strip_tags($blog['content']), 0, 100)) . '...'; ?>
                  </p>
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
            <p>No blog posts available.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-4 bg-dark text-white text-center">
    <div class="container">
      <p class="mb-0">&copy; <?php echo date("Y"); ?> AsgardTour. All rights reserved.</p>
    </div>
  </footer>

  <!-- JavaScript Dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
