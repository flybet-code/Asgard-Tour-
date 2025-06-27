<?php
// totalrevenue.php
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
  <title>Total Revenue Overview - Asgard Tour Guide</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
      transition: background-color 0.3s, color 0.3s;
    }
    header.page-header {
      background: linear-gradient(135deg, #007bff, #00d4ff);
      color: #fff;
      padding: 40px 20px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    header.page-header h1 {
      font-size: 2.5rem;
      font-weight: bold;
    }
    header.page-header p.subtitle {
      font-size: 1.25rem;
      font-style: italic;
    }
    .metric-card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      padding: 20px;
      background-color: #fff;
      margin-bottom: 20px;
    }
    .chart-container {
      position: relative;
      margin: auto;
      height: 300px;
      width: 100%;
    }
    .filter-section {
      margin-bottom: 20px;
    }
    .transition {
      transition: all 0.3s ease;
    }
    /* Print styles */
    @media print {
      header, .filter-section, button {
        display: none;
      }
    }
  </style>
</head>
<body>
  <!-- Page Header -->
  <header class="page-header">
    <h1><i class="bi bi-currency-dollar"></i> Total Revenue Overview</h1>
    <p class="subtitle">Analyze Your Tour Revenue Effectively</p>
  </header>

  <div class="container mt-4">
    <!-- Revenue Metrics -->
    <div class="row">
      <div class="col-md-4">
        <div class="metric-card text-center">
          <h4>Total Revenue</h4>
          <p id="totalRevenueMetric" class="fs-2">Loading...</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="metric-card text-center">
          <h4>Monthly Revenue</h4>
          <p id="monthlyRevenueMetric" class="fs-2">Loading...</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="metric-card text-center">
          <h4>Revenue Growth</h4>
          <p id="revenueGrowthMetric" class="fs-2">Loading...</p>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label for="dateRange" class="form-label">Date Range</label>
          <select class="form-select" id="dateRange">
            <option value="last12" selected>Last 12 Months</option>
            <option value="last6">Last 6 Months</option>
            <option value="last3">Last 3 Months</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="tourType" class="form-label">Tour Type</label>
          <select class="form-select" id="tourType">
            <option value="all" selected>All</option>
            <option value="adventure">Adventure</option>
            <option value="cultural">Cultural</option>
            <option value="leisure">Leisure</option>
          </select>
        </div>
        <div class="col-md-3">
          <button id="applyFilters" class="btn btn-primary w-100">Apply Filters</button>
        </div>
        <div class="col-md-3">
          <button id="exportPDF" class="btn btn-secondary w-100"><i class="bi bi-file-earmark-pdf"></i> Export as PDF</button>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="row">
      <!-- Line Chart: Revenue Over Time -->
      <div class="col-md-12">
        <div class="metric-card">
          <h5><i class="bi bi-graph-up"></i> Revenue Over Time</h5>
          <div class="chart-container">
            <canvas id="lineChart"></canvas>
          </div>
        </div>
      </div>
      <!-- Bar Chart: Revenue by Tour Category -->
      <div class="col-md-6">
        <div class="metric-card">
          <h5><i class="bi bi-bar-chart"></i> Revenue by Tour Category</h5>
          <div class="chart-container">
            <canvas id="barChart"></canvas>
          </div>
        </div>
      </div>
      <!-- Pie Chart: Revenue Percentage by Tour -->
      <div class="col-md-6">
        <div class="metric-card">
          <h5><i class="bi bi-pie-chart"></i> Revenue Contribution</h5>
          <div class="chart-container">
            <canvas id="pieChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript Dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Chart.js is already loaded from header -->

  <script>
    // Function to load revenue metrics via AJAX
    function loadRevenueMetrics() {
      $.ajax({
        url: 'getRevenueMetrics.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#totalRevenueMetric').text(data.totalRevenue ? 'ETB ' + data.totalRevenue : 'Error');
          $('#monthlyRevenueMetric').text(data.monthlyRevenue ? 'ETB ' + data.monthlyRevenue : 'Error');
          $('#revenueGrowthMetric').text(data.growth ? data.growth + '%' : 'Error');
        },
        error: function() {
          $('#totalRevenueMetric').text('Error');
          $('#monthlyRevenueMetric').text('Error');
          $('#revenueGrowthMetric').text('Error');
        }
      });
    }

    // Function to load and render the Line Chart
    function renderLineChart(dateRange, tourType) {
      $.ajax({
        url: 'getRevenueLineData.php',
        method: 'GET',
        data: { dateRange: dateRange, tourType: tourType },
        dataType: 'json',
        success: function(data) {
          const ctx = document.getElementById('lineChart').getContext('2d');
          if(window.lineChartInstance) {
            window.lineChartInstance.destroy();
          }
          window.lineChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
              labels: data.labels,
              datasets: [{
                label: 'Revenue',
                data: data.revenues,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.2)',
                fill: true,
                tension: 0.4
              }]
            },
            options: {
              plugins: {
                tooltip: { enabled: true }
              },
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                x: { title: { display: true, text: 'Month' } },
                y: { title: { display: true, text: 'Revenue (ETB)' } }
              }
            }
          });
        },
        error: function() {
          console.error('Error loading line chart data');
        }
      });
    }

    // Function to load and render the Bar Chart
    function renderBarChart(dateRange, tourType) {
      $.ajax({
        url: 'getRevenueBarData.php',
        method: 'GET',
        data: { dateRange: dateRange, tourType: tourType },
        dataType: 'json',
        success: function(data) {
          const ctx = document.getElementById('barChart').getContext('2d');
          if(window.barChartInstance) {
            window.barChartInstance.destroy();
          }
          window.barChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: data.labels,
              datasets: [{
                label: 'Revenue by Category',
                data: data.revenues,
                backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107'],
              }]
            },
            options: {
              plugins: {
                tooltip: { enabled: true }
              },
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                x: { title: { display: true, text: 'Tour Category' } },
                y: { title: { display: true, text: 'Revenue (ETB)' } }
              }
            }
          });
        },
        error: function() {
          console.error('Error loading bar chart data');
        }
      });
    }

    // Function to load and render the Pie Chart
    function renderPieChart(dateRange, tourType) {
      $.ajax({
        url: 'getRevenuePieData.php',
        method: 'GET',
        data: { dateRange: dateRange, tourType: tourType },
        dataType: 'json',
        success: function(data) {
          const ctx = document.getElementById('pieChart').getContext('2d');
          if(window.pieChartInstance) {
            window.pieChartInstance.destroy();
          }
          window.pieChartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
              labels: data.labels,
              datasets: [{
                data: data.revenues,
                backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6c757d'],
              }]
            },
            options: {
              plugins: {
                tooltip: { enabled: true }
              },
              responsive: true,
              maintainAspectRatio: false,
            }
          });
        },
        error: function() {
          console.error('Error loading pie chart data');
        }
      });
    }

    // Function to apply filters and refresh charts
    function applyFilters() {
      const dateRange = $('#dateRange').val();
      const tourType = $('#tourType').val();
      console.log("Applying filters: ", dateRange, tourType);
      renderLineChart(dateRange, tourType);
      renderBarChart(dateRange, tourType);
      renderPieChart(dateRange, tourType);
    }

    $(document).ready(function() {
      loadRevenueMetrics();
      applyFilters();
      
      // Trigger filters on Apply Filters button click
      $('#applyFilters').on('click', function() {
        applyFilters();
      });
      
      // Also trigger filters on dropdown change
      $('#dateRange, #tourType').on('change', function() {
        applyFilters();
      });
      
      // Export PDF functionality: use window.print() for a basic implementation
      $('#exportPDF').on('click', function() {
        window.print();
      });
    });
  </script>
</body>
</html>
