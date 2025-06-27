<?php
// travelersdetails.php
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
  <title>Traveler Details - Admin Panel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
    }
    header {
      background: #007bff;
      color: #fff;
      padding: 20px;
    }
    header h1 {
      margin: 0;
      font-size: 2rem;
    }
    header nav a {
      color: #fff;
      margin-right: 15px;
      text-decoration: none;
      font-size: 1.1rem;
    }
    .table-responsive {
      margin-top: 20px;
    }
    .pagination {
      margin-top: 20px;
    }
    /* Custom style for our deletion modal */
    .modal-confirm {		
      color: #636363;
      width: 400px;
    }
    .modal-confirm .modal-content {
      padding: 20px;
      border-radius: 5px;
      border: none;
    }
    .modal-confirm .modal-header {
      border-bottom: none;   
      position: relative;
    }
    .modal-confirm h4 {
      text-align: center;
      font-size: 26px;
      margin: 30px 0 -15px;
    }
    .modal-confirm .close {
      position: absolute;
      top: -5px;
      right: -2px;
    }
    .modal-confirm .modal-body {
      color: #999;
    }
    .modal-confirm .modal-footer {
      border: none;
      text-align: center;		
      border-radius: 5px;
      font-size: 13px;
    }	
    .modal-confirm .modal-footer a {
      color: #999;
    }		
    .modal-confirm .btn {
      min-width: 100px;
      border-radius: 3px; 
      border: none;
      line-height: normal;
      text-align: center;
      font-size: 14px;
      padding: 6px 20px;
    }
    .modal-confirm .btn-danger {
      background: #eeb711;
    }
  </style>
</head>
<body>
  <!-- Header Section -->
  <header class="text-center">
    <h1><i class="bi bi-people-fill"></i> Traveler Details</h1>
    <nav>
      <a href="adminpanel.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="travelersdetails.php"><i class="bi bi-people"></i> Traveler Details</a>
      <a href="manageTours.php"><i class="bi bi-geo-alt"></i> Manage Tours</a>
    </nav>
  </header>

  <div class="container my-4">
    <!-- Real-Time Search Bar -->
    <div class="row mb-3">
      <div class="col-md-12">
        <input type="text" id="searchTraveler" class="form-control" placeholder="Search travelers by name or email">
      </div>
    </div>

    <!-- Traveler Table -->
    <div class="table-responsive">
      <table class="table table-striped" id="travelerTable">
        <thead>
          <tr>
            <th><a href="#" id="sortName">Name <i class="bi bi-arrow-down-up"></i></a></th>
            <th><a href="#" id="sortEmail">Email <i class="bi bi-arrow-down-up"></i></a></th>
            <th>Role</th>
            <th>Joined</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="travelerData">
          <!-- Traveler rows will be loaded here dynamically -->
        </tbody>
      </table>
    </div>

    <!-- Pagination Controls -->
    <nav>
      <ul class="pagination justify-content-center" id="pagination">
        <!-- Pagination links loaded via AJAX -->
      </ul>
    </nav>
  </div>

  <!-- Deletion Confirmation Modal -->
  <div id="deleteModal" class="modal fade">
    <div class="modal-dialog modal-confirm">
      <div class="modal-content">
        <div class="modal-header flex-column">
          <h4 class="modal-title w-100">Are you sure?</h4>	
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <p id="deleteMessage">Do you really want to delete this traveler?</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Global variables for pagination and sorting
    let currentPage = 1;
    const travelersPerPage = 20;
    let currentSort = 'name'; // default sort field
    let currentOrder = 'ASC'; // default order

    // Function to load traveler data via AJAX
    function loadTravelers(page = 1, searchQuery = '') {
      $.ajax({
        url: 'getTravelers.php', // Endpoint must return JSON with travelers data and totalPages
        type: 'GET',
        dataType: 'json',
        data: {
          page: page,
          per_page: travelersPerPage,
          search: searchQuery,
          sort: currentSort,
          order: currentOrder
        },
        success: function(response) {
          let html = '';
          response.travelers.forEach(function(traveler) {
            html += `
              <tr>
                <td>${traveler.name}</td>
                <td>${traveler.email}</td>
                <td>${traveler.role}</td>
                <td>${traveler.created_at}</td>
                <td>
                  <button class="btn btn-sm btn-info me-1" onclick="openEditModal(${traveler.user_id})"><i class="bi bi-pencil-square"></i></button>
                  <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${traveler.user_id}, '${traveler.name}')"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
            `;
          });
          $('#travelerData').html(html);

          // Build pagination links
          let paginationHtml = '';
          for (let i = 1; i <= response.totalPages; i++) {
            paginationHtml += `<li class="page-item ${i === page ? 'active' : ''}">
              <a class="page-link" href="#" onclick="loadTravelers(${i}, $('#searchTraveler').val()); return false;">${i}</a>
            </li>`;
          }
          $('#pagination').html(paginationHtml);
          currentPage = page;
        },
        error: function() {
          $('#travelerData').html('<tr><td colspan="5" class="text-danger">Failed to load travelers.</td></tr>');
        }
      });
    }

    // Real-time search filtering (using keyup)
    $('#searchTraveler').on('keyup', function() {
      loadTravelers(1, $(this).val());
    });

    // Sorting handlers
    $('#sortName').click(function(e) {
      e.preventDefault();
      currentSort = 'name';
      currentOrder = (currentOrder === 'ASC') ? 'DESC' : 'ASC';
      loadTravelers(1, $('#searchTraveler').val());
    });
    $('#sortEmail').click(function(e) {
      e.preventDefault();
      currentSort = 'email';
      currentOrder = (currentOrder === 'ASC') ? 'DESC' : 'ASC';
      loadTravelers(1, $('#searchTraveler').val());
    });

    // Delete functionality: open deletion modal
    let travelerToDelete = null;
    function openDeleteModal(userId, userName) {
      travelerToDelete = userId;
      $('#deleteMessage').text(`Are you sure you want to delete ${userName}?`);
      let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
    }

    // Confirm deletion via AJAX
    $('#confirmDeleteButton').click(function() {
      if (!travelerToDelete) return;
      $.ajax({
        url: 'deleteTraveler.php', // Endpoint for deletion (should accept POST with user_id)
        type: 'POST',
        dataType: 'json',
        data: { user_id: travelerToDelete },
        success: function(response) {
          if (response.success) {
            loadTravelers(currentPage, $('#searchTraveler').val());
            // Hide modal
            let deleteModalEl = document.getElementById('deleteModal');
            let modalInstance = bootstrap.Modal.getInstance(deleteModalEl);
            modalInstance.hide();
          } else {
            alert('Error: ' + response.message);
          }
        },
        error: function() {
          alert('Failed to delete traveler.');
        }
      });
    });

    // (Optional) Function to open the edit modal (not implemented here, but can follow similar pattern)
    function openEditModal(userId) {
      window.location.href = 'editTraveler.php?user_id=' + userId;
    }

    // Initial load of travelers on page ready
    $(document).ready(function() {
      loadTravelers();
    });
  </script>
</body>
</html>
