<?php
// adminpanel.php
// Access Control: Only allow logged-in Admins
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: loginandregister.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Asgard Tour Guide</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Chart.js for charts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

  <!-- Custom CSS for cool header, icons, and animations -->
  <style>
    /* Global Styles */
    body {
      font-family: 'Roboto', sans-serif;
      transition: background-color 0.3s, color 0.3s;
    }
    /* Cool Header Styling */
    header.cool-header {
      background: linear-gradient(135deg, #007bff, #00d4ff);
      color: #fff;
      padding: 40px 20px;
      text-align: center;
      position: relative;
      overflow: hidden;
      animation: slideIn 1s ease-out;
    }
    header.cool-header h1 {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 10px;
    }
    header.cool-header p.subtitle {
      font-size: 1.25rem;
      margin-bottom: 20px;
    }
    header.cool-header p.tagline {
      font-size: 1rem;
      font-style: italic;
      margin-bottom: 30px;
    }
    header.cool-header .cta-buttons .btn {
      margin: 5px;
      border-radius: 30px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: transform 0.2s;
    }
    header.cool-header .cta-buttons .btn:hover {
      transform: scale(1.05);
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    /* Sidebar Navigation */
    #sidebar {
      min-height: 100vh;
      background-color: #343a40;
    }
    #sidebar a {
      color: #fff;
      text-decoration: none;
      transition: background-color 0.3s;
    }
    #sidebar a:hover {
      background-color: #495057;
    }
    /* Section Headers */
    .section-header {
      margin-top: 20px;
      margin-bottom: 10px;
      font-weight: bold;
      border-bottom: 2px solid #dee2e6;
      padding-bottom: 5px;
    }
    /* Fade-in Animation for sections */
    .fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    body {
  transition: background-color 0.5s ease, color 0.5s ease, font-size 0.3s ease;
}

.dark-theme {
  background-color: #121212 !important;
  color: #e0e0e0 !important;
}

/* Theme Colors */
.blue-theme { background-color: #007bff !important; color: white !important; }
.green-theme { background-color: #28a745 !important; color: white !important; }
.red-theme { background-color: #dc3545 !important; color: white !important; }
.purple-theme { background-color: #6f42c1 !important; color: white !important; }
.gold-theme { background-color: #ffcc00 !important; color: black !important; }
.teal-theme { background-color: #20c997 !important; color: white !important; }
.pink-theme { background-color: #e83e8c !important; color: white !important; }
.gray-theme { background-color: #6c757d !important; color: white !important; }

/* Button Animation */
.btn {
  transition: background-color 0.3s ease;
}

  </style>
</head>
<body>
  <!-- COOL HEADER -->
  <header class="cool-header">
    <h1><i class="bi bi-globe2"></i> AsgardTour Admin Dashboard</h1>
    <p class="subtitle">Manage Your Tours and Users Efficiently</p>
    <p class="tagline">Explore the world, one tour at a time!</p>
    <div class="cta-buttons">
      <button class="btn btn-light" onclick="window.location.href='addTour.php'"><i class="bi bi-plus-circle"></i> Create New Tour</button>
      <button class="btn btn-light" onclick="window.location.href='viewReports.php'"><i class="bi bi-bar-chart-line"></i> View Reports</button>
    </div>
  </header>

  <div class="container-fluid mt-4">
    <div class="row">
      <!-- Sidebar Navigation -->
      <nav id="sidebar" class="col-md-2 d-none d-md-block bg-dark sidebar">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="#dashboard">Dashboard Overview</a></li>
            <li class="nav-item"><a class="nav-link" href="#manage-tours">Manage Tours</a></li>
            <li class="nav-item"><a class="nav-link" href="#notifications">Notifications & Alerts</a></li>
            <li class="nav-item"><a class="nav-link" href="#profile-settings">Profile & Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="#system-settings">Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
          </ul>
        </div>
      </nav>

      <!-- Main Content Area -->
      <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
        <!-- Dashboard Overview Section -->
        <section id="dashboard" class="fade-in">
          <!-- Header with Title and Actions -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-header mb-0">
              <i class="bi bi-speedometer2"></i> Dashboard Overview
            </h2>
            <div class="d-flex">
              <button class="btn btn-secondary me-2" onclick="refreshDashboard()">
                <i class="bi bi-arrow-repeat"></i> Refresh
              </button>
              <button class="btn btn-primary" onclick="window.location.href='viewReports.php'">
                <i class="bi bi-bar-chart-line"></i> Detailed Reports
              </button>
            </div>
          </div>
          <hr>
          <!-- Metrics Cards -->
          <div class="row g-3">
            <!-- Total Travelers Card -->
            <div class="col-md-3">
              <div class="card shadow-sm">
                <div class="card-body text-center">
                  <h5 class="card-title">
                    <i class="bi bi-people-fill"></i> Total Travelers
                  </h5>
                  <!-- Clickable area; using a link style -->
                  <a href="travelerslist.php" id="totalTravelersLink" class="stretched-link" style="text-decoration: none;">
                    <p class="card-text fs-3" id="totalTravelers">Loading...</p>
                  </a>
                </div>
              </div>
            </div>

            <!-- Total Users Card -->
            <div class="col-md-3">
              <div class="card shadow-sm">
                <div class="card-body text-center">
                  <h5 class="card-title">
                    <i class="bi bi-person-lines-fill"></i> Total Users
                  </h5>
                  <p class="card-text fs-3" id="totalUsers">Loading...</p>
                </div>
              </div>
            </div>
            
            <!-- Total Revenue Card -->
            <div class="col-md-3">
              <a href="totalrevenue.php" style="text-decoration:none; color:inherit;">
                <div class="card shadow-sm">
                  <div class="card-body text-center">
                    <h5 class="card-title">
                      <i class="bi bi-currency-dollar"></i> Total Revenue
                    </h5>
                    <p class="card-text fs-3" id="totalRevenue">ETB 0.00</p>
                  </div>
                </div>
              </a>
            </div>
            <!-- Active Tours Card -->
            <div class="col-md-3">
              <div class="card shadow-sm">
                <div class="card-body text-center">
                  <h5 class="card-title">
                    <i class="bi bi-flag-fill"></i> Active Tours
                  </h5>
                  <p class="card-text fs-3" id="activeTours">Loading...</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Charts and Graphs -->
          <div class="row mt-4">
            <!-- Hourly Revenue Chart -->
            <div class="col-md-8">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title">Hourly Revenue</h5>
                  <canvas id="hourlyRevenueChart" width="100%" height="40"></canvas>
                </div>
              </div>
            </div>
            
            <!-- Top Revenue Days List -->
            <div class="col-md-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title">Top Revenue Days</h5>
                  <ul class="list-group" id="topRevenueDays">
                    <li class="list-group-item">Loading...</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Most Popular Tours Card -->
          <div class="col-md-3">
            <div class="card shadow-sm">
              <div class="card-body">
                <h5 class="card-title">
                  <i class="bi bi-star-fill"></i> Popular Tours
                </h5>
                <ul class="list-group" id="popularTours">
                  <li class="list-group-item">Loading...</li>
                </ul>
              </div>
            </div>
          </div>
  
          <!-- Recent Activities -->
          <div class="row mt-4">
            <div class="col-12">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title">Recent Activities</h5>
                  <div id="recentActivities">
                    Loading recent activities...
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        
<!-- Manage Tours Section -->
<section id="manage-tours" class="fade-in mt-4">
  <h2 class="section-header">Manage Tours</h2>
  <div class="container my-4" id="manageToursSection">
    <!-- Header & Add New Tour Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <button class="btn btn-primary" onclick="window.location.href='addTour.php'">
        <i class="bi bi-plus-circle"></i> Add New Tour
      </button>
    </div>
  
    <!-- Search and Filter Form -->
    <form id="tourFilterForm" class="row g-3 mb-3">
      <!-- Search Bar -->
      <div class="col-md-3">
        <input type="text" id="searchTour" class="form-control" placeholder="Search by title">
      </div>
      <!-- Location Filter -->
      <div class="col-md-2">
        <select id="filterLocation" class="form-select">
          <option value="">All Locations</option>
          <option value="Addis Ababa">Addis Ababa</option>
          <option value="Gondar">Gondar</option>
          <option value="Axum">Axum</option>
          <!-- More options as needed -->
        </select>
      </div>
      <!-- Price Range Filter -->
      <div class="col-md-2">
        <input type="number" id="minPrice" class="form-control" placeholder="Min Price">
      </div>
      <div class="col-md-2">
        <input type="number" id="maxPrice" class="form-control" placeholder="Max Price">
      </div>
      <!-- Rating Filter -->
      <div class="col-md-2">
        <select id="filterRating" class="form-select">
          <option value="">Any Rating</option>
          <option value="1">1 Star & Up</option>
          <option value="2">2 Stars & Up</option>
          <option value="3">3 Stars & Up</option>
          <option value="4">4 Stars & Up</option>
          <option value="5">5 Stars</option>
        </select>
      </div>
      <!-- Optional Search Button -->
      <div class="col-md-1 text-end">
        <button id="searchTourButton" type="button" class="btn btn-secondary">
          <i class="bi bi-search"></i>
        </button>
      </div>
    </form>
  
    <!-- Tours Cards Grid -->
    <div class="row" id="toursContainer">
      <!-- Tour cards will be loaded here via AJAX -->
    </div>
  
    <!-- Pagination Controls -->
    <nav>
      <ul class="pagination justify-content-center" id="tourPagination">
        <!-- Pagination links loaded via AJAX -->
      </ul>
    </nav>
  </div>
</section>

        <!-- Notifications & Alerts Section -->
        <section id="notifications" class="fade-in mt-4">
          <h2 class="section-header">Notifications & Alerts</h2>
          <div id="notificationsList">
            <!-- AJAX-loaded notifications -->
          </div>
        </section>
  
        <!-- Profile & Settings Section -->
        <section id="profile-settings" class="fade-in mt-4">
          <h2 class="section-header">Profile & Settings</h2>
          <form id="profileForm" method="POST" action="updateProfile.php">
            <div class="mb-3">
              <label for="adminName" class="form-label">Name</label>
              <input type="text" class="form-control" id="adminName" name="adminName" value="Admin Name" required>
            </div>
            <div class="mb-3">
              <label for="adminEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="adminEmail" name="adminEmail" value="admin@example.com" required>
            </div>
            <div class="mb-3">
              <label for="adminPassword" class="form-label">New Password</label>
              <input type="password" class="form-control" id="adminPassword" name="adminPassword">
            </div>
            <button type="submit" class="btn btn-success">Update Profile</button>
          </form>
        </section>
  
        <!-- System Settings Section -->
        <section id="system-settings" class="fade-in mt-4">
  <h2 class="section-header">System Settings</h2>
  <form id="settingsForm">
    <!-- Language Selector -->
    <div class="mb-3">
      <label class="form-label">Language</label>
      <select id="languageSelect" class="form-select">
        <option value="en">English (US)</option>
        <option value="en-uk">English (UK)</option>
        <option value="am">Amharic</option>
        <option value="om">Oromo</option>
        <option value="so">Somali</option>
        <option value="ti">Tigrinya</option>
        <option value="aa">Afar</option>
        <option value="ar">Arabic</option>
        <option value="sw">Swahili</option>
        <option value="es">Spanish</option>
        <option value="zh">Mandarin</option>
        <option value="ru">Russian</option>
        <option value="it">Italian</option>
      </select>
    </div>

    <!-- Theme Selector -->
    <div class="mb-3">
      <label class="form-label">Theme</label>
      <select class="form-select" id="themeSelect">
        <option value="light" selected>Light</option>
        <option value="dark">Dark</option>
        <option value="blue">Blue</option>
        <option value="green">Green</option>
        <option value="red">Red</option>
        <option value="purple">Purple</option>
        <option value="gold">Gold</option>
        <option value="teal">Teal</option>
        <option value="pink">Pink</option>
        <option value="gray">Gray</option>
      </select>
    </div>

    <!-- Font Selector -->
    <div class="mb-3">
      <label class="form-label">Font Style</label>
      <select class="form-select" id="fontSelect">
        <option value="Arial" selected>Arial</option>
        <option value="Roboto">Roboto</option>
        <option value="Georgia">Georgia</option>
        <option value="Verdana">Verdana</option>
        <option value="Tahoma">Tahoma</option>
        <option value="Courier New">Courier New</option>
        <option value="Times New Roman">Times New Roman</option>
        <option value="Garamond">Garamond</option>
        <option value="Comic Sans MS">Comic Sans MS</option>
        <option value="Impact">Impact</option>
      </select>
    </div>

    <!-- Font Size Selector -->
    <div class="mb-3">
      <label class="form-label">Font Size</label>
      <input type="range" class="form-range" id="fontSizeRange" min="12" max="24" step="1" value="16">
      <span id="fontSizeLabel">16px</span>
    </div>

    <!-- Dark Mode Toggle -->
    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" id="darkModeToggle">
      <label class="form-check-label">Dark Mode</label>
    </div>

    <!-- Auto-Refresh Toggle -->
    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" id="autoRefreshToggle">
      <label class="form-check-label">Enable Auto-Refresh</label>
    </div>

    <!-- Action Buttons -->
    <button type="button" class="btn btn-primary me-2" onclick="applySettings()">Apply Settings</button>
    <button type="button" class="btn btn-danger" onclick="resetDefaults()">Reset Defaults</button>
  </form>
</section>

<script src="script.js"></script>

  <!-- JavaScript Dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom JS: AJAX calls and dynamic functionality -->
  <script>
    // Dashboard Overview AJAX Functions
  
    // Load key metrics: Total Travelers, Total Users, Total Revenue, and Active Tours
    function loadDashboardMetrics() {
      // Total Travelers
      $.ajax({
        url: 'getTotalTravelers.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#totalTravelers').text(data.totalTravelers !== undefined ? data.totalTravelers : 'Error');
        },
        error: function() {
          $('#totalTravelers').text('Error');
        }
      });
      // Example: Clicking on Total Travelers widget directs to userList.php
      $('#totalTravelers').on('click', function() {
        window.location.href = 'userList.php';
      });
  
      // Total Users
      $.ajax({
        url: 'getTotalUsers.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#totalUsers').text(data.totalUsers !== undefined ? data.totalUsers : 'Error');
        },
        error: function() {
          $('#totalUsers').text('Error');
        }
      });
      
      // Total Revenue
      $.ajax({
        url: 'getTotalRevenue.php',
        method: 'GET',
        dataType: 'json',   
        success: function(data) {   
          $('#totalRevenue').text(data.totalRevenue !== undefined ? 'ETB ' + data.totalRevenue : 'Error'); 
        },
        error: function() {
          $('#totalRevenue').text('Error');
        }
      });
      
      // Active Tours
      $.ajax({
        url: 'getActiveTours.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#activeTours').text(data.activeTours !== undefined ? data.activeTours : 'Error');
        },
        error: function() {
          $('#activeTours').text('Error');
        }
      });
    }
  
    // Load hourly revenue data and render the chart using Chart.js
    function loadHourlyRevenue() {
      $.ajax({
        url: 'getHourlyRevenue.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          let labels = [];
          let revenues = [];
          data.forEach(function(item) {
            labels.push(item.hour);
            revenues.push(item.revenue);
          });
          renderHourlyRevenueChart(labels, revenues);
        },
        error: function() {
          console.error('Error loading hourly revenue data');
        }
      });
    }
  
    function renderHourlyRevenueChart(labels, revenues) {
      const ctx = document.getElementById('hourlyRevenueChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Hourly Revenue',
            data: revenues,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          scales: {
            x: { title: { display: true, text: 'Hour of Day' } },
            y: { title: { display: true, text: 'Revenue ($)' } }
          }
        }
      });
    }
  
    // Load Top Revenue Days and update the list
    function loadTopRevenueDays() {
      $.ajax({
        url: 'getTopRevenueDays.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          var html = '';
          if(data.error) {
             html = '<li class="list-group-item">' + data.error + '</li>';
          } else if(data.length === 0) {
             html = '<li class="list-group-item">No revenue data available</li>';
          } else {
             data.forEach(function(day) {
               html += `<li class="list-group-item">${day.day_of_week}, ${day.date} - ETB ${day.revenue}</li>`;
             });
          }
          $('#topRevenueDays').html(html);
        },
        error: function() {
          $('#topRevenueDays').html('<li class="list-group-item">Error loading data</li>');
        }
      });
    }
  
    function loadPopularTours() {
      $.ajax({
        url: 'getPopularTours.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          let html = '';
          if(data.error) {
            html = '<li class="list-group-item">' + data.error + '</li>';
          } else if(data.length === 0) {
            html = '<li class="list-group-item">No popular tours available</li>';
          } else {
            data.forEach(function(tour) {
              html += `<li class="list-group-item">
                        <a href="tourDetails.php?tour_id=${tour.tour_id}" style="text-decoration: none; color: inherit;">
                          ${tour.title} (${tour.bookings} bookings)
                        </a>
                       </li>`;
            });
          }
          $('#popularTours').html(html);
        },
        error: function() {
          $('#popularTours').html('<li class="list-group-item">Error loading popular tours</li>');
        }
      });
    }
  
    // Load Recent Activities (registrations and bookings)
    function loadRecentActivities() {
      $.ajax({
        url: 'getNewActivities.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          let html = '<h6>Recent Registrations:</h6><ul>';
          data.registrations.forEach(function(reg) {
            html += `<li>${reg.name} registered on ${reg.created_at}</li>`;
          });
          html += '</ul><h6>Recent Bookings:</h6><ul>';
          data.bookings.forEach(function(booking) {
            html += `<li>Booking ID: ${booking.booking_id} by User ID: ${booking.user_id} on ${booking.booking_date}</li>`;
          });
          html += '</ul>';
          $('#recentActivities').html(html);
        },
        error: function() {
          $('#recentActivities').html('Error loading activities.');
        }
      });
    }
  
    // Refresh all dashboard data
    function refreshDashboard() {
      loadDashboardMetrics();
      loadHourlyRevenue();
      loadTopRevenueDays();
      loadRecentActivities();
      loadPopularTours();
    }
  
    // Initialize dashboard on document ready
    $(document).ready(function() {
      refreshDashboard();
    });
  
    // Apply System Settings: Theme, Color Palette, Font Size
// Apply Settings Function
function applySettings() {
  const theme = $('#themeSelect').val();
  const font = $('#fontSelect').val();
  const fontSize = $('#fontSizeRange').val();
  const darkMode = $('#darkModeToggle').is(':checked');
  const autoRefresh = $('#autoRefreshToggle').is(':checked');

  // Apply Theme
  $('body').removeClass().addClass(`${theme}-theme`);

  // Apply Font and Font Size
  $('body').css({ 'font-family': font, 'font-size': fontSize + 'px' });
  $('#fontSizeLabel').text(fontSize + 'px');

  // Dark Mode Toggle
  if (darkMode) {
    $('body').addClass('dark-theme');
  } else {
    $('body').removeClass('dark-theme');
  }

  // Store Settings in LocalStorage
  const settings = { theme, font, fontSize, darkMode, autoRefresh };
  localStorage.setItem('systemSettings', JSON.stringify(settings));

  // Toast Notification
  showToast('Settings applied successfully!');
}

// Reset to Default Settings
function resetDefaults() {
  localStorage.removeItem('systemSettings');
  $('#themeSelect').val('light');
  $('#fontSelect').val('Arial');
  $('#fontSizeRange').val(16);
  $('#darkModeToggle').prop('checked', false);
  $('#autoRefreshToggle').prop('checked', false);
  applySettings();
  showToast('Settings reset to default!');
}

// Load Settings from LocalStorage
function loadSettings() {
  const savedSettings = JSON.parse(localStorage.getItem('systemSettings'));
  if (savedSettings) {
    $('#themeSelect').val(savedSettings.theme);
    $('#fontSelect').val(savedSettings.font);
    $('#fontSizeRange').val(savedSettings.fontSize);
    $('#fontSizeLabel').text(savedSettings.fontSize + 'px');
    $('#darkModeToggle').prop('checked', savedSettings.darkMode);
    $('#autoRefreshToggle').prop('checked', savedSettings.autoRefresh);
    applySettings();
  }
}

// Show Toast Notification
function showToast(message) {
  const toast = $('<div class="toast-container">' + message + '</div>');
  $('body').append(toast);
  toast.fadeIn(400).delay(2000).fadeOut(400, function() { $(this).remove(); });
}

// Run on Page Load
$(document).ready(function() {
  loadSettings();
  $('#fontSizeRange').on('input', function() {
    $('#fontSizeLabel').text($(this).val() + 'px');
  });
});

    // --- Manage Tours Section JS ---
    
  // Global variables for Manage Tours pagination and sorting
  let currentTourPage = 1;
  const toursPerPage = 20;
  let currentTourSort = 'title'; // default sort field
  let currentTourOrder = 'ASC';  // default order

  // Function to load tours based on filters, search, sorting, and pagination (Manage Tours)


  
  function loadTours(page = 1) {
    currentTourPage = page;
    // Gather filter values from the Manage Tours filter form
    const searchQuery = $('#searchTour').val();
    const filterLocation = $('#filterLocation').val();
    const minPrice = $('#minPrice').val();
    const maxPrice = $('#maxPrice').val();
    const filterRating = $('#filterRating').val();

    $.ajax({
      url: 'getTours2.php', // Using getTours2.php as preferred
      type: 'GET',
      dataType: 'json',
      data: {
        page: page,
        per_page: toursPerPage,
        search: searchQuery,
        location: filterLocation,
        minPrice: minPrice,
        maxPrice: maxPrice,
        rating: filterRating,
        sort: currentTourSort,
        order: currentTourOrder
      },
      success: function(response) {
        let html = '';
        // Render each tour as a card in a grid format
        response.tours.forEach(function(tour) {
          html += `
            <div class="col-md-4 mb-3">
              <div class="card h-100 shadow-sm">
                ${tour.main_image ? `<img src="${tour.main_image}" class="card-img-top" alt="${tour.title} Thumbnail">` : ''}
                <div class="card-body">
                  <h5 class="card-title">${tour.title}</h5>
                  <p class="card-text">${tour.location}</p>
                  <p class="card-text"><strong>Price:</strong> $${tour.price}</p>
                  <p class="card-text"><small class="text-muted">Posted on: ${tour.created_at}</small></p>
                </div>
                <div class="card-footer text-end">
                  <button class="btn btn-sm btn-danger" onclick="deleteTour(${tour.tour_id}, '${tour.title}')">
                    <i class="bi bi-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          `;
        });
        $('#toursContainer').html(html);
  
        // Build pagination links
        let paginationHtml = '';
        for (let i = 1; i <= response.totalPages; i++) {
          paginationHtml += `<li class="page-item ${i === page ? 'active' : ''}">
            <a class="page-link" href="#" onclick="loadTours(${i}); return false;">${i}</a>
          </li>`;
        }
        $('#tourPagination').html(paginationHtml);
      },
      error: function() {
        $('#toursContainer').html('<div class="col-12"><p class="text-danger">Error loading tours.</p></div>');
      }
    });
  }


  // Sorting handlers for Manage Tours
  $('#sortTitle').click(function(e) {
    e.preventDefault();
    currentTourSort = 'title';
    currentTourOrder = (currentTourOrder === 'ASC') ? 'DESC' : 'ASC';
    loadTours(1);
  });
  $('#sortLocation').click(function(e) {
    e.preventDefault();
    currentTourSort = 'location';
    currentTourOrder = (currentTourOrder === 'ASC') ? 'DESC' : 'ASC';
    loadTours(1);
  });

  // Optional search button handler for Manage Tours
  $('#searchTourButton').click(function() {
    loadTours(1);
  });

  // Real-time filtering: trigger loadTours when any filter input changes in Manage Tours form
  $('#searchTour, #filterLocation, #minPrice, #maxPrice, #filterRating').on('keyup change', function() {
    loadTours(1);
  });

  // Function to delete a tour via AJAX for Manage Tours
  function deleteTour(tourId, tourTitle) {
    if (confirm(`Are you sure you want to delete the tour "${tourTitle}"?`)) {
      $.ajax({
        url: 'deleteTour.php',
        type: 'POST',
        dataType: 'json',
        data: { tour_id: tourId },
        success: function(response) {
          if (response.success) {
            loadTours(currentTourPage);
          } else {
            alert('Error: ' + response.message);
          }
        },
        error: function() {
          alert('Failed to delete tour.');
        }
      });
    }
  }

  // Initial load of Manage Tours on document ready
  $(document).ready(function() {
    loadTours();
  });

    // NEW CODE: Show only one section at a time, set active link, and show Dashboard by default.
    $(document).ready(function(){
      $('main > section').hide();
      $('#dashboard').show();
      
      $('#sidebar ul.nav li.nav-item a.nav-link').click(function(e){
         e.preventDefault();
         var targetSection = $(this).attr('href');
         $('#sidebar ul.nav li.nav-item a.nav-link').removeClass('active');
         $(this).addClass('active');
         $('main > section').fadeOut(200, function(){
            $(targetSection).fadeIn(400);
         });
      });
    });
  </script>
</body>
</html>
