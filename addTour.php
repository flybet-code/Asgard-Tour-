<?php
// addTour.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: loginandregister.php");
    exit();
}

// Start output buffering
ob_start();

// Enable error reporting (development only)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'DB_Bifrost.php'; // Database connection

// Helper function to sanitize tour title for folder names
function sanitizeTitle($title) {
    return preg_replace('/[^A-Za-z0-9_\-]/', '', $title);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajaxSubmit'])) {
    // Collect basic tour data
    $title         = $_POST['title'] ?? '';
    $description   = $_POST['description'] ?? '';
    $price         = $_POST['price'] ?? 0;
    $location      = $_POST['location'] ?? '';
    $start_date    = $_POST['start_date'] ?? '';
    $end_date      = $_POST['end_date'] ?? '';
    $status        = isset($_POST['status']) ? 'active' : 'inactive';
    $main_video_url = $_POST['main_video_url'] ?? '';
    $featured      = isset($_POST['featured']) ? 1 : 0;
    $recommended   = isset($_POST['recommended']) ? 1 : 0;
    
    // Determine folder structure based on tour title
    $sanitizedTitle = sanitizeTitle($title);
    $baseDir = "uploads/$sanitizedTitle/";
    $mainImageDir = $baseDir . "main image/";
    $galleryDir   = $baseDir . "gallery/";
    $videoDir     = $baseDir . "videos/";
    
    // Create directories if they don't exist
    if (!is_dir($mainImageDir)) {
        mkdir($mainImageDir, 0777, true);
    }
    if (!is_dir($galleryDir)) {
        mkdir($galleryDir, 0777, true);
    }
    if (!is_dir($videoDir)) {
        mkdir($videoDir, 0777, true);
    }
    
    // Process main thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $filename  = time() . '_' . basename($_FILES['thumbnail']['name']);
        $targetFile = $mainImageDir . $filename;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile)) {
            $thumbnail = $targetFile;
        } else {
            $thumbnail = null;
        }
    } else {
        $thumbnail = null;
    }
    
    // Insert main tour record into tours table
    try {
        $stmt = $pdo->prepare("INSERT INTO tours (title, description, price, start_date, end_date, location, main_image, video_url, status, featured, recommended, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$title, $description, $price, $start_date, $end_date, $location, $thumbnail, $main_video_url, $status, $featured, $recommended]);
        $tour_id = $pdo->lastInsertId();
    } catch (Exception $e) {
        // Clear any buffered output and return error JSON
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error adding tour: ' . $e->getMessage()]);
        exit();
    }
    
    // Process multiple gallery images upload
    if (isset($_FILES['gallery_images'])) {
        $galleryFiles = $_FILES['gallery_images'];
        for ($i = 0; $i < count($galleryFiles['name']); $i++) {
            if ($galleryFiles['error'][$i] == 0) {
                $filename = time() . '_' . basename($galleryFiles['name'][$i]);
                $targetFile = $galleryDir . $filename;
                if (move_uploaded_file($galleryFiles['tmp_name'][$i], $targetFile)) {
                    $stmt = $pdo->prepare("INSERT INTO galleryimages (tour_id, image_url, uploaded_at) VALUES (?, ?, NOW())");
                    $stmt->execute([$tour_id, $targetFile]);
                }
            }
        }
    }
    
    // Process multiple video file uploads
    if (isset($_FILES['video_files'])) {
        $videoFiles = $_FILES['video_files'];
        for ($i = 0; $i < count($videoFiles['name']); $i++) {
            if ($videoFiles['error'][$i] == 0) {
                $filename = time() . '_' . basename($videoFiles['name'][$i]);
                $targetFile = $videoDir . $filename;
                if (move_uploaded_file($videoFiles['tmp_name'][$i], $targetFile)) {
                    $stmt = $pdo->prepare("INSERT INTO videogallery (tour_id, video_url, uploaded_at) VALUES (?, ?, NOW())");
                    $stmt->execute([$tour_id, $targetFile]);
                }
            }
        }
    }
    
    // Process multiple video URLs
    if (isset($_POST['video_urls']) && is_array($_POST['video_urls'])) {
        foreach ($_POST['video_urls'] as $videoUrl) {
            $videoUrl = trim($videoUrl);
            if (!empty($videoUrl)) {
                $stmt = $pdo->prepare("INSERT INTO videogallery (tour_id, video_url, uploaded_at) VALUES (?, ?, NOW())");
                $stmt->execute([$tour_id, $videoUrl]);
            }
        }
    }
    
    // Clear the output buffer and send JSON response
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Tour added successfully.']);
    exit();
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Tour - Asgard Tour Guide</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
    }
    header.admin-banner {
      background: linear-gradient(135deg, #007bff, #00d4ff);
      color: #fff;
      padding: 30px 20px;
      text-align: center;
      animation: slideIn 1s ease-out;
    }
    header.admin-banner h1 {
      font-size: 2.5rem;
      font-weight: bold;
      margin-bottom: 5px;
    }
    header.admin-banner p {
      font-size: 1.25rem;
      font-style: italic;
    }
    header.admin-banner .nav-links a {
      color: #fff;
      margin: 0 10px;
      text-decoration: none;
      transition: color 0.3s;
    }
    header.admin-banner .nav-links a:hover {
      color: #ffc107;
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .card-section { margin-bottom: 20px; }
    .upload-box {
      border: 2px dashed #ced4da;
      padding: 30px;
      text-align: center;
      color: #6c757d;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .upload-box.dragover { background-color: #e9ecef; }
    .thumbnail-preview img {
      max-height: 100px;
      margin: 5px;
      border-radius: 5px;
      transition: opacity 0.3s;
    }
    .thumbnail-preview img:hover { opacity: 0.8; }
    .btn-action { transition: transform 0.2s, background-color 0.2s; }
    .btn-action:hover { transform: scale(1.05); }
    footer {
      text-align: center;
      padding: 15px;
      background-color: #343a40;
      color: #fff;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <!-- Header Section -->
  <header class="admin-banner">
    <div class="nav-links mb-3">
      <a href="adminpanel.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="manageTours.php"><i class="bi bi-geo-alt"></i> Manage Tours</a>
      <a href="viewReports.php"><i class="bi bi-bar-chart-line"></i> Reports</a>
    </div>
    <h1><i class="bi bi-plus-circle"></i> Add New Tour</h1>
    <p>Enter the tour details below to add a new tour to the system.</p>
  </header>

  <div class="container my-4">
    <!-- Tour Details Form -->
    <form id="addTourForm" method="POST" enctype="multipart/form-data">
      <!-- Basic Information Card -->
      <div class="card card-section">
        <div class="card-header bg-primary text-white">Basic Information</div>
        <div class="card-body">
          <div class="mb-3">
            <label for="title" class="form-label">Tour Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="E.g., Explore Ancient Ruins" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Tour Description</label>
            <textarea class="form-control" id="description" name="description" rows="5" placeholder="Detailed description including history, culture, and significance" required></textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="location" class="form-label">Location</label>
              <input type="text" class="form-control" id="location" name="location" placeholder="City, Country" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="price" class="form-label">Price ($)</label>
              <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="E.g., 99.99" required>
            </div>
          </div>
          <!-- Recommended and Featured Toggles -->
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="featured" name="featured">
                <label class="form-check-label" for="featured">Featured Tour</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="recommended" name="recommended">
                <label class="form-check-label" for="recommended">Recommended Tour</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Media Upload Card -->
      <div class="card card-section">
        <div class="card-header bg-secondary text-white">Media Upload</div>
        <div class="card-body">
          <!-- Main Thumbnail Upload -->
          <div class="mb-3">
            <label class="form-label">Thumbnail Image</label>
            <div id="thumbnailUpload" class="upload-box">
              Drag and drop an image here, or click to select a file.
              <input type="file" id="thumbnail" name="thumbnail" class="d-none" accept="image/*" required>
            </div>
            <div id="thumbnailPreview" class="thumbnail-preview mt-2"></div>
          </div>
          <!-- Gallery Images Upload -->
          <div class="mb-3">
            <label class="form-label">Gallery Images</label>
            <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple accept="image/*">
            <small class="form-text text-muted">You can select multiple images for the gallery.</small>
          </div>
          <!-- Video Upload Section -->
          <div class="mb-3">
            <label class="form-label">Video Uploads</label>
            <input type="file" class="form-control" id="video_files" name="video_files[]" multiple accept="video/*">
            <small class="form-text text-muted">Select one or more video files to upload.</small>
          </div>
          <!-- Video URLs Section -->
          <div class="mb-3">
            <label class="form-label">Video URLs</label>
            <div id="videoUrlsContainer">
              <div class="input-group mb-2">
                <input type="text" class="form-control" name="video_urls[]" placeholder="Enter video URL">
              </div>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="addVideoUrlButton">
              <i class="bi bi-plus-circle"></i> Add Video URL
            </button>
            <small class="form-text text-muted">You may add multiple video URLs.</small>
          </div>
        </div>
      </div>

      <!-- Tour Timing Card -->
      <div class="card card-section">
        <div class="card-header bg-info text-white">Tour Timing</div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="start_date" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="end_date" class="form-label">End Date</label>
              <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
          </div>
        </div>
      </div>

      <!-- Status Card -->
      <div class="card card-section">
        <div class="card-header bg-warning text-dark">Status</div>
        <div class="card-body">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="status" name="status" checked>
            <label class="form-check-label" for="status">Active Tour</label>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex justify-content-end card-section">
        <button type="button" class="btn btn-secondary me-2 btn-action" onclick="window.location.href='adminpanel.php'">Cancel</button>
        <button type="submit" class="btn btn-primary btn-action">Add Tour</button>
      </div>
    </form>
  </div>

  <!-- Footer -->
  <footer>
    <p>Need help? <a href="#" class="text-warning">FAQ</a> | <a href="#" class="text-warning">Support</a></p>
  </footer>

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom JavaScript for Drag-and-Drop and Ajax Submission -->
  <script>
    // DRAG AND DROP functionality for thumbnail upload
    const uploadBox = document.getElementById('thumbnailUpload');
    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailPreview = document.getElementById('thumbnailPreview');

    uploadBox.addEventListener('click', () => {
      thumbnailInput.click();
    });

    uploadBox.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadBox.classList.add('dragover');
    });

    uploadBox.addEventListener('dragleave', () => {
      uploadBox.classList.remove('dragover');
    });

    uploadBox.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadBox.classList.remove('dragover');
      if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        thumbnailInput.files = e.dataTransfer.files;
        previewThumbnail();
      }
    });

    thumbnailInput.addEventListener('change', previewThumbnail);

    function previewThumbnail() {
      thumbnailPreview.innerHTML = "";
      const file = thumbnailInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          thumbnailPreview.appendChild(img);
        }
        reader.readAsDataURL(file);
      }
    }

    // Add dynamic video URL input fields
    $('#addVideoUrlButton').click(function() {
      const newInput = `<div class="input-group mb-2">
                          <input type="text" class="form-control" name="video_urls[]" placeholder="Enter video URL">
                          <button class="btn btn-danger remove-video-url" type="button"><i class="bi bi-x-circle"></i></button>
                        </div>`;
      $('#videoUrlsContainer').append(newInput);
    });

    // Remove dynamic video URL input field if not needed
    $(document).on('click', '.remove-video-url', function() {
      $(this).closest('.input-group').remove();
    });

// AJAX submission for the form
$('#addTourForm').submit(function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append('ajaxSubmit', 'true');

  const submitButton = $(this).find('button[type="submit"]');
  submitButton.prop('disabled', true).text('Submitting...');

  $.ajax({
    url: 'addTour.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json', // Ensure jQuery parses the response as JSON
    success: function(data) {
      if (data.success) {
        // Create an animated pop-up card for success message
        const popup = $(`
          <div id="successPopup" class="position-fixed top-0 start-50 translate-middle-x mt-3 alert alert-success" style="z-index: 1050; display: none; max-width: 90%;">
            ${data.message}
          </div>
        `);
        $('body').append(popup);
        popup.slideDown(500).delay(3000).slideUp(500, function() {
          $(this).remove();
        });
        $('#addTourForm')[0].reset();
        thumbnailPreview.innerHTML = "";
      } else {
        $('<div class="alert alert-danger" id="errorAlert">' + data.message + '</div>')
          .prependTo('body')
          .hide()
          .slideDown();
      }
      submitButton.prop('disabled', false).text('Add Tour');
    },
    error: function() {
      $('<div class="alert alert-danger" id="errorAlert">An error occurred. Please try again.</div>')
        .prependTo('body')
        .hide()
        .slideDown();
      submitButton.prop('disabled', false).text('Add Tour');
    }
  });
});

  </script>
</body>
</html>
