<?php
// viewReports.php
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
  <title>View Reports - Asgard Tour Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Chart.js and Data Labels Plugin -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
  <!-- Custom CSS -->
  <style>
    body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; }
    .section-header { margin-top: 20px; margin-bottom: 10px; font-weight: bold; border-bottom: 2px solid #dee2e6; padding-bottom: 5px; }
    .chart-container { position: relative; height: 40vh; width: 80vw; }
    .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
    .card-icon { font-size: 2rem; }
    .fade-in { animation: fadeIn 0.8s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .export-btn { margin-top: 1rem; }
  </style>
</head>
<body class="fade-in">
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>View Reports</h1>
      <button class="btn btn-secondary" onclick="refreshDashboard()">
        <i class="bi bi-arrow-repeat"></i> Refresh
      </button>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <i class="card-icon bi bi-people"></i>
            <h5 class="card-title">Total Users</h5>
            <p id="totalUsers" class="card-text">Loading...</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <i class="card-icon bi bi-map"></i>
            <h5 class="card-title">Total Tours</h5>
            <p id="totalTours" class="card-text">Loading...</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <i class="card-icon bi bi-currency-dollar"></i>
            <h5 class="card-title">Revenue Generated</h5>
            <p id="totalRevenue" class="card-text">Loading...</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <i class="card-icon bi bi-calendar-check"></i>
            <h5 class="card-title">Total Bookings</h5>
            <p id="totalBookings" class="card-text">Loading...</p>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex align-items-center gap-2">
        <label for="reportFilter" class="form-label mb-0">Filter Reports:</label>
        <select id="reportFilter" class="form-select form-select-sm w-auto">
          <option value="daily">Daily</option>
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
        </select>
      </div>
      <div class="d-flex align-items-center gap-2">
        <input type="text" id="reportSearch" class="form-control form-control-sm" placeholder="Search data...">
        <button class="btn btn-outline-primary btn-sm" onclick="applySearch()">Search</button>
      </div>
    </div>

    <div class="row report-section">
      <div class="col-md-8 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Revenue Trends (Last 30 Days)</h5>
            <div class="chart-container">
              <canvas id="revenueTrendChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Popular Destinations</h5>
            <div class="chart-container" style="height:250px;">
              <canvas id="popularDestinationsChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="text-end export-btn">
      <button class="btn btn-primary" id="downloadReport">Download Detailed Report (PDF/Excel)</button>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function refreshDashboard() {
      loadMetrics();
      loadRevenueTrendChart($('#reportFilter').val());
      loadPopularDestinationsChart();
    }

    function loadMetrics() {
      $.ajax({
        url: 'fetch_metrics.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#totalUsers').text(data.totalUsers);
          $('#totalTours').text(data.totalTours);
          $('#totalRevenue').text('ETB ' + data.totalRevenue);
          $('#totalBookings').text(data.totalBookings);
        },
        error: function() {
          console.error('Error loading metrics');
        }
      });
    }

    function loadRevenueTrendChart(filter) {
      $.ajax({
        url: 'fetch_revenue_trend.php',
        method: 'GET',
        data: { filter: filter },
        dataType: 'json',
        success: function(data) {
          updateRevenueChart(data.labels, data.values);
        },
        error: function() {
          console.error('Error loading revenue trend data');
        }
      });
    }

    function updateRevenueChart(labels, values) {
      const ctx = document.getElementById('revenueTrendChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Revenue ($)',
            data: values,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: true,
            tension: 0.3,
            pointRadius: 4,
            pointHoverRadius: 6
          }]
        },
        options: {
          responsive: true,
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  return 'Revenue: $' + context.parsed.y.toFixed(2);
                }
              }
            }
          },
          scales: {
            x: { title: { display: true, text: 'Date' } },
            y: { title: { display: true, text: 'Revenue ($)' }, beginAtZero: true }
          }
        },
        plugins: [ChartDataLabels]
      });
    }

    function loadPopularDestinationsChart() {
      $.ajax({
        url: 'fetch_popular_destinations.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          updatePopularDestinationsChart(data.labels, data.values);
        },
        error: function() {
          console.error('Error loading popular destinations data');
        }
      });
    }

    function updatePopularDestinationsChart(labels, values) {
      const ctx = document.getElementById('popularDestinationsChart').getContext('2d');
      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            data: values,
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
              'rgba(75, 192, 192, 0.7)',
              'rgba(153, 102, 255, 0.7)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.label + ': ' + context.parsed + ' bookings';
                }
              }
            }
          }
        }
      });
    }

    function applySearch() {
      let searchQuery = $('#reportSearch').val();
      console.log('Search for:', searchQuery);
      // Implement search functionality as needed
    }

    $('#downloadReport').click(function() {
      window.location.href = 'generate_report.php';
    });

    $(document).ready(function() {
      refreshDashboard();
      $('#reportFilter').change(function() {
        loadRevenueTrendChart($(this).val());
      });
    });
  </script>
</body>
</html>
