<?php
session_start();
include 'DB_Bifrost.php';

if (!isset($_GET['tour_id'])) {
    header("Location: tours.php");
    exit;
}
$tour_id = intval($_GET['tour_id']);

// Fetch main tour details
$stmt = $pdo->prepare("SELECT * FROM tours WHERE tour_id = :tour_id");
$stmt->execute([':tour_id' => $tour_id]);
$tour = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$tour) {
    echo "Tour not found.";
    exit;
}

// Fetch average rating for the tour
$stmt = $pdo->prepare("SELECT COALESCE(AVG(rating),0) AS average_rating FROM tour_ratings WHERE tour_id = :tour_id");
$stmt->execute([':tour_id' => $tour_id]);
$ratingData = $stmt->fetch(PDO::FETCH_ASSOC);
$average_rating = $ratingData['average_rating'];

// Fetch gallery images for the tour
$stmt = $pdo->prepare("SELECT * FROM galleryimages WHERE tour_id = :tour_id");
$stmt->execute([':tour_id' => $tour_id]);
$galleryImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch videos from the multimedia table (only those with media_type = 'Video')
$stmt = $pdo->prepare("SELECT * FROM multimedia WHERE tour_id = :tour_id AND media_type = 'Video'");
$stmt->execute([':tour_id' => $tour_id]);
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($tour['title']); ?> - Tour Information</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Custom CSS -->
  <style>
    /* Fixed background image with dark blue overlay */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(rgba(0,0,139,0.5), rgba(0,0,139,0.5)), url('image.jpg') fixed center/cover;
      color: #333;
    }
    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1050;
      background-color: transparent;
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
      padding: 20px;
      background-color: rgba(255,255,255,0.9);
      border-radius: 8px;
      max-width: 1200px;
      margin-left: auto;
      margin-right: auto;
    }
    .tour-title {
      font-size: 2.5rem;
      font-weight: bold;
    }
    .tour-summary {
      font-size: 1.2rem;
      margin-bottom: 15px;
    }
    .rating-stars i {
      color: #ffc107;
      font-size: 1.5rem;
    }
    .badge-featured {
      background-color: #28a745;
    }
    .badge-recommended {
      background-color: #17a2b8;
    }
    .gallery-img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      cursor: pointer;
      margin-bottom: 10px;
    }
    .video-thumb {
      width: 100%;
      height: 150px;
      object-fit: cover;
      cursor: pointer;
      margin-bottom: 10px;
      position: relative;
    }
    .section-title {
      margin-top: 30px;
      margin-bottom: 15px;
      font-size: 1.8rem;
      font-weight: bold;
    }
    .book-now-btn {
      font-size: 1.2rem;
      padding: 10px 20px;
    }
  </style>
</head>
<body>
  <!-- Header -->
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
            <?php if (!isset($_SESSION['user_id'])): ?>
              <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  
  <!-- Main Tour Information -->
  <div class="main-content">
    <div class="row">
      <!-- Main Image -->
      <div class="col-md-6">
        <img src="<?php echo htmlspecialchars($tour['main_image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($tour['title']); ?>">
      </div>
      <!-- Tour Details -->
      <div class="col-md-6">
        <h1 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h1>
        <p class="tour-summary"><?php echo htmlspecialchars(substr($tour['description'], 0, 150)) . '...'; ?></p>
        <p>
          <strong>Price:</strong> ETB <?php echo number_format($tour['price'], 2); ?> &nbsp; | &nbsp;
          <i class="bi bi-calendar-event"></i> <?php echo date("M d, Y", strtotime($tour['start_date'])); ?> - <?php echo date("M d, Y", strtotime($tour['end_date'])); ?>
        </p>
        <p class="rating-stars">
          <?php 
            for ($i = 1; $i <= 5; $i++) {
              echo ($i <= round($average_rating)) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
            }
            echo " (" . number_format($average_rating, 1) . ")";
          ?>
        </p>
        <!-- Tour Status Badges -->
        <?php if ($tour['featured']): ?>
          <span class="badge badge-featured">Featured</span>
        <?php endif; ?>
        <?php if ($tour['recommended']): ?>
          <span class="badge badge-recommended">Recommended</span>
        <?php endif; ?>
        <br><br>
        <button class="btn btn-primary book-now-btn" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
      </div>
    </div>
    
    <!-- Detailed Description -->
    <div class="section-title">Detailed Description</div>
    <p><?php echo nl2br(htmlspecialchars($tour['description'])); ?></p>
    
    <!-- Location Information -->
    <div class="section-title">Location</div>
    <p><?php echo htmlspecialchars($tour['location']); ?></p>
    <!-- You could integrate an embedded map here using the location -->
    
    <!-- Image Gallery -->
    <?php if (count($galleryImages) > 0): ?>
      <div class="section-title">Image Gallery</div>
      <div class="row">
        <?php foreach ($galleryImages as $img): ?>
          <div class="col-md-3">
            <img src="<?php echo htmlspecialchars($img['image_url']); ?>" class="gallery-img" alt="Gallery Image" data-bs-toggle="modal" data-bs-target="#imageModal" data-image="<?php echo htmlspecialchars($img['image_url']); ?>">
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    
    <!-- Video Gallery -->
    <?php if (count($videos) > 0): ?>
      <div class="section-title">Video Gallery</div>
      <div class="row">
        <?php foreach ($videos as $video): ?>
          <div class="col-md-3">
            <div class="video-thumb" data-bs-toggle="modal" data-bs-target="#videoModal" data-video="<?php echo htmlspecialchars($video['media_url']); ?>">
              <!-- Assuming the media_url is a YouTube link; we extract the video id to display a thumbnail -->
              <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars(basename($video['media_url'])); ?>/hqdefault.jpg" class="img-fluid" alt="Video Thumbnail">
              <div class="position-absolute top-50 start-50 translate-middle">
                <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #fff;"></i>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  
  <!-- Image Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body p-0">
          <img src="" id="modalImage" class="img-fluid" alt="Expanded Image">
        </div>
      </div>
    </div>
  </div>
  
  <!-- Video Modal -->
  <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="ratio ratio-16x9">
            <iframe src="" id="modalVideo" frameborder="0" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Booking Confirmation Modal -->
  <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form method="post" action="book_tour.php">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Booking</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php if (isset($_SESSION['user_id'])): ?>
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                <div class="alert alert-danger">
                  <i class="bi bi-exclamation-triangle-fill"></i> Admins cannot book tours. Only travelers can book tours.
                </div>
              <?php else: ?>
                <p>Do you want to book <strong><?php echo htmlspecialchars($tour['title']); ?></strong>?</p>
                <p><strong>Tour Dates:</strong> <?php echo date("M d, Y", strtotime($tour['start_date'])); ?> - <?php echo date("M d, Y", strtotime($tour['end_date'])); ?></p>
                <p><strong>Total Price:</strong> ETB <?php echo number_format($tour['price'], 2); ?></p>
                <input type="hidden" name="tour_id" value="<?php echo $tour['tour_id']; ?>">
              <?php endif; ?>
            <?php else: ?>
              <p>You need to <a href="signup.php">sign up</a> or <a href="signup.php">log in</a> to book this tour.</p>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <?php if (isset($_SESSION['user_id'])): ?>
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <?php else: ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Yes, Book Now</button>
              <?php endif; ?>
            <?php else: ?>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <?php endif; ?>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  <!-- Bootstrap JS and Modal Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Handle Image Modal
    var imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function (event) {
      var trigger = event.relatedTarget;
      var imageUrl = trigger.getAttribute('data-image');
      document.getElementById('modalImage').src = imageUrl;
    });
    
    // Handle Video Modal
    var videoModal = document.getElementById('videoModal');
    videoModal.addEventListener('show.bs.modal', function (event) {
      var trigger = event.relatedTarget;
      var videoUrl = trigger.getAttribute('data-video');
      // If the URL is from YouTube, append autoplay parameter
      if (videoUrl.indexOf('youtube') !== -1) {
          videoUrl += (videoUrl.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1';
      }
      document.getElementById('modalVideo').src = videoUrl;
    });
    videoModal.addEventListener('hidden.bs.modal', function () {
      document.getElementById('modalVideo').src = '';
    });
  </script>
</body>
</html>
